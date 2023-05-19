<?php

namespace App\Model\Entity;

use App\Utils\Connection;
use PDO;
use PDOStatement;

class Testimony
{
    public int $id;
    public string $name;
    public string $mensagem;
    public string $data;

    public function cadastrar() : bool
    {
        $dbConnection = Connection::getInstance();
        $this->data = date("Y-m-d H:i:s");
        $statement = $dbConnection->prepare("INSERT INTO depoimentos (name, mensagem, data) VALUE (:name, :mensagem, :data)");
        $testimony = [
            "name" => $this->name,
            "mensagem" => $this->mensagem,
            "data" => $this->data
        ];
        $statement->execute($testimony);
        return true;
    }

    public static function getTestimonies(
        string $where = "true", string $orderBy = "id",
        string $limit = "100", string $fields = "*") : PDOStatement
    {
        $dbConnection = Connection::getInstance();
        $statement = $dbConnection->prepare("SELECT {$fields} FROM depoimentos WHERE {$where} ORDER BY {$orderBy} LIMIT {$limit}");
        return $statement;
    }

    public static function countRegistries()
    {
        $dbConnection = Connection::getInstance();
        $statement = $dbConnection->prepare("SELECT COUNT(*) as qtd FROM depoimentos");
        return $statement;
    }
}