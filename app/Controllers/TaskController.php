<?php

namespace App\Controllers;
use App\Model\Project;
use Engine\Request\Request;
use App\Model\Tasks;
use App\Model\TasksManage;
use Carbon\Carbon;
use App\Model\User;


class TaskController
{
    public function index(Request $request)
    {
        $params = $request->getAll();
        if($params['page'])
        {
            $page = $request->get('page');
        }else{
            $page = 1;
        }

        $tasks = Tasks::where('tasks.user_id',\Auth::user()->id)->orderBy('updated_at','desc')->paginate(10,['*'],null,$page);

        return view('tasks',['tasks' => $tasks,'currentPage' => $page,'lastPage' => $tasks->lastPage()]);
    }

    public function manage(Request $request)
    {
        $params = $request->getAll();
        if($params['page'])
        {
            $page = $request->get('page');
        }else{
            $page = 1;
        }

        $tasks = TasksManage::with('task')->where('user_id',\Auth::user()->id)->orderBy('date','desc')->paginate(10,['*'],null,$page);

        return view('tasks_manage',['tasks' => $tasks,'currentPage' => $page,'lastPage' => $tasks->lastPage()]);
    }

    public function create(Request $request)
    {

        if($request->method('POST'))
        {
            $task = Tasks::create($request->getAll());
            $response = ['success' => true,'data' => $task];
            echo json_encode($response);
        }else{
            $tasks = Tasks::where('user_id',\Auth::user()->id)->get();

            $projects = new Project();
            $projects = $projects->getByPermission()->get();
            return view('create',['tasks' => $tasks,'projects' => $projects]);
        }

    }

    public function update(Request $request)
    {
        $task = Tasks::find($request->get('id'));

        if($task){

            $task->update([
                $request->get('key') => $request->get('value')
            ]);

            echo json_encode(['success' => true]);
        }else{
            echo json_encode(['success' => false]);
        }
    }

    public function delete(Request $request)
    {
        $data = $request->getAll();
        $task = Tasks::find($data['id']);

        if($task)
        {
            $task->delete();
            echo 1;
        }else{
            echo 0;
        }
    }

    public function deleteManage(Request $request)
    {
        $data = $request->getAll();
        $task = TasksManage::find($data['id']);

        $taskUpdate = Tasks::find($task->task_id);
        $taskUpdate->update([
            'spent' => (int) ($taskUpdate->spent - $task->time)
        ]);

        if($task)
        {
            $task->delete();
            echo 1;
        }else{
            echo 0;
        }
    }

    public function createManage(Request $request)
    {
        $data = $request->getAll();
        $status = $data['status'];

        unset($data['status']);
        $task = TasksManage::create($data);
        $taskUpdate = Tasks::find($data['task_id']);
        $taskUpdate->update([
            'spent' => (int) ($taskUpdate->spent+$data['time']),
            'status' => $status
        ]);

        $response = ['success' => true,'data' => $task];
        echo json_encode($response);
    }

    public function updateManage(Request $request)
    {
        $task = TasksManage::find($request->get('id'));
        $taskUpdate = Tasks::find($task->task_id);

        if($task){
            $task->update([
                'time' => $request->get('time')
            ]);
            $taskUpdate->update([
                'spent' => (int) ($taskUpdate->spent + ($request->get('time') - $task->time))
            ]);
            echo json_encode(['success' => true]);
        }else{
            echo json_encode(['success' => false]);
        }
    }

    public function single(Tasks $task)
    {
        //dd($task->manage);
        $monthAgo = Carbon::now()->subMonth(1);
        $data['hours']['month'] = TasksManage::with('task')->where('task_id',$task->id)->where('created_at','>',$monthAgo)->sum('time');
        $data['hours']['all'] = TasksManage::with('task')->where('task_id',$task->id)->sum('time');
        $data['status'] = TasksManage::with('task')->where('task_id',$task->id)->orderBy('date','desc')->first();

        return view('single',['task' => $task,'data' => $data]);
    }
}