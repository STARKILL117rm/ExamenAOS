<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Categoria extends Model
{
    /**
     * The table associated with the model.
     */
    protected $table = 'categorias';

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'nombre',
        'descripcion',
    ];

    /**
     * Get the productos for the categoria.
     */
    public function productos(): HasMany
    {
        return $this->hasMany(Producto::class);
    }
}
