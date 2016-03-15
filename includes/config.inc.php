<?php 

// Boolean site status variable:
define('LIVE', TRUE);

// Base URL ending with a slash:
define ('BASE_URL', 'http://your_domain_url.com/');

// URL for the MySQL connection:
// You may want to move mysqli_connect outside the http folder
// and change this URL for security reasons
define ('MYSQL', 'includes/mysqli_connect.php');

// Administrator contact email:
define('DEVEMAIL', 'your_admin_email_address');

// Adjust the time zone for PHP 5.1 and greater:
date_default_timezone_set ('US/Eastern');


//errors array 
$errors = array();

// Custom error handler:
function my_error_handler ($e_number, $e_message, $e_file, $e_line, $e_vars) {

	// Error message:
	$message = "An error occurred in script '$e_file' on line $e_line: $e_message\n";
	
	// Error date and time:
	$message .= "Date/Time: " . date('n-j-Y H:i:s') . "\n";
	
	if (!LIVE) { // if the site is live.

		// Show the error:
        // nl2br is used to insert line breaks where newlines (\n) occur in the string
		echo '<div class="error">' . nl2br($message);
	
		// Add the variables and a backtrace:
		echo '<pre>' . print_r ($e_vars, 1) . "\n";
        // debug_print_backtrace prints a PHP backtrace.  
        // It prints the function calls, included/required files and eval()ed stuff
		debug_print_backtrace();
		echo '</pre></div>';
		
	} else { // if site is LIVE do not show the error:

		// Then send an error email to the admin:
		$body = $message . "\n" . print_r ($e_vars, 1);
		mail(DEVEMAIL, 'Site Error!', $body, 'From: email@example.com');
	
		// Just print the error message 
        // as lonf as the error isn't a notice:
        
		if ($e_number != E_NOTICE) {
			$errors[] = '<div class="error">A system error occurred. We apologize for the inconvenience.</div><br />';
		}
        
	} // End if !LIVE.

} // End my_error_handler()

// Use my_error_handler to handle errors:
set_error_handler ('my_error_handler');