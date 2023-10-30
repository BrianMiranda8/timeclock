<?php

function buildEmployeeList($conn)
{

    $empNames = $conn->query("SELECT DISTINCT `fname`,`lname` , `role`,`employeeid` FROM `employees` WHERE `active` = 1 ORDER BY `lname`");

    if ($empNames->num_rows > 0)
        while ($name = $empNames->fetch_assoc()) {


            echo "<option role='{$name["role"]}' value='{$name["employeeid"]}'>
            
                    {$name["fname"]} {$name["lname"]}
            
                </option>";
        }
}
