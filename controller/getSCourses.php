<?php
    include_once("connection.php");
    $resp = array(
        array("stat" => false, "mesg" => "defaults")
    );

    if(isset($_POST['sems']) && isset($_POST['course']) && isset($_POST['tea'])){
        $s = $_POST['sems'];
        $c = $_POST['course'];
        $t = $_POST['tea'];
        $si = $s[0];
        $ci = $c[0];
        $ti = $t[0];      
      
        $query = "SELECT semester_course.id AS scid, time_table.id AS timtabid, time_table.sectionNo, time_table.days, time_table.time, time_table.room FROM time_table, semester_course, semester WHERE semester_course.Instructor_id = $ti AND semester_course.Course_id = $ci AND semester_course.Semester_id = semester.id AND semester.semNo = $si AND semester_course.Time_Table_id = time_table.id ";  
        $con = new Database;
        $connecton = Database::getInstance()->getConnection();      
        $result = mysqli_query($connecton,$query);        
        if($result != false){
            if($result->num_rows > 0){
                while($row = mysqli_fetch_assoc($result)){                    
                    $ar = array(
                        "scid" => $row['scid'],
                        "timtabid" => $row['timtabid'],
                        "secNo" => $row['sectionNo'],
                        "days" => $row['days'],
                        "time" => $row['time'],
                        "room" => $row['room']                        
                    );
                    array_push($resp,$ar);
                }                
            } else
            $resp['mesg'] = 'no row fount';
            $resp[0]['stat'] = true ;
        } else{
        $resp['mesg'] = 'reasult error';
        echo mysqli_error($connecton);
    }        
    } else
    $resp['mesg'] = 'not post';
    echo json_encode($resp);
?>