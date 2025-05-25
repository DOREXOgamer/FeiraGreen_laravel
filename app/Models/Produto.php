<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Produto extends Model
{
    protected $fillable = ['nome', 'preco', 'categoria', 'imagem', 'user_id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
