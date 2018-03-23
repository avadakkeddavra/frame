<?php
namespace Engine\DB\Basics;

use \PDO;
use \PDOException;
use Engine\DB\Interfaces\DBConnectionInterface;

class DB implements DBConnectionInterface
{
    protected static $db = null;
    private static $instance = null;
    private static $dsn;
    private static $username;
    private static $password;
    
    
    
    
    /**
     * Creates new instance representing a connection to a database
     * @param string $dsn The Data Source Name, or DSN, contains the information required to connect to the database.
     *
     * @param string $username The user name for the DSN string.
     * @param string $password The password for the DSN string.
     * @see http://www.php.net/manual/en/function.PDO-construct.php
     * @throws  PDOException if the attempt to connect to the requested database fails.
     *
     * @return $this DB
     */
    public static function connect($dsn, $username = '', $password = '')
    {
        if (self::$db === null) {
            try {
                self::$dsn = $dsn;
                self::$username = $username;
                self::$password = $password;



                self::$db = new PDO($dsn, $username, $password);
            } catch (PDOException $e) {
                echo 'Подключение не удалось: ' . $e->getMessage();
                die();
            }
        } 
        
        return new DB;
    }
    
    /**
     * Returns the PDO instance.
     *
     * @return PDO the PDO instance, null if the connection is not established yet
     */
    public function getPdoInstance()
    {
        if (self::$db !== null) {
            return self::$db;
        } else {
            return null;
        }
    }
    
    /**
     * Returns the ID of the last inserted row or sequence value.
     *
     * @param string $sequenceName name of the sequence object (required by some DBMS)
     *
     * @return string the row ID of the last row inserted, or the last value retrieved from the sequence object
     * @see http://www.php.net/manual/en/function.PDO-lastInsertId.php
     */
    public function getLastInsertID($sequenceName = '')
    {
       return self::$db->lastInsertId($sequenceName);
    }
    
    /**
     * Completes the current session connection, and creates a new.
     *
     * @return void
     */
    public function reconnect()
    {
        self::close();
        self::$db = new PDO (self::$dsn, self::$username, self::$password);
        
    }
    
    /**
     * Sets an attribute on the database handle.
     * Some of the available generic attributes are listed below;
     * some drivers may make use of additional driver specific attributes.
     *
     * @param int $attribute
     * @param mixed $value
     *
     * @return bool
     * @see http://php.net/manual/en/pdo.setattribute.php
     */
    public function setAttribute($attribute, $value)
    {
        self::$db->setAttribute($attribute, $value);
    }
    
    /**
     * Returns the value of a database connection attribute.
     *
     * @param int $attribute
     *
     * @return mixed
     * @see http://php.net/manual/en/pdo.setattribute.php
     */
    public function getAttribute($attribute)
    {
        self::$db->getAttribute($attribute);
    }
    
    /**
     * Closes the currently active DB connection.
     * It does nothing if the connection is already closed.
     *
     * @return void
     */
    public function close()
    {
        self::$db = null;
    }
    
    private function __construct(){}
    private function __clone(){}
    private function __wakeup(){}
    
}