<?php
namespace Engine\DB;

use Engine\DB\Basics\DB;
use Engine\DB\Basics\DBQuery;

class Model
{
	public static $db;
	public static $query;
	protected $table;
	public  $select;
	public static $insert;
	protected $response;
	private $order;
	protected $created_at = 'created_at';
	protected $where;


	public function __construct($data = null)
	{
		$connection = 'mysql:dbname='.env('db_name').';host='.env('host').';charset=UTF8';
		$userName = env('db_user');
		$userPassword = env('db_password');

		self::$db = DB::connect($connection, $userName, $userPassword,'');
    	self::$query = new DBQuery(self::$db);
		$this->select = "SELECT * FROM ".$this->table." ";
        self::$insert = "INSERT into ".$this->table." ";

        $this->response = $data;
	}


	public function __get($key)
	{
		$data = $this->response;

		if($key == 'fillable')
		{
			return $data;
		}else{
            if(isset($data[$key]))
            {
                return $data[$key];
            }else{
                echo new \Exception('There no such property');
                die();
            }
		}

	}

	public function all()
	{
		return self::$query->queryAll('SELECT * FROM '.$this->table.' ');
	}

    public function where($col,$compression = null,$value)
	{
		if($compression == null)
		{
			$compression = " = ";
		}

		if(strpos($this->where,'WHERE') === false)
		{
            $this->where .= "WHERE $col $compression $value ";
		}else{
            $this->where .= "AND WHERE $col $compression $value ";
		}

        return $this;
	}

	public function orWhere($col,$compression = '=',$value)
	{
        if(strpos($this->where,'WHERE') !== false)
        {
            $this->where .= "AND WHERE $col $compression $value ";
        }else{
        	echo new \Exception('You can\'t use orWhere expression without WHERE');
		}
	}


	public function get()
	{
		$this->compareQuery();

		$response = self::$query->queryAll($this->select);

        if(!is_bool($response))
        {
            return self::refactor($response,$this);
        }else{
			return $response;
        }
	}

	public function first()
	{
        $this->compareQuery();

		$response = self::$query->queryRow($this->select);

		if(!is_bool($response))
		{
            $this->response = $response;
		}else{
            return $response;
		}

		return $this;
	}

	public static function create(array $fields)
	{

		$values = '';
		$vars = '';
		foreach($fields as $key => $value)
		{
			$vars .= $key.',';
			$values .= ':'.$key.',';


		}
        $vars = '('.substr($vars,0,strlen($vars)-1).') ';
		$values = 'VALUES('.substr($values,0,strlen($values)-1).')';

		self::$insert .= $vars.$values;

		return self::$query->execute(self::$insert,$fields);
	}

    public function orderBy($field,$type)
    {
        $this->order = " ORDER BY $field $type ";
        return $this;
    }

    public function latest()
    {
        $this->order = " ORDER BY `$this->created_at` DESC";
        return $this;
    }

    public function oldest()
    {
        $this->order = " ORDER BY `$this->created_at` ASC";
        return $this;
    }

    protected function compareQuery()
	{
		$this->select .= $this->where.$this->order;
	}

	protected function refactor(array $data)
	{
        $response = [];

        foreach($data as $key => $item)
        {
            $response[$key] = new self($item);
        }

        return $response;
	}


}

 ?>
	