<?php

namespace App\Livewire;
use Livewire\WithFileUploads;

use Livewire\Component;

class LoadDbfs extends Component
{
    use WithFileUploads;
    public $dbfFile;

    public function render()
    {
        return view('livewire.load-dbfs');
    }

    public function save()
    {
        $this->validate([
            'dbfFile' => 'required|file|mimes:dbf',
        ]);
 
        $this->dbfFile->storeAs('dbfs', $this->dbfFile->getClientOriginalName());
        session()->flash('message', __('DBF file successfully uploaded.'));
        $this->dbfFile = null; // Clear the file input after saving
    }
}
