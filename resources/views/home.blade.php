@extends('layouts.main')

@section('head')
<link rel="stylesheet" href="{{ asset('css/carousel.css') }}">
<link rel="stylesheet" href="{{ asset('css/home.css') }}">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
@endsection

@section('content')


@if(session('login_success') && Auth::check())
    <div id="popup-bemvindo" class="popup-bemvindo">
        <h2>Bem-vindo, {{ Auth::user()->name }}!</h2>
        <p>Aproveite os produtos fresquinhos da agricultura familiar e fique de olho nas promoções!</p>
        <button onclick="document.getElementById('popup-bemvindo').style.display='none'" class="btn-fechar-popup">
            Fechar
        </button>
    </div>

    <script>
        setTimeout(() => {
            const popup = document.getElementById('popup-bemvindo');
            if (popup) popup.style.display = 'none';
        }, 8000); // Fecha depois de 8 segundos
    </script>
@endif

<section id="home" class="full-width-banner">
    @if (session('error'))
        <div class="alert alert-danger w-100 text-center">{{ session('error') }}</div>
    @endif

    {{-- Carrossel de imagens --}}
    @isset($imagens)
        @include('partials.carousel', ['imagens' => $imagens])
    @else
        <div class="alert alert-warning w-100 text-center">Nenhuma imagem disponível</div>
    @endisset
</section>


<hr class="my-4">

<section class="promocao-header">
    <h2 class="promocao-header--title">Produtos em Destaque</h2>
    <p>Conheça alguns dos nossos produtos mais populares, cultivados com carinho por famílias agricultoras.</p>
</section>

<div class="container-principal">
    @forelse($produtos ?? [] as $produto)
        <div class="produto-container-principal">
            @if($produto->organico)
                <span class="badge-organico">Orgânico</span>
            @endif
            <a href="{{ route('produto.show', $produto->id) }}" aria-label="Ver detalhes do produto {{ $produto->nome }}">
                <img src="{{ asset('imagens/product_images/' . $produto->imagem) }}"
                     alt="{{ $produto->nome }}"
                     class="card-img-top"
                     onerror="this.onerror=null; this.src='{{ asset('imagens/product_images/default-product.jpg') }}';" />
            </a>
            <div class="card-body">
                <h5 class="card-title">{{ $produto->nome }}</h5>
                @if($produto->produtor)
                    <p class="card-text text-muted">{{ $produto->produtor }}</p>
                @else
                    <p class="card-text text-muted">{{ $produto->categoria }}</p>
                @endif

                <div class="produto-footer">
                    <p class="card-text">R$ {{ number_format($produto->preco, 2, ',', '.') }}</p>

                    <form action="{{ route('cart.add', $produto->id) }}" method="POST" style="display:inline-block; margin-left: 10px;">
                        @csrf
                        <button type="submit" class="btn-adicionar">
                            <i class="bi bi-cart"></i> Adicionar
                        </button>
                    </form>
                </div>
            </div>
        </div>
    @empty
        <div class="w-100 text-center">
            <p>Nenhum produto em destaque encontrado.</p>
        </div>
    @endforelse
</div>

{{-- Seção Por Que Escolher a Agricultura Familiar --}}
<section class="porque-escolher">
    <div class="porque-escolher-container">
        <div class="porque-escolher-image">
            <img src="{{ asset('imagens/Agricultura-familiar.png') }}"
                 alt="Agricultura Familiar"
                 onerror="this.onerror=null; this.src='{{ asset('imagens/product_images/default-product.jpg') }}';" />
        </div>

        <div class="porque-escolher-content">
            <h2 class="porque-escolher-title">Por Que Escolher a Agricultura Familiar?</h2>

            <ul class="beneficios-list">
                <li class="beneficio-item">
                    <div class="beneficio-icon">
                        <img src="{{ asset('imagens/folha.png') }}" alt="Ícone de folha" width="40" height="40">
                    </div>
                    <div class="beneficio-content">
                        <h3 class="beneficio-title">Sustentabilidade</h3>
                        <p class="beneficio-text">Práticas agrícolas que respeitam o meio ambiente e preservam recursos naturais.</p>
                    </div>
                </li>

                <li class="beneficio-item">
                    <div class="beneficio-icon">
                        <img src="{{ asset('imagens/caminhao.png') }}" alt="Ícone de caminhão" width="40" height="40">
                    </div>
                    <div class="beneficio-content">
                        <h3 class="beneficio-title">Economia Local</h3>
                        <p class="beneficio-text">Ao comprar diretamente dos produtores, você fortalece a economia da sua região.</p>
                    </div>
                </li>

                <li class="beneficio-item">
                    <div class="beneficio-icon">
                        <img src="{{ asset('imagens/carrinho.png') }}" alt="Ícone de carrinho" width="40" height="40">
                    </div>
                    <div class="beneficio-content">
                        <h3 class="beneficio-title">Qualidade Superior</h3>
                        <p class="beneficio-text">Alimentos mais frescos, saborosos e nutritivos, colhidos no ponto certo.</p>
                    </div>
                </li>
            </ul>

        </div>
    </div>
</section>

<hr class="my-4">

{{-- NOVA SEÇÃO DE PRODUTOS: AGORA EXIBINDO LEGUMES --}}
<section class="promocao-header">
    <h2 class="promocao-header--title">Descubra Mais Produtos Frescos</h2>
    <p>Conheça a variedade de legumes frescos e saudáveis, colhidos diretamente para você.</p>
</section>

<div class="container-principal">
    @forelse($legumes ?? [] as $produto) 
        <div class="produto-container-principal">
            @if($produto->organico)
                <span class="badge-organico">Orgânico</span>
            @endif
            <a href="{{ route('produto.show', $produto->id) }}" aria-label="Ver detalhes do produto {{ $produto->nome }}">
                <img src="{{ asset('imagens/product_images/' . $produto->imagem) }}"
                     alt="{{ $produto->nome }}"
                     class="card-img-top"
                     onerror="this.onerror=null; this.src='{{ asset('imagens/product_images/default-product.jpg') }}';" />
            </a>
            <div class="card-body">
                <h5 class="card-title">{{ $produto->nome }}</h5>
                @if($produto->produtor)
                    <p class="card-text text-muted">{{ $produto->produtor }}</p>
                @else
                    <p class="card-text text-muted">{{ $produto->categoria }}</p>
                @endif

                <div class="produto-footer">
                    <p class="card-text">R$ {{ number_format($produto->preco, 2, ',', '.') }}</p>

                    <form action="{{ route('cart.add', $produto->id) }}" method="POST" style="display:inline-block; margin-left: 10px;">
                        @csrf
                        <button type="submit" class="btn-adicionar">
                            <i class="bi bi-cart"></i> Adicionar
                        </button>
                    </form>
                </div>
            </div>
        </div>
    @empty
        <div class="w-100 text-center">
            <p>Nenhum legume disponível no momento.</p> {{-- Mensagem atualizada --}}
        </div>
    @endforelse
</div>
{{-- FIM DA NOVA SEÇÃO DE PRODUTOS --}}

@endsection