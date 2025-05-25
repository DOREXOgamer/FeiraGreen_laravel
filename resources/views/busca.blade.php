@extends('layouts.main')

@section('head')
<link rel="stylesheet" href="{{ asset('css/search_results.css') }}">
@endsection

@section('content')
<div class="search-container">
    <div class="search-header">
        <h1>Resultados da Busca: <span class="search-term">"{{ $termo }}"</span></h1>
    </div>

    @if($produtos->isEmpty())
        <div class="search-empty">
            <p>Nenhum produto encontrado para "{{ $termo }}"</p>
            <p>Tente buscar por outro termo ou navegue por nossas categorias.</p>
        </div>
    @else
        <ul class="search-results">
            @foreach($produtos as $produto)
                <div class="produto-container-principal card p-3" style="width: 18rem;">
                    <a href="{{ route('produto.show', $produto->id) }}" aria-label="Ver detalhes do produto {{ $produto->nome }}">
                        <img src="{{ asset('imagens/product_images/' . $produto->imagem) }}" 
                            alt="{{ $produto->nome }}" 
                            class="card-img-top img-fluid" 
                            onerror="this.onerror=null; this.src='{{ asset('imagens/product_images/default-product.jpg') }}';" />
                    </a>
                    <div class="card-body text-center">
                        <h5 class="card-title">{{ $produto->nome }}</h5>
                        <p class="card-text">R$ {{ number_format($produto->preco, 2, ',', '.') }}</p>
                        <p class="card-text text-muted">{{ $produto->categoria }}</p>
                    </div>
                </div>
            @endforeach
        </ul>
        
        @if(isset($produtos) && method_exists($produtos, 'links'))
            <div class="search-pagination">
                {{ $produtos->appends(['termo' => $termo])->links() }}
            </div>
        @endif
    @endif
</div>
@endsection