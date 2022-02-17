<?php

namespace App;

use System\Database\ORM\Model;

class Category extends Model
{
    protected $table = 'categories';
    protected $fillable = [
        'name',
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    public function posts()
    {
        return $this->hasMany('\App\Post', 'cat_id', 'id');
    }
}