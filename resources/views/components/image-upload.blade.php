@props([
    'id',
    'name',
    'label',
    'value' => null,
    'class' => '',
    'height' => '350px', // valor padrão
    'exibir' => true
])

<div class="form-group">
    @php
        $valor = $value;
            if (is_null($value)) {
                $valor = null;
            }

        $altura = isset($height) ? $height : '350px';
        $alturaImagem = is_numeric(str_replace('px', '', $altura)) ? ((int) str_replace('px', '', $altura) - 20) . 'px' : '330px';
    @endphp

    <label for="{{ $name }}">{{ $label }}</label>

    <div id="dropzone-{{ $name }}" class="dropzone-container">
        <input type="file" class="{{$class}}" id="file-{{ $name }}" name="{{ $name }}" accept="{{ $mimetypes ?? 'image/*' }}" hidden>

        <div class="dropzone-area" onclick="document.getElementById('file-{{ $name }}').click();" style="height: {{$altura}}">
            <div id="dropzone-text-{{ $name }}" class="dropzone-text">
                <div class="text-center" style="width:100%;display:flex;justify-content:center;margin-bottom: 10px;">
                    <img src="/build/assets/images/dropzone.svg" width="45px" height="auto">
                </div>
                <p>Selecione ou arraste e solte aqui</p>
            </div>

            <img id="preview-{{ $name }}" src="{{ $valor ?? '' }}" class="image-preview" style="display: {{ $valor ? 'block' : 'none' }};">
        </div>

            <div class="file-info" id="file-info-{{ $name }}" style="display: {{  $valor ? 'block' :  'none' }};">
                <button type="button" class="delete-btn" onclick="removeImage('{{ $name }}')">✖</button>
                <span class="truncate" id="file-name-{{ $name }}">{{ $valor ? basename($valor) : 'Nome do arquivo' }}</span>
            </div>

        <div class="progress-bar-container" id="progress-bar-{{ $name }}" style="display: none;">
            <div class="progress-bar"></div>
        </div>
    </div>
</div>

<style>
    .dropzone-container { text-align: center; position: relative; }
    .dropzone-area {
        width: 100%;
        padding: 10px; border: 2px dashed #d8d8d85b;
        display: flex; align-items: center; justify-content: center;
        cursor: pointer; border-radius: 10px; flex-direction: column;
        position: relative;
    }
    .dropzone-area:hover { background-color: #00000005; }
    .dropzone-text {
        position: absolute; top: 33%; left: 0; right: 0;
        width: 100%; display:flex;align-items:center;justify-content: center;
        color: var(--gateway-color); font-weight: bold; margin: 0;
    }
    .image-preview {
        display:flex;
        justify-content: center;
        align-items: center;
        width: auto; height: calc({{ $altura }} - 20px); height: auto; max-width: auto; max-height: calc({{ $altura }} - 20px);
        border-radius: 10px; margin-top: 10px; z-index: 10;
    }
    .file-info {
        position: absolute; top: 0; left: 0; right: 0;
        display: flex;
        align-items: center;
        justify-content: flex-start;
        width: 100%;
        align-items: center; padding: 5px 10px;
        background: #fdfdfdb0; border-radius: 2px; font-size: 14px; z-index: 11;
        box-shadow: 0 0 2px black;
    }
    .delete-btn {
        position: absolute;
        top: 10%;
        left: 10px;
        width: 25px;
        height: 25px;
        padding: 2px;
        border-radius: 50px;
        font-size: 14px; cursor: pointer;
        margin-right: 10px;
        color: rgb(131, 0, 0) !important;
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

        // Verifica se a imagem já está presente
        const hasImage = preview.getAttribute('src') && preview.getAttribute('src').trim() !== '';
        dztext.style.display = hasImage ? 'none' : 'block';

        // Drag and drop
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
                    fileName.innerText = 'Imagem '+getFileExtension(file.name).toUpperCase();
                    fileInfo.style.display = 'flex';

                    if (preview && preview.getAttribute('src') && preview.getAttribute('src') !== '') {
                        dztext.style.display = 'none';  // Esconde o texto
                        preview.style.display = 'block';  // Exibe a imagem
                        fileInfo.style.display = 'flex';  // Exibe as informações do arquivo
                    } else {
                        preview.style.display = 'none';  // Não exibe a imagem
                        fileInfo.style.display = 'none';  // Não exibe as informações do arquivo
                        dztext.style.display = 'block';  // Exibe o texto
                    }

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

        window.removeImage = function (name) {
            const dropzone = document.getElementById(`dropzone-${name}`);
            const fileInput = dropzone.querySelector(`#file-${name}`);
            const preview = dropzone.querySelector(`#preview-${name}`);
            const fileInfo = dropzone.querySelector(`#file-info-${name}`);
            const dztext = dropzone.querySelector(`#dropzone-text-${name}`);

            fileInput.value = '';
            preview.src = '';
            preview.style.display = 'none';
            fileInfo.style.display = 'none';
            dztext.style.display = 'block';
        };
    });
});

function getFileExtension(url) {
  const match = url.match(/\.([a-zA-Z0-9]+)(?:\?|#|$)/);
  return match ? match[1] : null;
}
</script>

<script>
window.initImageUpload = function(context = document) {
    const dropzones = context.querySelectorAll('.dropzone-container');

    dropzones.forEach(function (dropzone) {
        const fileInput = dropzone.querySelector('input[type="file"]');
        const name = fileInput.name;
        const preview = dropzone.querySelector(`#preview-${name}`);
        const fileInfo = dropzone.querySelector(`#file-info-${name}`);
        const fileName = dropzone.querySelector(`#file-name-${name}`);
        const progressBarContainer = dropzone.querySelector(`#progress-bar-${name}`);
        const progressBar = progressBarContainer.querySelector('.progress-bar');
        const dztext = dropzone.querySelector(`#dropzone-text-${name}`);

        const hasImage = preview.getAttribute('src') && preview.getAttribute('src').trim() !== '';
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

        window.removeImage = function (name) {
            const dropzone = document.getElementById(`dropzone-${name}`);
            const fileInput = dropzone.querySelector(`#file-${name}`);
            const preview = dropzone.querySelector(`#preview-${name}`);
            const fileInfo = dropzone.querySelector(`#file-info-${name}`);
            const dztext = dropzone.querySelector(`#dropzone-text-${name}`);
            fileInput.value = '';
            preview.src = '';
            preview.style.display = 'none';
            fileInfo.style.display = 'none';
            dztext.style.display = 'block';
        };
    });
};

document.addEventListener('DOMContentLoaded', function () {
    initImageUpload(); // inicializa todos os atuais
});

function getFileExtension(url) {
    const match = url.match(/\.([a-zA-Z0-9]+)(?:\?|#|$)/);
    return match ? match[1] : null;
}
</script>

</script>
