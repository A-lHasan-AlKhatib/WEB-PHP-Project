<?php
session_start();
include_once("connection.php");
    $resp = array(
        array("stat" => false, "mesg" => "default")
    );

    $id = $_SESSION['sid'];

    $query ="SELECT registered_courses.id, course.name AS cname, instructors.name AS tname, time_table.sectionNo,time_table.days,time_table.time,time_table.room, semester.semNo FROM instructors, course, time_table, semester, registered_courses,student,semester_course WHERE registered_courses.Student_id = student.id AND registered_courses.Semester_Course_id = semester_course.id AND semester_course.Instructor_id = instructors.id AND semester_course.Time_Table_id = time_table.id AND semester_course.Course_id = course.id AND semester_course.Semester_id = semester.id AND student.id = $id AND registered_courses.grade is null";

    $con = new Database;
    $connecton = Database::getInstance()->getConnection();

    $result = mysqli_query($connecton,$query);
    if($result != false){
        if($result->num_rows > 0){
            while($row = mysqli_fetch_assoc($result)){
                $ar = array(
                    "id" => $row['id'],
                    "cname" => $row['cname'],
                    "tname" => $row['tname'],
                    "secNo" => $row['sectionNo'],
                    "days" => $row['days'],
                    "time" => $row['time'],
                    "room" => $row['room'],
                    "semNo" => $row['semNo']
                );
                array_push($resp,$ar);
            }
            $resp[0]['stat'] = true ;
        } else
        $resp['mesg'] = 'no row fount';
    }else
    $resp['mesg'] = 'reasult error';

    echo json_encode($resp);
?>