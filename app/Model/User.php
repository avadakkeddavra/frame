<?php

namespace App\Model;

use Engine\DB\Model;

class User extends Model
{
    protected $table = 'users';

    protected $fillable = ['name','login','password'];
}