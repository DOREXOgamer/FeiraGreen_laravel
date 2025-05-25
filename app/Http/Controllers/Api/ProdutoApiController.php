<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Produto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class ProdutoApiController extends Controller
{
    // List all products
    public function index()
    {
        $produtos = Produto::all();
        return response()->json($produtos);
    }

    // Show a single product
    public function show($id)
    {
        $produto = Produto::find($id);
        if (!$produto) {
            return response()->json(['message' => 'Produto não encontrado'], 404);
        }
        return response()->json($produto);
    }

    // Create a new product
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nome' => 'required|string|max:255',
            'preco' => 'required|numeric',
            'categoria' => 'required|string|max:255',
            'imagem' => 'nullable|image|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $produto = new Produto();
        $produto->nome = $request->input('nome');
        $produto->preco = $request->input('preco');
        $produto->categoria = $request->input('categoria');

        if ($request->hasFile('imagem')) {
            $path = $request->file('imagem')->store('public/product_images');
            $produto->imagem = basename($path);
        }

        $produto->save();

        return response()->json($produto, 201);
    }

    // Update an existing product
    public function update(Request $request, $id)
    {
        $produto = Produto::find($id);
        if (!$produto) {
            return response()->json(['message' => 'Produto não encontrado'], 404);
        }

        $validator = Validator::make($request->all(), [
            'nome' => 'sometimes|required|string|max:255',
            'preco' => 'sometimes|required|numeric',
            'categoria' => 'sometimes|required|string|max:255',
            'imagem' => 'nullable|image|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        if ($request->has('nome')) {
            $produto->nome = $request->input('nome');
        }
        if ($request->has('preco')) {
            $produto->preco = $request->input('preco');
        }
        if ($request->has('categoria')) {
            $produto->categoria = $request->input('categoria');
        }
        if ($request->hasFile('imagem')) {
            // Delete old image if exists
            if ($produto->imagem) {
                Storage::delete('public/product_images/' . $produto->imagem);
            }
            $path = $request->file('imagem')->store('public/product_images');
            $produto->imagem = basename($path);
        }

        $produto->save();

        return response()->json($produto);
    }

    // Delete a product
    public function destroy($id)
    {
        $produto = Produto::find($id);
        if (!$produto) {
            return response()->json(['message' => 'Produto não encontrado'], 404);
        }

        // Delete image if exists
        if ($produto->imagem) {
            Storage::delete('public/product_images/' . $produto->imagem);
        }

        $produto->delete();

        return response()->json(['message' => 'Produto deletado com sucesso']);
    }
}
