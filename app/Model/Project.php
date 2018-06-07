<?php
/**
 * Created by PhpStorm.
 * User: smartit-9
 * Date: 16.04.18
 * Time: 16:10
 */

namespace App\Model;
use Engine\DB\Model;
use Illuminate\Support\Facades\DB;

class Project extends Model
{
    protected $table = 'projects';

    protected $fillable = ['name','description','organizer_id'];

    public function tasks()
    {
        return $this->hasMany(Tasks::class,'project_id','id');
    }

    public function organizer()
    {
        return $this->hasOne(User::class,'organizer_id','id');
    }

    public function permissions()
    {
        return $this->hasMany(Permissions::class,'project_id','id');
    }

    public function getByPermission()
    {
        return $this->where('organizer_id',\Auth::user()->id)
            ->orWhere(function($query) {
                foreach (User::find(\Auth::user()->id)->permissions as $permission)
                {
                    $query->where('id',$permission->id);
                }
            });
    }

    public function getProjectUsers()
    {
        $users =  User::select('users.id','users.name')
                ->leftJoin('project_permissions','project_permissions.user_id','users.id')
                ->where('project_permissions.project_id',$this->id)->get();

        return $users;
    }


    public function settings()
    {
        return DB::table('project_settings')->where('project_id',$this->id)->get();
    }

    public function stages()
    {
        return $this->hasMany(Stage::class,'project_id','id');
    }
}