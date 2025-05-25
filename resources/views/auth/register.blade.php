@extends('layouts.main')

@section('content')
<div class="register-container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="register-card">
                <div class="card-header">Criar Conta</div>

                <div class="card-body">
                
                    @if ($errors->any())
                        <div class="register-alert">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ str_replace(['The ', ' field is required.', ' must be a valid email address.', ' must be at least 8 characters.', ' confirmation does not match.', ' has already been taken.'], ['O campo ', ' é obrigatório.', ' deve ser um endereço de email válido.', ' deve ter pelo menos 8 caracteres.', ' de confirmação não corresponde.', ' já está em uso.'], $error) }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('register') }}" enctype="multipart/form-data" class="register-form">
                        @csrf

                        <div class="register-row">
                            <label for="name" class="register-label">Nome</label>

                            <div class="register-input-container">
                                <input id="name" type="text" class="register-control @error('name') register-invalid @enderror" name="name" value="{{ old('name') }}" required autocomplete="name" autofocus placeholder="Digite seu nome completo">

                                @error('name')
                                    <span class="register-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="register-row">
                            <label for="email" class="register-label">Email</label>

                            <div class="register-input-container">
                                <input id="email" type="email" class="register-control @error('email') register-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" placeholder="Digite seu email">

                                @error('email')
                                    <span class="register-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="register-row">
                            <label for="password" class="register-label">Senha</label>

                            <div class="register-input-container">
                                <input id="password" type="password" class="register-control @error('password') register-invalid @enderror" name="password" required autocomplete="new-password" placeholder="Digite sua senha">

                                @error('password')
                                    <span class="register-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="register-row">
                            <label for="password-confirm" class="register-label">Confirmar Senha</label>

                            <div class="register-input-container">
                                <input id="password-confirm" type="password" class="register-control" name="password_confirmation" required autocomplete="new-password" placeholder="Confirme sua senha">
                            </div>
                        </div>

                        <div class="register-row">
                            <label for="image" class="register-label">Imagem de Perfil</label>

                            <div class="register-input-container">
                                <input id="image" type="file" class="register-control @error('image') register-invalid @enderror" name="image" accept="image/*">
                                <small class="register-help-text">Opcional: Escolha uma imagem para seu perfil</small>

                                @error('image')
                                    <span class="register-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="register-row">
                            <div class="register-button-container">
                                <button type="submit" class="register-button">
                                    Criar Conta
                                </button>
                                
                                <div class="register-links">
                                    <a href="{{ route('login') }}" class="register-link">Já tem uma conta? Faça login</a>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection