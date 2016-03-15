<?php
    session_start();
    require ('includes/config.inc.php');
    $page_title = 'Login';
    include('includes/template_top.inc.php');
    include('includes/mainNav.inc.php');


    echo '
    <section id="addUserForm" class="fullPanel">
        <div class="container-fluid">
            <div class="row">
            
                <div class="col-sm-2"></div>
                
                <div class="col-sm-8">
                    
                    <form class="form-horizontal" role="form" action="login.php" method="post">
                        <div class="container-fluid">
                                <div class="row">
                                    <div class="col-sm-1"></div>
                                    <div class="col-sm-10">
                                        <h2 id="pageTitle">' . $page_title . '</h2>
                                    </div>
                                    <div class="col-sm-1"></div>
                                </div>
                                <div class="row">
                                    <div class="form-group"> 
                                        <div class="col-sm-offset-3 col-sm-6">';
                                                                                
                                        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                                            require (MYSQL);
                                            
                                            // Validate the email address:
                                            if (!empty($_POST['email'])) {
                                                $e = mysqli_real_escape_string ($dbc, $_POST['email']);
                                            } else {
                                                $e = FALSE;
                                                $errors[] = '<p class="error">You forgot to enter your email address!</p>';
                                            }
                                            
                                            // Validate the password:
                                            if (!empty($_POST['pass'])) {
                                                $p = mysqli_real_escape_string ($dbc, $_POST['pass']);
                                            } else {
                                                $p = FALSE;
                                                $errors[] = '<p class="error">You forgot to enter your password!</p>';
                                            }
                                            
                                            if ($e && $p) { // If everything's OK.
                                        
                                                // Query the database:
                                                $q = "SELECT user_id, first_name, last_name, user_level FROM users WHERE (email='$e' AND pass=SHA1('$p')) AND active IS NULL";		
                                                $r = mysqli_query ($dbc, $q) or trigger_error("Query: $q\n<br />MySQL Error: " . mysqli_error($dbc));
                                                
                                                if (@mysqli_num_rows($r) == 1) { // A match was made.
                                                    
                                                    // Register the values:
                                                    $_SESSION = mysqli_fetch_array ($r, MYSQLI_ASSOC); 
                                                    mysqli_free_result($r);
                                                    mysqli_close($dbc);
                                                                    
                                                    ob_end_clean(); // Delete the buffer.
                                                    //used JavaScript redirect
                                                    //since session data kept getting dumped on header redirect
                                                    echo '<script type="text/javascript">location.href = "index.php"; </script>';
                                                    //header("Location: $url");
                                                    exit(); // Quit the script.
                                                        
                                                } else { // No match was made.
                                                    $errors[] = '<p class="error">Either the email address and password entered do not match those on file or you have not yet activated your account.</p>';
                                                }
                                                
                                            } else { // If everything wasn't OK.
                                                $errors[] = '<p class="error">Please try again.</p>';
                                            }
                                            
                                            mysqli_close($dbc);
                                        
                                        } // End of SUBMIT conditional.
        echo '
                                    </div>
                                        <div class="col-sm-3"></div>
                                    </div>
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
                                        <label class="control-label col-sm-3" for="email">Username (email):</label>
                                        <div class="col-sm-6">
                                            <input type="text" class="form-control" id="email" name="email" placeholder="Email">
                                        </div>
                                        <div class="col-sm-3"></div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group">
                                        <label class="control-label col-sm-3" for="pass">Password:</label>
                                        <div class="col-sm-6">
                                            <input type="password" class="form-control" id="pass" name="pass" placeholder="Password">
                                        </div>
                                        <div class="col-sm-3"></div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group"> 
                                        <div class="col-sm-offset-3 col-sm-6">
                                            <input id="submit" type="submit" class="btn btn-block" value="Login">
                                            <p style="text-align:center"><small>Cookies must be enabled</small></p>
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