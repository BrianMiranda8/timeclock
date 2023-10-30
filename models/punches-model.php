<?php

class Punches
{
    private $conn;
    public function __construct(mysqli $conn)
    {
        $this->conn = $conn;
    }

    public function isClockedIn(int $id)
    {
        try {

            $lastPunch = $this->conn->query(" SELECT `punchid`,`department` FROM `punches` WHERE `punchid` = (SELECT MAX(`punchid`)FROM `punches`WHERE `employeeid` = $id) AND `out_time` IS NULL;
        ");
            $recentPunch = ($lastPunch->num_rows == 0) ? false : $lastPunch->fetch_assoc();
            return $recentPunch;
        } catch (Exception $err) {

            throw new Exception($err->getMessage());
        }
    }

    public function getPunches(int $id, string $startDate, string $endDate, string $department)
    {

        try {

            $punches = $this->conn->query("SELECT `punchid`,`department`, DATE_FORMAT(`in_time`, '%m/%d/%Y  %h:%i %p') as time_in, 
            DATE_FORMAT(`out_time`, '%m/%d/%Y  %h:%i %p') as time_out, 
            FORMAT(TIME_TO_SEC(TIMEDIFF(`out_time`,`in_time`))/60/60, 2) as total_time, 
            TIME_FORMAT(TIMEDIFF(`out_time`,`in_time`), '%H:%i') as total_minutes 
            FROM `punches` WHERE `employeeid` = " . $id . "
            AND ((`in_time` between '" . $startDate . " 00:00:00' 
            AND '" . $endDate . " 23:59:59') 
            OR (`out_time` between '" . $startDate . " 00:00:00' AND '" . $endDate . " 23:59:59')) 
            AND `department` = '$department' 
            order by `in_time`")->fetch_all(MYSQLI_ASSOC);
            return $punches;
        } catch (Exception $err) {
            throw new Exception($err->getMessage());
        }
    }

    public function getTotalTime(int $id, string $startDate, string $endDate, string $department)
    {
        $totaltime = $this->conn->query("SELECT FORMAT(SUM(TIME_TO_SEC(TIMEDIFF(`out_time`,`in_time`)))/60/60, 2) as total_time, TIME_FORMAT( SEC_TO_TIME( SUM( TIME_TO_SEC( TIMEDIFF(  `out_time` ,  `in_time` ) ) ) ) ,  '%H:%i' ) AS total_minutes FROM `punches` WHERE `employeeid` = " . $id . "
        AND `out_time` > '1970-01-01 11:59:59' 
        AND ((`in_time` between '" . $startDate . " 00:00:00' AND '" . $endDate . " 23:59:59') 
        OR (`out_time` between '" . $startDate . " 00:00:00' AND '" . $endDate . " 23:59:59')) AND `department` = '$department'")->fetch_all(MYSQLI_ASSOC);
        return $totaltime;
    }

    public function addPunch($id, $dep, $start = null, $end = null)
    {
        if ($start === null) {
            return $this->conn->query("INSERT INTO `punches` (`employeeid`, `department`) VALUES ('$id', '$dep')");
        } else {
            // Check if $end is null and adjust the SQL accordingly
            if ($end === null) {
                return $this->conn->query("INSERT INTO `punches` (`employeeid`, `department`, `in_time`) VALUES ('$id', '$dep', '$start')");
            } else {
                return $this->conn->query("INSERT INTO `punches` (`employeeid`, `department`, `in_time`, `out_time`) VALUES ('$id', '$dep', '$start', '$end')");
            }
        }
    }


    public function punchOut($id)
    {
        $lastPunch = $this->isClockedIn($id)['punchid'];

        $this->conn->query("UPDATE `punches` SET `out_time` = CURRENT_TIMESTAMP() WHERE `punchid` = ('$lastPunch')");
    }

    public function editPunch($punchid, $start, $end, $department)
    {
        if ($end === null) {
            $result = $this->conn->query("UPDATE `punches` SET `in_time`= '$start', `out_time` = NULL, `department` = '$department' WHERE `punchid` = ('$punchid')");
        } else {
            $result = $this->conn->query("UPDATE `punches` SET `in_time`= '$start', `out_time` = '$end', `department` = '$department' WHERE `punchid` = ('$punchid')");
        }

        return $result;
    }

    public function deletePunch($punchid)
    {
        $result = $this->conn->query("DELETE FROM `punches` WHERE `punchid` = '$punchid' ");
        return $result;
    }
    public function workingEmployees($start, $end)
    {
        $query = "SELECT DISTINCT `e`.*
        FROM `employees` AS `e`
        INNER JOIN `punches` AS `p` ON `e`.`employeeid` = `p`.`employeeid`
        WHERE `p`.`in_time` >= '{$start}  00:00:00' AND (`p`.`out_time` <= '{$end} 23:59:59' OR `p`.`out_time` IS NULL)
        ORDER BY `e`.`lname`";
        return $this->conn->query($query)->fetch_all(MYSQLI_ASSOC);
    }
}
