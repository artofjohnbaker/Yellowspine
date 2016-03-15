<?php
    require ('includes/config.inc.php');
    $page_title = 'Activate User';
    include('includes/template_top.inc.php');
    include('includes/mainNav.inc.php');

    echo '
    <section id="confirmError" class="fullPanel">
        <div class="container-fluid">
            <div class="row">
            
                <div class="col-sm-2"></div>
                
                <div class="col-sm-8">
    ';

    // If $x and $y don't exist or aren't of the proper format, redirect the user:
    if (isset($_GET['x'], $_GET['y']) 
        && filter_var($_GET['x'], FILTER_VALIDATE_EMAIL)
        && (strlen($_GET['y']) == 32 )
        ) {
    
        // Update the database...
        require (MYSQL);
        $q = "UPDATE users SET active=NULL WHERE (email='" . mysqli_real_escape_string($dbc, $_GET['x']) . "' AND active='" . mysqli_real_escape_string($dbc, $_GET['y']) . "') LIMIT 1";
        $r = mysqli_query ($dbc, $q) or trigger_error("Query: $q\n<br />MySQL Error: " . mysqli_error($dbc));
        
        // Print a customized message:
        if (mysqli_affected_rows($dbc) == 1) {
            // Confirmation message
				echo '<h3>Thank you!</h3><p><b>' . $_GET['x'] . '</b>\'s account is now active. You may now <a href=\'login.php\' target=\'_self\'>log in</a>.</p>';
        } else {
            echo '<p class="error">Your account could not be activated. Please re-check the link or contact the system administrator.</p>'; 
        }
    
        mysqli_close($dbc);
    
    } else { // Redirect.
    
        $url = BASE_URL . 'index.php'; // Define the URL.
        ob_end_clean(); // Delete the buffer.
        header("Location: $url");
        exit(); // Quit the script.
    
    } // End of main IF-ELSE.

    echo'
                </div><!--end col-sm-8 -->
                
                <div class="col-sm-2"></div>
                
            </div>
        </div>
    </section>
    ';

    include("includes/template_bottom.inc.php");
?>