<?php

namespace App\Models;

use Illuminate\Contracts\Database\Eloquent\Builder;

class Administrateur extends User
{

    /**
     * Specify the table associated with this model (STI)
     */
    protected $table = 'users';

    /**
     * Booted : add a global scope to limit the model to administrators only
     */
    protected static function booted()
    {
        static::addGlobalScope('administrateur', function (Builder $builder) {
            $builder->where('type', 'administrateur');
        });
    }

    /**
     * Define the default attributes values when creating a new model
     *
     */
    protected $attributes = [
        'type' => 'administrateur',
    ];
}
