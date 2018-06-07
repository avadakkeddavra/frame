<?php
namespace App\Model;

use Engine\DB\Model;

class Tasks extends Model
{
    protected $table = 'tasks';

    protected $fillable = ['name','type','task_id','status','user_id','project_id','date','estimated','spent'];

    public function manage()
    {
        return $this->hasMany(TasksManage::class,'task_id','id')->orderBy('date','asc');
    }

    public function project()
    {
        return $this->belongsTo(Project::class,'project_id','id');
    }

    public function lastUpdate()
    {
        $lastDate = TasksManage::where('task_id',$this->id)->orderBy('date','desc')->first();
        return $lastDate->date;
    }

    public function task_type()
    {
        return $this->belongsTo(TaskTypes::class,'type','id');
    }

    public function stage()
    {
        return $this->belongsTo(Stage::class,'stage_id','id');
    }
}