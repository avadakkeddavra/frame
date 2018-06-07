<?php
/**
 * Created by PhpStorm.
 * User: smartit-9
 * Date: 25.04.18
 * Time: 13:43
 */

namespace App\Model;


use Engine\DB\Model;

class TaskTypes extends Model
{
    protected $table = 'task_types';

    protected $fillable = ['name'];


}