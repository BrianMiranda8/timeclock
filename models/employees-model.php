<?php

class Employees
{

    private $conn;

    public function __construct(mysqli $conn)
    {

        $this->conn = $conn;
    }

    public function insertEmployee($data)
    {
        extract(insertDynamicFields($data));
        $response = executeStatement($this->conn, "INSERT INTO `employees` $query", array($types, $values));
        $id = $response->insert_id;
        return $this->getEmployee($id);
    }

    public function getEmployee($id)
    {
        $employee = new Employee($id, $this->conn);
        return $employee;
    }
    public function getEmployees()
    {
        $response = executeStatement($this->conn, "SELECT `employeeid`,`fname`,`lname`,`department` FROM `employees`", array('', array()));
        return $response;
    }
}
