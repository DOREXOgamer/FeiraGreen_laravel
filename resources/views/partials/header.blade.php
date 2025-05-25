<header>
    <div class="logo">
        <a href="{{ route('home') }}"><img src="{{ asset('imagens/logoverde.png') }}" alt="Logo" width="200"></a>
    </div>
    <div id="area-menu">
        <a href="">Frutas</a>
        <a href="">Verduras</a>
        <a href="">Hortaliças</a>
        <a href="">Legumes</a>
        <a href="">Outros</a>
    </div>
    <nav>
        <form method="GET" action="{{ route('buscar') }}" class="barra-pesquisa">
            <input type="text" name="palavra" placeholder="Buscar produto..." required>
            <button type="submit" class="botao-pesquisa">
                <img src="{{ asset('imagens/pesquisa.png') }}" alt="Pesquisar" width="20">
            </button>
        </form>
        <a href="{{ route('perfil') }}"><img src="{{ asset('imagens/usuario.png') }}" alt="Usuário" width="20"></a>
        <a href=""><img src="{{ asset('imagens/carrinho-carrinho.png') }}" alt="Carrinho" width="20"></a>
    </nav>
</header>