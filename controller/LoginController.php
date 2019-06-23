<?php
    include_once("connection.php");
    include_once('../../model/Student.php');
    class LoginController{
        public static function verfiyStudent($id,$pass){            
            $con = new Database;
            $connecton = Database::getInstance()->getConnection();
            $id =  mysqli_real_escape_string($connecton,$id);          
            $pass =  mysqli_real_escape_string($connecton,$pass);          
            $query = "SELECT * FROM student WHERE id = $id AND PASSWORD = MD5('$pass')";            
            $result = mysqli_query($connecton,$query);
            $row = mysqli_fetch_assoc($result);
            if($result->num_rows == 1){
                session_status();
                $stud = new Student;
                $stud->id = $id;
                $stud->name = $row['name'];
                $stud->email = $row['email'];
                $stud->gpa = $row['gpa'];
                $stud->phone = $row['phone'];
                $_SESSION['stud'] = $stud;
                $_SESSION['sid'] = $id;
                return $stud;
            }else{
                return false;
            }
            
        }

        public static function verfiyStudentId($id){            
            $con = new Database;
            $connecton = Database::getInstance()->getConnection();
            $id =  mysqli_real_escape_string($connecton,$id);                      
            $query = "SELECT * FROM student WHERE id = $id";            
            $result = mysqli_query($connecton,$query);
            $row = mysqli_fetch_assoc($result);
            if($result->num_rows == 1){
                session_status();
                $stud = new Student;
                $stud->id = $id;
                $stud->name = $row['name'];
                $stud->email = $row['email'];
                $stud->gpa = $row['gpa'];
                $stud->phone = $row['phone'];
                $_SESSION['stud'] = $stud;
                $_SESSION['sid'] = $id;
                return $stud;
            }else{
                return false;
            }
            
        }



    }
?>