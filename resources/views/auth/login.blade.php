@extends('layouts.main')

@section('content')
<div class="formulario-login">
    <div class="container">
        <div class="centralizar">
            <div class="imgen">
                <img src="{{ asset('imagens/logoverde.png') }}" alt="Feira Green Logo">
            </div>
            
            <form method="POST" action="{{ route('login') }}">
                @csrf
                
                <div class="form-group">
                    <label for="email"><b>Email</b></label>
                    <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" 
                           name="email" value="{{ old('email') }}" required autocomplete="email" autofocus
                           placeholder="Insira seu email">
                    
                    @error('email')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="password" class="local-senha"><b>Senha</b></label>
                    <input id="password" type="password" 
                           class="form-control @error('password') is-invalid @enderror" 
                           name="password" required autocomplete="current-password"
                           placeholder="Insira sua senha">
                    
                    @error('password')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <div class="form-group">
                    <button type="submit" class="btn btn-primary">
                        Entrar
                    </button>
                </div>

                <div class="form-group">
                   
                    <a href="{{ route('register') }}">Criar uma conta</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection