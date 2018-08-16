<?php

// Use Autoloader or :
// include_once 'DBData.php';
include_once 'Modules/Application/Autoloader/Autoloader.php';
Autoloader::register();

/*
 * Class needs another class named DBData.php :
 *
 *   public static $database = "";
 *   public static $username = "";
 *   public static $password = "";
 *   public static $server   = "";
 *
 * --------------------------------------------
 * DBData holds the connection-information for PDO
 */
class DBConnector
{
    /** @var PDO $connection */
    private $connection;

    public function __construct()
    {
        try{
        $this->connection = new PDO("mysql:host=" . DBData::$server . ";dbname=" . DBData::$database, DBData::$username, DBData::$password);
        $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        }
        catch(PDOException $e)
        {
            echo "Connection failed: " . $e->getMessage();
        }
    }

    /**
     * Sends data to the database without returning a response
     * @param string $sql
     */
    public function sendVoid($sql)
    {
        $statement = $this->connection->prepare($sql);
        $statement->execute();
    }

    /**
     * Returns an array of Responses
     *
     * @param string $sql - the query
     * @param string $entity - the entity to that the method returns its data | just the classname
     * @return array | response
     */
    public function sendReturn($sql, $entity)
    {
        $statement = $this->connection->prepare($sql);
        $statement->execute();

        // To use an entity, you have to define and include it
        return $statement->fetchAll(PDO::FETCH_CLASS|PDO::FETCH_UNIQUE, $entity);
    }

    /**
     * Returns a single response the the data
     *
     * @param string $sql
     * @param string $entity - the entity to that the method returns its data | just the classname
     * @return mixed | response
     */
    public function sendSingleReturn($sql, $entity)
    {
        $statement = $this->connection->prepare($sql);
        $statement->execute();

        // To use an entity, you have to define and include it
        return $statement->fetchObject($entity);
    }

}