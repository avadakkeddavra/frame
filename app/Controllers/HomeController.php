<?php
namespace App\Controllers;
use App\Model\Tasks;
use Carbon\Carbon;
use App\Model\TasksManage;
use Engine\Request\Request;
use Illuminate\Support\Facades\DB;
class HomeController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $title = 'Home Controller';
        $monthAgo = Carbon::now()->subMonth(1);
        $data['hours']['month'] = TasksManage::with('task')->where('created_at','>',$monthAgo)->sum('time');
        $data['hours']['all'] = TasksManage::with('task')->sum('time');
        $data['tasks']['month'] = TasksManage::with('task')->where('created_at','>',$monthAgo)->count();
        $data['tasks']['all'] = TasksManage::with('task')->count();

        return view('home',['title' => $title,'data' => $data]);
    }

    public function chartdata()
    {
        $chartData = Tasks::leftJoin('tasks_manage','tasks.id','=','tasks_manage.task_id')
            ->select('tasks_manage.*', 'tasks.task_id')
            ->where('tasks_manage.time','!=','null')
            ->orderBy('tasks_manage.date','asc')->get()->toArray();
        echo json_encode($chartData);
    }

    public function getCsvData(Request $request)
    {
        $params = $request->getAll();
        $tasks = TasksManage::with('task')
            ->where('date','>=',$params['from'])
            ->where('date','<=',$params['to'])
            ->orderBy('date','desc')
            ->get();

        $hours = TasksManage::with('task')
            ->where('date','>=',$params['from'])
            ->where('date','<=',$params['to'])
            ->sum('time');

        $filename = trim(date('Y-m-d_H:i:s').'.csv');
        $file=fopen(storage_path().'/app/'.$filename,'w');
        fputcsv($file,['Номер задачи','Название','Часов потрачено','Дата']);

        foreach($tasks as $task)
        {
            $data = ['# '.$task->task->task_id,$task->task->name,$task->time,$task->date];
            fputcsv($file,$data);
        }

        fputcsv($file,[]);
        fputcsv($file,['Всего часов',$hours]);
        fclose($file);

        echo json_encode(['file'=>$filename]);
    }

}