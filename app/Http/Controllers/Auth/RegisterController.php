<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
    use RegistersUsers;

    /**
     * Para onde redirecionar após o registro.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Construtor
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Validação dos dados
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name'  => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'image' => ['nullable', 'image', 'max:2048'], // validação da imagem (opcional)
        ]);
    }

    /**
     * Criação do usuário no banco de dados
     *
     * @param  array  $data
     * @return \App\Models\User
     */
    protected function create(array $data)
    {
        $imageName = null;

        if (request()->hasFile('image') && request()->file('image')->isValid()) {
            $image = request()->file('image');

            // Renomeia para evitar conflitos, igual ao HomeController
            $imageName = time() . '_' . $image->getClientOriginalName();

            // Move para a mesma pasta que o HomeController usa
            $image->move(public_path('imagens/profile_images'), $imageName);
        }

        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'image' => $imageName,
        ]);
    }
}
