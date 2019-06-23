<?php
    session_start();
    include_once("connection.php");
    $resp ="default";
    if(isset($_GET['id'])){

        $con = new Database;
        $connecton = Database::getInstance()->getConnection();        
        $id = $_GET['id'];
        $query = "DELETE FROM `registered_courses` WHERE id = $id";
        $result = mysqli_query($connecton,$query);
        if($result != false){
            if(mysqli_affected_rows($connecton) > 0){                
                $resp = 'Course deleted succesfully';
            }else
            $resp = 'no row fount';            
        } else{
        $resp = 'reasult error';
        echo mysqli_error($connecton);
    }   
    }else
    $resp = 'not post';
    echo json_encode($resp);

    ?>