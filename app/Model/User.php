<?php

namespace App\Model;

use Carbon\Carbon;
use Engine\DB\Model;

class User extends Model
{
    protected $table = 'users';

    protected $fillable = ['name','login','password'];

    public function permissions()
    {
        return $this->hasMany(Permissions::class,'user_id','id');
    }

    public function tasks()
    {
        return $this->hasMany(Tasks::class,'user_id','id');
    }

    public function hours()
    {
        return $this->hasMany(TasksManage::class,'user_id','id')
            ->where('date',Carbon::today())
            ->sum('time');
    }

    public function totalhours($project_id)
    {
        return $this->hasMany(Tasks::class,'user_id','id')->where('project_id',$project_id)->sum('spent');
    }

    public function project()
    {
        return $this->hasMany(Project::class,'organizer_id','id');
    }

}