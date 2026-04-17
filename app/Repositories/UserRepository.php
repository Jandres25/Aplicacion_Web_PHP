<?php

namespace App\Repositories;

use PDO;

class UserRepository
{
    private $connection;

    public function __construct(PDO $connection)
    {
        $this->connection = $connection;
    }

    public function findByCredentials($username, $password)
    {
        $statement = $this->connection->prepare(
            "SELECT ID, Nombreusuario, Correo
             FROM `tbl-usuarios`
             WHERE Nombreusuario = :Nombreusuario
               AND Password = :Password
             LIMIT 1"
        );

        $statement->bindParam(':Nombreusuario', $username);
        $statement->bindParam(':Password', $password);
        $statement->execute();

        $user = $statement->fetch(PDO::FETCH_ASSOC);
        return $user === false ? null : $user;
    }
}
