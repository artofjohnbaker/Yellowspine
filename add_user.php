<?php
    require ('includes/config.inc.php');
    include('includes/redirected.php');
    $page_title = 'Add New User';
    include('includes/template_top.inc.php');
    include('includes/mainNav.inc.php');
    


    if ($_SERVER['REQUEST_METHOD'] == 'POST') { // Handle the form.

	// Require the database connection:
	require (MYSQL);
	
	// Trim the incoming data:
	$trimmed = array_map('trim', $_POST);

	// Assume invalid values:
	$fn = $ln = $e = $p = $u = FALSE;
        
	// Check for a first name:
	if (preg_match ('/^[A-Z \'.-]{2,20}$/i', $trimmed['first_name'])) {
		$fn = mysqli_real_escape_string ($dbc, $trimmed['first_name']);
	} else {
		$errors[] = '<p class="error">Please enter a first name!</p>';
	}

	// Check for a last name:
	if (preg_match ('/^[A-Z \'.-]{2,40}$/i', $trimmed['last_name'])) {
		$ln = mysqli_real_escape_string ($dbc, $trimmed['last_name']);
	} else {
		$errors[] = '<p class="error">Please enter a last name!</p>';
	}
	
	// Check for an email address:
	if (filter_var($trimmed['email'], FILTER_VALIDATE_EMAIL)) {
		$e = mysqli_real_escape_string ($dbc, $trimmed['email']);
	} else {
		$errors[] = '<p class="error">Please enter a valid email address!</p>';
	}

	// Check for a password and match against the confirmed password:
	if (preg_match ('/^\w{4,20}$/', $trimmed['password1']) ) {
		if ($trimmed['password1'] == $trimmed['password2']) {
			$p = mysqli_real_escape_string ($dbc, $trimmed['password1']);
		} else {
			$errors[] = '<p class="error">The password did not match the confirmed password!</p>';
		}
	} else {
		$errors[] = '<p class="error">Please enter a valid password!</p>';
	}
        
        // Check for a user level:
	if (preg_match ('/^[0-1]{1}$/i', $trimmed['user_level'])) {
		$ul = mysqli_real_escape_string ($dbc, $trimmed['user_level']);
	} else {
		$errors[] = '<p class="error">Please enter a user level!</p>';
	}
	
	if ($fn && $ln && $e && $p) { // If everything's OK...

		// Make sure the email address is available:
		$q = "SELECT user_id FROM users WHERE email='$e'";
		$r = mysqli_query($dbc, $q);
		
		if (mysqli_num_rows($r) == 0) { // Available.

			// Create the activation code:
			$a = md5(uniqid(rand(), true));

			// Add the user to the database:
			$q = "INSERT INTO users (email, pass, first_name, last_name, active, user_level, registration_date) VALUES (?, SHA1(?), ?, ?, ?, ?, NOW())";
            //prepare the statement
            $stmt = mysqli_prepare($dbc, $q);
            //bind the variables
            mysqli_stmt_bind_param($stmt , 'sssssi', $e, $p, $fn, $ln, $a, $ul);
            //execute statement
			mysqli_stmt_execute($stmt);

			if (mysqli_stmt_affected_rows($stmt) == 1) { // If it ran OK.

				// Send the email:
				$body = "Thank you for registering at Yellowspine. To activate your account, please click on this link:\n\n";
				$body .= BASE_URL . 'activate.php?x=' . urlencode($e) . "&y=$a";
				mail($e, 'Registration Confirmation', $body, 'From: johnbaker@bex.net');
				
				// Finish the page:
				echo '
                        <section class="fullPanel">
                            <div class="container-fluid">
                                <div class="col-sm-2"></div>
                                <div class="col-sm-8">
                                    <h3>Thank you for registering!</h3> <p>A confirmation email has been sent to your address. Please click on the link in that email in order to activate your account. This may take a few minutes.</p>
                                </div>
                                <div class="col-sm-2"></div>
                            </div>
                        </section>';
				include ('includes/template_bottom.inc.php'); // Include the HTML footer.
				exit(); // Stop the page.
				
			} else { // If it did not run OK.
				$errors[] = '<p class="error">You could not be registered due to a system error. We apologize for any inconvenience.</p>';
			}
			
		} else { // The email address is not available.
			$errors[] = '<p class="error">That email address has already been registered. If you have forgotten your password, use the link at right to have your password sent to you.</p>';
		}
		
	} else { // If one of the data tests failed.
		$errors[] = '<p class="error">Please try again.</p>';
	}
    //close the statement
    mysqli_stmt_close($stmt);
    //close database connection
	mysqli_close($dbc);

    } // End of the main Submit conditional.

    echo '
    <section id="addUserForm" class="fullPanel">
        <div class="container-fluid">
            <div class="row">
            
                <div class="col-sm-2"></div>
                
                <div class="col-sm-8">
                    
                    <form class="form-horizontal" role="form" action="add_user.php" method="post">
                        
                        <div class="container-fluid">
                                <div class="row">
                                    <div class="col-sm-1"></div>
                                    <div class="col-sm-10">
                                        <h2 id="pageTitle">' . $page_title . '</h2>
                                    </div>
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
                                        <label class="control-label col-sm-3" for="first_name">First Name:</label>
                                        <div class="col-sm-6">
                                            <input type="text" class="form-control" id="first_name" name="first_name" placeholder="First Name">
                                        </div>
                                        <div class="col-sm-3"></div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group">
                                        <label class="control-label col-sm-3" for="last_name">Last Name:</label>
                                        <div class="col-sm-6"> 
                                            <input type="text" class="form-control" id="last_name" name="last_name" placeholder="Last Name">
                                        </div>
                                        <div class="col-sm-3"></div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group">
                                        <label class="control-label col-sm-3" for="email">Email:</label>
                                        <div class="col-sm-6"> 
                                            <input type="email" class="form-control" id="email" name="email" placeholder="Email Address">
                                        </div>
                                        <div class="col-sm-3"></div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group">
                                        <label class="control-label col-sm-3" for="password1">Password:</label>
                                        <div class="col-sm-6"> 
                                            <input type="text" class="form-control" id="password1" name="password1" placeholder="Password">
                                        </div>
                                        <div class="col-sm-3"></div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group">
                                        <label class="control-label col-sm-3" for="password2">Confirm Password:</label>
                                        <div class="col-sm-6"> 
                                            <input type="text" class="form-control" id="password2" name="password2" placeholder="Confirm Password">
                                        </div>
                                        <div class="col-sm-3"></div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group">
                                        <label class="control-label col-sm-3" for="user_level">User Level:</label>
                                        <div class="col-sm-6"> 
                                            <select class="form-control" id="user_level" name="user_level">
                                                <option value="0">Administrator</option>
                                                <option selected value="1">Guest</option>
                                            </select>
                                        </div>
                                        <div class="col-sm-3"></div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group"> 
                                        <div class="col-sm-offset-3 col-sm-3">
                                            <input id="submit" type="submit" class="btn btn-block" value="Submit">
                                        </div>
                                        <div class="col-sm-3">
                                            <input id="reset" type="reset" class="btn btn-block" value="Reset">
                                        </div>
                                        <div class="col-sm-3"></div>
                                    </div>
                                </div>
                        </div><!--//end fluid container-->
                    </form>
                    
                </div><!--end col-sm-8 -->
                
                <div class="col-sm-2"></div>
                
            </div>
        </div>
    </section>
    ';
    include("includes/template_bottom.inc.php");
?>