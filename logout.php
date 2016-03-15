<?php 
session_start();
require ('includes/config.inc.php');
$page_title = 'Logged out';
include('includes/template_top.inc.php');

$u = FALSE;

if($_SESSION['user_id']){
    $u == $_SESSION['user_id'];
}

if ($u) {

	$url = BASE_URL . 'index.php'; // Define the URL.
	ob_end_clean(); // Delete the buffer.
	header("Location: $url");
	exit(); // Quit the script.
	
} else { // Log out the user.

	$_SESSION = array(); // Destroy the variables.
	session_destroy(); // Destroy the session itself.
	setcookie (session_name(), '', time()-3600); // Destroy the cookie.
    include('includes/mainNav.inc.php');
    // Print a customized message:
    echo '
        <section id="addUserForm" class="fullPanel">
        <div class="container-fluid">
            <div class="row">
            
                <div class="col-sm-2"></div>
                
                <div class="col-sm-8">
                    <h3 style="text-align:center">You are now logged out.</h3>
                </div><!--end col-sm-8 -->
                
                <div class="col-sm-2"></div>
                
            </div><!--//end container row-->
        </div><!--//end fluid container-->
    </section>
    ';
}

include('includes/template_bottom.inc.php');
?>