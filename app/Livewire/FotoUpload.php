<?php

namespace App\Livewire;

use Illuminate\Contracts\Queue\Job;
use Livewire\Component;
use Livewire\WithFileUploads;

class FotoUpload extends Component
{
    use WithFileUploads;

    public $image;


    protected $rules = [
        'image' => 'image|max:2048',
    ];



    public function updatedImage()
    {
        $this->validateOnly('image');
    }

    public function upload()
    {
        $this->validate();

        try {
            $filename = uniqid() . '.' . $this->image->getClientOriginalExtension();
            $destination = public_path('uploads');
            if (!file_exists($destination)) {
                mkdir($destination, 0775, true);
            }

            $path = $this->image->move($destination, $filename);

            session()->flash('message', 'Imagem enviada com sucesso: ' . $path);
        } catch (\Throwable $e) {
            session()->flash('message', 'Erro ao salvar imagem: ' . $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.foto-upload');
    }
}
