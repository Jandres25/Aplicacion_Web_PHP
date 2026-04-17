<?php

namespace App\Repositories;

use PDO;

class PositionRepository
{
    private $connection;

    public function __construct(PDO $connection)
    {
        $this->connection = $connection;
    }

    public function listAll()
    {
        $statement = $this->connection->prepare(
            "SELECT ID, Nombredelpuesto
             FROM `tbl-puestos`
             ORDER BY ID DESC"
        );
        $statement->execute();
        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }

    public function findById($id)
    {
        $statement = $this->connection->prepare(
            "SELECT ID, Nombredelpuesto
             FROM `tbl-puestos`
             WHERE ID = :ID
             LIMIT 1"
        );
        $statement->bindParam(':ID', $id, PDO::PARAM_INT);
        $statement->execute();
        $row = $statement->fetch(PDO::FETCH_ASSOC);
        return $row === false ? null : $row;
    }

    public function create($name)
    {
        $statement = $this->connection->prepare(
            "INSERT INTO `tbl-puestos` (ID, Nombredelpuesto)
             VALUES (NULL, :Nombredelpuesto)"
        );
        return $statement->execute([
            ':Nombredelpuesto' => $name
        ]);
    }

    public function update($id, $name)
    {
        $statement = $this->connection->prepare(
            "UPDATE `tbl-puestos`
             SET Nombredelpuesto = :Nombredelpuesto
             WHERE ID = :ID"
        );
        return $statement->execute([
            ':Nombredelpuesto' => $name,
            ':ID' => (int)$id
        ]);
    }

    public function deleteById($id)
    {
        $statement = $this->connection->prepare(
            "DELETE FROM `tbl-puestos` WHERE ID = :ID"
        );
        $statement->bindParam(':ID', $id, PDO::PARAM_INT);
        return $statement->execute();
    }
}
