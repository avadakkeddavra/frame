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
        $user = \Auth::user()->id;
        $data['hours']['month'] = TasksManage::with('task')->where('user_id',$user)->where('created_at','>',$monthAgo)->sum('time');
        $data['hours']['all'] = TasksManage::with('task')->where('user_id',$user)->sum('time');
        $data['tasks']['month'] = TasksManage::with('task')->where('user_id',$user)->where('created_at','>',$monthAgo)->count();
        $data['tasks']['all'] = TasksManage::with('task')->where('user_id',$user)->count();

        return view('home',['title' => $title,'data' => $data]);
    }

    public function chartdata(Request $request)
    {
        $param = $request->getAll();
        if($param['id'])
        {
            $chartData =  Tasks::with('manage')->where('id',$request->get('id'))->first();
        }else{
            $chartData = Tasks::leftJoin('tasks_manage','tasks.id','=','tasks_manage.task_id')
                ->select('tasks_manage.*', 'tasks.task_id')
                ->where('tasks_manage.time','!=','null')
                ->orderBy('tasks_manage.date','asc')->get()->toArray();
        }

        echo json_encode($chartData);
    }

    public function getCsvData(Request $request)
    {
        $params = $request->getAll();


        $hours = Tasks::where('task_id','!=',0)
            ->where('user_id',\Auth::user()->id)
            ->where('updated_at','>=',$params['from'])
            ->where('updated_at','<=',$params['to'])->sum('spent');

        $allTasks = Tasks::where('user_id',\Auth::user()->id)
            ->where('task_id','!=',0)
            ->where('updated_at','>=',$params['from'])
            ->where('updated_at','<=',$params['to'])
            ->orderBy('updated_at','desc')
            ->get();

        $filename = trim(date('Y-m-d_H:i:s').'.csv');
        $file=fopen(storage_path().'/app/'.$filename,'w');
        fputcsv($file,['Номер задачи','Название','Проект','Часов потрачено']);


        foreach($allTasks as $fulltask)
        {
            $data = ['# '.$fulltask->task_id,$fulltask->name,$fulltask->project->name,$fulltask->spent];
            fputcsv($file,$data);
        }


        fputcsv($file,[]);


        fputcsv($file,['Всего часов',$hours]);
        fclose($file);

        echo json_encode(['file'=>$filename]);
    }

}