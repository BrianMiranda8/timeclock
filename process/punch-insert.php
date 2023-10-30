<?php
session_start();
require_once $_SERVER['DOCUMENT_ROOT'] . '/prefs-mysql.php';
include("../models/punches-model.php");

$newDep = ($_POST['newDepartment'] === $_POST['currentDep']) ? "" : $_POST['newDepartment'];
$currentDep = $_POST['currentDep'];

$timeclock = mysqli_connect($server, $username, $password, 'timeclock');
$punches = new Punches($timeclock);

$lastPunch = $punches->isClockedIn($_SESSION['employee']['employeeid']);
$isClocked = is_array($lastPunch);


if (!$isClocked) {


    $newPunch = "INSERT INTO `punches` (`employeeid`, `department`) VALUES ('{$_SESSION['employee']['employeeid']}', '$newDep')";
    $timeclock->query($newPunch);
    header('Location: ../index.php');
    exit();
}


if ($isClocked && empty($newDep)) {

    $punches->punchOut($_SESSION['employee']['employeeid']);
} else {

    $punches->punchOut($_SESSION['employee']['employeeid']);
    $punches->addPunch($_SESSION['employee']['employeeid'], $newDep);
}

header('Location: ../index.php');
