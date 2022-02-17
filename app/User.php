<?php

namespace App;

use System\Database\ORM\Model;

class Users extends Model
{
    protected $table = 'users';
    protected $fillable = [
        'username',
        'created_at',
        'updated_at',
        'deleted_at'
    ];
}