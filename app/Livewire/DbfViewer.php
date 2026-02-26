<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads;
use XBase\TableReader;

class DbfViewer extends Component
{
    use WithFileUploads;

    public $dbfFile;
    public $headers = [];
    public $rows = [];

    public function updatedDbfFile()
    {
        $this->validate([
            'dbfFile' => 'required|max:20480', // Soporta hasta 20MB
        ]);

        try {
            // Obtenemos la ruta temporal del archivo subido
            $path = $this->dbfFile->getRealPath();
            
            // Inicializamos el lector de XBase
            $table = new TableReader($path);
            
            // 1. Extraer Nombres de Columnas (Headers)
            $this->headers = [];
            foreach ($table->getColumns() as $column) {
                $this->headers[] = $column->getName();
            }

            // 2. Extraer Filas (limitamos a 50 para evitar saturar la memoria del componente)
            $this->rows = [];
            $count = 0;
            
            while ($record = $table->nextRecord()) {
                if ($count >= 50) break; // Límite visual
                
                $rowData = [];
                foreach ($this->headers as $header) {
                    // Limpieza básica de codificación para evitar errores en JSON/Blade
                    $value = $record->get($header);
                    $rowData[$header] = mb_check_encoding($value, 'UTF-8') 
                                        ? $value 
                                        : utf8_encode($value);
                }
                
                $this->rows[] = $rowData;
                $count++;
            }

            $table->close();

        } catch (\Exception $e) {
            session()->flash('error', 'Error al leer el archivo: ' . $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.dbf-viewer');
    }
}