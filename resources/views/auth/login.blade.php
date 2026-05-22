@php
  use App\Helpers\Helper;
  $setting = Helper::getSetting();
@endphp
<x-auth-layout :route="'Login'">
  <div class="wrapper">
    <div class="section-authentication-cover">
      <div class="">
        <div class="row g-0">

          <div
            class="col-12 col-xl-7 col-xxl-8 auth-cover-left align-items-center justify-content-center d-none d-xl-flex">

            <div class="card shadow-none bg-transparent shadow-none rounded-0 mb-0">
              <div class="card-body">
                <img src="{{ asset('assets-front/images/login-cover.svg') }}" class="img-fluid auth-img-cover-login"
                  width="650" alt="" />
              </div>
            </div>

          </div>

          <div class="col-12 col-xl-5 col-xxl-4 auth-cover-right bg-light align-items-center justify-content-center">
            <div class="card rounded-0 m-3 shadow-none bg-transparent mb-0">
              <div class="card-body p-sm-5">
                <div class="">
                  <div class="mb-3 text-center">
                    <img src="{{ asset($setting->gateway_logo) }}" width="60" alt="">
                  </div>
                  <div class="text-center mb-4">
                    <h5 class="">{{ $setting->gateway_name }}</h5>
                    <p class="mb-0">Entre com suas credências</p>
                  </div>
                  @if(session('block'))
                    <div class="alert alert-warning" role="alert">
                      {{ session('block') }}<a href="{{session('contato')}}" target="_blank" class="alert-link">Clique
                        aqui</a>. Para reportar ao seu gerente.
                    </div>
                  @endif
                  <div class="form-body">
                    <form method="POST" action="{{ route('login') }}" class="row g-3">
                      @csrf
                      <div class="col-12">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="email" name="email"
                          placeholder="{{ 'cliente@' . str_replace('https://', '', env('APP_URL')) }}"
                          value="{{ old('email') }}">
                      </div>
                      @if (session('error'))
                        <span class="text-danger">{{ session('error') }} </span>
                      @endif
                      <div class="col-12">
                        <label for="inputChoosePassword" class="form-label">Senha</label>
                        <div class="input-group" id="show_hide_password">
                          <input type="password" class="form-control border-end-0" id="password" name="password"
                            value="{{ old('password') }}" placeholder="Digite sua senha"> <a href="javascript:;"
                            class="input-group-text bg-transparent"><i class="bx bx-hide"></i></a>
                        </div>
                      </div>
                      @if (session('error'))
                        <span class="text-danger">{{ session('error') }} </span>
                      @endif
                      <div class="col-md-6">
                        <div class="form-check form-switch">
                          <input class="form-check-input" type="checkbox" id="flexSwitchCheckChecked">
                          <label class="form-check-label" for="flexSwitchCheckChecked">Lembrar-me</label>
                        </div>
                      </div>
                      <div class="col-md-6 text-end"> <a href="#">Esqueceu a senha?</a>
                      </div>
                      <div class="col-12">
                        <div class="d-grid">
                          <button type="submit" class="btn btn-light">Acessar</button>
                        </div>
                      </div>
                      <div class="col-12">
                        <div class="text-center">
                          <p class="mb-0">Ainda não tem cadastro? <a href="/register">Criar conta</a>
                          </p>
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


  <!-- <x-auth-session-status class="mb-4" :status="session('status')" />
    <div class="col-xxl-4 col-xl-5 col-lg-6 col-md-8">
        <div class="mt-5 mb-4 card card-raised shadow-10 mt-xl-10">
            <div class="p-5 card-body">
                <div class="text-center">
                    <img class="mb-3" src="{{ asset($setting->gateway_logo) }}" alt="..." style="height: 48px" />
                    <h1 class="mb-0 display-5">Efetue o login</h1>
                    <div class="mb-5 subheading-1">para continuar</div>
                </div>
                @if(session('block'))
                    <div class="alert alert-warning" role="alert">
                        {{ session('block') }}<a href="{{session('contato')}}" target="_blank" class="alert-link">Clique aqui</a>. Para reportar ao seu gerente.
                    </div>
                @endif
                <form method="POST" action="{{ route('login') }}">
                    @csrf
                    <div class="mb-4">
                        <mwc-textfield class="w-100" label="Email" autofocus type="email" id="email" name="email" value="{{ old('email') }}" outlined>
                            </mwc-textfield>
                        </div>
                        @if (session('error'))
                        <span class="text-danger">{{ session('error') }} </span>
                    @endif
                    <div class="mb-4">
                        
                        <div class="form-outlined">
                            <input type="password" id="passwordInput" name="password" value="{{ old('password') }}" class="form-control" placeholder=" " />
                            <label for="passwordInput">Senha</label>
                            <button type="button" id="togglePassword" class="toggle-visibility">
                                <i class="bi bi-eye-slash"></i>
                            </button>
                        </div>
                        @if (session('error'))
                        <span class="text-danger">{{ session('error') }} </span>
                    @endif
                    <div class="d-flex align-items-center">
                        <mwc-formfield label="Lembrar-me">
                            <mwc-checkbox>
                                </mwc-checkbox>
                            </mwc-formfield> 
                    </div>
                    <div class="mt-4 mb-0 form-group d-flex align-items-center justify-content-between">
                        <a class="small fw-500 text-decoration-none" href="{{ route('password.request') }}">Esqueci a senha</a>
                    </div>
                    <div class="mt-4 mb-0 form-group d-flex align-items-center justify-content-between">
                        <a class="small fw-500 text-decoration-none" href="/register">Cadastrar-me</a>
                        <button type="submit" class="btn btn-primary" >Acessar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

 <script>
  document.addEventListener("DOMContentLoaded", function () {
    const toggleBtn = document.getElementById("togglePassword");
    const passwordInput = document.getElementById("passwordInput");
    const icon = toggleBtn.querySelector("i");

    toggleBtn.addEventListener("click", function () {
      const isPassword = passwordInput.type === "password";
      passwordInput.type = isPassword ? "text" : "password";
      icon.classList.toggle("bi-eye");
      icon.classList.toggle("bi-eye-slash");
    });
  });
</script> -->
</x-auth-layout>