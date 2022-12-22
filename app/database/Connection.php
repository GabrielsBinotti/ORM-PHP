<?php

namespace App\database;

final class Connection
{
    protected static $pdo;

    private function __construct()
    {
        $host   = "";
        $user   = "";
        $pass   = "";
        $dbname = "";

        $dsn = "sqlsrv:Server={$host};Database={$dbname}";

        try{

            self::$pdo = new \PDO($dsn, $user, $pass);
            self::$pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
            self::$pdo->setAttribute(\PDO::ATTR_DEFAULT_FETCH_MODE,\PDO::FETCH_OBJ);
            
        }catch(\PDOException $e)
        {
            $mensagem = "Drivers disponiveis: " . implode(",", \PDO::getAvailableDrivers());
            $mensagem .= " Erro: " . $e->getMessage();

            echo $mensagem;
        }

    }

    public static function getConnection()
    {
        if(!self::$pdo)
        {
            new Connection();
        }

        return self::$pdo;
    }
}