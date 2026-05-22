@php
$setting = \App\Helpers\Helper::getSetting();
@endphp
<x-guest-layout :route="'Esqueci a senha'">


    <x-auth-session-status class="mb-4" :status="session('status')" />
    <div class="col-xxl-4 col-xl-5 col-lg-6 col-md-8">
        <div class="mt-5 mb-4 card card-raised shadow-10 mt-xl-10">
            <div class="p-5 card-body">
                <div class="text-center">
                    <img class="mb-3" src="{{ asset($setting->gateway_logo) }}" alt="..." style="height: 48px" />
                    <h1 class="mb-3 display-5">Recuperar senha</h1>
                </div>
                @if (session('success'))
                    <div class="mb-3 alert alert-success">
                        {{ session('success') }}
                    </div>
                    <div class="w-100 text-end">
                        <a type="button" class="btn btn-success" href="/login">
                            Ir para login
                        </a>
                    </div> 
                @else
                <form method="POST" action="{{ route('password.email') }}">
                    @csrf
                    <div class="mb-4">
                        <mwc-textfield class="w-100" label="Email" autofocus type="email" id="email" name="email" value="{{ old('email') }}" outlined>
                            </mwc-textfield>
                        </div>
                       @error('email')
                             <span class="text-danger">{{ $message }} </span>
                        @enderror
                    <div class="mt-4 mb-0 form-group d-flex align-items-center justify-content-between">
                        <a class="small fw-500 text-decoration-none" href="/login">Voltar para login</a>
                        <button type="submit" class="btn btn-primary" >Enviar</button>
                    </div>
                </form>
                @endif
            </div>
        </div>
    </div>
</x-guest-layout>
