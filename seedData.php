<?php
    $data = json_decode(file_get_contents("php://input"), true);
    $list = $data['list'];
//    print_r($list);
//    $list =array(
//            array("name"=>"Hoàng Quốc Việt", "id_district"=> 1, "created_at"=> "2021-11-01", "des"=> "Test", "status"=> "0"),
//            array("name"=>"Phố Tôn Đức Thắng", "id_district"=> 2, "created_at"=> "2021-11-01", "des"=> "Test", "status"=> "1"),
//            array("name"=>"Hàng Bột", "id_district"=> 3, "created_at"=> "2021-11-01", "des"=> "Test", "status"=> "2"),
//            array("name"=>"Khúc Thừa Dụ", "id_district"=> 4, "created_at"=> "2021-11-01", "des"=> "Test", "status"=> "0"),
//            array("name"=>"Giảng Võ", "id_district"=> 5, "created_at"=> "2021-11-01", "des"=> "Test", "status"=> "1"),
//            array("name"=>"Khuất Duy Tiến", "id_district"=> 1, "created_at"=> "2021-11-01", "des"=> "Test", "status"=> "2"),
//            array("name"=>"Phương Liệt", "id_district"=> 2, "created_at"=> "2021-11-01", "des"=> "Test", "status"=> "0"),
//            array("name"=>"Khuất Duy Tiến", "id_district"=> 3, "created_at"=> "2021-11-01", "des"=> "Test", "status"=> "1"),
//            array("name"=>"Khuất Duy Tiến", "id_district"=> 4, "created_at"=> "2021-11-01", "des"=> "Test", "status"=> "2"),
//            array("name"=>"Bưởi", "id_district"=> 5, "created_at"=> "2021-11-01", "des"=> "Test", "status"=> "1"),
//        );

//    $listDistrict =array(
//        array("name"=>"Cầu Giấy"),
//        array("name"=>"Đống Đa"),
//        array("name"=>"Hai Bà Trưng"),
//        array("name"=>"Hà Đông"),
//        array("name"=>"Hoàn Kiếm"),
//    );

    $listDistrict =$data['listDistrict'];

//    $name = implode(",",$name);
//    $id_district = implode(",",$id_district);
//    $created_at = implode(",",$created_at);
//    $des = implode(",",$des);
//    $status = implode(",",$status);


//$sql = "ALTER TABLE `street`
//            ADD CONSTRAINT `FK_district`
//            FOREIGN KEY (`id_district`)
//            REFERENCES `district`(`id`)
//            ON DELETE RESTRICT ON UPDATE RESTRICT";
//if ($conn->query($sql) === TRUE){
//    echo "Foreign key ok";
//} else{
//    echo "Foreign key fails";
//}

    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "manage_city";

    $conn = new mysqli($servername,$username,$password,$dbname);

    if ($conn->connect_error){
        die("Connection failed: " . $conn->connect_error);
    }
    $conn->set_charset("utf8");

    if ($conn->query("SHOW TABLES LIKE 'district'")->num_rows == 1){
        $conn->query("ALTER TABLE manage_city.street DROP FOREIGN KEY id_district_FK_district_id");
        $conn->query("DROP TABLE district");
        $sqlDistrict = "CREATE TABLE `manage_city`.`district` ( 
                            `id` INT NOT NULL AUTO_INCREMENT , 
                            `name` VARCHAR(250) NOT NULL , PRIMARY KEY (`id`)) ENGINE = InnoDB";
        $conn->query($sqlDistrict);
        for ($row = 0; $row < count($listDistrict); $row++) {
            $name =$listDistrict[$row]["name"];
            $sql = "INSERT INTO district (name) VALUES ('$name')";
            $conn->query($sql);
        }
    } else{
        $sqlDistrict = "CREATE TABLE `manage_city`.`district` ( 
                            `id` INT NOT NULL AUTO_INCREMENT , 
                            `name` VARCHAR(250) NOT NULL , PRIMARY KEY (`id`)) ENGINE = InnoDB";
        $conn->query($sqlDistrict);
        for ($row = 0; $row < count($listDistrict); $row++) {
            $name =$listDistrict[$row]["name"];
            $sql = "INSERT INTO district (name) VALUES ('$name')";
            $conn->query($sql);
        }
    }

    $sql = "SHOW TABLES LIKE 'street'";
    $result = $conn->query($sql);
    header('Content-Type: application/json; charset=utf-8');
    if( $result->num_rows == 1 ) {
        $sql = "DROP TABLE street ";
        $delete = $conn->query($sql);
        if ($delete === TRUE){
            $sql = "CREATE TABLE `manage_city`.`street` ( 
            `id` INT NOT NULL AUTO_INCREMENT , 
            `name` VARCHAR(250) NOT NULL , 
            `id_district` INT NOT NULL , 
            `created_at` DATE NOT NULL , 
            `des` TEXT NOT NULL , 
            `status` INT NOT NULL , 
            PRIMARY KEY (`id`)) ENGINE = InnoDB";
            if ($conn->query($sql) === TRUE){
                for ($row = 0; $row < count($list); $row++) {
                    $name =$list[$row]["name"];
                    $id_district = $list[$row]["id_district"];
                    $created_at = $list[$row]["created_at"];
                    $des = $list[$row]["des"];
                    $status = $list[$row]["status"];

                    $sql = "INSERT INTO street (name,id_district,created_at,des,status) VALUES ('$name','$id_district','$created_at','$des','$status')";
                    if ($conn->query($sql) === TRUE){
                        $sql = "ALTER TABLE `street` 
                            ADD CONSTRAINT `id_district_FK_district_id` 
                            FOREIGN KEY (`id_district`) 
                            REFERENCES `district`(`id`) ON DELETE RESTRICT ON UPDATE RESTRICT";
                            $conn->query($sql);
                            $data = new stdClass();
                            $data->message = 'Action seed Data success';
                            http_response_code(201);
                    } else{
                        $data = new stdClass();
                        $data->message = 'Action seed Data fails';
                        http_response_code(500);
//                    echo json_encode($data);
                    }
                }
            } else{
                echo "Fails Create Table";
            }
        } else{
            echo("This table has not been deleted.");
        }
    } else{
        $sql = "CREATE TABLE `manage_city`.`street` ( 
            `id` INT NOT NULL AUTO_INCREMENT , 
            `name` VARCHAR(250) NOT NULL , 
            `id_district` INT NOT NULL , 
            `created_at` DATE NOT NULL , 
            `des` TEXT NOT NULL , 
            `status` INT NOT NULL , 
            PRIMARY KEY (`id`)) ENGINE = InnoDB";
        if ($conn->query($sql) === TRUE){
            for ($row = 0; $row < count($list); $row++) {
                $name =$list[$row]["name"];
                $id_district = $list[$row]["id_district"];
                $created_at = $list[$row]["created_at"];
                $des = $list[$row]["des"];
                $status = $list[$row]["status"];

                $sql = "INSERT INTO street (name,id_district,created_at,des,status) VALUES ('$name','$id_district','$created_at','$des','$status')";
                if ($conn->query($sql) === TRUE){
                    $sql = "ALTER TABLE `street` 
                            ADD CONSTRAINT `id_district_FK_district_id` 
                            FOREIGN KEY (`id_district`) 
                            REFERENCES `district`(`id`) ON DELETE RESTRICT ON UPDATE RESTRICT";
                    $conn->query($sql);
                    $data = new stdClass();
                    $data->message = 'Action seed Data success';
                    http_response_code(201);
                } else{
                    $data = new stdClass();
                    $data->message = 'Action seed Data fails';
                    http_response_code(500);
//                    echo json_encode($data);
                }
            }
        } else{
            echo "Fails Create Table";
        }
    }
    echo json_encode($data);
    $conn->close();
?>