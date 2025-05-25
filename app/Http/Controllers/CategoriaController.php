<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Produto;

class CategoriaController extends Controller
{
    public function exibirPorCategoria($categoria)
    {
        $produtos = Produto::where('categoria', $categoria)->get();
        return view('categorias.index', compact('produtos', 'categoria'));
    }
}
