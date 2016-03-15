<?php 

// Database info using constants:
// Fill in the username for your database
DEFINE ('DB_USER', 'database_username_here');
// Fill in the password for your database
DEFINE ('DB_PASSWORD', 'database_password_here');
// Fill in the hostname, which is usually localhost
DEFINE ('DB_HOST', 'hostname_here');
// Fill in the name of your database
DEFINE ('DB_NAME', 'database_name_here');

// Make the connection as $dbc:
$dbc = @mysqli_connect (DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

// If you cannot connect, show an error:
if (!$dbc) {
	trigger_error ('Could not connect to MySQL: ' . mysqli_connect_error() );
} else { // Else set the encoding for the connection:
	mysqli_set_charset($dbc, 'utf8');
}