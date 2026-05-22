<div class="form-group">
    <label for="{{ $name }}">{{ $label }}</label>

    <div id="dropzone-{{ $name }}" class="dropzone-container">
        <input type="file" class="d-none" wire:model="image" id="file-{{ $name }}" name="{{ $name }}" accept="image/*">

        <div class="dropzone-area" onclick="document.getElementById('file-{{ $name }}').click();" style="height: {{ $height }}">
            <div id="dropzone-text-{{ $name }}" class="dropzone-text">
                <div class="text-center" style="width:100%;display:flex;justify-content:center;margin-bottom: 10px;">
                    <img src="/build/assets/images/dropzone.svg" width="45px" height="auto">
                </div>
                <p>Selecione ou arraste e solte aqui</p>
            </div>

            @if($previewUrl)
                <img id="preview-{{ $name }}" src="{{ $previewUrl }}" class="image-preview" style="display: block;">
            @endif
        </div>

        @if ($previewUrl)
            <div class="file-info" id="file-info-{{ $name }}">
                <button type="button" class="delete-btn" wire:click="removeImage">✖</button>
                <span class="truncate" id="file-name-{{ $name }}">{{ $fileName }}</span>
            </div>
        @endif

        <div class="progress-bar-container" id="progress-bar-{{ $name }}" style="display: none;">
            <div class="progress-bar"></div>
        </div>
    </div>
</div>

<style>
    .dropzone-container { text-align: center; position: relative; }
    .dropzone-area {
        width: 100%; max-width: 1080px;
        padding: 10px; border: 2px dashed #d8d8d85b;
        display: flex; align-items: center; justify-content: center;
        cursor: pointer; border-radius: 10px; flex-direction: column;
        position: relative;
    }
    .dropzone-area:hover { background-color: #00000005; }
    .dropzone-text {
        position: absolute; top: 20%; left: 0; right: 0;
        width: 100%; display:flex;align-items:center;justify-content: center;
        color: #007bff; font-weight: bold; margin: 0;
    }
    .image-preview {
        width: auto; height: calc({{ $height }} - 20px); max-width: auto; max-height: calc({{ $height }} - 20px);
        border-radius: 10px; margin-top: 10px; z-index: 10;
    }
    .file-info {
        position: absolute; top: 0; left: 0; right: 0;
        width: 100%;
        align-items: center; padding: 5px 10px;
        background: #fdfdfdb0; border-radius: 2px; font-size: 14px; z-index: 11;
        box-shadow: 0 0 2px black;
    }
    .delete-btn {
        width: 25px;
        height: 25px;
        display: flex; align-items: center; justify-content: center;
        background: transparent; border: none; color: rgba(255, 4, 4, 0.555);
        padding: 2px;
        border-radius: 50px;
        font-size: 14px; cursor: pointer;
        margin-right: 10px;
    }
    .progress-bar-container {
        position: absolute; top: 10%; left: 0; right: 0;
        width: 100%; background: #ddd; border-radius: 5px;
        margin-top: 10px; overflow: hidden; height: 5px; display: none;
    }
    .progress-bar {
        width: 0; height: 5px; background: red; transition: width 0.5s;
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const dropzones = document.querySelectorAll('.dropzone-container');

        dropzones.forEach(function (dropzone) {
            const fileInput = dropzone.querySelector('input[type="file"]');
            const name = fileInput.name;
            const preview = dropzone.querySelector(`#preview-${name}`);
            const fileInfo = dropzone.querySelector(`#file-info-${name}`);
            const fileName = dropzone.querySelector(`#file-name-${name}`);
            const progressBarContainer = dropzone.querySelector(`#progress-bar-${name}`);
            const progressBar = progressBarContainer.querySelector('.progress-bar');
            const dztext = dropzone.querySelector(`#dropzone-text-${name}`);

            const hasImage = preview && preview.getAttribute('src') && preview.getAttribute('src').trim() !== '';
            dztext.style.display = hasImage ? 'none' : 'block';

            dropzone.addEventListener('dragover', (event) => {
                event.preventDefault();
                dropzone.classList.add('dragover');
            });

            dropzone.addEventListener('dragleave', () => {
                dropzone.classList.remove('dragover');
            });

            dropzone.addEventListener('drop', (event) => {
                event.preventDefault();
                dropzone.classList.remove('dragover');

                if (event.dataTransfer.files.length > 0) {
                    fileInput.files = event.dataTransfer.files;
                    previewImage(event.dataTransfer.files[0]);
                }
            });

            fileInput.addEventListener('change', function (event) {
                if (event.target.files.length > 0) {
                    previewImage(event.target.files[0]);
                }
            });

            function previewImage(file) {
                if (file && file.type.startsWith('image/')) {
                    const reader = new FileReader();
                    reader.onload = function (e) {
                        preview.src = e.target.result;
                        preview.style.color = 'white';
                        preview.style.display = 'block';
                        fileName.innerText = 'Imagem ' + getFileExtension(file.name).toUpperCase();
                        fileInfo.style.display = 'flex';
                        dztext.style.display = 'none';
                        simulateProgressBar();
                    };
                    reader.readAsDataURL(file);
                }
            }

            function simulateProgressBar() {
                progressBarContainer.style.display = 'block';
                progressBar.style.width = '0%';
                setTimeout(() => { progressBar.style.background = "#ff2f00"; progressBar.style.width = '10%'; }, 100);
                setTimeout(() => { progressBar.style.background = "#ff7700"; progressBar.style.width = '25%'; }, 275);
                setTimeout(() => { progressBar.style.background = "#ffd000"; progressBar.style.width = '50%'; }, 450);
                setTimeout(() => { progressBar.style.background = "#c8ff00"; progressBar.style.width = '75%'; }, 625);
                setTimeout(() => { progressBar.style.background = "#00ff11"; progressBar.style.width = '100%'; }, 900);
                setTimeout(() => { progressBarContainer.style.display = 'none'; }, 1200);
            }

            function getFileExtension(url) {
                const match = url.match(/\.([a-zA-Z0-9]+)(?:\?|#|$)/);
                return match ? match[1] : null;
            }
        });
    });
</script>
