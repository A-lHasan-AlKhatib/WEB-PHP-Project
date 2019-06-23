<?php
    session_start();
    include_once("connection.php");
    $resp ="default";
    if(isset($_GET['id'])){
        $con = new Database;
        $connecton = Database::getInstance()->getConnection();
        $sid =    $_SESSION['sid'];
        $scid = $_GET['id'];        
        $query = "INSERT INTO `registered_courses`(`Student_id`, `Semester_Course_id`) VALUES ($sid,$scid)";
        $result = mysqli_query($connecton,$query);        
        if($result != false){
            if(mysqli_affected_rows($connecton) > 0){                
                $resp = 'Course added succesfully';
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