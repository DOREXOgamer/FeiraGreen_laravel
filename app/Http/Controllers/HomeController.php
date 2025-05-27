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
     * Exibe a página inicial com produtos de categorias específicas.
     */
    public function home()
    {
        $categoriasDesejadas = ['Frutas', 'Hortaliças', 'Verduras',];
        $produtosSelecionados = Produto::whereIn('categoria', $categoriasDesejadas)->get();

        $jsonPath = public_path('imagens/imagens.json');
        $imagens = File::exists($jsonPath) ? json_decode(File::get($jsonPath), true) ?? [] : [];

        $legumes = Produto::where('categoria', 'Legumes')->get();


        return view('home', [
            'produtos' => $produtosSelecionados,
            'imagens'  => $imagens,
            'legumes'  => $legumes,
        ]);
    }



    /**
     * Processa a busca de produtos pelo nome.
     */
    public function buscar(Request $request)
    {
        $request->validate([
            'termo' => 'nullable|string|max:100',
        ], [
            'termo.max' => 'O termo de busca não pode ter mais de 100 caracteres.',
            'termo.string' => 'O termo de busca deve ser um texto válido.'
        ]);

        $termo = $request->input('termo', '');
        $produtos = Produto::where('nome', 'LIKE', "%{$termo}%")->get();

        return view('busca', compact('produtos', 'termo'));
    }

    /**
     * Exibe o painel administrativo.
     */
    public function dashboard()
    {
        return view('dashboard');
    }

    /**
     * Exibe o perfil do usuário logado.
     */
    public function perfil()
    {
        $user = Auth::user();
        $produtos = $user->produtos ?? collect();
        return view('perfil', compact('user', 'produtos'));
    }

    /**
     * Atualiza nome e imagem do perfil do usuário.
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
            $image->move(public_path('imagens/profile_images'), $imageName);
            $user->image = $imageName;
        }

        $user->save();

        return redirect()->route('perfil')->with('success', 'Perfil atualizado com sucesso!');
    }

    /**
     * Exibe todos os produtos do usuário logado.
     */
    public function indexProdutos()
    {
        $user = Auth::user();
        $produtos = $user->produtos()->get();
        return view('produto.index', compact('produtos'));
    }

    /**
     * Adiciona um novo produto.
     */
    public function addProduto(Request $request)
    {
        $request->validate([
            'nome'      => 'required|string|max:50',
            'preco'     => 'required|numeric|min:0.01|max:5000.00',
            'categoria' => 'required|string|max:255',
            'imagem'    => 'required|image|max:2048',
        ], [
            'preco.min' => 'O preço deve ser no mínimo R$ 0,01.',
            'preco.max' => 'O preço não pode exceder R$ 5.000,00.',
            'imagem.required' => 'A imagem do produto é obrigatória.',
            'imagem.image' => 'O arquivo deve ser uma imagem válida.',
            'imagem.max' => 'A imagem não pode ter mais de 2MB.'
        ]);

        $produto = new Produto();
        $produto->fill($request->only(['nome', 'preco', 'categoria']));
        $produto->user_id = Auth::id();

        if ($request->hasFile('imagem')) {
            $image = $request->file('imagem');
            $imageName = time() . '_' . $image->getClientOriginalName();
            $image->move(public_path('imagens/product_images'), $imageName);
            $produto->imagem = $imageName;
        }

        $produto->save();

        return redirect()->route('produto.index')->with('success', 'Produto adicionado com sucesso!');
    }

    /**
     * Exibe o formulário de edição de um produto.
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
     * Atualiza os dados de um produto.
     */
    public function updateProduto(Request $request, $id)
    {
        $produto = Produto::findOrFail($id);
        if ($produto->user_id !== Auth::id()) {
            abort(403, 'Acesso negado');
        }

        $rules = [
            'nome'      => 'required|string|max:50',
            'preco'     => 'required|numeric|min:0.01|max:5000.00',
            'categoria' => 'required|string|max:255',
        ];

        $rules['imagem'] = $produto->imagem ? 'nullable|image|max:2048' : 'required|image|max:2048';

        $request->validate($rules, [
            'preco.min' => 'O preço deve ser no mínimo R$ 0,01.',
            'preco.max' => 'O preço não pode exceder R$ 5.000,00.',
            'imagem.required' => 'A imagem do produto é obrigatória se não houver uma existente.',
            'imagem.image' => 'O arquivo deve ser uma imagem válida.',
            'imagem.max' => 'A imagem não pode ter mais de 2MB.'
        ]);

        $produto->fill($request->only(['nome', 'preco', 'categoria']));

        if ($request->hasFile('imagem')) {
            if ($produto->imagem) {
                $oldImagePath = public_path('imagens/product_images/' . $produto->imagem);
                if (file_exists($oldImagePath)) {
                    unlink($oldImagePath);
                }
            }

            $image = $request->file('imagem');
            $imageName = time() . '_' . $image->getClientOriginalName();
            $image->move(public_path('imagens/product_images'), $imageName);
            $produto->imagem = $imageName;
        }

        $produto->save();

        return redirect()->route('produto.index')->with('success', 'Produto atualizado com sucesso!');
    }

    /**
     * Remove um produto.
     */
    public function deleteProduto($id)
    {
        $produto = Produto::findOrFail($id);
        if ($produto->user_id !== Auth::id()) {
            abort(403, 'Acesso negado');
        }

        if ($produto->imagem) {
            $imagePath = 'public/product_images/' . $produto->imagem;
            if (Storage::exists($imagePath)) {
                Storage::delete($imagePath);
            }
        }

        $produto->delete();

        return redirect()->route('produto.index')->with('success', 'Produto deletado com sucesso!');
    }

    /**
     * Deleta a conta do usuário e todos os produtos associados.
     */
    public function deleteAccount()
    {
        $user = Auth::user();

        foreach ($user->produtos as $produto) {
            if ($produto->imagem) {
                $imagePath = 'public/product_images/' . $produto->imagem;
                if (Storage::exists($imagePath)) {
                    Storage::delete($imagePath);
                }
            }
            $produto->delete();
        }

        if ($user->image) {
            $imagePath = 'public/profile_images/' . $user->image;
            if (Storage::exists($imagePath)) {
                Storage::delete($imagePath);
            }
        }

        $user->delete();
        Auth::logout();

        return redirect('/')->with('success', 'Conta deletada com sucesso.');
    }

    /**
     * Exibe os detalhes de um produto.
     */
    public function showProduto($id)
    {
        $produto = Produto::findOrFail($id);
        return view('produto.show', compact('produto'));
    }

    /**
     * Redireciona para a home.
     */
    public function index()
    {
        return $this->home();
    }
}
