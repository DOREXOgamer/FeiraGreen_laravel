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
        // Produtos em Destaque (ou todos os produtos se não houver lógica de destaque)
        $produtosDestaque = Produto::all(); // Pode ajustar para pegar produtos em destaque reais se tiver um campo 'destaque' no model

        $jsonPath = public_path('imagens/imagens.json');
        $imagens = [];

        if (File::exists($jsonPath)) {
            $imagens = json_decode(File::get($jsonPath), true) ?? [];
        }

        $legumes = Produto::where('categoria', 'Legumes')->get();

        return view('home', [
            'produtos' => $produtosDestaque, // Usado para "Produtos em Destaque"
            'imagens'  => $imagens,
            'legumes'  => $legumes // <<< Nova variável para a seção de legumes
        ]);
    }

    /**
     * Processa a busca de produtos
     */
    public function buscar(Request $request)
    {
        // Adicionando validação para o termo de busca
        $request->validate([
            'termo' => 'nullable|string|max:100', // Limite de 100 caracteres para o termo de busca
        ], [
            'termo.max' => 'O termo de busca não pode ter mais de 100 caracteres.',
            'termo.string' => 'O termo de busca deve ser um texto válido.'
        ]);

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
            'preco'     => 'required|numeric|min:0.01|max:5000.00', // Adicionando min e max para o preço
            'categoria' => 'required|string|max:255', // Ajustei para garantir que a categoria também tem um max de caracteres.
            'imagem'    => 'required|image|max:2048', // <<< ALTERADO: 'nullable' removido, agora é obrigatório
        ], [
            'preco.min' => 'O preço deve ser no mínimo R$ 0,01.',
            'preco.max' => 'O preço não pode exceder R$ 5.000,00.',
            'imagem.required' => 'A imagem do produto é obrigatória.', // <<< Mensagem de erro personalizada
            'imagem.image' => 'O arquivo deve ser uma imagem válida.',
            'imagem.max' => 'A imagem não pode ter mais de 2MB.'
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

        $rules = [
            'nome'      => 'required|string|max:255',
            'preco'     => 'required|numeric|min:0.01|max:5000.00',
            'categoria' => 'required|string|max:255',
            // 'imagem' => 'nullable|image|max:2048', // Original
        ];

        // Se o produto não tiver uma imagem, torne a imagem obrigatória na atualização também.
        // Se já tiver uma imagem e o usuário não enviar uma nova, mantém a existente.
        if (!$produto->imagem) {
            $rules['imagem'] = 'required|image|max:2048'; // Torna obrigatório se não houver imagem
        } else {
            $rules['imagem'] = 'nullable|image|max:2048'; // Permite que a imagem seja opcional se já existir uma
        }


        $request->validate($rules, [
            'preco.min' => 'O preço deve ser no mínimo R$ 0,01.',
            'preco.max' => 'O preço não pode exceder R$ 5.000,00.',
            'imagem.required' => 'A imagem do produto é obrigatória se não houver uma existente.', // <<< Mensagem de erro personalizada
            'imagem.image' => 'O arquivo deve ser uma imagem válida.',
            'imagem.max' => 'A imagem não pode ter mais de 2MB.'
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

        // Correção no caminho do Storage para deletar imagem de produto
        if ($produto->imagem) {
            $imagePath = 'public/product_images/' . $produto->imagem; // Corrigido para product_images
            if (Storage::exists($imagePath)) {
                Storage::delete($imagePath);
            }
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
            // Correção no caminho do Storage para deletar imagem de produto
            if ($produto->imagem) {
                $imagePath = 'public/product_images/' . $produto->imagem; // Corrigido para product_images
                if (Storage::exists($imagePath)) {
                    Storage::delete($imagePath);
                }
            }
            $produto->delete();
        }

        // Correção no caminho do Storage para deletar imagem de perfil
        if ($user->image) {
            $imagePath = 'public/profile_images/' . $user->image; // Corrigido para profile_images
            if (Storage::exists($imagePath)) {
                Storage::delete($imagePath);
            }
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