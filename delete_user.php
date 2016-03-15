<?php
    require ('includes/config.inc.php');
    include('includes/redirected.php');
    $page_title = 'Delete User';
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

        if($_POST['sure'] == 'Yes') {//delete the record
            
            //make the query
            $q = "DELETE FROM users WHERE user_id = $id LIMIT 1";
            $r = @mysqli_query($dbc,$q);
            
            if(mysqli_affected_rows($dbc) == 1) {//if it ran ok
                echo '
                <section class="fullPanel">
                    <div class="container-fluid">
                        <div class="col-sm-2"></div>
                        <div class="col-sm-8">
                            <h3>Thank you!<h3> <p>The user has been deleted.</p>
                        </div>
                        <div class="col-sm-2"></div>
                    </div>
                </section>
                ';
            } else {//if the query didn't run ok
                echo '
                <section class="fullPanel">
                    <div class="container-fluid">
                        <div class="col-sm-2"></div>
                        <div class="col-sm-8">
                            <p class="error">The user could not be deleted due to a system error.</p>
                            <p>' . mysqli_error($dbc) . '</p>
                            <p>' . $q . '</p>
                        </div>
                        <div class="col-sm-2"></div>
                    </div>
                </section>
                ';
            }
            
        } else { //no confirmation of deletion
            echo '
            <section class="fullPanel">
                <div class="container-fluid">
                    <div class="col-sm-2"></div>
                    <div class="col-sm-8">
                        <p class="error">The user has NOT been deleted.</p>
                    </div>
                    <div class="col-sm-2"></div>
                </div>
            </section>
            ';
        }
        
    } else { //show the form
        //retrieve the user information
        $q = "SELECT CONCAT(last_name, ',', first_name) FROM users WHERE user_id = $id";
        $r = @mysqli_query($dbc,$q);
        
        if(mysqli_num_rows($r) == 1) {
            //valid user id, show form
            
            //get the user info
            $row = mysqli_fetch_array($r, MYSQLI_NUM);
            
            //which record is being deleted
            echo '
            <section class="fullPanel">
                <div class="container-fluid">
                    <div class="col-sm-2"></div>
                    <div class="col-sm-8">';
            echo '<h3>Name: ' . $row[0] . '</h3>';
            echo '
                    <p>Are you sure you want to delete this user?</p>
                    <form action="delete_user.php" method="POST">
                        <input type="radio" name="sure" value="Yes"/> Yes
                        <input type="radio" name="sure" value="No"/> No
                        <input type="submit" name="submit" value="Submit"/>
                        <input type="hidden" name="uid" value="' . $id . '"/>
                    </form>
                    </div>
                    <div class="col-sm-2"></div>
                </div>
            </section>
            ';
        } else {
            echo '
            <section class="fullPanel">
                <div class="container-fluid">
                    <div class="col-sm-2"></div>
                    <div class="col-sm-8">
                    <p class="error">This page has been accessed in error.</p>
                    ';
            echo '</div>
                    <div class="col-sm-2"></div>
                </div>
            </section>
            ';
        }
    } // End of the main Submit conditional.

    //close database connection
	mysqli_close($dbc);

    include("includes/template_bottom.inc.php");
?>