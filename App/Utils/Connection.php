<?php

namespace App\Utils;
use PDO;
use PDOException;

final class Connection
{
    private const HOST = "mysql-db";
    private const USER = "root";
    private const DBNAME = "projeto_mvc";
    private const PASSWORD = "A1r2t3h4u5r6";

    private const OPTIONS = [
        PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8",
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ,
        PDO::ATTR_CASE => PDO::CASE_NATURAL
    ];

    private static PDO $instance;

    public static function getInstance() : PDO
    {
        if(empty(self::$instance)) {
            try {
                self::$instance = new PDO("mysql:host=" . self::HOST . ";dbname=" . self::DBNAME,
                "" . self::USER, "" . self::PASSWORD, self::OPTIONS);
                return self::$instance;
            }  catch(PDOException $exception) {
                throw new PDOException($exception);
                echo $exception->getMessage();
                die("<h1>Oops... algo deu errado ao conectar!</h1>");
            }
        }
        return self::$instance;
    }

    private function __construct()
    {}
    private function __clone()
    {}
}