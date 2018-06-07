<?php
namespace App\Model;
use Engine\DB\Model;

class Settings extends Model
{
    protected $table = 'settings';

    protected $fillable = ['name','description'];
}