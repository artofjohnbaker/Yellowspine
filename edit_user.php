<?php
    require ('includes/config.inc.php');
    include('includes/redirected.php');
    $page_title = 'Edit User';
    include('includes/template_top.inc.php');
    include('includes/mainNav.inc.php');

    //Check for a valid id passed via GET or POST from view_users.php
    if( (isset($_GET['uid'])) && (is_numeric($_GET['uid'])) ){
        $id = $_GET['uid'];
    } else if( (isset($_POST['uid'])) && (is_numeric($_POST['uid'])) ) {
        $id = $_POST['uid'];
    } else { //the id is not valid
        echo '
        <section class="fullPanel">
            <div class="container-fluid">
                <div class="col-sm-2"></div>
                <div class="col-sm-8">
                    <p>This page has been accessed in error</p>
                </div>
                <div class="col-sm-2"></div>
            </div>
        </section>
        ';
        include("includes/template_bottom.inc.php");
        exit();
    }
    //get the connection info
    require (MYSQL);

    if ($_SERVER['REQUEST_METHOD'] == 'POST') { // Handle the form.
        
	// Require the database connection:
	require (MYSQL);
	
	// Trim the incoming data:
	$trimmed = array_map('trim', $_POST);

	// Assume invalid values:
	$fn = $ln = $e = $p = $u = FALSE;
        
	// Check for a first name:
	if (preg_match ('/^[A-Z \'.-]{2,20}$/i', $trimmed['first_name'])) {
		$fn = mysqli_real_escape_string($dbc, $trimmed['first_name']);
	} else {
		$errors[] = '<p class="error">Please enter a first name!</p>';
	}

	// Check for a last name:
	if (preg_match ('/^[A-Z \'.-]{2,40}$/i', $trimmed['last_name'])) {
		$ln = mysqli_real_escape_string($dbc, $trimmed['last_name']);
	} else {
		$errors[] = '<p class="error">Please enter a last name!</p>';
	}
	
	// Check for an email address:
	if (filter_var($trimmed['email'], FILTER_VALIDATE_EMAIL)) {
		$e = mysqli_real_escape_string($dbc, $trimmed['email']);
	} else {
		$errors[] = '<p class="error">Please enter a valid email address!</p>';
	}

	// Check for a password and match against the confirmed password:
	if (preg_match ('/^\w{4,20}$/', $trimmed['password1']) ) {
		if ($trimmed['password1'] == $trimmed['password2']) {
			$p = mysqli_real_escape_string($dbc, $trimmed['password1']);
		} else {
			$errors[] = '<p class="error">The password did not match the confirmed password!</p>';
		}
	} else {
		$errors[] = '<p class="error">Please enter a valid password!</p>';
	}
        
        // Check for a user level:
	if (preg_match ('/^[0-1]{1}$/i', $trimmed['user_level'])) {
		$ul = mysqli_real_escape_string($dbc, $trimmed['user_level']);
	} else {
		$errors[] = '<p class="error">Please enter a user level!</p>';
	}
	
	if (empty($errors)) { // If everything's OK...

		// test for unique email address:
		$q = "SELECT user_id FROM users WHERE email='$e' AND user_id != $id";
		$r = mysqli_query($dbc, $q) or trigger_error("Query: $q\n<br />MySQL Error: " . mysqli_error($dbc));
		
		if (mysqli_num_rows($r) == 0) { // Available.

			// Add the user to the database:
			$q = "UPDATE users SET first_name = ?, last_name = ?, email = ?, pass = ?, user_level = ? WHERE user_id = ? LIMIT 1";
            //prepare the statement
            $stmt = mysqli_prepare($dbc, $q);
            //bind the variables
            mysqli_stmt_bind_param($stmt , 'ssssii', $fn, $ln, $e, SHA1($p), $ul);
            //execute statement
			mysqli_stmt_execute($stmt);

			if (mysqli_stmt_affected_rows($dbc) == 1) { // If it ran OK.
				
				// Finish the page:
				echo '
                    <section class="fullPanel">
                        <div class="container-fluid">
                            <div class="col-sm-2"></div>
                            <div class="col-sm-8">
                                <p>The user has been edited.</p>
                            </div>
                            <div class="col-sm-2"></div>
                        </div>
                    </section>
                            ';
				include ('includes/template_bottom.inc.php'); // Include the HTML footer.
				exit(); // Stop the page.
				
			} else { // If it did not run OK.
                echo '
                    <section class="fullPanel">
                        <div class="container-fluid">
                            <div class="col-sm-2"></div>
                            <div class="col-sm-8">';
				                echo '<p class="error">The user could not be edited due to a system error. We apologize for the inconvenience.</p>';
                                echo '<p>' .
                                    trigger_error("Query: $q\n<br />MySQL Error: " . mysqli_error($dbc))
                                . '</p>';
                        echo '</div>
                            <div class="col-sm-2"></div>
                        </div>
                    </section>';
                include ('includes/template_bottom.inc.php'); // Include the HTML footer.
				exit(); // Stop the page.
			}
			
		} else { // The email address is not available.
                echo '
                    <section class="fullPanel">
                        <div class="container-fluid">
                            <div class="col-sm-2"></div>
                            <div class="col-sm-8">';
			    echo '<p class="error">That email address already belongs to another person.</p>';
                echo '</div>
                            <div class="col-sm-2"></div>
                        </div>
                    </section>';
                include ('includes/template_bottom.inc.php'); // Include the HTML footer.
				exit(); // Stop the page.
		}
		

	}
    //close the statement
    mysqli_stmt_close($stmt);


    } // End of the main Submit conditional.

    //always show the form
    
    //retrieve the users information
    $q = "SELECT first_name, last_name, email FROM users WHERE user_id = $id";
    $r = @mysqli_query($dbc, $q);

    //valid id, show the form
    if(mysqli_num_rows($r) == 1) {
        
        //get the user's info
        $row = mysqli_fetch_array($r, MYSQLI_NUM);
    
        echo '
        <section id="addUserForm" class="fullPanel">
            <div class="container-fluid">
                <div class="row">
                
                    <div class="col-sm-2"></div>
                    
                    <div class="col-sm-8">
                        
                        <form class="form-horizontal" role="form" action="edit_user.php?uid=' . $id .  '" method="post">
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
                                                <input type="text" class="form-control" id="first_name" name="first_name" value="' . $row[0] . '">
                                            </div>
                                            <div class="col-sm-3"></div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="form-group">
                                            <label class="control-label col-sm-3" for="last_name">Last Name:</label>
                                            <div class="col-sm-6"> 
                                                <input type="text" class="form-control" id="last_name" name="last_name" value="' . $row[1] . '">
                                            </div>
                                            <div class="col-sm-3"></div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="form-group">
                                            <label class="control-label col-sm-3" for="email">Email:</label>
                                            <div class="col-sm-6"> 
                                                <input type="email" class="form-control" id="email" name="email" value="' . $row[2] . '">
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
    } else {//not a valid user id
        echo '<p class="error">This page has been accessed in error.</p>';
    }

    //close database connection
	mysqli_close($dbc);

    include("includes/template_bottom.inc.php");
?>