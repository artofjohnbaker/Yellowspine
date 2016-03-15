<?php 

require ('includes/config.inc.php');
include('includes/redirected.php');
$page_title = 'Change Password';
include('includes/template_top.inc.php');
include('includes/mainNav.inc.php');

// If no first_name session variable exists, redirect the user:
if (!isset($_SESSION['user_id'])) {
	
	$url = BASE_URL . 'login.php'; // Define the URL.
	ob_end_clean(); // Delete the buffer.
	header("Location: $url");
	exit(); // Quit the script.
	
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	require (MYSQL);
			
	// Check for a new password and match against the confirmed password:
	$p = FALSE;
	if (preg_match ('/^(\w){4,20}$/', $_POST['password1']) ) {
		if ($_POST['password1'] == $_POST['password2']) {
			$p = mysqli_real_escape_string ($dbc, $_POST['password1']);
		} else {
			$errors[] = '<p class="error">Your password did not match the confirmed password!</p>';
		}
	} else {
		$errors[] = '<p class="error">Please enter a valid password!</p>';
	}
	
	if ($p) { // If everything's OK.

		// Make the query:
		$q = "UPDATE users SET pass=SHA1('$p') WHERE user_id={$_SESSION['user_id']} LIMIT 1";	
		$r = mysqli_query ($dbc, $q) or trigger_error("Query: $q\n<br />MySQL Error: " . mysqli_error($dbc));
		if (mysqli_affected_rows($dbc) == 1) { // If it ran OK.

			// Send an email, if desired.
			echo '
            <section id="addUserForm" class="fullPanel">
                <div class="container-fluid">
                    <div class="row">
            
                        <div class="col-sm-2"></div>
                    
                        <div class="col-sm-8">
                            <h3>Your password has been changed.</h3>
                        </div><!--end col-sm-8 -->
                    
                        <div class="col-sm-2"></div>
                
                    </div><!--//end container row-->
                </div><!--//end fluid container-->
            </section>';
			mysqli_close($dbc); // Close the database connection.
			include("includes/template_bottom.inc.php"); // include the footer.
			exit();
			
		} else { // If it did not run OK.
		
			$errors[] = '<p class="error">Your password was not changed. Make sure your new password is different than the current password. Contact the system administrator if you think an error occurred.</p>'; 

		}

	} else { // Failed the validation test.
		$errors[] = '<p class="error">Please try again.</p>';		
	}
	
	mysqli_close($dbc); // Close the database connection.

} // End of the main Submit conditional.

echo '
    <section id="addUserForm" class="fullPanel">
        <div class="container-fluid">
            <div class="row">
            
                <div class="col-sm-2"></div>
                
                <div class="col-sm-8">
                    <form action="change_password.php" class="form-horizontal" role="form" method="post">
                        <div class="container-fluid">
                                <div class="row">
                                    <div class="col-sm-1"></div>
                                    <div class="col-sm-10">
                                        <h2 id="pageTitle">' . $page_title . '</h2>
                                    <div class="col-sm-1"></div>
                                </div>
                                <!--show errors if there are any-->
                                <div class="row">
                                        <div class="col-sm-3"></div>
                                        <div class="col-sm-6">';
                                        
                                        if(!empty($errors)){
                                            foreach($errors as $msg){
                                                echo "$msg\n";   
                                            }
                                        }
                                        
                                        echo'</div>
                                        <div class="col-sm-3"></div>
                                </div>
                                <div class="row">
                                    <div class="form-group">
                                        <label class="control-label col-sm-3" for="password1">New Password:</label>
                                        <div class="col-sm-6">
                                            <input type="password" class="form-control" name="password1" maxlength="20" />
                                        </div>
                                        <div class="col-sm-3"></div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group">
                                        <label class="control-label col-sm-3" for="password2">Confirm New Password:</label>
                                        <div class="col-sm-6">
                                            <input type="password" class="form-control" name="password2" maxlength="20" />
                                        </div>
                                        <div class="col-sm-3"></div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group">
                                        <div class="col-sm-offset-3 col-sm-6">
                                            <p><small>Use only letters, numbers, and the underscore. Must be between 4 and 20 characters long.</small></p>
                                        </div>
                                        <div class="col-sm-3"></div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group"> 
                                        <div class="col-sm-offset-3 col-sm-6">
                                            <input id="submit" type="submit" class="btn btn-block" value="Change Password">
                                        </div>
                                        <div class="col-sm-3"></div>
                                    </div>
                                </div>
                            </div><!--//end fluid container-->
                    </form>
                </div><!--end col-sm-8 -->
                
                <div class="col-sm-2"></div>
                
            </div><!--//end container row-->
        </div><!--//end fluid container-->
    </section>
';

include("includes/template_bottom.inc.php");

?>