<?php

namespace App\Services;

use App\Models\Articulo;
use XBase\TableEditor;
use Illuminate\Support\Facades\Log;

class DbfService
{
    /**
     * Actualiza un DBF basado en los datos de la tabla SQL.
     */
    public function syncSqlToDbf(?string $sourcePath = null, ?string $destinationPath = null): int
    {
        // Usar rutas del .env si no se proporcionan
        $source = $sourcePath ?? config('services.dbf.source');
        $destination = $destinationPath ?? config('services.dbf.destination');

        if (!file_exists($source)) {
            throw new \Exception("Archivo de origen no encontrado en: $source");
        }

        // Asegurar que el directorio de destino exista
        $directory = dirname($destination);
        if (!is_dir($directory)) {
            throw new \Exception("El disco o carpeta de destino no existe: $directory");
        }

        copy($source, $destination);

        $table = new TableEditor($destination, [
            'editMode' => TableEditor::EDIT_MODE_REALTIME
        ]);

        $updatedCount = 0;

        while ($record = $table->nextRecord()) {
            $codart = trim($record->get('CODART'));

            // Buscamos en la base de datos SQL
            $articuloSql = Articulo::where('codart', $codart)->first();

            if ($articuloSql) {
                // Sincronizamos campos
                $record->set('PRECIO_A', $articuloSql->precio_a);
                $record->set('PRECIO_B', $articuloSql->precio_b);
                $record->set('EXISTE_ACT', $articuloSql->existe_act);
                
                // Conversión de encoding para el DBF (ISO-8859-1)
                $record->set('NOMART', mb_convert_encoding($articuloSql->nomart, 'ISO-8859-1', 'UTF-8'));

                $table->writeRecord();
                $updatedCount++;
            }
        }

        $table->save()->close();
        
        return $updatedCount;
    }
}