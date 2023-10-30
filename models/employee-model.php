<?php

/**
 * 
 */

class Employee
{

    private $conn;
    private $employee;
    private $isManager;


    public function __construct(int $id, mysqli $conn)
    {
        //$this->$id = $id;
        $this->conn = $conn;
        try {

            $this->employee = $this->conn->query("SELECT * FROM `employees` WHERE `employeeid` = $id")->fetch_all(MYSQLI_ASSOC)[0];

            $this->isManager = ($this->employee['role'] <= 2) ? true : false;
        } catch (Exception $err) {
            throw new Exception($err->getMessage());
        }
    }


    public function updateEmployee(array $data)
    {
        try {
            foreach ($data as $key => $value) {
                $this->employee[$key] = $value;
            }
        } catch (Exception $err) {
            throw new Error($err->getMessage());
        }

        try {
            $queryBuild = updateDynamicFields($this->employee, 'employeeid');
            $query = $queryBuild['query'];
            $types = $queryBuild['types'];
            $values = $queryBuild['values'];
            executeStatement($this->conn, "UPDATE `employees` SET $query WHERE `employeeid` = ?", array($types, $values));
        } catch (Exception $err) {
            throw new Exception($err->getMessage());
        }

        return $this->employee;
    }


    public function employee()
    {
        return $this->employee;
    }

    public function isManager()
    {

        return $this->isManager;
    }

    public function fullName()
    {
        return "{$this->employee["fname"]} {$this->employee["lname"]}";
    }

    public function SetSession()
    {
        if ($this->isManager() && !isset($_SESSION['manager'])) {
            $_SESSION['manager'] = $this->employee();
        }
        $_SESSION['employee'] = $this->employee();
        $_SESSION['employee']['isManager'] = $this->isManager();
        $_SESSION['employee']['fullName'] = $this->fullName();
        $_SESSION['employee']['department'] = explode(',', $this->employee()['department']);
    }
}
