<?php
namespace App\Model;

use Engine\DB\Model;

class TasksManage extends Model
{
    protected $table = 'tasks_manage';

    protected $fillable = ['status','task_id','user_id','time','date'];

    public function task()
    {
        return $this->belongsTo(Tasks::class,'task_id','id');
    }

    public function user()
    {
        return $this->belongsTo(User::class,'user_id','id');
    }
}