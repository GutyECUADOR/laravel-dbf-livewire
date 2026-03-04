<?php

namespace App\Livewire;
use Livewire\WithFileUploads;
use XBase\TableReader;
use Livewire\Component;

class LoadDbfs extends Component
{
    use WithFileUploads;
    public $dbfFile;
    public $headers = [];
    public $rows = [];
    public $recordCount = 0;

    protected $rules = [
        'dbfFile' => 'required|mimes:dbf,bin|max:10240', // A veces el MIME se detecta como bin
    ];

    public function render() {
        return view('livewire.load-dbfs');
    }
    
    public function updatedDbfFile()
    {
        $this->validate();

        try {
            // Obtener la ruta del archivo temporal
            $filePath = $this->dbfFile->getRealPath();

            // 1. Instanciar el TableReader
            $table = new TableReader($filePath);
            
            $this->recordCount = $table->getRecordCount();

            // 2. Extraer los nombres de las columnas
            $this->headers = [];
            foreach ($table->getColumns() as $column) {
                $this->headers[] = $column->getName();
            }

            // 3. Extraer registros (Limitamos a 50 para no sobrecargar el estado de Livewire)
            $this->rows = [];
            $limit = 50; 
            $current = 0;

            while ($record = $table->nextRecord()) {
                if ($current >= $limit) break;

                $row = [];
                foreach ($this->headers as $header) {
                    $value = $record->get($header);
                    
                    // Asegurar codificación UTF-8 para evitar errores en la vista
                    $row[$header] = is_string($value) ? mb_convert_encoding($value, 'UTF-8', 'ISO-8859-1') : $value;
                }
                
                $this->rows[] = $row;
                $current++;
            }

            // 4. Importante: Cerrar el archivo
            $table->close();

        } catch (\Exception $e) {
            session()->flash('error', 'Error al procesar el archivo DBF: ' . $e->getMessage());
            $this->rows = [];
        }
    }
}
