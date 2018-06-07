<?php
/**
 * Created by PhpStorm.
 * User: smartit-9
 * Date: 16.04.18
 * Time: 16:24
 */

namespace App\Controllers;
use App\Model\Project;
use App\Model\Tasks;
use App\Model\TasksManage;
use App\Model\User;
use Engine\Request\Request;
use Illuminate\Support\Collection;

class ProjectsController extends Controller
{
    public function index()
    {
        $projects = Project::all();
        return view('all_projects',['projects' => $projects]);
    }

    public function single(Project $project,Request $request)
    {
        $tasks = Tasks::where('project_id',$project->id)->get();
        $data = [];
        $ip = 0;
        $d = 0;
        $c = 0;
        foreach($tasks as $task)
        {
            if($task->status == 1)
            {
                $ip++;
                $data['in_progress']['title'] = 'In progress';
                $data['in_progress']['value'] = $ip;
            }elseif ($task->status == 2)
            {
                $d++;
                $data['done']['title'] = 'Done';
                $data['done']['value'] = $d;
            }else{
                $c++;
                $data['completed']['title'] = 'Completed';
                $data['completed']['value'] = $c;
            }
        }

        $collecton = new Collection($data);
        $users = $project->getProjectUsers();

        return view('project',['project'=>$project,'tasks' =>json_encode($collecton->toArray()),'users' => $users]);
    }

    public function create(Request $request)
    {
        $project = Project::create($request->getAll());

        echo json_encode($project);
    }

}