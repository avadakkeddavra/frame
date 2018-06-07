<?php
/**
 * Created by PhpStorm.
 * User: smartit-9
 * Date: 25.04.18
 * Time: 12:36
 */

namespace App\Model;


use Engine\DB\Model;

class Stage extends Model
{
    protected $table = 'project_stages';
    protected $fillable = ['name','project_id','start_date','end_date'];

    public function project()
    {
        return $this->belongsTo(Project::class,'project_id','id');
    }
}