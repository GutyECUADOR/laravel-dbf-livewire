<?php

namespace App\Livewire;

use Illuminate\Support\Facades\DB;
use Livewire\WithFileUploads;
use XBase\TableReader;
use Livewire\Component;
use App\Models\Articulo;
use App\Services\DbfService;
use Carbon\Carbon;
use XBase\TableEditor;

class LoadDbfs extends Component
{
    use WithFileUploads;
    public $dbfFile;
    public $headers = [];
    public $rows = [];
    public $recordCount = 0;
    public $tempPath;

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
            $this->tempPath = $this->dbfFile->getRealPath();

            // 1. Instanciar el TableReader
            $table = new TableReader($this->tempPath);
            
            $this->recordCount = $table->getRecordCount();

            // 2. Extraer los nombres de las columnas
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

    public function import()
    {
        if (!$this->tempPath || !file_exists($this->tempPath)) {
            session()->flash('error', 'El archivo ya no está disponible. Por favor, súbelo de nuevo.');
            return;
        }

        try {
            $table = new TableReader($this->tempPath);
            
            DB::transaction(function () use ($table) {
                while ($record = $table->nextRecord()) {
                    Articulo::updateOrCreate(
                        ['codart' => trim($record->get('CODART'))], // Busca por código para no duplicar
                        [
                            'nomart'     => trim(utf8_encode($record->get('NOMART'))),
                            'grupo'      => trim($record->get('GRUPO')),
                            'alterno'    => trim($record->get('ALTERNO')),
                            'iva'        => trim($record->get('IVA')),
                            'precio_a'   => $record->get('PRECIO_A') ? $record->get('PRECIO_A') * rand(110, 190) / 100 : 0,
                            'precio_b'   => $record->get('PRECIO_B') ? $record->get('PRECIO_B') * rand(110, 190) / 100 : 0,
                            'existe_act' => $record->get('EXISTE_ACT') ?: 0,
                            'ult_costo'  => $record->get('ULT_COSTO') ?: 0,
                            'fecha_cos'  => $this->parseDbfDate($record->get('FECHA_COS')),
                            'fec_dig'    => $this->parseDbfDate($record->get('FEC_DIG')),
                            'codbar'     => trim($record->get('CODBAR')),
                        ]
                    );
                }
            });

            $table->close();
            $this->reset(['rows', 'headers', 'dbfFile', 'recordCount']);
            session()->flash('success', '¡Importación completada con éxito!');

        } catch (\Exception $e) {
            session()->flash('error', 'Error al importar: ' . $e->getMessage());
        }
    }

    private function parseDbfDate($value)
    {
        if (!$value || $value == '00000000') return null;
        try {
            return Carbon::parse($value)->format('Y-m-d');
        } catch (\Exception $e) {
            return null;
        }
    }

    public function updateDbfFromDatabase(DbfService $dbfService)
    {
        if (!$this->tempPath || !file_exists($this->tempPath)) {
            session()->flash('error', 'Cargue el archivo DBF primero.');
            return;
        }

        // Definimos la ruta de destino en el disco D:
        /* $destinationPath = 'D:\\MXCTAINV_ACTUALIZADO.DBF'; */

        try {
            // Aquí SÍ pasamos el path temporal como origen, pero usamos el destino del .env
            $count = $dbfService->syncSqlToDbf($this->tempPath);

            session()->flash('success', "Se han actualizado $count registros dentro del archivo DBF.");
            
            // Forzamos la recarga de la previsualización
            $this->updatedDbfFile();

        } catch (\Exception $e) {
            session()->flash('error', 'Error al actualizar el DBF: ' . $e->getMessage());
        }
    }
}
