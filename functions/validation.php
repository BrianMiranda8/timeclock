<?php
function validation()
{
    if (!$_SESSION['manager_validation']) header("location: ../index.php?message=Invalid Credentials");
}

function invalidation()
{
    session_unset();
    session_destroy();
    $_SESSION['manager_validation'] = 0;
}
