<?php

namespace App\Models;

class Administrateur extends User
{
    /**
     * Define the default attributes values.
     *
     */
    protected $attributes = [
        'type' => 'administrateur',
    ];
}
