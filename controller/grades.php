<?php
session_start();
include_once("connection.php");
    $resp = array(
        array("stat" => false, "mesg" => "default")
    );

    $id = $_SESSION['sid'];

    $query ="SELECT course.name AS cname, instructors.name AS tname, time_table.sectionNo, semester.semNo,  registered_courses.grade FROM instructors, course, time_table, semester, registered_courses,student,semester_course WHERE registered_courses.Student_id = student.id AND registered_courses.Semester_Course_id = semester_course.id AND semester_course.Instructor_id = instructors.id AND semester_course.Time_Table_id = time_table.id AND semester_course.Course_id = course.id AND semester_course.Semester_id = semester.id AND student.id = $id AND registered_courses.grade IS NOT null";
    $query2 ="SELECT AVG(registered_courses.grade) AS avg FROM registered_courses WHERE registered_courses.Student_id = $id";
    $con = new Database;
    $connecton = Database::getInstance()->getConnection();
    $result = mysqli_query($connecton,$query);
    $result2 = mysqli_query($connecton,$query2);    
    if($result != false){
        if($result->num_rows > 0){
            $row2 = mysqli_fetch_assoc($result2);
            $avg = $row2['avg'];
            while($row = mysqli_fetch_assoc($result)){
                $ar = array(                    
                    "cname" => $row['cname'],
                    "tname" => $row['tname'],
                    "secNo" => $row['sectionNo'],
                    "semNo" => $row['semNo'],
                    "grade" => $row['grade']
                );
                array_push($resp,$ar);
            }
            array_push($resp,$avg);
            $resp[0]['stat'] = true ;
        } else
        $resp['mesg'] = 'no row fount';
    }else
    $resp['mesg'] = 'reasult error';

    echo json_encode($resp);
?>