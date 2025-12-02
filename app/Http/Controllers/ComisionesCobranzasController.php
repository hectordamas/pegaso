<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\{Savend, saitemfac, Safact, DetalleCxC, CobranzaResponsable, CobranzasOrigen};
use Carbon\Carbon;

class ComisionesCobranzasController extends Controller
{
    public function listSafact(Request $request)
    {
        $year = $request->year ?: date('Y');
        $mes  = $request->mes ?: date('m');
        $id = $request->savendId;

        $startOfMonth = Carbon::createFromDate($year, $mes, 1)->startOfDay();
        $endOfMonth   = Carbon::createFromDate($year, $mes, 1)->endOfMonth()->endOfDay();

        $safacts = Safact::with(['cxc.detallecxc'])
            ->whereHas('cxc.detallecxc', function ($q) use ($startOfMonth, $endOfMonth) {
                $q->whereNotNull('fechaDePago')->whereBetween('fechaDePago', [
                    $startOfMonth->toDateString(),
                    $endOfMonth->toDateString()
                ]);
            })
            ->whereIn('codestatus', [3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 15])
            ->where('aplica_comision', false)
            ->get();

        $query = $safacts;

        $totalRecords = $query->count();
        $data = [];

        foreach ($query as $safact) {
            $row = [];
            $row[] = $safact->id;
            $row[] = $safact->descrip;
            $row[] = $safact->numerod;
            $row[] = \Carbon\Carbon::parse($safact->fechae)->format('d-m-Y');
            $row[] = '$ ' . number_format($safact->tgravable / ($safact->factor ?? 1), 2);
            $row[] = '<input type="checkbox" class="safact-checkbox" data-id="' . $safact->id . '" name="presupuestos[]" value="' . $safact->id . '" ' . ($safact->aplica_comision ? 'checked' : '') . '>';
            $data[] = $row;
        }

        return response()->json([
            "sEcho" => 1,
            "iTotalRecords" => $totalRecords,
            "iTotalDisplayRecords" => $totalRecords,
            'aaData' => $data,
            'safacts' => $safacts
        ]);
    }

    public function guardarSafacts(Request $request)
    {
        $safacts = $request->input('safacts', []);
        $ids = collect($safacts)->pluck('id')->toArray();

        foreach ($ids as $id) {
            $safact = Safact::find($id);
            $safact->aplica_comision = true;
            $safact->save();
        }

        return response()->json([
            'status' => 'ok',
            'message' => 'Presupuestos guardados correctamente',
            'ids' => $ids
        ]);
    }

    public function getComisionesData(Request $request)
    {
        $year = $request->year ?: date('Y');
        $mes  = $request->mes ?: 8;
        $id   = $request->savendId;

        $startOfMonth = Carbon::createFromDate($year, $mes, 1)->startOfDay();
        $endOfMonth   = Carbon::createFromDate($year, $mes, 1)->endOfMonth()->endOfDay();

        // ✅ 1. Eager loading completo para evitar consultas por relación
        $detallecxc = DetalleCxC::with([
            'cxc.safact',
            'bank',
            'cobranzasOrigen'
        ])
            ->whereBetween('fechaDePago', [
                $startOfMonth->toDateString(),
                $endOfMonth->toDateString()
            ])
            ->whereHas('cxc.safact')
            ->where('aplica_comision', true)
            ->get();

        $totalRecords = $detallecxc->count();
        $totalCobradoUSD = $detallecxc->sum('monto');
        $data = [];

        // ✅ 2. Precalcular todos los abonos de las facturas implicadas, ordenados
        $safactIds = $detallecxc->pluck('cxc.safact_id')->unique()->values();
        $todosLosAbonos = DetalleCxC::with('cxc')
            ->whereHas('cxc', fn($q) => $q->whereIn('safact_id', $safactIds))
            ->orderBy('fechaDePago')
            ->orderBy('id')
            ->get()
            ->groupBy(fn($a) => $a->cxc->safact_id);

        // ✅ 3. Loop optimizado
        foreach ($detallecxc as $pago) {
            $safact = $pago->cxc->safact;
            $base = $safact->base_imponible() ?? 0;
            $abonos = $todosLosAbonos[$safact->id] ?? collect();

            // Encontrar posición del pago actual
            $index = $abonos->search(fn($a) => $a->id === $pago->id);

            // Abonos antes y hasta el actual
            $totalAntes = $abonos->take($index)->sum('monto');
            $totalHasta = $abonos->take($index + 1)->sum('monto');

            // Cálculos base (en memoria, sin queries)
            $montoAPagar = max($base - $totalAntes, 0);
            $saldoPendiente = max($base - $totalHasta, 0);
            $porcentajePagado = $base > 0 ? ($totalHasta / $base) * 100 : 0;
            $porcentajePendiente = 100 - $porcentajePagado;

            // Factor y monto convertido
            $factor = $safact->factor ?? 1;
            $montoConvertido = $pago->monto * $factor;

            // ✅ Renderización de fila
            $row = [];
            $row[] = "<p>{$pago->id}</p>";
            $row[] = "<p></p>";
            $row[] = "<p>{$safact->descrip}</p>";
            $row[] = "<p></p>";
            $row[] = "<p>{$safact->numerod}</p>";
            $row[] = Carbon::parse($safact->fechae)->format('d-m-Y');
            $row[] = '';
            $row[] = '$' . number_format($montoAPagar, 2, '.', ',');
            $row[] = '';
            $row[] = Carbon::parse($pago->fechaDePago)->format('d-m-Y');
            $row[] = $pago->bank?->nombre ?: '-';
            $row[] = number_format($pago->monto, 2, '.', ',');
            $row[] = number_format($factor, 2);
            $row[] = number_format($montoConvertido, 2, '.', ',');
            $row[] = round($porcentajePagado) . '%';
            $row[] = number_format($saldoPendiente, 2, '.', ',');
            $row[] = round($porcentajePendiente) . '%';
            $row[] = '<input type="checkbox" class="check-admin" data-id="' . $pago->id . '" ' . ($pago->check_admin ? 'checked' : '') . ' ' . ($pago->check_manager ? 'disabled' : '') . '>';
            $row[] = '<input type="checkbox" class="check-manager" data-id="' . $pago->id . '" ' . ($pago->check_manager ? 'checked' : '') . ' ' . ($pago->check_manager ? 'disabled' : '') . '>';
            $row[] = $pago->descripcion;
            $row[] = view('comisionesCobranzas.partials.buttons', compact('safact', 'pago'))->render();

            $data[] = $row;
        }

        // ✅ 4. Cálculo de comisiones por responsable
        $responsables = CobranzaResponsable::all();
        $dataResponsables = $responsables->map(function ($r) use ($totalCobradoUSD) {
            $comisionUSD = $totalCobradoUSD * ($r->comision / 100);
            return [
                'id' => $r->id,
                'name' => $r->name,
                'porcentaje' => $r->comision,
                'total_comision' => $comisionUSD,
            ];
        });

        $htmlResponsables = view('comisionesCobranzas.partials.comisiones_responsables', [
            'responsables' => $dataResponsables,
            'totalCobradoUSD' => $totalCobradoUSD
        ])->render();

        // ✅ 5. Totales por origen (ya en memoria)
        $totalesPorOrigen = $detallecxc->groupBy('cobranzas_origen_id')->map(function ($items) {
            $monto = $items->sum('monto');
            $nombre = optional($items->first()->cobranzasOrigen)->nombre ?? 'Sin origen';
            return [
                'nombre' => $nombre,
                'total' => $monto,
            ];
        });

        $dataOrigenes = $totalesPorOrigen->map(function ($o) use ($totalCobradoUSD) {
            $porcentaje = $totalCobradoUSD > 0 ? ($o['total'] / $totalCobradoUSD) * 100 : 0;
            return [
                'nombre' => $o['nombre'],
                'total' => $o['total'],
                'porcentaje' => $porcentaje,
            ];
        });

        $htmlOrigenes = view('comisionesCobranzas.partials.cobrado_origen', [
            'origenes' => $dataOrigenes,
            'totalCobradoUSD' => $totalCobradoUSD
        ])->render();

        return response()->json([
            "sEcho" => 1,
            "iTotalRecords" => $totalRecords,
            "iTotalDisplayRecords" => $totalRecords,
            'aaData' => $data,
            'html_responsables' => $htmlResponsables,
            'html_origenes' => $htmlOrigenes,
            'origenes' => CobranzasOrigen::all()
        ]);
    }


    public function index(Request $request)
    {
        $origenes = CobranzasOrigen::all();
        $mes = $request->mes ?: date('m');

        return view('comisionesCobranzas.index', [
            'origenes' => $origenes,
            'mes' => $mes
        ]);
    }


    public function getPagoInfo($id)
    {
        $pago = DetalleCxC::findOrFail($id);

        return response()->json([
            'fechaDeCobro' => $pago->fechaDePago,
            'origen_id' => $pago->cobranzasOrigen?->id,
            'factura' => $pago->factura,
            'fechaDeFactura' => $pago->fechaDeFactura,
        ]);
    }

    public function addComisionesCobranzasInfo(Request $request)
    {
        $pago = DetalleCxC::findOrFail($request->pago_id);
        $pago->fechaDeCobro = $request->fechaDePago;
        $pago->origen_id = $request->origen;
        $pago->factura = $request->factura;
        $pago->fechaDeFactura = $request->fechaDeFactura;
        $pago->save();

        return response()->json([
            'success' => true
        ]);
    }

    public function createResponsable(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:100',
            'comision' => 'required|numeric|min:0|max:100',
        ]);

        CobranzaResponsable::create([
            'name' => $request->name,
            'comision' => $request->comision,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Responsable creado correctamente.',
        ]);
    }

    public function updateResponsable(Request $request)
    {
        $request->validate([
            'id' => 'required',
            'name' => 'required|string|max:100',
            'comision' => 'required|numeric|min:0|max:100',
        ]);

        $responsable = CobranzaResponsable::findOrFail($request->id);
        $responsable->update([
            'name' => $request->name,
            'comision' => $request->comision,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Responsable actualizado correctamente.',
        ]);
    }

    public function updateCheck(Request $request, $id)
    {
        $pago = DetalleCxC::findOrFail($id);

        if ($request->has('check_admin')) {
            $pago->check_admin = $request->check_admin;
        }
        if ($request->has('check_manager')) {
            $pago->check_manager = $request->check_manager;
        }

        $pago->save();

        return response()->json(['success' => true]);
    }

}
