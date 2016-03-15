<?php
    
    require ('includes/config.inc.php');
    include('includes/redirected.php');
    $page_title = "View Books";
    include('includes/template_top.inc.php');
    include('includes/mainNav.inc.php');
    require (MYSQL);

    //limit number of records to 100 or less
    $r_limit = 100;
    //get the starting record number if provided
    if(isset($_GET{'s'})) { 
        $s_limit = $_GET{'s'};    
    } else {
        $s_limit = 0;
    }
    echo '
    <script>$("#accordion .collapse").collapse();</script>
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
                                            <p style="text-align:center"><span>Jump to range: </span>
                                            <a href = "' . $_SERVER['PHP_SELF'] . '?s=0" >0-100</a>&nbsp;|&nbsp;
                                            <a href = "' . $_SERVER['PHP_SELF'] . '?s=100">101-200</a>&nbsp;|&nbsp;
                                            <a href = "' . $_SERVER['PHP_SELF'] . '?s=200 ">201-300</a>&nbsp;|&nbsp;
                                            <a href = "' . $_SERVER['PHP_SELF'] . '?s=300">301-400</a>&nbsp;|&nbsp;
                                            <a href = "' . $_SERVER['PHP_SELF'] . '?s=400">401-500</a>&nbsp;|&nbsp;
                                            <a href = "' . $_SERVER['PHP_SELF'] . '?s=500">501-580</a>
                                            </p>
                                            <p style="text-align:center"><small>Click a book title for more info</small></p>
                                            <div class="panel-group" id="accordion" ng-controller="owned">';
                                                //query the database for the skill categories table
                                                $q = "SELECT book_id, book_title, book_author, book_pub_date, book_pub_num, owned FROM books LIMIT $s_limit, $r_limit";		
                                                $r = mysqli_query ($dbc, $q) or trigger_error("Query: $q\n<br />MySQL Error: " . mysqli_error($dbc));
                                                if (mysqli_num_rows($r) > 0) {
                                                    while($row = mysqli_fetch_assoc($r)) {
                                                        echo '<div class="panel panel-default">
                                                                    <div class="panel-heading">
                                                                        <div class="panel-title"> 
                                                                            <span class="book_pub_num"><b>#'.$row["book_pub_num"].'</b></span>&nbsp;
                                                                            <a data-toggle="collapse" data-parent="#accordion" href="#collapse' . $row["book_pub_num"] . '"><em>' . $row["book_title"] . '</em></a>
                                                                            <span style="display:inline-block;float:right">';
                                                                                if($row["owned"] == 1){
                                                                                    echo  '<span class="glyphicon glyphicon-check" ng-click="toggle($event, ' . $row["book_id"] . "," . $_SESSION['user_level'] . ')" data-num="0"></span>';
                                                                                }else{
                                                                                    echo  '<span class="glyphicon glyphicon-unchecked" ng-click="toggle($event, ' . $row["book_id"] . "," . $_SESSION['user_level'] . ')" data-num="1"></span>';
                                                                                }
                                                                       echo     '</span><!--//end right hand span-->
                                                                        </div>
                                                                    </div><!--//end of panel heading-->
                                                                    <div id="collapse' . $row["book_pub_num"] . '" class="panel-collapse collapse">
                                                                        <div class="panel-body">
                                                                            <p><b>Author: </b>' . $row["book_author"] . '</p>
                                                                            <p><b>Publication Date: </b>' . $row["book_pub_date"] . '</p>
                                                                            <p><b><a href="http://www.amazon.com/s/ref=nb_sb_noss?url=search-alias%3Daps&field-keywords=' . $row["book_title"] . " " . $row["book_author"] . " " . substr($row["book_pub_date"],0,4) . '" target="_blank"><span class="glyphicon glyphicon-search"></span>&nbsp;Search Amazon</a></b></p>
                                                                        </div><!--//end of panel body-->
                                                                    </div><!--//end of panel panel-default div-->
                                                                </div><!--//end of panel-collapse collapse in div-->';
                                                    }
                                                } else {
                                                    echo '<p>No books were found</p>';   
                                                }
                                        echo'</div> <!--//end of accordion div-->
                                        </div>
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