<?php
/**
 * Created by Igor Banchikov [gurikin]
 * At 29.04.2020 22:41
 */


class DBConnection
{
    /** @var PDO */
    private $connection;
    /** @var string  */
    private $dns;
    /** @var string */
    private $userName;
    /** @var string */
    private $userPassword;
    /** @var DBConnection */
    private static $instance;

    private function __construct($server = DB_HOST, $username = DB_USER, $password = DB_PASSWORD, $db = DB_NAME)
    {
        $this->userName = $username;
        $this->userPassword = $password;
        $this->dns = 'mysql:dbname=' . $db . ';host=' . $server;
        $this->connect();
    }

    private function __clone()
    {
    }

    /**
     * @return object $_instance - the instance of this class
     */
    public static function getConnection()
    {
        if (!(self::$instance instanceof self)) {
            self::$instance = new self();
        }
        return self::$instance->connection;
    }

    private function connect(): void
    {
        try {
            $this->connection = new PDO($this->dns, $this->userName, $this->userPassword);
        } catch (PDOException $ex) {
            echo 'Connection to the DB is fail: ' . $ex->getMessage();
        }
    }
}