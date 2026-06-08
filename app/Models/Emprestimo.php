<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Emprestimo extends Model
{
    use HasFactory;

    protected $table = 'emprestimos';

    protected $fillable = [
        'user_id',
        'livro_id',
        'data_emprestimo',
        'data_devolucao',
        'status'
    ];

    protected $casts = [
        'data_emprestimo' => 'datetime',
        'data_devolucao' => 'datetime',
    ];

    // Relacionamento com usuário
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relacionamento com livros
    public function livro()
    {
        return $this->belongsTo(Livro::class, 'livro_id');
    }
}
