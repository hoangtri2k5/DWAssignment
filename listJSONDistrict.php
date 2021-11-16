<?php
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "manage_city";

    $conn = new mysqli($servername,$username,$password,$dbname);

    if ($conn->connect_error){
        die("Connection failed: " . $conn->connect_error);
    }
    $conn->set_charset("utf8");
    $sql = "SELECT * FROM district";

    $result = $conn->query($sql);
    header('Content-Type: application/json; charset=utf-8');
    if ($result->num_rows > 0){
        $row = array();
        http_response_code(201);
        while ($r = $result->fetch_assoc()){
            $row[] = $r;
        }
        $obj = new stdClass();
        $obj->status = 200;
        $obj->message = "Everything is going to OK";
        $obj->data = $row;
        print json_encode($obj);

    } else {
        print_r([]);
    }
    $conn->close();
?>