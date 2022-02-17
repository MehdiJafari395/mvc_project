<?php

namespace App;

use System\Database\ORM\Model;

class Role extends Model
{
    protected $table = 'roles';
    protected $fillable = [
        'name',
        'created_at',
        'updated_at',
        'deleted_at'
    ];
}