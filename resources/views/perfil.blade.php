@extends('layouts.main')

@section('head')
<link rel="stylesheet" href="{{ asset('css/perfil_custom.css') }}">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
@endsection

@section('content')
<div class="container perfil-container">
    <!-- Cabeçalho da página -->
    <div class="perfil-header">
        <h1>Meu Perfil</h1>
        <p>Gerencie suas informações pessoais e configurações da conta</p>
    </div>

    <!-- Mensagem de sucesso -->
    @if(session('success'))
        <div class="alert alert-success">
            <i class="fas fa-check-circle"></i>
            {{ session('success') }}
        </div>
    @endif

    <!-- Card de Informações Pessoais -->
    <div class="card">
        <div class="card-header">
            <h2>Informações Pessoais</h2>
        </div>
        <div class="card-body">
            <form action="{{ route('perfil.update') }}" method="POST" enctype="multipart/form-data">
                @csrf
                
                <div class="perfil-info">
                    <!-- Imagem de Perfil - Corrigida -->
                    <div class="profile-image-container">
                        @if($user->image)
                            <img src="{{ asset('imagens/profile_images/' . $user->image) }}" alt="Foto de Perfil" class="profile-image-preview">
                        @else
                            <div class="profile-image-placeholder">
                                <i class="fas fa-user"></i>
                            </div>
                        @endif
                    </div>
                    
                    <!-- Novo botão de seleção de arquivo -->
                    <label for="image" class="file-upload-button">
                        <i class="fas fa-camera"></i> Alterar foto
                    </label>
                    <input type="file" id="image" name="image" accept="image/*" class="form-control">
                    
                    @error('image')
                        <div class="text-danger text-center mb-3">{{ $message }}</div>
                    @enderror
                    
                    <!-- Nome -->
                    <div class="form-group">
                        <label for="name" class="form-label">Nome Completo</label>
                        <input type="text" id="name" name="name" value="{{ old('name', $user->name) }}" required class="form-control">
                        @error('name')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <button type="submit" class="btn btn-primary update-btn">
                        Salvar Alterações
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Card de Ações da Conta -->
    <div class="card">
        <div class="card-header">
            <h2>Gerenciar Conta</h2>
        </div>
        <div class="card-body">
            <div class="account-actions">
                <!-- Meus Produtos -->
                <div class="action-card">
                    <h3 class="action-title">Meus Produtos</h3>
                    <p class="action-description">Gerencie seus produtos, adicione novos ou edite os existentes.</p>
                    <a href="{{ route('produto.index') }}" class="action-button action-button-primary">
                        Acessar
                    </a>
                </div>
                
                <!-- Sair -->
                <div class="action-card">
                    <h3 class="action-title">Sair</h3>
                    <p class="action-description">Encerre sua sessão atual no sistema FeiraGreen.</p>
                    <form action="{{ route('logout') }}" method="POST" style="width: 100%;">
                        @csrf
                        <button type="submit" class="action-button action-button-primary">
                            Sair
                        </button>
                    </form>
                </div>
                
                <!-- Excluir Conta -->
                <div class="action-card">
                    <h3 class="action-title">Excluir Conta</h3>
                    <p class="action-description">Exclua permanentemente sua conta e todos os seus dados.</p>
                    <form action="{{ route('perfil.delete') }}" method="POST" style="width: 100%;" onsubmit="return confirm('Tem certeza que deseja excluir sua conta? Esta ação não pode ser desfeita.')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="action-button action-button-danger">
                            Excluir
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection