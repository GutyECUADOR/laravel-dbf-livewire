<?php

namespace App\Livewire;

use Livewire\WithFileUploads;
use Maatwebsite\Excel\Facades\Excel;
use Livewire\Component;

class ExcelViewer extends Component
{
    use WithFileUploads;
    public $archivo;
    public $path;
    public $datos = [];

    public function render()
    {
        return view('livewire.excel-viewer');
    }

    /*Livewire tiene un método mágico llamado updatedNombreDeLaPropiedad. 
    Este método se ejecuta automáticamente después de que una propiedad pública cambia de valor. */
    public function updatedArchivo()
    {
        // Validar que sea un archivo Excel
        $this->validate([
            'archivo' => 'required|mimes:xlsx,xls,csv|max:10240',
        ]);

        // Leer el contenido y convertirlo en array
        $path = $this->archivo->getRealPath();
        $this->datos = Excel::toArray([], $path)[0];
        session()->flash('message', __('DBF file successfully uploaded.'));
    }

}
