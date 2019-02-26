<?php
class Database
{
    public $db;

    function __construct()
    {
        try {
            $this->db = new PDO("mysql:host=localhost;dbname=api;charset=utf8", "root", "");
        }
        catch (PDOException $e)
        {
            echo $e->getMessage();
        }
    }
}