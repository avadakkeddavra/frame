<?php
/**
 * Created by PhpStorm.
 * User: smartit-9
 * Date: 20.04.18
 * Time: 15:24
 */

namespace App\Model;


use Engine\DB\Model;

class Permissions extends Model
{
    protected $table = 'project_permissions';

    protected $fillable = ['project_id', 'user_id'];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function project()
    {
        return $this->belongsTo(Project::class,'project_id','id');
    }
}