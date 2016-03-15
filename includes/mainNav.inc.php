    <!--Begin navbar with Bootstrap classes -->
    <nav class="navbar navbar-inverse" id="mainMenu">
        <div class="container-fluid">
            <div class="navbar-header">
                <!-- Navbar foldout mobile icon -->
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#myNavbar">
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>                        
                </button>
                <!-- Branding -->
                <a class="navbar-brand" href="index.php"><span style="font-family: 'Pacifico', cursive;font-size:1.1em">Yellowspine</span>
                    <?php
                        if (isset($_SESSION['user_level'])) {
                            echo '<span class="glyphicon glyphicon-book"></span>&nbsp;';
                        }
                      ?>  
                </a>
            </div><!--end div.navbar-header-->
            <div class="collapse navbar-collapse" id="myNavbar">
                <!-- Menu actual-->
                <ul class="nav navbar-nav">
                    
                    <?php
                        //the menu should only be seen if user_id is set
                        if (isset($_SESSION['user_id'])) {
                            echo'
                            <li class="dropdown">
                                <a class="dropdown-toggle" data-toggle="dropdown" href="#"><span class="glyphicon glyphicon-th-list"></span>&nbsp;Collection<span class="caret"></span></a>
                                <ul class="dropdown-menu">
                                    <li><a href="index.php"><span class="glyphicon glyphicon-eye-open"></span>&nbsp;View All Books</a></li>
                                    <li><a href="search_books.php"><span class="glyphicon glyphicon-search"></span>&nbsp;Search Collection</a></li>
                                </ul>
                            </li>';
                            
                        }
                        //administrative menu is only for administrators
                        if (isset($_SESSION['user_id']) && ($_SESSION['user_level']==0)) {
                            echo'
                            <li class="dropdown">
                                <a class="dropdown-toggle" data-toggle="dropdown" href="#"><span class="glyphicon glyphicon-user"></span>&nbsp;Users<span class="caret"></span></a>
                                <ul class="dropdown-menu">
                                    <li><a href="view_users.php"><span class="glyphicon glyphicon-eye-open"></span>&nbsp;View Users</a></li>
                                    <li><a href="add_user.php"><span class="glyphicon glyphicon-plus"></span>&nbsp;Add User</a></li>
                                    <li><a href="change_password.php"><span class="glyphicon glyphicon-cog"></span>&nbsp;Change Password</a></li>
                                </ul>
                            </li>
                            ';
                        }
                        //toggle login/logout link
                        if (isset($_SESSION['user_id'])) {
                            echo '<li><a href="logout.php"><span class="glyphicon glyphicon-log-out"></span> Logout</a></li>';
                        } else {
                            echo '<li><a href="login.php"><span class="glyphicon glyphicon-log-in"></span> Login</a></li>';
                        }
                    ?>


                </ul><!--end ul.navbar-nav-->
            </div><!--end div#myNavbar-->
        </div><!--end div.container-fluid-->
    </nav><!--end nav.navbar-inverse-->
    <!-- End navbar with Bootstrap classes -->

    <!--Begin page content-->