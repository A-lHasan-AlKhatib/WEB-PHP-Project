<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Student Portal</title>
    <meta name="description" content="Student main Dashboard">
	<meta name="author" content="Al-Hasan">        
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">    
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="css/bootstrap.min.css">    
    <link rel="stylesheet" href="css/style.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
</head>
<body onload="getCourses()">
    <div class="wrapper">
        <div class="left-side">
            <div class="logo">
                <img src="img/logo.png">
            </div>
            <div class="left-content">
                <ul role="tablist">
                    <li class="active"><a href="#one" aria-controls="home" role="tab" data-toggle="tab"><span><i class="fa fa-user"></i></span>Student Profile</a></li>
                    <li><a  href="#two" aria-controls="home" role="tab" data-toggle="tab"><span><i class="fa fa-folder"></i></span>Register Courses</a></li>
                    <li><a href="#three" onclick="deleteTable()" aria-controls="home" role="tab" data-toggle="tab"><span><i class="fa fa-calendar"></i></span>Student Schedule</a></li>
                    <li><a href="#six"  onclick="grades()" aria-controls="home" role="tab" data-toggle="tab"><span><i class="fa fa-align-justify"></i></span>Grades</a></li>
                    <li><a href="logout.php" aria-controls="home" role="tab" target="_self"><span><i class="fa fa-power-off"></i></span>Log Out</a></li>
                </ul>
            </div>
            <div class="copyright">
                <p>Copyright &#169; 2018 <a href="https://colorlib.com/wp/templates/" >Colorlib</a></p>
            </div>
        </div>
        <div class="right-side">
            <div class="right-content">
                <div id="one" class="content active fade in">
                    <h1><span>Welcome</span> to the Student Portal</h1>
                    <div class="content-welcome">
					<div class="alert alert-primary text-center" role="alert">
					<h2>Welcome</h2>
				</div>
				<center>
						   <?php 
								 include_once('../../model/Student.php');								 
								 session_start();								
								 echo "<h2 class='p-3 mb-2 bg-secondary text-white'>Student ID :<p class = 'h3' id ='id'>";								 
								 echo $_SESSION['stud']->id."</p> </h2>";
								 echo "<h2 class = 'p-3 mb-2 bg-light text-dark'>Student Name : ";  
								 echo "<p class = 'h3'>".$_SESSION['stud']->name."</p></h2>";
								 echo "<h2 class = 'p-3 mb-2 bg-secondary text-white'>Student GPA : ";  
								 echo "<p class = 'h3'>".$_SESSION['stud']->gpa."<p></h2>"; 
								 echo "<h2 class = 'p-3 mb-2 bg-light text-dark'>Student Email : "; 
								 echo "<p class = 'h3'>".$_SESSION['stud']->email."<p></h2>";
								 echo "<h2 class = 'p-3 mb-2 bg-secondary text-white'>Student Phone : ";  
                                 echo "<p class = 'h3'>".$_SESSION['stud']->phone."<p></h2>";   
                                 $_SESSION['sid'] = $_SESSION['stud']->id;
						   ?>
</center>
</div>
            </div>
                <div id="two" class="content fade">
				<h1>Courses</h1>
				<form>
					<div>
						<h3 style = "display: inline-block;">Semester : &nbsp; </h3>
                        <h4 style = "display: inline-block;"> <select name="select" id = "ss" onchange="getCourses()"> </h4>
							 <option value="1" selected="selected"><h5>First</h5></option>
								<option value="2"><h5>Second</h5></option>
								<option value="3"><h5>Summer</h5></option>
							</select>                            
					</div>
				</form>

                <div class = "row">

                    <div class = "col-md-6">
                        <table class="table table-hover table-light">
                           
                                <thead class="thead-dark">
                                  <tr>
                                    <th scope="col">Course_name</th>
                                    <th scope="col">Instructor_name</th>
                                    <th scope="col">faculty</th>                                    
                                  </tr>
                                </thead>
                                <tbody id = "t1">
                                  
                                </tbody>
                              </table>
                              
                              
                    </div>

                    <div class = "col-md-6">
                        <table class="table table-hover">

                            <thead class="thead-light">
                                    <tr>
                                        <th scope="col">section No.</th>
                                        <th scope="col">Days</th>
                                        <th scope="col">Time</th>                                    
                                        <th scope="col">Room</th>                                    
                                    </tr>
                            </thead>
                            <tbody id = "t2">

                            </tbody>
                        </table>
                          
                    </div>

                </div>

                <script type="text/javascript">
                function getCourses() {
                    var sems = document.getElementById("ss").value;
                    $.ajax({
                        url: '../../controller/getCourses.php',
                        method: 'POST',
                        dataType: 'JSON',
                        data: {'sems' : sems},
                        success:function(data){                            
                            var da =  data;
                            $('#t1').html("");
                            for (var i = 1 ; i < da.length ; i++) {
                               var ci = da[i].cid;
                               var ti = da[i].tid;
                               var st = sems + "," + ci + "," + ti;
                               
						    $('#t1').append('<tr onclick="myFunction('+sems+","+ci+","+ti+')"><td>'+da[i].cName+'</td><td>'+da[i].tName+'</td><td>'+da[i].tFac+'</td></tr>');
					}
  
                        },
                        error:function(resp, status){
                            alert(status);  
                        }    
                    });                  
                }

        function myFunction(s,c,t) {            
            $.ajax({
            url: '../../controller/getSCourses.php',
            method: 'POST',
            dataType: 'JSON',
            data: {'sems' : s,
                   'course' : c,
                   'tea' : t
            },
            success:function(data){
                var da =  data;                                                            
                $("#t2").html("");
                for(var j = 1 ; j < da.length ; j++){
                    $("#t2").append('<tr  onclick="func('+da[j].scid+')"><td>'+da[j].secNo+'</td><td>'+da[j].days+'</td><td>'+da[j].time+'</td><td>'+da[j].room+'</td></tr>');
                }
            },
            error:function(resp, status){
                alert(status);                    
            }    
        });     
        }   
        function func(id){
            if(confirm('Are you sure you want to add this course?')){                
            $.ajax({
                url: '../../controller/register.php',
                method: 'GET',
                dataType: 'JSON',
                data: {'id': id},
                success:function(data){
                    alert('Course added succesfully');
                },
                error:function(resp,status){
                    alert(status);
                }
            });
            }
        }


        function deleteTable(){
            $.ajax({
                url: '../../controller/tableDelete.php',
                dataType: 'JSON',
                data: {
                    'ss' : 123
                },
                success:function(data){
                    var da =  data;                                                            
                    $("#t3").html(""); 
                    for(var j = 1 ; j < da.length ; j++){
                    $("#t3").append('<tr onclick="dele('+da[j].id+')"><td>'+da[j].cname+'</td><td>'+da[j].tname+'</td><td>'+da[j].secNo+'</td><td>'+da[j].days+'</td><td>'+da[j].time+'</td><td>'+da[j].room+'</td><td>'+da[j].semNo+'</td></tr>');
                }
                },
                error:function(resp,status){
                    alert(resp);
                }
                
            });
        }

        function dele(scid){
            if(confirm('Are you sure you want to delete this course?')){
                $.ajax({                
                    url: '../../controller/dele.php',
                    dataType: 'JSON',
                    method: 'GET',
                    data: {
                        'id' : scid
                    },
                    success:function(data){
                        alert('Course deletet succefully');
                        deleteTable();
                    },error:function(resp,status){
                        alert(resp);
                    }
                    });
            }
        }

        function grades(){

            $.ajax({
                url: '../../controller/grades.php',
                dataType: 'JSON',
                data: {
                    'ss' : 123
                },
                success:function(data){
                    var da =  data;                                                            
                    $("#t3").html(""); 
                    var j = 1;
                    for(j = 1 ; j < da.length -1 ; j++){
                    $("#t4").append('<tr><td>'+da[j].cname+'</td><td>'+da[j].tname+'</td><td>'+da[j].secNo+'</td><td>'+da[j].semNo+'</td><td>'+da[j].grade+'</td></tr>');
                }
                $("#h2").html(""); 
                $("#h2").append("Your AVG = " + da[j]); 
                },
                error:function(resp,status){
                    alert(status);
                }
                
            });

        }
                </script>

                 </div>
              
                <div id="three" class="content fade">
                    <h1>Student Courses</h1>
                    
                    <div class = "col-md-12">
                        <table class="table table-hover">                            
                                <thead class="thead-dark">
                                  <tr>
                                    <th scope="col">Course_name</th>
                                    <th scope="col">Instructor_name</th>
                                    <th scope="col">section No.</th>
                                        <th scope="col">Days</th>
                                        <th scope="col">Time</th>                                    
                                        <th scope="col">Room</th> 
                                        <th scope="col">Semester</th>                                     
                                  </tr>
                                </thead>
                                <tbody id = "t3">
                                  
                                </tbody>
                              </table>
                              
                              
                    </div>

   

                </div>
                
                <div id="six" class="content fade">
				
				<div class="plugins-area">
					<h1>Grades</h1>
					<div class="static-table-list">

                    <div class = "col-md-12">
                        <table class="table table-hover">                            
                                <thead class="thead-dark">
                                  <tr>
                                    <th scope="col">Course_name</th>
                                    <th scope="col">Instructor_name</th>
                                    <th scope="col">section No.</th>                                                                                                                       
                                    <th scope="col">Semester</th>                                     
                                    <th scope="col">Grade</th>                                     
                                  </tr>
                                </thead>
                                <tbody id = "t4">
                                  
                                </tbody>
                              </table>
                              <h2 class='p-3 mb-2 bg-secondary text-white' id = "h2"></h2>
                              
                    </div>



					</div>
				</div>
				</div>
            </div>
        </div>
    </div>
    <script src="js/jquery-3.1.0.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
</body>
</html>