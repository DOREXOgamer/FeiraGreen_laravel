<?php

namespace App\Http\Controllers;

use App\Models\Produto;
use Illuminate\Http\Request;

class CartController extends Controller
{
    // Show cart contents
    public function index(Request $request)
    {
        $cart = session()->get('cart', []);
        return view('cart.index', compact('cart'));
    }

    // Add product to cart
    public function add(Request $request, $id)
    {
        $produto = Produto::findOrFail($id);

        $cart = session()->get('cart', []);

        if(isset($cart[$id])) {
            // Limitar a quantidade ao adicionar
            if ($cart[$id]['quantity'] < 100) {
                $cart[$id]['quantity']++;
            } else {
                return redirect()->back()->with('error', 'A quantidade máxima para este produto é 100 unidades.');
            }
        } else {
            $cart[$id] = [
                "nome" => $produto->nome,
                "preco" => $produto->preco,
                "imagem" => $produto->imagem,
                "quantity" => 1
            ];
        }

        session()->put('cart', $cart);

        return redirect()->back()->with('success', 'Produto adicionado ao carrinho!');
    }

    // Update product quantity in cart
    public function update(Request $request, $id)
    {
        $cart = session()->get('cart', []);

        if(isset($cart[$id])) {
            $quantity = $request->input('quantity');

            // Limitar a quantidade ao atualizar
            if ($quantity > 0 && $quantity <= 100) {
                $cart[$id]['quantity'] = $quantity;
                session()->put('cart', $cart);
                return redirect()->back()->with('success', 'Carrinho atualizado!');
            } elseif ($quantity > 100) {
                // Se a quantidade for maior que 100, defina para 100
                $cart[$id]['quantity'] = 100;
                session()->put('cart', $cart);
                return redirect()->back()->with('error', 'A quantidade máxima para este produto é 100 unidades. Quantidade ajustada.');
            } else { // quantity <= 0
                unset($cart[$id]);
                session()->put('cart', $cart);
                return redirect()->back()->with('success', 'Produto removido do carrinho!');
            }
        }

        return redirect()->back()->with('error', 'Item não encontrado no carrinho.');
    }

    // Remove product from cart
    public function remove(Request $request, $id)
    {
        $cart = session()->get('cart', []);

        if(isset($cart[$id])) {
            unset($cart[$id]);
            session()->put('cart', $cart);
        }

        return redirect()->back()->with('success', 'Produto removido do carrinho!');
    }
}