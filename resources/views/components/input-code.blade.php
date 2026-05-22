@props([
    'id' => 'code',
    'name' => 'code',
])
<style>
    .code-box {
        display: flex;
        gap: .5rem;
        justify-content: center;
        align-items: center;
    }

    .code-input {
        width: 3rem;
        height: 3.6rem;
        text-align: center;
        font-size: 1.4rem;
        border-radius: .5rem;
        border: 1px solid #ced4da;
        outline: none;
        caret-color: transparent;
        -moz-appearance: textfield;
    }

    .code-input:focus {
        border-color: #6c757d;
        /* look slightly like MUI focus */
        box-shadow: 0 0 0 .15rem rgba(108, 117, 125, .15);
    }

    /* hide number input spinner in Firefox/Chrome */
    input[type=number]::-webkit-outer-spin-button,
    input[type=number]::-webkit-inner-spin-button {
        -webkit-appearance: none;
        margin: 0;
    }

    input[type=number] {
        -moz-appearance: textfield;
    }
</style>
<div id="code-component" class="my-3">
    <div class="code-box" role="group" aria-label="Código de confirmação">
        <input inputmode="numeric" pattern="[0-9]*" maxlength="1" class="form-control code-input" aria-label="dígito 1">
        <input inputmode="numeric" pattern="[0-9]*" maxlength="1" class="form-control code-input" aria-label="dígito 2">
        <input inputmode="numeric" pattern="[0-9]*" maxlength="1" class="form-control code-input" aria-label="dígito 3">
        <input inputmode="numeric" pattern="[0-9]*" maxlength="1" class="form-control code-input" aria-label="dígito 4">
        <input inputmode="numeric" pattern="[0-9]*" maxlength="1" class="form-control code-input" aria-label="dígito 5">
        <input inputmode="numeric" pattern="[0-9]*" maxlength="1" class="form-control code-input" aria-label="dígito 6">
    </div>
    <!-- campo oculto com o valor completo -->
    <input type="hidden" id="{{ $id }}" name="{{ $name }}" value="">
</div>
<script>
    (function () {
        const root = document.getElementById('code-component');
        const inputs = Array.from(root.querySelectorAll('.code-input'));
        const hidden = root.querySelector("#{{ $id }}");
        const preview = root.querySelector('#code-preview');


        function combine() {
            const code = inputs.map(i => i.value || '').join('');
            hidden.value = code;
            preview.textContent = code.length ? code : '—';
            if (code.length === inputs.length) {
                // dispara evento customizado com o código
                const ev = new CustomEvent('codeCompleted', { detail: { code } });
                root.dispatchEvent(ev);
            }
        }


        function focusNext(index) {
            if (index < inputs.length - 1) inputs[index + 1].focus();
        }


        function focusPrev(index) {
            if (index > 0) inputs[index - 1].focus();
        }


        inputs.forEach((input, idx) => {
            // impede entrada de não-dígitos e aceita apenas 1 char
            input.addEventListener('input', (e) => {
                const val = e.target.value.replace(/[^0-9]/g, '');
                e.target.value = val ? val.slice(-1) : '';
                if (val) focusNext(idx);
                combine();
            });


            input.addEventListener('keydown', (e) => {
                const key = e.key;
                if (key === 'Backspace') {
                    if (input.value === '') {
                        focusPrev(idx);
                    } else {
                        input.value = '';
                        combine();
                    }
                    // allow default to keep caret behavior
                    return;
                }


                if (key === 'ArrowLeft') {
                    e.preventDefault();
                    focusPrev(idx);
                    return;
                }
                if (key === 'ArrowRight') {
                    e.preventDefault();
                    focusNext(idx);
                    return;
                } if (key === 'ArrowRight') {
                    e.preventDefault();
                    focusNext(idx);
                    return;
                }


                // allow only digits, arrows, tab
                if (!/^[0-9]$/.test(key) && key.length === 1) {
                    e.preventDefault();
                }
            });


            input.addEventListener('paste', (e) => {
                e.preventDefault();
                const paste = (e.clipboardData || window.clipboardData).getData('text').replace(/\D/g, '');
                if (!paste) return;
                const digits = paste.slice(0, inputs.length).split('');
                for (let i = 0; i < digits.length; i++) {
                    inputs[i].value = digits[i];
                }
                // focus the next empty or last
                const firstEmpty = inputs.findIndex(i => i.value === '');
                if (firstEmpty === -1) inputs[inputs.length - 1].focus();
                else inputs[firstEmpty].focus();
                combine();
            });


            input.addEventListener('focus', (e) => e.target.select());
        });


        // Expose small API
        root.getCode = () => hidden.value;
        root.clear = () => {
            inputs.forEach(i => i.value = '');
            hidden.value = '';
            preview.textContent = '—';
            inputs[0].focus();
        };


        // Buttons demo
        document.getElementById('btn-get').addEventListener('click', () => alert('Código: ' + root.getCode()));
        document.getElementById('btn-clear').addEventListener('click', () => root.clear());


        // start focus
        inputs[0].focus();


        // exemplo: escutando o evento codeCompleted
        root.addEventListener('codeCompleted', (e) => {
            // console.log('Código completo:', e.detail.code);
            // aqui você pode disparar submit, chamada AJAX etc.
        });


    })();
</script>