<div>
    <div id="drop-area" class="p-4 text-center border rounded border-secondary"
         style="position: relative; transition: background 0.3s;">
        <p class="mb-2">Arraste uma imagem aqui ou clique para selecionar</p>

        <label for="image" class="mb-2 btn btn-outline-primary">
            Selecionar Imagem
        </label>

        <input type="file" id="image" wire:model="image" accept="image/*" class="d-none">

        <div wire:loading wire:target="image" class="mt-2 text-info">
            Carregando imagem...
        </div>

        @error('image')
            <div class="mt-2 text-danger">{{ $message }}</div>
        @enderror

        @if ($image)
            <div class="mt-3">
                <img src="{{ $image->temporaryUrl() }}" class="img-thumbnail" style="max-width: 250px;">
            </div>

            <div class="mt-3">
                <button type="button" class="btn btn-success" wire:click="upload">
                    Enviar imagem
                </button>
            </div>
        @endif
    </div>

    @if (session()->has('message'))
        <div class="mt-3 alert alert-info">
            {{ session('message') }}
        </div>
    @endif
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const dropArea = document.getElementById('drop-area');
        const fileInput = document.getElementById('image');

        ['dragenter', 'dragover'].forEach(eventName => {
            dropArea.addEventListener(eventName, (e) => {
                e.preventDefault();
                dropArea.classList.add('bg-light');
            });
        });

        ['dragleave', 'drop'].forEach(eventName => {
            dropArea.addEventListener(eventName, (e) => {
                e.preventDefault();
                dropArea.classList.remove('bg-light');
            });
        });

        dropArea.addEventListener('drop', (e) => {
            if (e.dataTransfer.files.length) {
                fileInput.files = e.dataTransfer.files;
                const event = new Event('input', { bubbles: true });
                fileInput.dispatchEvent(event);
            }
        });
    });
</script>

