<?php
session_start();
include '../../config/connection.php';
//header('Content-Type: application/json');
$user_id = $_SESSION['user_id'];
$id_new_course = $_POST['id_new_course'];
error_reporting(0);
//$id_new_course = 9;
$response = null;
$subject_conflect= "";
$connection = DBConnection::get_instance()->get_connection();

$queryFoundIsRegisterThisCourse = "select semester_course.id , course.name , time_table.days , time_table.time FROM semester_course , time_table , course
                      where semester_course.id IN
                      (select registered_courses.Semester_Course_id FROM registered_courses WHERE registered_courses.Student_id = $user_id )
                      AND time_table.id = semester_course.Time_Table_id
                      AND course.id = semester_course.Course_id
                      AND course.name = (select course.name FROM semester_course , course WHERE semester_course.Course_id = course.id
                      AND semester_course.id = $id_new_course)";


$resultQuertFounded = $connection->query($queryFoundIsRegisterThisCourse);
if($resultQuertFounded-> num_rows >  0){
  $response2['status'] = false;
  $response2['message'] = "This course Is Registerd With Other sessions " ;
  echo json_encode($response2);
  exit();

}






$query_get_courses = "select semester_course.id , course.name , time_table.days , time_table.time FROM semester_course , time_table , course
                      where semester_course.id IN
                      (select registered_courses.Semester_Course_id FROM registered_courses WHERE registered_courses.Student_id = $user_id)
                      AND time_table.id = semester_course.Time_Table_id
                      AND course.id = semester_course.Course_id";


$courses =  $connection -> query($query_get_courses);
getTimeForNewCourse($id_new_course);
//print_r($courses);



if($courses-> num_rows == 0){

  $response['status'] = true;
  $response['message'] = "register successfly";
  register_course();
  echo json_encode($response);
  exit();
}else{
  while ($data = $courses->fetch_assoc())
  {
     $tab_days =   preg_split ("/\,/", $data['days']);
     $tab_times = preg_split ("/\ /", $data['time']);

      // echo "\nTTTT\n";
      // print_r($tab_times);


     $patern = "";

     foreach ($tab_days as $key => $value) {
       $patern.= getDayIndex($value);
     }
     $patern .="-" .getTinmePattern($tab_times) ."";

     //print_r($patern);


     //print_r($data);

      echo comparTime($patern , getTimeForNewCourse($id_new_course) , $id_new_course , $data['name']);
      //echo "\nff"  , getDiffrentTimeNewCourse($tab_times);
     // print_r($tab_days);
     // print_r($tab_times);

    $statistic[] = $data;
  }

}




      function comparTime($pattern , $newPattern , $id_new_course  , $name_course_resigter){
        // echo $pattern , " " , $newPattern,"\n";
          global  $response ;
         $days_of_regist = preg_split ("/\-/",$pattern)[0];
         // echo $days_of_regist , "    ";

         $days_of_new = preg_split ("/\-/",$newPattern)[0];
         // echo $days_of_new ,  "    \n";


         $isMatch = false;

         for ($i=0; $i <strlen($days_of_regist) ; $i++) {
           // echo "   --------" , $days_of_regist[$i] , "\n";

           for ($j=0; $j <strlen($days_of_new) ; $j++) {
                  if($days_of_new[$j] == $days_of_regist[$i]){
                    // echo $days_of_new[$j], " == " ,$days_of_regist[$i];
                    $isMatch = true;


                  }
           }

         }



         if($isMatch == true){
          // echo "truuuu ,,, ";
          // echo "\nYou Must Compare Time \n";
          echo  comparTimeByKareemAlgorthim(preg_split ("/\-/",$pattern)[1] , preg_split ("/\-/",$newPattern)[1] , /* interval*/getDiffrentTimeNewCourse(getTimeNewCourse($id_new_course))  , $name_course_resigter  );

         }else{
           $response  .= " true , succsflu ";
         }

      }


function comparTimeByKareemAlgorthim($start_register_time , $start_new_course , $interval_of_new_course , $name_course_resigter   ){
  global $response ;
  global $subject_conflect ;
  //echo "DDD  " ,  abs($start_register_time - $start_new_course )  , " SSSS " , $interval_of_new_course;
  if( (abs((int)$start_register_time - (int)$start_new_course ) >= (int)$interval_of_new_course) ){
        $response .= "true , succsfluy ";
  }else{
        $response .= "false , conflect With :  " . $name_course_resigter;
        //echo "FFF" , $name_course_resigter;
          $subject_conflect .= $name_course_resigter;
          }



  // echo "\n       Start Regist Course Time : " , $start_register_time;
  //   echo "\n       Start new  Course Time : " , $start_new_course;
  //   echo "\n                    " , abs($start_register_time -$start_new_course );
  //   if(  (abs($start_register_time -$start_new_course ) > $interval_of_new_course) > 0){
  //     // echo "\nSuccsflu \n";
  //      // $response['status'] = true;
  //      // $response['message'] = "register successfly";
  //      // register_course();
  //      // echo json_encode($response);
  //
  //      $response .= ",status:" .  true;
  //      $response .= "message : register successfly ,";
  //
  //   }else{
  //
  //     echo (abs($start_register_time -$start_new_course ) > $interval_of_new_course);
  //     // $response['status'] = false;
  //     // $response['message'] = "conflect with course : " . $name_course_resigter;
  //      $subject_conflect = $name_course_resigter;
  //      //print_r( $subject_conflect);
  //      $response .= ",status:" .  false;
  //      $response .= "message : conflect with course : " . $name_course_resigter . ",";
  //      //echo "\nfalid\nTard With " , $name_course_resigter ;
  //     // echo json_encode($response);
  //     //  exit();
  //
  //   }

}


function getTimeNewCourse($id_new_course){
  $connection = DBConnection::get_instance()->get_connection();
  $query = "SELECT
                        semester_course.id , course.name , time_table.days , time_table.time
                        FROM
                        semester_course , time_table , course
                        where
                            semester_course.id = $id_new_course
                        AND
                            time_table.id = semester_course.Time_Table_id
                        AND
                            course.id = semester_course.Course_id";

        $reult_new_course =  $connection -> query($query)->fetch_assoc();
                   $tab_times = preg_split ("/\ /", $reult_new_course['time']);
                  // print_r($tab_times);
                   return $tab_times;
}




function getTimeForNewCourse($id_new_course){
        $connection = DBConnection::get_instance()->get_connection();
        $query = "SELECT
                              semester_course.id , course.name , time_table.days , time_table.time
                              FROM
                              semester_course , time_table , course
                              where
                                  semester_course.id = $id_new_course
                              AND
                                  time_table.id = semester_course.Time_Table_id
                              AND
                                  course.id = semester_course.Course_id";

              $reult_new_course =  $connection -> query($query)->fetch_assoc();

              $pattern_new_course ="";


               $tab_days =   preg_split ("/\,/", $reult_new_course['days']);
               $tab_times = preg_split ("/\ /", $reult_new_course['time']);


               foreach ($tab_days as $key => $value) {
                 $pattern_new_course.= getDayIndex($value);
               }


               $pattern_new_course.="-".getTinmePattern($tab_times);
 //echo "new Course Pattern : " ,$pattern_new_course ," \n";
               return $pattern_new_course;

          //print_r($reult_new_course);

}



//echo json_encode($statistic);


function getTinmePattern($time){
   $time =   preg_split ("/\-/", $time[0]);

   $start_time_array =  preg_split ("/\:/", $time[0]);
   //print_r($start_time_array);

   $minut_start = 200 + (  ((int) $start_time_array[0] - 7 ) * 60 ) +(int) $start_time_array[1] ;
   //echo "stat Time by Minut : " , $minut_start;

   $end_time_array =  preg_split ("/\:/", $time[1]);
   //print_r($end_time_array);
   $minut_end = 200 + ( ((int) $end_time_array[0]  - 7 )* 60 ) +   (int) $end_time_array[1] ;
   //echo "end Time by Minut : " , $minut_end , "\n";


   return $minut_start ;


   //print_r($time);
}

function getDiffrentTimeNewCourse($time){
      //print_r($time);

      $time =   preg_split ("/\-/", $time[0]);
    //  print_r($time);

     $start_time_array =  preg_split ("/\:/", $time[0]);
     //print_r($start_time_array);

      $start_time_by_sconds = $start_time_array[0]*60 + $start_time_array[1];
      //print_r($start_time_by_sconds);

     $end_time_array =  preg_split ("/\:/", $time[1]);
      $end_time_by_sconds = $end_time_array[0]*60 + $end_time_array[1];
      //print_r( "FFFF" . $end_time_by_sconds);
    // echo "\nINTERVAL : " , ($end_time_by_sconds - $start_time_by_sconds ) , " \n";
    return ($end_time_by_sconds - $start_time_by_sconds ) ;

}


function getDayIndex($day){
  switch ($day) {
    case 'sat':
        return 1;
      break;
    case 'sun':
        return 2;
    break;

    case 'mon':
        return 3;
    break;

    case 'thu':
        return 4;
    break;

    case 'wed':
        return 5;
    break;
    default:
      // code...
      break;
  }
}


function register_course(){

  $user_id = $_SESSION['user_id'];
  $course_id = $_POST['id_new_course'];

  $queryInsert = "INSERT INTO `registered_courses`(`Student_id`, `Semester_Course_id`, `grade`) VALUES ($user_id , $course_id , 0.0 )";
  $connection = DBConnection::get_instance()->get_connection();
  $resultInsert = $connection -> query($queryInsert);

  //print_r($resultInsert);

}

// if (strcmp( $response , "conflect") == 0){
//   echo "\n", $response , "\n";
//   echo "FFFF";
// }else{
//   // في تعارض
//   print_r( $subject_conflect);
//   echo "\n",  $response, "\n";
//   echo "SSS";
    // echo $response;
    //cho "\n" ,!(strcmp( $response , "false") == 0) , " fdfd \n";

    if (strpos($response, 'false') !== false) {
      $response2['status'] = false;
      $response2['message'] = "This course Conflet With Course : "  . $subject_conflect  ;
      echo json_encode($response2);
      exit();


      }else{
        $response2['status'] = true;
        $response2['message'] = "Register Course Successfly " ;
        register_course();
        echo json_encode($response2);

        exit();
      }


/*

select course.id FROM registered_courses , semester_course , course
WHERE
	registered_courses.Student_id = 1
    AND
    registered_courses.Semester_Course_id =9
    AND
    course.id = semester_course.Course_id
    AND
    semester_course.Semester_id = (select semester.id FROM semester WHERE semester.year = 2019 AND semester.type_semester = 2)


      -------------------------------------------
       select course.id FROM semester_course , course where semester_course.id = 9 AND semester_course.Course_id = course.id

    ------------------------------------------------------================
    select DISTINCT course.id FROM course , semester_course WHERE course.id = semester_course.Course_id

    */

 ?>
