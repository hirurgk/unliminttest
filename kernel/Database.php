<?php

namespace Kernel;

class Database extends Singleton
{
    const USER = 'unlimint';
    const PASSWORD = 'JGh8zGh87tFH7';
    const DBNAME = 'unliminttest';

    private $db;

    public function __construct()
    {
        $this->db = new \PDO("mysql:host=localhost;dbname=".self::DBNAME, self::USER, self::PASSWORD);
    }
    
    /**
     * getDB
     * @return void
     */
    public function getDB()
    {
        return $this->db;
    }
}
