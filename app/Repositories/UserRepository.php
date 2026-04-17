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

    public function listAll()
    {
        $statement = $this->connection->prepare(
            "SELECT ID, Nombreusuario, Password, Correo
             FROM `tbl-usuarios`
             ORDER BY ID DESC"
        );
        $statement->execute();
        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }

    public function findById($id)
    {
        $statement = $this->connection->prepare(
            "SELECT ID, Nombreusuario, Password, Correo
             FROM `tbl-usuarios`
             WHERE ID = :ID
             LIMIT 1"
        );
        $statement->bindParam(':ID', $id, PDO::PARAM_INT);
        $statement->execute();
        $user = $statement->fetch(PDO::FETCH_ASSOC);
        return $user === false ? null : $user;
    }

    public function create($data)
    {
        $statement = $this->connection->prepare(
            "INSERT INTO `tbl-usuarios` (ID, Nombreusuario, Password, Correo)
             VALUES (NULL, :Nombreusuario, :Password, :Correo)"
        );
        return $statement->execute([
            ':Nombreusuario' => $data['Nombreusuario'],
            ':Password' => $data['Password'],
            ':Correo' => $data['Correo']
        ]);
    }

    public function update($id, $data)
    {
        $statement = $this->connection->prepare(
            "UPDATE `tbl-usuarios`
             SET Nombreusuario = :Nombreusuario,
                 Password = :Password,
                 Correo = :Correo
             WHERE ID = :ID"
        );
        return $statement->execute([
            ':Nombreusuario' => $data['Nombreusuario'],
            ':Password' => $data['Password'],
            ':Correo' => $data['Correo'],
            ':ID' => (int)$id
        ]);
    }

    public function deleteById($id)
    {
        $statement = $this->connection->prepare(
            "DELETE FROM `tbl-usuarios` WHERE ID = :ID"
        );
        $statement->bindParam(':ID', $id, PDO::PARAM_INT);
        return $statement->execute();
    }
}
