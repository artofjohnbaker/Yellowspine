<?php
    require ('includes/config.inc.php');
    include('includes/redirected.php');
    $page_title = 'Search Books';
    include('includes/template_top.inc.php');
    include('includes/mainNav.inc.php');

    //always show the form
    echo '
        <section class="fullPanel">
            <div class="container-fluid">
                <div class="row">
                
                    <div class="col-sm-2"></div>
                    
                    <div class="col-sm-8">
                        <div class="row">
                            <div class="col-sm-12">
                                <h2 id="pageTitle"><span class="glyphicon glyphicon-search"></span>&nbsp;' . $page_title . '</h2>
                            </div>
                        </div>
                        <form action="search_books.php" method="post" role="form" id="searchForm">
                            <div class="form-group">
                                <label for="search_category">Search by:</label>
                                <select name="search_category" id="search_category" class="form-control" >
                                    <option value="book_title" selected>Title</option>
                                    <option value="book_author">Author</option>
                                    <option value="book_pub_num">Number</option>
                                </select>
                            </div>
                            <div class="form-group">
                            <label for="search_term">Search Term: </label>
                            <input type="text" name="search_term" id="search_term" class="form-control" placeholder="Search term"/>
                            </div>
                            <input type="submit" name="submit" class="btn btn-default" value="Submit"/>
                            
                        </form>
                        ';

    if ($_SERVER['REQUEST_METHOD'] == 'POST') { // Handle the form.
    //get the connection info
    require (MYSQL);

        $sc = $_POST['search_category'];
        $search = trim($_POST['search_term']);
        
        if($search){//if a search term was entered
            
            //check search category to determine query category
            switch($sc) {
                case "book_title":
                    $q = "SELECT book_id, book_title, book_author, book_pub_date, book_pub_num, owned FROM books WHERE book_title LIKE '%$search%'";
                    break;
                case "book_author":
                    $q = "SELECT book_id, book_title, book_author, book_pub_date, book_pub_num, owned FROM books WHERE book_author LIKE '%$search%'";
                    break;
                case "book_pub_num":
                    $sc = intval($sc);
                    $q = "SELECT book_id, book_title, book_author, book_pub_date, book_pub_num, owned FROM books WHERE book_pub_num = $search";
                    break;
                default:
                    echo '<p class="error">No search category was chosen.</p>';
            }
            
                //run the query
                $r = mysqli_query ($dbc, $q) or trigger_error("Query: $q\n<br />MySQL Error: " . mysqli_error($dbc));
                
                //show results
                echo '<div class="panel-group" id="accordion" ng-controller="owned">';
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
            echo'</div> <!--//end of accordion div-->';
        } else {//no search term was entered
            echo '<p class="error">No search term was entered.</p>';
        }
           //close database connection
	       mysqli_close($dbc);
    } // End of the main Submit conditional.

    echo            '</div><!--end col-sm-8 -->
                    
                    <div class="col-sm-2"></div>
                    
                </div>
            </div>
        </section>
    ';

    include("includes/template_bottom.inc.php");
?>