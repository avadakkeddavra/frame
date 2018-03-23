<?php
namespace Engine\DB\Basics;

use \PDO;
use \PDOExeption;
use Engine\DB\Interfaces\DBQueryInterface;
use Engine\DB\Interfaces\DBConnectionInterface;

class DBQuery implements DBQueryInterface
{
    public static $DBConnection;
    public static $time;
    
    /**
     * Create new instance DBQuery.
     *
     * @param DBConnectionInterface $DBConnection
     */
    public function __construct(DBConnectionInterface $DBConnection)
    {
        self::$DBConnection = $DBConnection;
    }
    
    /**
     * Returns the DBConnection instance.
     *
     * @return DBConnectionInterface
     */
    public function getDBConnection()
    {
        return self::$DBConnection;
    }
    
    /**
     * Change DBConnection.
     *
     * @param DBConnectionInterface $DBConnection
     *
     * @return void
     */
    public function setDBConnection(DBConnectionInterface $DBConnection)
    {
        self::$DBConnection = $DBConnection;
    }
    
    /**
     * Executes the SQL statement and returns query result.
     *
     * @param string $query sql query
     * @param array $params input parameters (name=>value) for the SQL execution
     *
     * @return mixed if successful, returns a PDOStatement on error false
     */
    public function query($query, array $params = array())
    {
        self::$time = microtime(true);    
        $statement = self::$DBConnection->getPdoInstance()->prepare($query);
        $statement->execute($params);
        self::$time = microtime(true) - self::$time;
        return $statement;
    }
    
    /**
     * Executes the SQL statement and returns all rows of a result set as an associative array
     *
     * @param string $query sql query
     * @param array $params input parameters (name=>value) for the SQL execution
     *
     * @return array
     */
    public function queryAll($query, array $params = array())
    {
        $statement = self::query($query, $params);
        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }
    
    /**
     * Executes the SQL statement returns the first row of the query result
     *
     * @param string $query sql query
     * @param array $params input parameters (name=>value) for the SQL execution
     *
     * @return array
     */
    public function queryRow($query, array $params = array())
    {
        $statement = self::query($query, $params);
        return $statement->fetch(PDO::FETCH_ASSOC);
        
    }
    
    /**
     * Executes the SQL statement and returns the first column of the query result.
     *
     * @param string $query sql query
     * @param array $params input parameters (name=>value) for the SQL execution
     *
     * @return array
     */
    public function queryColumn($query, array $params = array())
    {
        $statement = self::query($query, $params);
        return $statement->fetchAll(PDO::FETCH_COLUMN, 0);
    }
    
    /**
     * Executes the SQL statement and returns the first field of the first row of the result.
     *
     * @param string $query sql query
     * @param array $params input parameters (name=>value) for the SQL execution
     *
     * @return mixed  column value
     */
    public function queryScalar($query, array $params = array())
    {
        $statement = self::query($query, $params);
        return $statement->fetchColumn();
    }
    
    /**
     * Executes the SQL statement.
     * This method is meant only for executing non-query SQL statement.
     * No result set will be returned.
     *
     * @param string $query sql query
     * @param array $params input parameters (name=>value) for the SQL execution
     *
     * @return integer number of rows affected by the execution.
     */
    public function execute($query, array $params = array())
    {
        $statement = self::$DBConnection->getPdoInstance()->prepare($query);
        return $statement->execute($params);
    }
    
    /**
     * Returns the last query execution time in seconds
     *
     * @return float query time in seconds
     */
    public function getLastQueryTime()
    {
        return self::$time;
    }
}