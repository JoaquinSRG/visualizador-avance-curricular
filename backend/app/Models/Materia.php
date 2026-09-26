<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Materia extends Model
{
    protected $table = 'materias';

    protected $fillable = [
        'clave',
        'nombre',
        'semestre',
        'creditos',
    ];

    /**
     * Materias que se deben cursar antes que esta.
     */
    public function prerrequisitos(): BelongsToMany
    {
        return $this->belongsToMany(
            Materia::class,
            'prerrequisitos',
            'materia_id',
            'prerrequisito_id'
        )->withTimestamps();
    }

    /**
     * Materias que tienen a esta como prerrequisito.
     */
    public function esPrerrequisitoDe(): BelongsToMany
    {
        return $this->belongsToMany(
            Materia::class,
            'prerrequisitos',
            'prerrequisito_id',
            'materia_id'
        )->withTimestamps();
    }
}
