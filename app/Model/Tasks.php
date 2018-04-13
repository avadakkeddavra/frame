<?php
namespace App\Model;

use Engine\DB\Model;

class Tasks extends Model
{
    protected $table = 'tasks';

    protected $fillable = ['name','task_id','date'];

    public function manage()
    {
        return $this->hasMany(TasksManage::class,'task_id','id');
    }

}