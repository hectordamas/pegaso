<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Carbon\Carbon;
use Mail;
use App\Models\Safact;
use App\Mail\PresupuestoPendienteMail;

class NotificarPresupuestosPendientes extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'presupuestos:notificar-pendientes';
    protected $description = 'Notifica presupuestos pendientes con más de 10 días';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $pendientes = Safact::where('codestatus', 1) // Asumiendo 1 = pendiente
            ->whereDate('fechae', '<=', Carbon::now()->subDays(10))
            ->get();

            foreach ($pendientes as $presupuesto) {
                $email = $presupuesto->savend->email ?? null;

                if ($email) {
                    Mail::to($email)->send(new PresupuestoPendienteMail($presupuesto));
                    $this->info("Correo enviado a $email para presupuesto #{$presupuesto->numerod}");
                }
            }

        return 0;
    }
}