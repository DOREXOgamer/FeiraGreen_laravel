@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-lg border-0 rounded-lg">
                <div class="card-header bg-gradient-primary text-white py-4">
                    <h3 class="text-center mb-0">{{ __('Verifique seu Endereço de Email') }}</h3>
                </div>

                <div class="card-body p-5">
                    <div class="text-center mb-4">
                        <svg xmlns="http://www.w3.org/2000/svg" width="64" height="64" fill="#4e73df" class="bi bi-envelope-check" viewBox="0 0 16 16">
                            <path d="M2 2a2 2 0 0 0-2 2v8.01A2 2 0 0 0 2 14h5.5a.5.5 0 0 0 0-1H2a1 1 0 0 1-.966-.741l5.64-3.471L8 9.583l7-4.2V8.5a.5.5 0 0 0 1 0V4a2 2 0 0 0-2-2H2Zm3.708 6.208L1 11.105V5.383l4.708 2.825ZM1 4.217V4a1 1 0 0 1 1-1h12a1 1 0 0 1 1 1v.217l-7 4.2-7-4.2Z"/>
                            <path d="M16 12.5a3.5 3.5 0 1 1-7 0 3.5 3.5 0 0 1 7 0Zm-1.993-1.679a.5.5 0 0 0-.686.172l-1.17 1.95-.547-.547a.5.5 0 0 0-.708.708l.774.773a.75.75 0 0 0 1.174-.144l1.335-2.226a.5.5 0 0 0-.172-.686Z"/>
                        </svg>
                    </div>

                    @if (session('resent'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="fas fa-check-circle me-2"></i>
                            {{ __('Um novo link de verificação foi enviado para seu endereço de email.') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fechar"></button>
                        </div>
                    @endif

                    <div class="text-center mb-4">
                        <p class="lead">{{ __('Antes de continuar, por favor, verifique seu email para o link de verificação.') }}</p>
                        <p class="text-muted">{{ __('Se você não recebeu o email') }},</p>
                    </div>

                    <div class="d-grid gap-2 col-md-6 mx-auto">
                        <form method="POST" action="{{ route('verification.resend') }}">
                            @csrf
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="fas fa-paper-plane me-2"></i>
                                {{ __('Clique aqui para solicitar outro') }}
                            </button>
                        </form>
                    </div>

                    <div class="text-center mt-4">
                        <p class="small text-muted">Está tendo problemas? Entre em contato com nossa <a href="{{ route('contact') }}">equipe de suporte</a>.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
