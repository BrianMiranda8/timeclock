<?php
class SessionManager
{
    public static function setManagerSession($employeeData)
    {
        if (!isset($_SESSION['manager'])) {
            $_SESSION['manager'] = $employeeData;
        }
    }

    public static function setEmployeeSession($employeeData, $manager, $departments, $fullname)
    {
        $_SESSION['employee'] = $employeeData;
        $_SESSION['employee']['isManager'] = $manager;
        $_SESSION['employee']['fullName'] = $fullname;
        $_SESSION['employee']['department'] = $departments;
    }
}
