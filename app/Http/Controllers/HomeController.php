<?php

namespace App\Http\Controllers;

use App\Models\Produto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class HomeController extends Controller
{
    /**
     * Exibe a página inicial
     */
    public function home()
    {
        $produtos = Produto::all() ?? [];
        $jsonPath = public_path('imagens/imagens.json');
        $imagens = [];

        if (File::exists($jsonPath)) {
            $imagens = json_decode(File::get($jsonPath), true) ?? [];
        }

        return view('home', [
            'produtos' => $produtos,
            'imagens'  => $imagens
        ]);
    }

    /**
     * Processa a busca de produtos
     */
    public function buscar(Request $request)
    {
        $termo = $request->input('termo', '');
        $produtos = Produto::where('nome', 'LIKE', "%{$termo}%")->get();
        return view('busca', compact('produtos', 'termo'));
    }

    /**
     * Área do painel administrativo
     */
    public function dashboard()
    {
        return view('dashboard');
    }

    /**
     * Exibe a página de perfil do usuário
     */
    public function perfil()
    {
        $user     = Auth::user();
        $produtos = $user->produtos ?? collect();
        return view('perfil', compact('user', 'produtos'));
    }

    /**
     * Atualiza o perfil do usuário (nome e imagem)
     */
    public function updatePerfil(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'name'  => 'required|string|max:255',
            'image' => 'nullable|image|max:2048',
        ]);

        $user->name = $request->input('name');

        if ($request->hasFile('image')) {
            if ($user->image) {
                $oldImagePath = public_path('imagens/profile_images/' . $user->image);
                if (file_exists($oldImagePath)) {
                    unlink($oldImagePath);
                }
            }
            $image = $request->file('image');
            $imageName = time() . '_' . $image->getClientOriginalName();
            $destinationPath = public_path('imagens/profile_images');
            $image->move($destinationPath, $imageName);
            $user->image = $imageName;
        }

        $user->save();

        return redirect()->route('perfil')->with('success', 'Perfil atualizado com sucesso!');
    }

    /**
     * Exibe a página de gerenciamento de produtos
     */
    public function indexProdutos()
    {
        $user     = Auth::user();
        $produtos = $user->produtos()->get();
        return view('produto.index', compact('produtos'));
    }

    /**
     * Adiciona um novo produto
     */
    public function addProduto(Request $request)
    {
        $request->validate([
            'nome'      => 'required|string|max:255',
            'preco'     => 'required|numeric',
            'categoria' => 'required|string|max:255',
            'imagem'    => 'nullable|image|max:2048',
        ]);

        $produto = new Produto();
        $produto->nome      = $request->input('nome');
        $produto->preco     = $request->input('preco');
        $produto->categoria = $request->input('categoria');
        $produto->user_id   = Auth::id();

        if ($request->hasFile('imagem')) {
            $image = $request->file('imagem');
            $imageName = time() . '_' . $image->getClientOriginalName();
            $destinationPath = public_path('imagens/product_images');
            $image->move($destinationPath, $imageName);
            $produto->imagem = $imageName;
        }

        $produto->save();

        return redirect()->route('produto.index')->with('success', 'Produto adicionado com sucesso!');
    }

    /**
     * Exibe o formulário de edição de um produto
     */
    public function editProduto($id)
    {
        $produto = Produto::findOrFail($id);
        if ($produto->user_id !== Auth::id()) {
            abort(403, 'Acesso negado');
        }
        return view('produto.edit', compact('produto'));
    }

    /**
     * Atualiza um produto existente
     */
    public function updateProduto(Request $request, $id)
    {
        $produto = Produto::findOrFail($id);
        if ($produto->user_id !== Auth::id()) {
            abort(403, 'Acesso negado');
        }

        $request->validate([
            'nome'      => 'required|string|max:255',
            'preco'     => 'required|numeric',
            'categoria' => 'required|string|max:255',
            'imagem'    => 'nullable|image|max:2048',
        ]);

        $produto->nome      = $request->input('nome');
        $produto->preco     = $request->input('preco');
        $produto->categoria = $request->input('categoria');

        if ($request->hasFile('imagem')) {
            if ($produto->imagem) {
                $oldImagePath = public_path('imagens/product_images/' . $produto->imagem);
                if (file_exists($oldImagePath)) {
                    unlink($oldImagePath);
                }
            }
            $image = $request->file('imagem');
            $imageName = time() . '_' . $image->getClientOriginalName();
            $destinationPath = public_path('imagens/product_images');
            $image->move($destinationPath, $imageName);
            $produto->imagem = $imageName;
        }

        $produto->save();

        return redirect()->route('produto.index')->with('success', 'Produto atualizado com sucesso!');
    }

    /**
     * Remove um produto
     */
    public function deleteProduto($id)
    {
        $produto = Produto::findOrFail($id);
        if ($produto->user_id !== Auth::id()) {
            abort(403, 'Acesso negado');
        }

        if ($produto->imagem) {
            Storage::delete('public/profile_images/' . $produto->imagem);
        }

        $produto->delete();

        return redirect()->route('produto.index')->with('success', 'Produto deletado com sucesso!');
    }

    /**
     * Deleta a conta do usuário e produtos associados
     */
    public function deleteAccount()
    {
        $user = Auth::user();

        foreach ($user->produtos as $produto) {
            if ($produto->imagem) {
                Storage::delete('public/profile_images/' . $produto->imagem);
            }
            $produto->delete();
        }

        if ($user->image) {
            Storage::delete('public/profile_images/' . $user->image);
        }

        $user->delete();
        Auth::logout();

        return redirect('/')->with('success', 'Conta deletada com sucesso.');
    }

    /**
     * Exibe os detalhes de um produto
     */
    public function showProduto($id)
    {
        $produto = Produto::findOrFail($id);
        return view('produto.show', compact('produto'));
    }
    
    public function index()
{
    return $this->home(); // Chama o método home existente
}
}
