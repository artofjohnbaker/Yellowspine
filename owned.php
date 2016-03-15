<?php
    require ('includes/config.inc.php');
    include('includes/redirected.php');

    // If $bid and $o don't exist or aren't of the proper format, redirect the user:
    if (isset($_POST['bid']) && isset($_POST['o']) ){
        
        $bid = $_POST['bid'];
        $o = $_POST['o'];
    
        // Update the database...
        require (MYSQL);
        $q = "UPDATE books SET owned = ? WHERE book_id = ? LIMIT 1";
        
        $stmt = mysqli_prepare($dbc, $q) or trigger_error("Query: $q\n<br />MySQL Error: " . mysqli_error($dbc));
        
        //bind the variables
        mysqli_stmt_bind_param($stmt , 'ii', $o, $bid);
        
        //execute statement
        mysqli_stmt_execute($stmt);
        
        //close the database connection
        mysqli_close($dbc);
    
    } else { // Redirect.
    
        $url = BASE_URL . 'index.php'; // Define the URL.
        ob_end_clean(); // Delete the buffer.
        header("Location: $url");
        exit(); // Quit the script.
    
    } // End of main IF-ELSE.

?>