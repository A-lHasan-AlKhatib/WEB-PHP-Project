<?php 
    include_once("connection.php");
    $resp = array(
        array("stat" => false, "mesg" => "default")
    );
        
    if(isset($_POST['sems'])){
        $sem = $_POST['sems'];
        $sems = $sem[0];
        $query = "SELECT DISTINCT course.id AS cid, instructors.id AS tid, course.name AS Course_name , instructors.name AS Instructor_name, instructors.faculty FROM semester_course, course, instructors, semester WHERE Course_id = course.id AND Instructor_id = instructors.id AND Semester_id =semester.id AND semester.semNo = $sems ";
        $con = new Database;
        $connecton = Database::getInstance()->getConnection();      
        $result = mysqli_query($connecton,$query);
        if($result != false){
            if($result->num_rows > 0){
                while($row = mysqli_fetch_assoc($result)){
                    $ar = array(
                        "cid" => $row['cid'],
                        "tid" => $row['tid'],
                        "cName" => $row['Course_name'],
                        "tName" => $row['Instructor_name'],
                        "tFac" => $row['faculty']
                    );
                    array_push($resp,$ar);
                }
                $resp[0]['stat'] = true ;
            } else
            $resp['mesg'] = 'no row fount';
        } else
        $resp['mesg'] = 'reasult error';
    } else
    $resp['mesg'] = 'not post';
    echo json_encode($resp);

?>