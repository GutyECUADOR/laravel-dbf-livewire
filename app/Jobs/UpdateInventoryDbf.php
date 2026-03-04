<?php

namespace App\Jobs;

use App\Services\DbfService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class UpdateInventoryDbf implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function handle(DbfService $dbfService): void
    {
        try {
            // Aquí defines las rutas fijas para la tarea automática
            /* $source = 'C:\\rutas_servidor\\MXCTAINV.DBF'; 
            $destination = 'D:\\MXCTAINV_ACTUALIZADO.DBF';
 */
            $count = $dbfService->syncSqlToDbf();
            
            Log::info("Tarea programada: $count artículos actualizados en DBF.");
        } catch (\Exception $e) {
            Log::error("Error en Job de actualización DBF: " . $e->getMessage());
        }
    }
}