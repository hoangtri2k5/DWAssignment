<?php
    $data = json_decode(file_get_contents("php://input"), true);
    $name = $data['keyword'];
    $id_district = $data['id_district'];

    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "manage_city";


    $conn = new mysqli($servername,$username,$password,$dbname);

    if ($conn->connect_error){
        die("Connection failed: " . $conn->connect_error);
    }
    $conn->set_charset("utf8");
    $sql = "SELECT *,street.name as streetName,district.name as districtName, street.id as streetId,district.id as districtId FROM street
            JOIN district
            ON street.`id_district` = district.`id`";

    if ($name == ''){
        $sql .=" WHERE street.id_district = '$id_district'";
    }
    if ($id_district == ''){
        $sql .=" WHERE street.name LIKE '$name'";
    }
    if ($name != '' && $id_district != ''){
        $sql .=" WHERE street.name LIKE '$name' AND district.`id` = '$id_district'";
    }

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
        $obj->message = "Search and Flitter is going to OK";
        $obj->data = $row;
        print json_encode($obj);
    } else {
        print_r([]);
    }
    $conn->close();
?>
