<?php
session_start();
// redirect to the login page
// if the user_id session is not set
if(!isset($_SESSION['user_id'])){
    header("Location: login.php");
}
?>