<?php
namespace App\Model;

use Engine\DB\Model;

class User extends Model
{
    protected $table = 'auth_users';

    protected $created_at = 'create';
}