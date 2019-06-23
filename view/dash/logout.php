<?php
	session_start();
	session_unset();
    setcookie("stid", $id, time() - (86400 * 30), "/");
	session_destroy();
    header("Location:http://localhost/codes/Project");

?>