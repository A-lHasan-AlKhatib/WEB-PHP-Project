<?php
function formBuild(){ ?>
    <html class="no-js" lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>Student Login Panel</title>
    <meta name="description" content="this is the login panel to the student dasboard">
    <!-- Bootstrap CSS
		============================================ -->
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <!-- Bootstrap CSS
		============================================ -->
    <link rel="stylesheet" href="css/font-awesome.min.css">
    <!-- main CSS
		============================================ -->
    <link rel="stylesheet" href="css/main.css">
    <!-- forms CSS
		============================================ -->
    <link rel="stylesheet" href="css/form/all-type-forms.css">
    <!-- style CSS
		============================================ -->
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12"></div>
            <div class="col-md-4 col-md-4 col-sm-4 col-xs-12">
                <div class="text-center m-b-md custom-login">
                    <h3>Student Login page</h3>
                    <p>Enter your Student Id and Password</p>
                </div>
                <br>
                <div class="hpanel">
                    <div class="panel-body">
                        <form action="" method="POST">
                            <div class="form-group">
                                <label class="control-label" for="id">ID</label>
                                <input type="text" placeholder="Enter your Id here" title="Please enter you Id" required="required" name="id" class="form-control">                                
                            </div>
                     
                            <br>

                            <div class="form-group">
                                <label class="control-label" for="password">Password</label>
                                <input type="password" title="Please enter your password" placeholder="******" required="required" value="" name="password" class="form-control">                                
                            </div>         
                            <br>
                            <div class="custom-control custom-checkbox mb-3">
                            <input type="checkbox" name="remember" value="yes" class="custom-control-input" checked="checked" id="ch">
                            <label class="custom-control-label" for="ch">Remember me</label>
                            </div>                  
                            <br>
                            <input type="submit" class="btn btn-info btn-block loginbtn" value="Login" name = "submit">                           
                        </form>
                    </div>
                </div>
            </div>            
        </div>       
    </div>
</body>
</html> 
<?php  }
include_once('../../controller/LoginController.php');
if(isset($_COOKIE['stid'])){
    session_start();
    $_SESSION['sid'] = $_COOKIE['stid'];
    LoginController::verfiyStudentId($_COOKIE['stid']);
    header('Location: http://localhost/codes/Project/view/dash');
} else{   
    if(isset($_SESSION["logged_in"])&&$_SESSION["logged_in"] == true){
        header('Location: http://localhost/codes/Project/view/dash');
    }else{
        if(!isset($_POST['submit'])){
            formBuild(); 
            die();
        }else{
            session_start();
            extract($_POST);    
            if(LoginController::verfiyStudent($id,$password) != false){
                if(isset($_POST['remember'])){
                    setcookie("stid", $id, time() + (86400 * 30), "/");
                }
                
                $_SESSION["logged_in"] = true;
                header('Location: http://localhost/codes/Project/view/dash');
            }else{
                ?>
            <script>
                alert("Invalid inputs");
            </script>
        <?php
            formBuild();
            die();
            }
        }
    }
}
?>