<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Clinic extends Model
{
    protected $table = 'clinicas';
    
    protected $fillable = [
        'razao_social',
        'nome_fantasia',
        'cnpj',
        'regional',
        'data_inauguracao',
        'ativa',
        'especialidades'
    ];

    protected $casts = [
        'data_inauguracao' => 'date:Y-m-d',
        'ativa' => 'boolean'
    ];

    public function users()
    {
        return $this->hasMany(User::class);
    }
}
