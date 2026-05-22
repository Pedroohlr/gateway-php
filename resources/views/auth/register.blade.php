@php
    use App\Helpers\Helper;
    $setting = Helper::getSetting();
@endphp
<x-auth-layout :route="'Cadastrar-me'">

    <div class="wrapper">
        <div class="section-authentication-cover">
            <div class="">
                <div class="row g-0">
                    <div
                        class="col-12 col-xl-7 col-xxl-8 auth-cover-left align-items-center justify-content-center d-none d-xl-flex">
                        <div class="card shadow-none bg-transparent shadow-none rounded-0 mb-0">
                            <div class="card-body">
                                <img src="{{ asset('assets-front/images/register-cover.svg') }}"
                                    class="img-fluid auth-img-cover-login" width="550" alt="">
                            </div>
                        </div>
                    </div>

                    <div
                        class="col-12 col-xl-5 col-xxl-4 auth-cover-right bg-light align-items-center justify-content-center">
                        <div class="card rounded-0 m-3 shadow-none bg-transparent mb-0">
                            <div class="card-body p-sm-5">
                                <div class="">
                                    <div class="mb-3 text-center">
                                        <img src="{{ asset($setting->gateway_logo) }}" width="60" alt="">
                                    </div>
                                    <div class="text-center mb-4">
                                        <h5 class="">{{ $setting->gateway_name }}</h5>
                                        <p class="mb-0">Entre com os dados para criar sua conta</p>
                                    </div>
                                    <div class="form-body">
                                        <form class="row g-3" method="POST" action="{{ route('register') }}">
                                            @csrf
                                            <div class="col-12">
                                                <label for="name" class="form-label">Nome Completo</label>
                                                <input type="text" class="form-control" id="name" name="name"
                                                    placeholder="Seu nome completo">
                                            </div>
                                            @if ($errors->has('name'))
                                                <span class="text-danger">{{ $errors->first('name') }}</span>
                                            @endif
                                            <div class="col-12">
                                                <label for="telefone" class="form-label">Celular</label>
                                                <input type="text" class="form-control" id="telefone" name="telefone"
                                                    placeholder="(11) 90000-0000">
                                            </div>
                                            @if ($errors->has('telefone'))
                                                <span class="text-danger">{{ $errors->first('telefone') }}</span>
                                            @endif
                                            <div class="col-12">
                                                <label for="username" class="form-label">Nome de usuário</label>
                                                <input type="text" class="form-control" id="username" name="username"
                                                    placeholder="Seu nome de usuário">
                                            </div>
                                            @if ($errors->has('username'))
                                                <span class="text-danger">{{ $errors->first('username') }}</span>
                                            @endif
                                            <div class="col-12">
                                                <label for="email" class="form-label">Email</label>
                                                <input type="email" class="form-control" id="email" name="email"
                                                    placeholder="Seu melhor email">
                                            </div>
                                            @if ($errors->has('email'))
                                                <span class="text-danger">{{ $errors->first('email') }}</span>
                                            @endif
                                            <div class="col-12">
                                                <label for="password" class="form-label">Senha</label>
                                                <div class="input-group" id="show_hide_password">
                                                    <input type="password" class="form-control border-end-0"
                                                        id="password" value="{{ old('password') }}" name="password"
                                                        placeholder="Digite sua senha"> <a href="javascript:;"
                                                        class="input-group-text bg-transparent"><i
                                                            class="bx bx-hide"></i></a>
                                                </div>
                                            </div>
                                            @if ($errors->has('password'))
                                                <span class="text-danger">{{ $errors->first('password') }}</span>
                                            @endif

                                            <div class="col-12">
                                                <label for="password_confirmation" class="form-label">Confirmar senha</label>
                                                <div class="input-group" id="password_confirmation">
                                                    <input type="password" class="form-control border-end-0"
                                                        id="password_confirmation"
                                                        value="{{ old('password_confirmation') }}"
                                                        name="password_confirmation" placeholder="Digite sua senha"> <a
                                                        href="javascript:;" class="input-group-text bg-transparent"><i
                                                            class="bx bx-hide"></i></a>
                                                </div>
                                            </div>
                                            @if ($errors->has('password_confirmation'))
                                                <span
                                                    class="text-danger">{{ $errors->first('password_confirmation') }}</span>
                                            @endif

                                            <div class="col-12">
                                                <div class="form-check form-switch">
                                                    <input class="form-check-input" type="checkbox"
                                                        id="flexSwitchCheckChecked">
                                                    <label class="form-check-label" for="flexSwitchCheckChecked">Eu li e
                                                        aceito os termos &amp; Condições</label>
                                                </div>
                                            </div>
                                            <div class="col-12">
                                                <div class="d-grid">
                                                    <button type="submit" class="btn btn-light">Cadastrar-me</button>
                                                </div>
                                            </div>
                                            <div class="col-12">
                                                <div class="text-center ">
                                                    <p class="mb-0">Já possui uma conta? <a href="/login">Efetuar
                                                            login</a></p>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
                <!--end row-->
            </div>
        </div>
    </div>


    <!-- <div class="col-xxl-7 col-xl-10">
        <div class="mt-5 mb-5 card card-raised shadow-10 mt-xl-10">
            <div class="p-5 card-body">
                <div class="text-center">
                    <img class="mb-3" src="{{ asset($setting->gateway_logo)}}" alt="..." style="height: 48px" />
                    <h1 class="mb-0 display-5">Crie uma nova conta</h1>
                    <div class="mb-5 subheading-1">para ter acesso a plataforma</div>
                </div>

                <form method="POST" action="{{ route('register') }}">
                    @csrf
                    <div class="row">
                        <div class="mb-4 col-sm-12">
                            <mwc-textfield class="w-100" label="Nome completo" id="name" name="name" value="{{ old('name') }}" class="form-control input-shadow" placeholder="Digite seu nome completo" outlined></mwc-textfield>
                            @if ($errors->has('name'))
                            <span class="text-danger">{{ $errors->first('name') }}</span>
                            @endif
                        </div>
                    </div>
                    <div class="row">
                        <div class="mb-4 col-sm-6">
                            <mwc-textfield class="w-100" label="Telefone" id="telefone" name="telefone" value="{{ old('telefone') }}" outlined></mwc-textfield>
                            @if ($errors->has('telefone'))
                            <span class="text-danger">{{ $errors->first('telefone') }}</span>
                            @endif
                        </div>
                        <div class="mb-4 col-sm-6">
                            <mwc-textfield class="w-100" label="Username" id="username" name="username" value="{{ old('username') }}" outlined></mwc-textfield>
                            @if ($errors->has('username'))
                            <span class="text-danger">{{ $errors->first('username') }}</span>
                            @endif
                        </div>
                    </div>
                    <div class="mb-4">
                        <mwc-textfield class="w-100" label="Email" id="email" name="email" value="{{ old('email') }}" outlined></mwc-textfield>
                        @if ($errors->has('email'))
                            <span class="text-danger">{{ $errors->first('email') }}</span>
                        @endif
                    </div>
                    <div class="row">
                        <div class="mb-4 col-sm-6">
                            <div class="form-floating position-relative mb-3">
                            <input
                                type="password"
                                class="form-control"
                                id="passwordInput"
                                placeholder="Senha"
                                autocomplete="current-password"
                                name="password" 
                                value="{{ old('password') }}"
                            >
                            <label for="passwordInput">Senha</label>
                        
                            <button
                                type="button"
                                class="btn position-absolute top-50 end-0 translate-middle-y me-3 p-0 border-0 bg-transparent"
                                id="togglePasswordBtn"
                                aria-label="Mostrar ou ocultar senha"
                            >
                                <i class="fa-solid fa-eye fs-5" id="togglePasswordIcon"></i>
                            </button>
                        </div>
                                @if ($errors->has('password'))
                                <span class="text-danger">{{ $errors->first('password') }}</span>
                                @endif
                        </div>
                        <div class="mb-4 col-sm-6">
                        
                        <div class="form-floating position-relative mb-3">
                            <input
                                type="password"
                                class="form-control"
                                id="confirmpasswordInput"
                                placeholder="Senha"
                                autocomplete="current-password"
                                name="password_confirmation" 
                                value="{{ old('password_confirmation') }}"
                            >
                            <label for="confirmpasswordInput">Senha</label>
                        
                            <button
                                type="button"
                                class="btn position-absolute top-50 end-0 translate-middle-y me-3 p-0 border-0 bg-transparent"
                                id="confirmtogglePasswordBtn"
                                aria-label="Mostrar ou ocultar senha"
                            >
                                <i class="fa-solid fa-eye fs-5" id="confirmtogglePasswordIcon"></i>
                            </button>
                        </div>
                                @if ($errors->has('password_confirmation'))
                                <span class="text-danger">{{ $errors->first('password_confirmation') }}</span>
                                @endif
                        </div>
                    </div>
                    <div class="mt-4 mb-0 form-group d-flex align-items-center justify-content-between">
                        <a class="small fw-500 text-decoration-none" href="/login">Efetuar login</a>
                        <button type="submit" class="btn btn-primary" >Cadastrar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script>
document.addEventListener('DOMContentLoaded', () => {
    const passwordInput = document.getElementById('passwordInput');
    const toggleBtn      = document.getElementById('togglePasswordBtn');
    const toggleIcon     = document.getElementById('togglePasswordIcon');
    
    
    
    const confirmpasswordInput = document.getElementById('confirmpasswordInput');
    const confirmtoggleBtn      = document.getElementById('confirmtogglePasswordBtn');
    const confirmtoggleIcon     = document.getElementById('confirmtogglePasswordIcon');
    
    
    toggleBtn.addEventListener('click', () => {
        const isPassword = passwordInput.type === 'password';
        passwordInput.type = isPassword ? 'text' : 'password';

        // Alterna o ícone (olho / olho-riscado)
        toggleIcon.classList.toggle('fa-eye-slash',       isPassword);
        toggleIcon.classList.toggle('fa-eye', !isPassword);
    });
    
    confirmtoggleBtn.addEventListener('click', () => {
        const isPassword = confirmpasswordInput.type === 'password';
        confirmpasswordInput.type = isPassword ? 'text' : 'password';

        // Alterna o ícone (olho / olho-riscado)
        confirmtoggleIcon.classList.toggle('fa-eye-slash',       isPassword);
        confirmtoggleIcon.classList.toggle('fa-eye', !isPassword);
    });
});
</script> -->
    </x-guest-layout>