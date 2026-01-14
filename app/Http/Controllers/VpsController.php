<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\{Saclie, VpsField, VpsPayment};
use Illuminate\Support\Facades\DB;

class VpsController extends Controller
{
    public function index(Request $request)
    {
        // Búsqueda opcional de cliente
        $clients = Saclie::orderby('descrip', 'asc')->where('activo', true)->get();

        $vpsClients = Saclie::whereIn('codclie', function ($q) {
            $q->select('codclie')->from('vps_fields')
                ->union(
                    DB::table('vps_payments')->select('codclie')
                );
        })->get();

        return view('vps.index', compact('clients', 'vpsClients'));
    }

    public function client($codclie)
    {
        $client = Saclie::where('codclie', $codclie)->firstOrFail();

        $fields = $client->vpsFields()->orderBy('group')->get();

        $payments = $client->vpsPayments()->orderBy('fecha', 'desc')->get();

        return view('vps.client', compact('client', 'fields', 'payments'));
    }

    public function addFields(Request $request, $codclie)
    {
        $count = count($request->value);
        for ($i = 0; $i < $count; $i++) {
            VpsField::create([
                'codclie' => $codclie,
                'group' => $request->group[$i],
                'label' => $request->label[$i],
                'value' => $request->value[$i],
            ]);
        }

        return back()->with('success', 'Credenciales agregadas');
    }

    public function updateFields(Request $request, $id)
    {
        $request->validate([
            'group' => 'nullable|string|max:100',
            'label' => 'required|string|max:255',
            'value' => 'required|string',
        ]);

        $field = VpsField::findOrFail($id);

        $field->update([
            'group' => $request->group,
            'label' => $request->label,
            'value' => $request->value,
        ]);

        return back()->with('success', 'Credencial actualizada correctamente');
    }


    public function deleteField($id)
    {
        VpsField::findOrFail($id)->delete();

        return back()->with('success', 'Credencial eliminada');
    }

    public function addPayment(Request $request, $codclie)
    {
        $data = $request->validate([
            'fecha' => 'required|date',
            'amount' => 'required|numeric|min:0',
            'status' => 'required|string|in:pending,paid',
            'receipt' => 'nullable|file|mimes:jpg,jpeg,png,pdf',
        ]);

        $data['year'] = date('Y'); // Año actual
        $data['codclie'] = $codclie;
        $data['month'] = date('n', strtotime($request->fecha));

        // Procesar archivo si existe
        if ($request->hasFile('receipt')) {
            $file = $request->file('receipt');
            $data['receipt'] = 'data:' . $file->getMimeType() . ';base64,' . base64_encode(file_get_contents($file->getRealPath()));
        }

        VpsPayment::create($data);

        return back()->with('success', 'Pago agregado correctamente.');
    }


    public function deletePayment($id)
    {
        $payment = VpsPayment::findOrFail($id);

        $payment->delete();

        return back()->with('success', 'Pago eliminado correctamente.');
    }

    public function updatePayment(Request $request, $id)
    {
        $payment = VpsPayment::findOrFail($id);

        $data = $request->validate([
            'amount' => 'required|numeric|min:0',
            'status' => 'required|string|in:pending,paid',
            'receipt' => 'nullable|file|mimes:jpg,jpeg,png,pdf',
            'fecha' => 'required|date',
        ]);

        $data['month'] = date('n', strtotime($request->fecha));

        if ($request->hasFile('receipt')) {
            $file = $request->file('receipt');
            $data['receipt'] = 'data:' . $file->getMimeType() . ';base64,' . base64_encode(file_get_contents($file->getRealPath()));
        }

        $payment->update($data);

        return back()->with('success', 'Pago actualizado correctamente.');
    }
}
