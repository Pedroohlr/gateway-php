<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads;

class ImageInput extends Component
{
    use WithFileUploads;

    public $image;
    public $previewUrl;
    public $height = '350px';
    public $label;
    public $name;
    public $fileName;
    public $exibir = true;

    public function mount($label, $name, $value = null, $height = '350px', $exibir = true)
    {
        $this->label = $label;
        $this->name = $name;
        $this->height = $height;
        $this->exibir = $exibir;

        if ($value) {
            $this->previewUrl = $value;
        }
    }

    public function updatedImage()
    {
        if ($this->image) {
            $this->fileName = $this->image->getClientOriginalName();
            $this->previewUrl = $this->image->temporaryUrl();
        }
    }

    public function removeImage()
    {
        $this->image = null;
        $this->fileName = null;
        $this->previewUrl = null;
    }

    public function render()
    {
        return view('livewire.image-input');
    }
}
