<?php

namespace App\Repositories;

use PDO;

class EmployeeRepository
{
    private $connection;

    public function __construct(PDO $connection)
    {
        $this->connection = $connection;
    }

    public function listAllWithPosition()
    {
        $statement = $this->connection->prepare(
            "SELECT e.*, p.Nombredelpuesto as puesto
             FROM `tbl-empleados` e
             LEFT JOIN `tbl-puestos` p ON p.ID = e.Idpuesto
             ORDER BY e.ID DESC"
        );
        $statement->execute();
        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }

    public function listPositions()
    {
        $statement = $this->connection->prepare(
            "SELECT ID, Nombredelpuesto
             FROM `tbl-puestos`
             ORDER BY Nombredelpuesto ASC"
        );
        $statement->execute();
        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }

    public function findById($id)
    {
        $statement = $this->connection->prepare(
            "SELECT * FROM `tbl-empleados` WHERE ID = :ID LIMIT 1"
        );
        $statement->bindParam(':ID', $id, PDO::PARAM_INT);
        $statement->execute();
        $row = $statement->fetch(PDO::FETCH_ASSOC);
        return $row === false ? null : $row;
    }

    public function findByIdWithPosition($id)
    {
        $statement = $this->connection->prepare(
            "SELECT e.*, p.Nombredelpuesto as puesto
             FROM `tbl-empleados` e
             LEFT JOIN `tbl-puestos` p ON p.ID = e.Idpuesto
             WHERE e.ID = :ID
             LIMIT 1"
        );
        $statement->bindParam(':ID', $id, PDO::PARAM_INT);
        $statement->execute();
        $row = $statement->fetch(PDO::FETCH_ASSOC);
        return $row === false ? null : $row;
    }

    public function findFilesById($id)
    {
        $statement = $this->connection->prepare(
            "SELECT Foto, CV FROM `tbl-empleados` WHERE ID = :ID LIMIT 1"
        );
        $statement->bindParam(':ID', $id, PDO::PARAM_INT);
        $statement->execute();
        $row = $statement->fetch(PDO::FETCH_ASSOC);
        return $row === false ? null : $row;
    }

    public function create($data)
    {
        $statement = $this->connection->prepare(
            "INSERT INTO `tbl-empleados` (
                ID, Primernombre, Segundonombre, Primerapellido, Segundoapellido, Foto, CV, Idpuesto, Fecha
            ) VALUES (
                NULL, :Primernombre, :Segundonombre, :Primerapellido, :Segundoapellido, :Foto, :CV, :Idpuesto, :Fecha
            )"
        );

        return $statement->execute([
            ':Primernombre' => $data['Primernombre'],
            ':Segundonombre' => $data['Segundonombre'],
            ':Primerapellido' => $data['Primerapellido'],
            ':Segundoapellido' => $data['Segundoapellido'],
            ':Foto' => $data['Foto'],
            ':CV' => $data['CV'],
            ':Idpuesto' => $data['Idpuesto'],
            ':Fecha' => $data['Fecha']
        ]);
    }

    public function update($id, $data)
    {
        $statement = $this->connection->prepare(
            "UPDATE `tbl-empleados`
             SET Primernombre = :Primernombre,
                 Segundonombre = :Segundonombre,
                 Primerapellido = :Primerapellido,
                 Segundoapellido = :Segundoapellido,
                 Foto = :Foto,
                 CV = :CV,
                 Idpuesto = :Idpuesto,
                 Fecha = :Fecha
             WHERE ID = :ID"
        );

        return $statement->execute([
            ':Primernombre' => $data['Primernombre'],
            ':Segundonombre' => $data['Segundonombre'],
            ':Primerapellido' => $data['Primerapellido'],
            ':Segundoapellido' => $data['Segundoapellido'],
            ':Foto' => $data['Foto'],
            ':CV' => $data['CV'],
            ':Idpuesto' => $data['Idpuesto'],
            ':Fecha' => $data['Fecha'],
            ':ID' => (int)$id
        ]);
    }

    public function deleteById($id)
    {
        $statement = $this->connection->prepare(
            "DELETE FROM `tbl-empleados` WHERE ID = :ID"
        );
        $statement->bindParam(':ID', $id, PDO::PARAM_INT);
        return $statement->execute();
    }
}
