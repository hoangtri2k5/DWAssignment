<?php
    $data = json_decode(file_get_contents("php://input"), true);
    $name = $data['name'];
    $id_district = $data['id_district'];
    $created_at = $data['created_at'];
    $des = $data['des'];
    $status = $data['status'];

    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "manage_city";

    $conn = new mysqli($servername,$username,$password,$dbname);

    if ($conn->connect_error){
        die("Connection failed: " . $conn->connect_error);
    }
    $sql = "INSERT INTO street (name,id_district,created_at,des,status) VALUES ('$name','$id_district','$created_at','$des','$status')";

    // config respone data kiểu json
    header('Content-Type: application/json; charset=utf-8');
    if ($conn->query($sql) === TRUE){
        $data = new stdClass();
        $data->message = 'Action success';
        http_response_code(201);
        echo json_encode($data);
    } else{
        $data = new stdClass();
        $data->message = 'Action fails';
        http_response_code(500);
        echo json_encode($data);
    }
    $conn->close();
?>
