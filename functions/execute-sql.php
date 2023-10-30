<?php

function executeStatement($connection, $query, $params = array())
{
    try {
        $stmt = $connection->prepare($query);
        if ($stmt === false) {
            throw new Exception("Unable to do prepared statement: " . $connection->error);
        }
        if (count($params) > 0) {
            $stmt->bind_param($params[0], ...$params[1]);
        }
        $stmt->execute();
        $response = $stmt->get_result();
        if ($response) {
            $result = $response->fetch_all(MYSQLI_ASSOC);
        } else {
            $result = $stmt;
        }


        return $result;
    } catch (Exception $e) {
        throw new Exception($e->getMessage());
    }
}
