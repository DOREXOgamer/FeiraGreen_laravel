<?php

namespace App\Http\Controllers;

use App\Models\Produto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

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
        'name'  => ['required', 'string', 'max:100', 'regex:/^[A-Za-zÀ-ÿ\s]+$/u'],
        'image' => 'nullable|mimes:jpeg,png,jpg,webp|max:2048',
    ], [
        'name.required' => 'O nome é obrigatório.',
        'name.string' => 'O nome deve ser um texto.',
        'name.max' => 'O nome não pode ter mais de 100 caracteres.',
        'name.regex' => 'O nome deve conter apenas letras e espaços.',
        'image.mimes' => 'O arquivo de imagem deve ser do tipo: jpeg, png, jpg, webp.',
        'image.max' => 'A imagem não pode ter mais de 2MB.'
    ]);

    $user->name = $request->input('name');

    if ($request->hasFile('image')) {
        // Deleta a imagem antiga se existir
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
        'categoria' => ['required', 'string', 'max:255', Rule::in(['Frutas', 'Verduras', 'Hortaliças', 'Legumes', 'Outros'])],
        'imagem'    => 'required|mimes:jpeg,png,jpg,webp|max:2048',
    ], [
        'preco.min' => 'O preço deve ser no mínimo R$ 0,01.',
        'preco.max' => 'O preço não pode exceder R$ 5.000,00.',
        'imagem.required' => 'A imagem do produto é obrigatória.',
        'imagem.mimes' => 'A imagem deve ser de um dos seguintes tipos: jpeg, png, jpg, webp.',
        'imagem.max' => 'A imagem não pode ter mais de 2MB.',
        'categoria.in' => 'A categoria selecionada é inválida.', 
    ]);

    $produto = new Produto();
    $produto->fill($request->only(['nome', 'preco', 'categoria']));
    $produto->user_id = Auth::id();

    if ($request->hasFile('imagem')) {
        $image = $request->file('imagem');
        $imageName = time() . '_' . $image->getClientOriginalName();

        $path = public_path('imagens/product_images');
        if (!File::isDirectory($path)) {
            File::makeDirectory($path, 0777, true, true);
        }
        $image->move($path, $imageName);
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

        // Validação da imagem atualizada para tipos específicos
        $rules['imagem'] = $produto->imagem ? 'nullable|mimes:jpeg,png,jpg,webp|max:2048' : 'required|mimes:jpeg,png,jpg,webp|max:2048';

        $request->validate($rules, [
            'preco.min' => 'O preço deve ser no mínimo R$ 0,01.',
            'preco.max' => 'O preço não pode exceder R$ 5.000,00.',
            'imagem.required' => 'A imagem do produto é obrigatória se não houver uma existente.',
            // Mensagem de erro para mimes adicionada
            'imagem.mimes' => 'A imagem deve ser de um dos seguintes tipos: jpeg, png, jpg, webp.',
            'imagem.max' => 'A imagem não pode ter mais de 2MB.'
        ]);

        $produto->fill($request->only(['nome', 'preco', 'categoria']));

        if ($request->hasFile('imagem')) {
            // Deleta a imagem antiga se existir
            if ($produto->imagem) {
                $oldImagePath = public_path('imagens/product_images/' . $produto->imagem);
                if (file_exists($oldImagePath)) {
                    unlink($oldImagePath);
                }
            }

            $image = $request->file('imagem');
            $imageName = time() . '_' . $image->getClientOriginalName();
            // Garanta que o diretório exista ou crie-o
            $path = public_path('imagens/product_images');
             if(!File::isDirectory($path)){
                File::makeDirectory($path, 0777, true, true);
            }
            $image->move($path, $imageName);
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

        // Ajuste para usar public_path ao deletar arquivos diretamente
        if ($produto->imagem) {
            $imagePath = public_path('imagens/product_images/' . $produto->imagem);
            if (File::exists($imagePath)) {
                File::delete($imagePath);
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

        // Deleta imagens de produtos
        foreach ($user->produtos as $produto) {
            if ($produto->imagem) {
                $imagePath = public_path('imagens/product_images/' . $produto->imagem);
                 if (File::exists($imagePath)) {
                    File::delete($imagePath);
                }
            }
            $produto->delete(); // Deleta o produto do banco de dados
        }

        // Deleta imagem de perfil
        if ($user->image) {
            $imagePath = public_path('imagens/profile_images/' . $user->image);
            if (File::exists($imagePath)) {
                File::delete($imagePath);
            }
        }
        
        $userName = $user->name; // Guarda o nome para a mensagem antes de deletar
        $user->delete();
        Auth::logout();

        return redirect('/')->with('success', 'Conta de ' . $userName . ' deletada com sucesso.');
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