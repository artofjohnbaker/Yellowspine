<?php
    
    require ('includes/config.inc.php');
    include('includes/redirected.php');
    $page_title = "View Users";
    include('includes/template_top.inc.php');
    include('includes/mainNav.inc.php');
    require (MYSQL);

    echo '
    <section id="addUserForm" class="fullPanel">
        <div class="container-fluid">
            <div class="row">
            
                <div class="col-sm-2"></div>
                
                <div class="col-sm-8">
                    
                    <form class="form-horizontal" role="form">
                        <div class="container-fluid">
                                <div class="row">
                                    <div class="col-sm-1"></div>
                                    <div class="col-sm-10">
                                        <h2 id="pageTitle"><span class="glyphicon glyphicon-eye-open"></span>&nbsp;' . $page_title . '</h2>
                                    </div>
                                    <div class="col-sm-1"></div>
                                </div>
                                <div class="row">
                                    <div class="form-group">
                                        <div class="col-sm-1"></div>
                                        <div class="col-sm-10"> 
                                            <ul class="list-group">';
                                                //query the database for the skill categories table
                                                $q = "SELECT user_id, first_name, last_name, user_level FROM users";		
                                                $r = mysqli_query ($dbc, $q) or trigger_error("Query: $q\n<br />MySQL Error: " . mysqli_error($dbc));
                                                if (mysqli_num_rows($r) > 0) {

                                                    while($row = mysqli_fetch_assoc($r)) {
                                                        //make user level plain string
                                                        if($row["user_level"]==0){
                                                            $ul = "Admin";
                                                        } else {
                                                            $ul = "Guest";
                                                            } 
                                                        echo '<li class="list-group-item">
                                                                <span>(' . $ul . ') </span>
                                                                <span>' . $row["first_name"] . ' ' . $row["last_name"] . '</span>
                                                                <span style="display:inline-block;float:right">
                                                                            <a href="edit_user.php?uid=' . $row["user_id"] . '" class="text-info"><span class="glyphicon glyphicon-pencil"></span></a>&nbsp;
                                                                            <a href="delete_user.php?uid=' . $row["user_id"] . '" class="text-danger"><span class="glyphicon glyphicon-remove-circle"></span></a>
                                                                </span>
                                                            </li>';
                                                    }
                                                } else {
                                                    echo 'No users were found';   
                                                }
                                        echo'</ul> <!--//end of list group-->
                                        </div><!--//end col-sm-10-->
                                        <div class="col-sm-1"></div>
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
    include('includes/template_bottom.inc.php');
?>