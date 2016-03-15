<?php
    require('includes/config.inc.php');
    $page_title = 'Install Yellowspine';
    include('includes/template_top.inc.php');

    if ($_SERVER['REQUEST_METHOD'] == 'POST') { // Handle the form.

	// Require the database connection:
	require (MYSQL);
	
	// Trim the incoming data:
	$trimmed = array_map('trim', $_POST);

	// Assume invalid values:
	$fn = $ln = $e = $p = $u = FALSE;
        
	// Check for a first name:
	if (preg_match ('/^[A-Z \'.-]{2,20}$/i', $trimmed['first_name'])) {
		$fn = mysqli_real_escape_string ($dbc, $trimmed['first_name']);
	} else {
		$errors[] = '<p class="error">Please enter a first name!</p>';
	}

	// Check for a last name:
	if (preg_match ('/^[A-Z \'.-]{2,40}$/i', $trimmed['last_name'])) {
		$ln = mysqli_real_escape_string ($dbc, $trimmed['last_name']);
	} else {
		$errors[] = '<p class="error">Please enter a last name!</p>';
	}
	
	// Check for an email address:
	if (filter_var($trimmed['email'], FILTER_VALIDATE_EMAIL)) {
		$e = mysqli_real_escape_string ($dbc, $trimmed['email']);
	} else {
		$errors[] = '<p class="error">Please enter a valid email address!</p>';
	}

	// Check for a password and match against the confirmed password:
	if (preg_match ('/^\w{4,20}$/', $trimmed['password1']) ) {
		if ($trimmed['password1'] == $trimmed['password2']) {
			$p = mysqli_real_escape_string ($dbc, $trimmed['password1']);
		} else {
			$errors[] = '<p class="error">The password did not match the confirmed password!</p>';
		}
	} else {
		$errors[] = '<p class="error">Please enter a valid password!</p>';
	}
        
	if ($fn && $ln && $e && $p) { // If everything's OK...
        
        //create the user's table
        $q = "CREATE TABLE users (
        user_id INT UNSIGNED NOT NULL AUTO_INCREMENT,
        email varchar(60) NOT NULL,
        pass CHAR(40) NOT NULL,
        first_name varchar(20) NOT NULL,
        last_name varchar(40) NOT NULL,
        active CHAR(32),
        user_level TINYINT(1) UNSIGNED NOT NULL DEFAULT 0,
        registration_date DATETIME NOT NULL,
        PRIMARY KEY (user_id),
        UNIQUE KEY (email)) ENGINE = InnoDB;";
		
        //If users table creation worked
		if (mysqli_query($dbc, $q)) { 

			// Create the activation code:
			$a = md5(uniqid(rand(), true));

			// Add the Admin user to the database:
			$q = "INSERT INTO users (email, pass, first_name, last_name, active, user_level, registration_date) VALUES (?, SHA1(?), ?, ?, ?, 0, NOW())";
            //prepare the statement
            $stmt = mysqli_prepare($dbc, $q);
            //bind the variables
            mysqli_stmt_bind_param($stmt , 'sssss', $e, $p, $fn, $ln, $a);
            //execute statement
			mysqli_stmt_execute($stmt);

			if (mysqli_stmt_affected_rows($stmt) == 1) { // If it ran OK.
                
                //create the books table
                $q = "CREATE TABLE books (
                    book_id INT UNSIGNED AUTO_INCREMENT NOT NULL ,
                    book_title varchar(120) NOT NULL,
                    book_author varchar(80) NOT NULL,
                    book_pub_date DATE NOT NULL,
                    book_pub_num INT UNSIGNED NOT NULL,
                    owned TINYINT(1) UNSIGNED NOT NULL DEFAULT 0,
                    PRIMARY KEY (book_id)) ENGINE = InnoDB;";
                
                //If books table creation worked
                if (mysqli_query($dbc, $q)) {
                
                    //define the books table insert
                    $q = "INSERT INTO books (
                    book_title,
                    book_author,
                    book_pub_date,
                    book_pub_num,
                    owned
                    )
                    VALUES 
                    ('Spell of the Witch World','Andre Norton','1972-04-01', '1', 0),
                    ('The Mind Behind the Eye','Joseph Green','1972-04-01', '2', 0),
                    ('The Probability Man','Brian Ball','1972-04-01', '3', 0),
                    ('The Book of A.E. Van Vogt','A.E. Van Vogt','1972-04-01', '4', 0),
                    ('The 1972 Annual World\'s Best Science Fiction','Donald A. Wollheim & Arthur W. Saha','1972-05-01', '5', 0),
                    ('Day Star','Mark S. Geston','1972-05-01', '6', 0),
                    ('To Challenge Chaos','Brian M. Stableford','1972-05-01', '7', 0),
                    ('The Mindblocked Man','Jeff Sutton','1972-05-01', '8', 0),
                    ('Tactics of Mistake','Gordon R. Dickson','1972-06-01', '9', 0),
                    ('At the Seventh Level','Suzette Haden Elgin','1972-06-01', '10', 0),
                    ('The Day before Tomorrow','Gerard Klein','1972-06-01', '11', 0),
                    ('A Darkness in My Soul','Dean R. Koontz','1972-06-01', '12', 0),
                    ('The Year\'s Best Horror Stories 1','Richard Davis','1972-07-01', '13', 0),
                    ('We Can Build You','Philip K. Dick','1972-07-01', '14', 0),
                    ('The World Menders','Lloyd Biggle Jr.','1972-07-01', '15', 0),
                    ('Genius Unlimited','John T. Phillifent','1972-07-01', '16', 0),
                    ('Blue Face','G.C. Edmondson','1972-08-01', '17', 0),
                    ('Century of the Mannikin','E.C. Tubb','1972-08-01', '18', 0),
                    ('The Regiments of Night','Brian Ball','1972-08-01', '19', 0),
                    ('Ole Doc Methuselah','L. Ron Hubbard','1972-08-01', '20', 0),
                    ('Dinosaur Beach','Keith Laumer','1972-09-01', '21', 0),
                    ('The Return of the Time Machine','Egon Friedell','1972-09-01', '22', 0),
                    ('The Stardroppers','John Brunner','1972-09-01', '23', 0),
                    ('The City Machine','Louis Trimble','1972-09-01', '24', 0),
                    ('Mention My Name in Atlantis','John Jakes','1972-10-01', '25', 0),
                    ('Entry to Elsewhen','John Brunner','1972-10-01', '26', 0),
                    ('Green Phoenix','Thomas Burnett Swann','1972-10-01', '27', 0),
                    ('Sleepwalker\'s World','Gordon R. Dickson','1972-10-01', '28', 0),
                    ('The Book of Brian Aldiss','Brian W. Aldiss','1972-11-01', '29', 0),
                    ('Under the Green Star','Lin Carter','1972-11-01', '30', 0),
                    ('Mirror Image','Michael G. Coney','1972-11-01', '31', 0),
                    ('The Halcyon Drift','Brian M. Stableford','1972-11-01', '32', 0),
                    ('Transit to Scorpio','Alan Burt Akers','1972-12-01', '33', 0),
                    ('Wandering Variables','Louis Trimble','1972-12-01', '34', 0),
                    ('Baphomet\'s Meteor','Pierre Barbet','1972-12-01', '35', 0),
                    ('Darkover Landfall','Marion Zimmer Bradley','1972-12-01', '36', 0),
                    ('Talent for the Invisible','Ron Goulart','1972-01-01', '37', 0),
                    ('The Lion Game','James H. Schmitz','1973-01-01', '38', 0),
                    ('The Book of Frank Herbert','Frank Herbert','1973-01-01', '39', 0),
                    ('Planet Probability','Brian Ball','1973-01-01', '40', 0),
                    ('Changeling Earth','Fred Saberhagen','1973-02-01', '41', 0),
                    ('A Spaceship for the King','Jerry Pournelle','1973-02-01', '42', 0),
                    ('Collision Course','Barrington J. Bayley','1973-02-01', '43', 0),
                    ('The Book of Philip K. Dick','Philip K. Dick','1973-02-01', '44', 0),
                    ('Garan the Eternal','Andre Norton','1973-03-01', '45', 0),
                    ('King of Argent','John T. Phillifent','1973-03-01', '46', 0),
                    ('Time Story','Stuart Gordon','1973-03-01', '47', 0),
                    ('The Other Log of Phileas Fogg','Philip José Farmer','1973-03-01', '48', 0),
                    ('The Suns of Scorpio','Alan Burt Akers','1973-04-01', '49', 0),
                    ('Strange Doings','R.A. Lafferty','1973-04-01', '50', 0),
                    ('Where Were You Last Pluterday?','Paul van Herck','1973-04-01', '51', 0),
                    ('Light That Never Was','Lloyd Biggle, Jr.','1973-04-01', '52', 0),
                    ('The 1973 Annual World\'s Best Science Fiction','Donald A. Wollheim & Arthur W. Saha','1973-05-01', '53', 0),
                    ('Mayenne','E.C. Tubb','1973-05-01', '54', 0),
                    ('The Book of Gordon R. Dickson','Gordon R. Dickson','1973-05-01', '55', 0),
                    ('Friends Come in Boxes','Michael G. Coney','1973-05-01', '56', 0),
                    ('Ocean on Top','Hal Clement','1973-06-01', '57', 0),
                    ('Bernhard the Conqueror','Sam J. Lundwall','1973-06-01', '58', 0),
                    ('Rhapsody in Black','Brian M. Stableford','1973-06-01', '59', 0),
                    ('What\'s Become of Screwloose?','Ron Goulart','1973-06-01', '60', 0),
                    ('The Wrong End of Time','John Brunner','1973-07-01', '61', 0),
                    ('When the Green Star Calls','Lin Carter','1973-07-01', '62', 0),
                    ('The Book of Philip José Farmer','Philip José Farmer','1973-07-01', '63', 0),
                    ('Testament XXI','Guy Snyder','1973-07-01', '64', 0),
                    ('Warrior of Scorpio','Alan Burt Akers','1973-08-01', '65', 0),
                    ('Pandora\'s Planet','Christopher Anvil','1973-08-01', '66', 0),
                    ('The Lords\' Pink Ocean','David Walker','1973-08-01', '67', 0),
                    ('Starmaster\'s Gambit','Gerard Klein','1973-08-01', '68', 0),
                    ('The Pritcher Mass','Gordon R. Dickson','1973-09-01', '69', 0),
                    ('The Hero of Downways','Michael G. Coney','1973-09-01', '70', 0),
                    ('Hunters of the Red Moon','Marion Zimmer Bradley','1973-09-01', '71', 0),
                    ('From This Day Forward','John Brunner','1973-09-01', '72', 0),
                    ('Breaking Point','James E. Gunn','1973-10-01', '73', 0),
                    ('Jondelle','E.C. Tubb','1973-10-01', '74', 0),
                    ('The Crystal Gryphon','Andre Norton','1973-10-01', '75', 0),
                    ('One-Eye','Stuart Gordon','1973-10-01', '76', 0),
                    ('The End of the Dream','Philip Wylie','1973-11-01', '77', 0),
                    ('Beanstalk','John Rackham','1973-11-01', '78', 0),
                    ('The Orchid Cage','Herbert W. Franke','1973-11-01', '79', 0),
                    ('The Tin Angel','Ron Goulart','1973-11-01', '80', 0),
                    ('Swordships of Scorpio','Alan Burt Akers','1973-12-01', '81', 0),
                    ('The Telzey Toy','James H. Schmitz','1973-12-01', '82', 0),
                    ('Games Psyborgs Play','Pierre Barbet','1973-12-01', '83', 0),
                    ('Singularity Station','Brian Ball','1973-12-01', '84', 0),
                    ('Polymath','John Brunner','1974-01-01', '85', 0),
                    ('The Bodelan Way','Louis Trimble','1974-01-01', '86', 0),
                    ('The Book of Fritz Leiber','Fritz Leiber','1974-01-01', '87', 0),
                    ('A Quest for Simbilis','Michael Shea','1974-01-01', '88', 0),
                    ('Midsummer Century','James Blish','1974-02-01', '89', 0),
                    ('Mindship','Gerard F. Conway','1974-02-01', '90', 0),
                    ('The Burrowers Beneath','Brian Lumley','1974-02-01', '91', 0),
                    ('Promised Land','Brian M. Stableford','1974-02-01', '92', 0),
                    ('The Overlords of War','Gerard Klein','1974-03-01', '93', 0),
                    ('How Are The Mighty Fallen','Thomas Burnett Swann','1974-03-01', '94', 0),
                    ('Identity Seven','Robert Lory','1974-03-01', '95', 0),
                    ('Hunters of Gor','John Norman','1974-03-01', '96', 0),
                    ('Prince of Scorpio','Alan Burt Akers','1974-04-01', '97', 0),
                    ('As the Curtain Falls','Robert Chilson','1974-04-01', '98', 0),
                    ('Can You Feel Anything When I Do This?','Robert Sheckley','1974-04-01', '99', 0),
                    ('Hadon of Ancient Opar','Philip José Farmer','1974-04-01', '100', 0),
                    ('The 1974 Annual World\'s Best Science Fiction','Donald A. Wollheim & Arthur W. Saha','1974-05-01', '101', 0),
                    ('The Unsleeping Eye','D.G. Compton','1974-05-01', '102', 0),
                    ('The Hawks of Arcturus','Cecil Snyder III','1974-05-01', '103', 0),
                    ('The Weathermonger','Peter Dickinson','1974-05-01', '104', 0),
                    ('The Fall of Chronopolis','Barrington J. Bayley','1974-06-01', '105', 0),
                    ('The Metallic Muse','Lloyd Biggle, Jr.','1974-06-01', '106', 0),
                    ('Flux','Ron Goulart','1974-06-01', '107', 0),
                    ('All Times Possible','Gordon Eklund','1974-06-01', '108', 0),
                    ('The Year\'s Best Horror Stories 2','Richard Davis','1974-07-01', '109', 0),
                    ('By the Light of the Green Star','Lin Carter','1974-07-01', '110', 0),
                    ('The Paradise Game','Brian M. Stableford','1974-07-01', '111', 0),
                    ('Give Warning to the World!','John Brunner','1974-07-01', '112', 0),
                    ('Manhounds of Antares','Alan Burt Akers','1974-08-01', '113', 0),
                    ('The Man With a Thousand Names','A.E. van Vogt','1974-08-01', '114', 0),
                    ('Zenya','E.C. Tubb','1974-08-01', 115, 0),
                    ('The Star Road','Gordon R. Dickson','1974-08-01', '116', 0),
                    ('To Die in Italbar','Roger Zelazny','1974-09-01', '117', 0),
                    ('Triple Détente','Piers Anthony','1974-09-01', '118', 0),
                    ('The Spell Sword','Marion Zimmer Bradley','1974-09-01', '119', 0),
                    ('Monitor Found in Orbit','Michael G. Coney','1974-09-01', '120', 0),
                    ('Here Abide Monsters','Andre Norton','1974-10-01', '121', 0),
                    ('Two-Eyes','Stuart Gordon','1974-10-01', '122', 0),
                    ('The Mind Net','Herbert W. Franke','1974-10-01', '123', 0),
                    ('A Touch of Infinity','Howard Fast','1974-10-01', '124', 0),
                    ('The Warrior of World\'s End','Lin Carter','1974-11-01', '125', 0),
                    ('Hard to Be a God','Arkady & Boris Strugatsky','1974-11-01', '126', 0),
                    ('A Voyage to Dari','Ian Wallace','1974-11-01', '127', 0),
                    ('Stress Pattern','Neal Barrett, Jr.','1974-11-01', '128', 0),
                    ('Arena of Antares','Alan Burt Akers','1974-12-01', '129', 0),
                    ('The Fenris Device','Brian M. Stableford','1974-12-01', '130', 0),
                    ('Conscience Interplanetary','Joseph Green','1974-12-01', '131', 0),
                    ('Spacehawk, Inc.','Ron Goulart','1974-12-01', '132', 0),
                    ('The Stone That Never Came Down','John Brunner','1975-01-01', '133', 0),
                    ('The Mote in Time\'s Eye','Gerard Klein','1975-01-01', '134', 0),
                    ('The Warriors of Dawn','M.A. Foster','1975-01-01', '135', 0),
                    ('The Book of Saberhagen','Fred Saberhagen','1975-01-01', '136', 0),
                    ('The R-Master','Gordon R. Dickson','1975-02-01', '137', 0),
                    ('As the Green Star Rises','Lin Carter','1975-02-01', '138', 0),
                    ('The Big Black Mark','A. Bertram Chandler','1975-02-01', '139', 0),
                    ('The Not World','Thomas Burnett Swann','1975-02-01', '140', 0),
                    ('Marauders of Gor','John Norman','1975-03-01', '141', 0),
                    ('2018 A.D. or the King Kong Blues','Sam J. Lundwall','1975-03-01', '142', 0),
                    ('Eloise','E.C. Tubb','1975-03-01', '143', 0),
                    ('The Jaws that Bite, The Claws That Catch','Michael G. Coney','1975-03-01', '144', 0),
                    ('Fliers of Antares','Alan Burt Akers','1975-04-01', '145', 0),
                    ('Flow My Tears, the Policeman Said','Philip K. Dick','1975-04-01', '146', 0),
                    ('Berserker Planet','Fred Saberhagen','1975-04-01', '147', 0),
                    ('The 1975 Annual World\'s Best Science Fiction','Donald A. Wollheim & Arthur W. Saha','1975-05-01', '148', 0),
                    ('Swan Song','Brian M. Stableford','1975-05-01', '149', 0),
                    ('Enchantress of World\'s End','Lin Carter','1975-05-01', '150', 0),
                    ('The Transition of Titus Crow','Brian Lumley','1975-05-01', '151', 0),
                    ('Merlin\'s Mirror','Andre Norton','1975-06-01', '152', 0),
                    ('The Book of Poul Anderson','Poul Anderson','1975-06-01', '153', 0),
                    ('The Birthgrave','Tanith Lee','1975-06-01', '154', 0),
                    ('Year\'s Best Horror Stories: III','Richard Davis','1975-07-01', '155', 0),
                    ('Enchanted Planet','Pierre Barbet','1975-07-01', '156', 0),
                    ('The Whenabouts of Burr','Michael Kurland','1975-07-01', '157', 0),
                    ('Twilight of Briareus','Richard Cowper','1975-07-01', '158', 0),
                    ('Bladesman of Antares','Alan Burt Akers','1975-08-01', '159', 0),
                    ('The Heritage of Hastur','Marion Zimmer Bradley','1975-08-01', '160', 0),
                    ('The Star-Crowned Kings','Rob Chilson','1975-08-01', '161', 0),
                    ('Total Eclipse','John Brunner','1975-09-01', '162', 0),
                    ('Eye of the Zodiac','E.C. Tubb','1975-09-01', '163', 0),
                    ('The Second Book of Fritz Leiber','Fritz Leiber','1975-09-01', '164', 0),
                    ('The Book of Andre Norton','Andre Norton','1975-10-01', '165', 0),
                    ('The Year\'s Best Fantasy Stories','Lin Carter','1975-10-01', '166', 0),
                    ('Star','C.I. Defontenay','1975-10-01', '167', 0),
                    ('Warlord\'s World','Christopher Anvil','1975-10-01', '168', 0),
                    ('Time Slave','John Norman','1975-11-01', '169', 0),
                    ('Rax','Michael G. Coney','1975-11-01', '170', 0),
                    ('Three-Eyes','Stuart Gordon','1975-11-01', '171', 0),
                    ('Soldier, Ask Not','Gordon R. Dickson','1975-11-01', '172', 0),
                    ('Avenger of Antares','Alan Burt Akers','1975-12-01', '173', 0),
                    ('Green Gene','Peter Dickinson','1975-12-01', '174', 0),
                    ('When the Waker Sleeps','Ron Goulart','1975-12-01', '175', 0),
                    ('Beyond the Galactic Lens','Gregory Kern','1975-12-01', '176', 0),
                    ('The Book of John Brunner','John Brunner','1976-01-01', '177', 0),
                    ('The Land Leviathan','Michael Moorcock','1976-01-01', '178', 0),
                    ('Witling','Vernor Vinge','1976-01-01', '179', 0),
                    ('In the Green Star\'s Glow','Lin Carter','1976-01-01', '180', 0),
                    ('Dorsai!','Gordon R. Dickson','1976-02-01', '181', 0),
                    ('The Minikins of Yam','Thomas Burnett Swann','1976-02-01', '182', 0),
                    ('Tomorrow Knight','Michael Kurland','1976-02-01', '183', 0),
                    ('Don\'t Bite the Sun','Tanith Lee','1976-02-01', '184', 0),
                    ('Tribesmen of Gor','John Norman','1976-03-01', '185', 0),
                    ('The Wrath of Fu Manchu','Sax Rohmer','1976-03-01', '186', 0),
                    ('Ironcastle','J.H. Rosny','1976-03-01', '187', 0),
                    ('Gate of Ivrel','C.J. Cherryh','1976-03-01', '188', 0),
                    ('Armada of Antares','Alan Burt Akers','1976-04-01', '189', 0),
                    ('Ancient, My Enemy','Gordon R. Dickson','1976-04-01', '190', 0),
                    ('The Shattered Chain','Marion Zimmer Bradley','1976-04-01', '191', 0),
                    ('The 1976 Annual World\'s Best Science Fiction','Donald A. Wollheim & Arthur W. Saha','1976-05-01', '192', 0),
                    ('The Storm Lord','Tanith Lee','1976-05-01', '193', 0),
                    ('The Mind Riders','Brian M. Stableford','1976-05-01', '194', 0),
                    ('Aldair in Albion','Neal Barrett, Jr','1976-05-01', '195', 0),
                    ('Perilous Dreams','Andre Norton','1976-06-01', '196', 0),
                    ('Flight to Opar','Philip José Farmer','1976-06-01', '197', 0),
                    ('Jack of Swords','E.C. Tubb','1976-06-01', '198', 0),
                    ('The Napoleons of Eridanus','Pierre Barbet','1976-06-01', '199', 0),
                    ('The DAW Science Fiction Reader','Donald A. Wollheim','1976-07-01', '200', 0),
                    ('Bunduki','J.T. Edson','1976-07-01', 201, 0),
                    ('A World Called Camelot','Arthur Landis','1976-07-01', '202', 0),
                    ('Quicksand','John Brunner','1976-07-01', '203', 0),
                    ('Tides of Kregen','Alan Burt Akers','1976-08-01', '204', 0),
                    ('The Year\'s Best Fantasy Stories: 2','Lin Carter','1976-08-01', '205', 0),
                    ('Earth Factor X','A.E. van Vogt','1976-08-01', '206', 0),
                    ('A Whiff of Madness','Ron Goulart','1976-08-01', '207', 0),
                    ('Interstellar Empire','John Brunner','1976-09-01', '208', 0),
                    ('Kioga of the Wilderness','William L. Chester','1976-09-01', '209', 0),
                    ('The Immortal of World\'s End','Lin Carter','1976-09-01', '210', 0),
                    ('The Florians','Brian M. Stableford','1976-09-01', '211', 0),
                    ('Brothers of the Earth','C.J. Cherryh','1976-10-01', '212', 0),
                    ('The Disciples of Cthulhu','Edward P. Berglund','1976-10-01', '213', 0),
                    ('Elric of Melniboné','Michael Moorcock','1976-10-01', '214', 0),
                    ('The Second War of the Worlds','George H. Smith','1976-10-01', '215', 0),
                    ('The World Asunder','Ian Wallace','1976-11-01', '216', 0),
                    ('Year\'s Best Horror Stories: IV','Gerald W. Page','1976-11-01', '217', 0),
                    ('Final Circle of Paradise','Arkady & Boris Strugatsky','1976-11-01', '218', 0),
                    ('Spectrum of a Forgotten Sun','E.C. Tubb','1976-11-01', '219', 0),
                    ('The Sailor on the Seas of Fate','Michael Moorcock','1976-12-01', '220', 0),
                    ('Renegade of Kregen','Alan Burt Akers','1976-12-01', '221', 0),
                    ('The Gods Abide','Thomas Burnett Swann','1976-12-01', '222', 0),
                    ('Walkers on the Sky','David J. Lake','1976-12-01', '223', 0),
                    ('Supermind','A.E. van Vogt','1977-01-01', '224', 0),
                    ('The Jewel in the Skull','Michael Moorcock','1977-01-01', '225', 0),
                    ('Drinking Sapphire Wine','Tanith Lee','1977-01-01', '226', 0),
                    ('Naked to the Stars','Gordon R. Dickson','1977-01-01', '227', 0),
                    ('One Against the Wilderness','William L. Chester','1977-02-01', '228', 0),
                    ('Legends from the End of Time','Michael Moorcock','1977-02-01', '229', 0),
                    ('Critical Threshold','Brian M. Stableford','1977-02-01', '230', 0),
                    ('Panchronicon Plot','Ron Goulart','1977-02-01', '231', 0),
                    ('Slave Girl of Gor','John Norman','1977-03-01', '232', 0),
                    ('The Weird of the White Wolf','Michael Moorcock','1977-03-01', '233', 0),
                    ('Star Courier','A. Bertram Chandler','1977-03-01', '234', 0),
                    ('Diadem from the Stars','Jo Clayton','1977-03-01', '235', 0),
                    ('The Gameplayers of Zan','M.A. Foster','1977-04-01', '236', 0),
                    ('Krozair of Kregen','Alan Burt Akers','1977-04-01', '237', 0),
                    ('The Mad God\'s Amulet','Michael Moorcock','1977-04-01', '238', 0),
                    ('The Right Hand of Dextra','David J. Lake','1977-04-01', '239', 0),
                    ('The 1977 Annual World\'s Best Science Fiction','Donald A. Wollheim & Arthur W. Saha','1977-05-01', '240', 0),
                    ('Earthchild','Doris Piserchia','1977-05-01', '241', 0),
                    ('Haven of Darkness','E.C. Tubb','1977-05-01', '242', 0),
                    ('The Barbarian of World\'s End','Lin Carter','1977-05-01', '243', 0),
                    ('The Best of John Jakes','John Jakes','1977-06-01', '244', 0),
                    ('The Vanishing Tower','Michael Moorcock','1977-06-01', '245', 0),
                    ('Beneath the Shattered Moon','Michael Bishop','1977-06-01', '246', 0),
                    ('Wildings of Westron','David J. Lake','1977-06-01', '247', 0),
                    ('The Realms of Tartarus','Brian M. Stableford','1977-07-01', '248', 0),
                    ('The Sword of the Dawn','Michael Moorcock','1977-07-01', '249', 0),
                    ('Year\'s Best Horror Stories: V','Gerald W. Page','1977-07-01', '250', 0),
                    ('Volkhavaar','Tanith Lee','1977-07-01', '251', 0),
                    ('Hunter of Worlds','C.J. Cherryh','1977-08-01', '252', 0),
                    ('Warlord of Chandor','Del Dowdell','1977-08-01', '253', 0),
                    ('The Bane of the Black Sword','Michael Moorcock','1977-08-01', '254', 0),
                    ('The Grand Wheel','Barrington J. Bayley','1977-08-01', '255', 0),
                    ('The Forbidden Tower','Marion Zimmer Bradley','1977-09-01', '256', 0),
                    ('The Runestaff','Michael Moorcock','1977-09-01', '257', 0),
                    ('The Siege of Wonder','Mark S. Geston','1977-09-01', '258', 0),
                    ('Master of Ships','Neal Barrett, Jr.','1977-09-01', '259', 0),
                    ('Dream Chariots','Manning Norvil','1977-10-01', '260', 0),
                    ('Productions of Time','John Brunner','1977-10-01', '261', 0),
                    ('Passing for Human','Jody Scott','1977-10-01', '262', 0),
                    ('Wildeblood\'s Empire','Brian M. Stableford','1977-10-01', '263', 0),
                    ('Stormbringer','Michael Moorcock','1977-11-01', '264', 0),
                    ('Monday Begins on Saturday','Arkady Strugatsky','1977-11-01', '265', 0),
                    ('None But Man','Gordon R. Dickson','1977-11-01', 266, 0),
                    ('The Year\'s Best Fantasy Stories: 3','Lin Carter','1977-11-01', '267', 0),
                    ('The Best from the Rest of the World','Donald A. Wollheim','1977-12-01', '268', 0),
                    ('Secret Scorpio','Alan Burt Akers','1977-12-01', '269', 0),
                    ('Cry Silver Bells','Thomas Burnett Swann','1977-12-01', '270', 0),
                    ('Prison of Night','E.C. Tubb','1977-12-01', '271', 0),
                    ('Vazkor, Son of Vazkor','Tanith Lee','1978-01-01', '272', 0),
                    ('The Way Back','A. Bertram Chandler','1978-01-01', '273', 0),
                    ('Necromancer','Gordon R. Dickson','1978-01-01', '274', 0),
                    ('Lamarchos','Jo Clayton','1978-01-01', '275', 0),
                    ('Quest for the White Witch','Tanith Lee','1978-02-01', '276', 0),
                    ('A Messiah at the End of Time','Michael Moorcock','1978-02-01', '277', 0),
                    ('A Spectre Is Haunting Texas','Fritz Leiber','1978-02-01', '278', 0),
                    ('The Gods of Xuma','David J. Lake','1978-02-01', '279', 0),
                    ('Beasts of Gor','John Norman','1978-03-01', '280', 0),
                    ('The Whetted Bronze','Manning Norvil','1978-03-01', '281', 0),
                    ('Dying for Tomorrow','Michael Moorcock','1978-03-01', '282', 0),
                    ('Calling Dr. Patchwork','Ron Goulart','1978-03-01', '283', 0),
                    ('The Well of Shiuan','C.J. Cherryh','1978-04-01', '284', 0),
                    ('Savage Scorpio','Alan Burt Akers','1978-04-01', '285', 0),
                    ('Touch of Strange','Theodore Sturgeon','1978-04-01', '286', 0),
                    ('Joan-of-Arc Replay','Pierre Barbet','1978-04-01', '287', 0),
                    ('The 1978 Annual World\'s Best Science Fiction','Donald A. Wollheim & Arthur W. Saha','1978-05-01', '288', 0),
                    ('The City of the Sun','Brian M. Stableford','1978-05-01', '289', 0),
                    ('Kioga of the Unknown Land','William L. Chester','1978-05-01', '290', 0),
                    ('Warlord of the Air','Michael Moorcock','1978-05-01', '291', 0),
                    ('Stormqueen!','Marion Zimmer Bradley','1978-06-01', '292', 0),
                    ('The Wizard of Zao','Lin Carter','1978-06-01', '293', 0),
                    ('Star Winds','Barrington J. Bayley','1978-06-01', '294', 0),
                    ('To Keep the Ship','A. Bertram Chandler','1978-07-01', '295', 0),
                    ('Pursuit of the Screamer','Ansen Dibell','1978-07-01', '296', 0),
                    ('Year\'s Best Horror Stories: VI','Gerald W. Page','1978-07-01', '297', 0),
                    ('The Island Snatchers','George H. Smith','1978-07-01', '298', 0),
                    ('Incident on Ath','E.C. Tubb','1978-08-01', '299', 0),
                    ('Kesrith','C.J. Cherryh','1978-08-01', '300', 0),
                    ('Captive Scorpio','Alan Burt Akers','1978-08-01', '301', 0),
                    ('The Panorama Egg','A.E. Silas','1978-08-01', '302', 0),
                    ('Hour of the Horde','Gordon R. Dickson','1978-08-01', '303', 0),
                    ('Yurth Burden','Andre Norton','1978-09-01', '304', 0),
                    ('Star King','Jack Vance','1978-09-01', '305', 0),
                    ('Irsud','Jo Clayton','1978-09-01', '306', 0),
                    ('Rituals of Infinity','Michael Moorcock','1978-09-01', '307', 0),
                    ('Z-Sting','Ian Wallace','1978-10-01', '308', 0),
                    ('The Killing Machine','Jack Vance','1978-10-01', '309', 0),
                    ('The Pirates of World\'s End','Lin Carter','1978-10-01', '310', 0),
                    ('The Wicked Cyborg','Ron Goulart','1978-10-01', '311', 0),
                    ('Wyst: Alastor 1716','	Jack Vance','1978-11-01', '312', 0),
                    ('Night\'s Master','Tanith Lee','1978-11-01', '313', 0),
                    ('War-Gamers\' World','Hugh Walker','1978-11-01', '314', 0),
                    ('Camelot in Orbit','Arthur Landis','1978-11-01', '315', 0),
                    ('Pendulum','A.E. van Vogt','1978-12-01', '316', 0),
                    ('Golden Scorpio','	Alan Burt Akers','1978-12-01', '317', 0),
                    ('The Year\'s Best Fantasy Stories: 4','Lin Carter','1978-12-01', '318', 0),
                    ('The Quillian Sector','E.C. Tubb','1978-12-01', '319', 0),
                    ('The Survivors','Marion Zimmer Bradley & Paul Edwin Bradley','1979-01-01', '320', 0),
                    ('City of the Beast','Michael Moorcock','1979-01-01', '321', 0),
                    ('The Balance of Power','Brian M. Stableford','1979-01-01', '322', 0),
                    ('Army of Darkness','Hugh Walker','1979-01-01', '323', 0),
                    ('Death\'s Master','Tanith Lee','1979-02-01', '324', 0),
                    ('The Palace of Love','Jack Vance','1979-02-01', '325', 0),
                    ('Lord of the Spiders','Michael Moorcock','1979-02-01', '326', 0),
                    ('The Far Traveler','A. Bertram Chandler','1979-02-01', '327', 0),
                    ('Explorers of Gor','John Norman','1979-03-01', '328', 0),
                    ('Isaac Asimov Presents the Great SF Stories, 1','Isaac Asimov & Martin H. Greenberg','1979-03-01', '329', 0),
                    ('Masters of the Pit','Michael Moorcock','1979-03-01', '330', 0),
                    ('Hello Lemuria Hello','Ron Goulart','1979-03-01', '331', 0),
                    ('Messengers of Darkness','Hugh Walker','1979-03-01', '332', 0),
                    ('Shon\'Jir','C.J. Cherryh','1979-04-01', '333', 0),
                    ('Heroic Fantasy','Gerald W. Page & Hank Reinhardt','1979-04-01', '334', 0),
                    ('A Life for Kregen','Alan Burt Akers','1979-04-01', '335', 0),
                    ('Space Opera','Jack Vance','1979-04-01', '336', 0),
                    ('The 1979 Annual World\'s Best Science Fiction','Donald A. Wollheim & Arthur W. Saha','1979-05-01', '337', 0),
                    ('Spaceling','Doris Piserchia','1979-05-01', '338', 0),
                    ('City of the Chasch','	Jack Vance','1979-05-01', '339', 0),
                    ('Saga of Lost Earths','Emil Petaja','1979-05-01', '340', 0),
                    ('Fires of Azeroth','C.J. Cherryh','1979-06-01', '341', 0),
                    ('Servants of the Wankh','Jack Vance','1979-06-01', '342', 0),
                    ('Morlock Night','K.W. Jeter','1979-06-01', '343', 0),
                    ('Maeve','Jo Clayton','1979-06-01', '344', 0),
                    ('Heller\'s Leap','Ian Wallace','1979-07-01', '345', 0),
                    ('Year\'s Best Horror Stories: VII','Gerald W. Page','1979-07-01', '346', 0),
                    ('The Dirdir','Jack Vance','1979-07-01', '347', 0),
                    ('Web of Sand','E.C. Tubb','1979-07-01', '348', 0),
                    ('Electric Forest','Tanith Lee','1979-08-01', '349', 0),
                    ('Isaac Asimov Presents the Great SF Stories, 2','Isaac Asimov & Martin H. Greenberg','1979-08-01', '350', 0),
                    ('Pnume','Jack Vance','1979-08-01', '351', 0),
                    ('A Sword for Kregen','Alan Burt Akers','1979-08-01', '352', 0),
                    ('Quag Keep','Andre Norton','1979-09-01', '353', 0),
                    ('Hestia','C.J. Cherryh','1979-09-01', '354', 0),
                    ('The Time Dweller','Michael Moorcock','1979-09-01', '355', 0),
                    ('The Stolen Sun','Emil Petaja','1979-09-01', '356', 0),
                    ('The Day of the Klesh','M.A. Foster','1979-10-01', '357', 0),
                    ('The Paradox of the Sets','Brian M. Stableford','1979-10-01', '358', 0),
                    ('The Douglas Convolution','Edward Llewellyn','1979-10-01', '359', 0),
                    ('The Broken Cycle','A. Bertram Chandler','1979-10-01', '360', 0),
                    ('The Face','Jack Vance','1979-11-01', '361', 0),
                    ('Journey to the Under-Ground World','Lin Carter','1979-11-01', '362', 0),
                    ('Iduna\'s Universe','E.C. Tubb','1979-11-01', '363', 0),
                    ('Amazons!','Jessica Amanda Salmonson','1979-12-01', '364', 0),
                    ('Emphyrio','Jack Vance','1979-12-01', '365', 0),
                    ('A Fortune for Kregen','Alan Burt Akers','1979-12-01', '366', 0),
                    ('The Golden Gryphon Feather','Richard Purtill','1979-12-01', '367', 0),
                    ('One On Me','Tim Huntley','1980-01-01', '368', 0),
                    ('The Avengers of Carrig','John Brunner','1980-01-01', '369', 0),
                    ('The Year\'s Best Fantasy Stories: 5','Lin Carter','1980-01-01', '370', 0),
                    ('The Bright Companion','Edward Llewellyn','1980-01-01', '371', 0),
                    ('Kutath','C.J. Cherryh','1980-02-01', '372', 0),
                    ('The Keeper\'s Price','Marion Zimmer Bradley','1980-02-01', '373', 0),
                    ('The Five Gold Bands','Jack Vance','1980-02-01', '374', 0),
                    ('The Garments of Caean','Barrington J. Bayley','1980-02-01', '375', 0),
                    ('Fighting Slave of Gor','John Norman','1980-03-01', '376', 0),
                    ('Isaac Asimov Presents the Great SF Stories, 3','Isaac Asimov & Martin H. Greenberg','1980-03-01', '377', 0),
                    ('The Winged Man','A.E. van Vogt & E. Mayne Hull','1980-03-01', '378', 0),
                    ('Across the Misty Sea','Neal Barrett, Jr.','1980-03-01', '379', 0),
                    ('Sabella, or the Blood Stone','Tanith Lee','1980-04-01', '380', 0),
                    ('The Many Worlds of Magnus Rudolph','Jack Vance','1980-04-01', '381', 0),
                    ('A Victory for Kregen','Alan Burt Akers','1980-04-01', '382', 0),
                    ('The Terra Data','E.C. Tubb','1980-04-01', '383', 0),
                    ('The 1980 Annual World\'s Best Science Fiction','Donald A. Wollheim & Arthur W. Saha','1980-05-01', '384', 0),
                    ('Rogue Ship','A.E. van Vogt','1980-05-01', '385', 0),
                    ('The People Beyond the Wall','Stephen Tall','1980-05-01', '386', 0),
                    ('The Green Gods','N.C. Henneberg','1980-05-01', '387', 0),
                    ('Two to Conquer','Marion Zimmer Bradley','1980-06-01', '388', 0),
                    ('The Languages of Pao','Jack Vance','1980-06-01', '389', 0),
                    ('Crown of the Sword God','Manning Norvil','1980-06-01', '390', 0),
                    ('Zanthodon','Lin Carter','1980-06-01', '391', 0),
                    ('The Spinner','Doris Piserchia','1980-07-01', '392', 0),
                    ('Year\'s Best Horror Stories: VIII','Karl Edward Wagner','1980-07-01', '393', 0),
                    ('Star Hunters','Jo Clayton','1980-07-01', '394', 0),
                    ('Wizard in Bedlam','Christopher Stasheff','1980-07-01', '395', 0),
                    ('Serpent\'s Reach','C.J. Cherryh','1980-08-01', '396', 0),
                    ('Beasts of Antares','Alan Burt Akers','1980-08-01', '397', 0),
                    ('Lost Worlds','Lin Carter','1980-08-01', '398', 0),
                    ('Hail, Hibbler','Ron Goulart','1980-08-01', '399', 0),
                    ('Lore of the Witchworld','Andre Norton','1980-09-01', '400', 0),
                    ('Kill the Dead','Tanith Lee','1980-09-01', '401', 0),
                    ('Nopalgarth','Jack Vance','1980-09-01', '402', 0),
                    ('Star Loot','A. Bertram Chandler','1980-09-01', '403', 0),
                    ('Waves','M.A. Foster','1980-10-01', '404', 0),
                    ('Isaac Asimov Presents the Great SF Stories, 4','Isaac Asimov & Martin H. Greenberg','1980-10-01', '405', 0),
                    ('Optiman','Brian M. Stableford','1980-10-01', '406', 0),
                    ('The Golden Barge','Michael Moorcock','1980-10-01', '407', 0),
                    ('Day by Night','Tanith Lee','1980-11-01', '408', 0),
                    ('The Fluger','Doris Piserchia','1980-11-01', '409', 0),
                    ('The Year\'s Best Fantasy Stories: 6','Lin Carter','1980-11-01', '410', 0),
                    ('World of Promise','E.C. Tubb','1980-11-01', '411', 0),
                    ('The Lucifer Comet','Ian Wallace','1980-12-01', '412', 0),
                    ('Rebel of Antares','Alan Burt Akers','1980-12-01', '413', 0),
                    ('Cosmic Crusaders','Pierre Barbet','1980-12-01', '414', 0),
                    ('Stolen Goddes','Richard Purtill','1980-12-01', '415', 0),
                    ('The Book of Dreams','Jack Vance','1981-01-01', '416', 0),
                    ('Dust of Far Stars','Jack Vance','1981-01-01', '417', 0),
                    ('Trullian: Alastor 2262','Jack Vance','1981-01-01', '418', 0),
                    ('Marune: Alastor 933','Jack Vance','1981-01-01', '419', 0),
                    ('Downbelow Station','C.J. Cherryh','1981-02-01', '420', 0),
                    ('Terra SF: The Year\'s Best European SF','Richard D. Nolane','1981-02-01', '421', 0),
                    ('To Conquer Chaos','John Brunner','1981-02-01', '422', 0),
                    ('Hurok of the Stone Age','Lin Carter','1981-02-01', '423', 0),
                    ('Rogue of Gor','John Norman','1981-03-01', '424', 0),
                    ('Circle, Crescent, Star','Ansen Dibell','1981-03-01', '425', 0),
                    ('Isaac Asimov Presents the Great SF Stories, 5','Isaac Asimov & Martin H. Greenberg','1981-03-01', '426', 0),
                    ('Daystar and Shadow','James B. Johnson','1981-03-01', '427', 0),
                    ('The Castaways of Tanagar','Brian M. Stableford','1981-04-01', '428', 0),
                    ('Lycanthia','Tanith Lee','1981-04-01', '429', 0),
                    ('Pilgrimage','Drew Mendelson','1981-04-01', '430', 0),
                    ('Nectar of Heaven','	E.C. Tubb','1981-04-01', '431', 0),
                    ('The 1981 Annual World\'s Best Science Fiction','Donald A. Wollheim & Arthur W. Saha','1981-05-01', '432', 0),
                    ('Sunfall','C.J. Cherryh','1981-05-01', '433', 0),
                    ('Doomtime','Doris Piserchia','1981-05-01', '434', 0),
                    ('Second Game','Charles De Vet','1981-05-01', '435', 0),
                    ('Blood Country','Curt Selby','1981-06-01', '436', 0),
                    ('The Magick of Camelot','Arthur Landis','1981-06-01', '437', 0),
                    ('Flow My Tears, the Policeman Said','Philip K. Dick','1981-06-01', '438', 0),
                    ('The Robot in the Closet','Ron Goulart','1981-06-01', '439', 0),
                    ('Horn Crown','Andre Norton','1981-07-01', '440', 0),
                    ('Banners of the Sa\'Yen','B.R. Stateham','1981-07-01', '441', 0),
                    ('Hadon of Opar','Philip José Farmer','1981-07-01', '442', 0),
                    ('The Repairmen of Cyclops','John Brunner','1981-07-01', '443', 0),
                    ('Wave Without a Shore','C.J. Cherryh','1981-08-01', '444', 0),
                    ('Year\'s Best Horror Stories: IX','Karl Edward Wagner','1981-08-01', '445', 0),
                    ('Legions of Antares','Alan Burt Akers','1981-08-01', '446', 0),
                    ('King of Argent','John T. Phillifent','1981-08-01', '447', 0),
                    ('Delusion\'s Master','Tanith Lee','1981-09-01', '448', 0),
                    ('The Anarch Lords','A. Bertram Chandler','1981-09-01', '449', 0),
                    ('Now Wait for Last Year','Philip K. Dick','1981-09-01', '450', 0),
                    ('Darya of the Bronze Age','Lin Carter','1981-09-01', '451', 0),
                    ('Sharra\'s Exile','Marion Zimmer Bradley','1981-10-01', '452', 0),
                    ('Showboat World','Jack Vance','1981-10-01', '453', 0),
                    ('The Year\'s Best Fantasy Stories: 7','Arthur W. Saha','1981-10-01', '454', 0),
                    ('The Terridae','E.C. Tubb','1981-10-01', '455', 0),
                    ('Guardsman of Gor','John Norman','1981-11-01', '456', 0),
                    ('The Nowhere Hunt','Jo Clayton','1981-11-01', '457', 0),
                    ('The Earth in Twilight','Doris Piserchia','1981-11-01', '458', 0),
                    ('Imaro','Charles R. Saunders','1981-11-01', '459', 0),
                    ('The Morphodite','M.A. Foster','1981-12-01', '460', 0),
                    ('Isaac Asimov Presents the Great SF Stories, 6','Isaac Asimov & Martin H. Greenberg','1981-12-01', '461', 0),
                    ('Allies of Antares','Alan Burt Akers','1981-12-01', '462', 0),
                    ('The Birthgrave','Tanith Lee','1981-12-01', '463', 0),
                    ('The Pride of Chanur','C.J. Cherryh','1982-01-01', '464', 0),
                    ('The Silkie','A.E. van Vogt','1982-01-01', '465', 0),
                    ('The Legion of Beasts','Neal Barrett, Jr.','1982-01-01', '466', 0),
                    ('Upside Downside','Ron Goulart','1982-01-01', '467', 0),
                    ('The Rape of the Sun','Ian Wallace','1982-02-01', '468', 0),
                    ('Hecate\'s Cauldron','Susan Shwartz','1982-02-01', '469', 0),
                    ('The Warrior Within','Sharon Green','1982-02-01', '470', 0),
                    ('The Werewolf Principle','Clifford D. Simak','1982-02-01', '471', 0),
                    ('Savages of Gor','John Norman','1982-03-01', '472', 0),
                    ('The Gray Prince','Jack Vance','1982-03-01', '473', 0),
                    ('The Pillars of Eternity','Barrington J. Bayley','1982-03-01', '474', 0),
                    ('Flight to Opar','Philip José Farmer','1982-03-01', '475', 0),
                    ('The Silver Metal Lover','Tanith Lee','1982-04-01', '476', 0),
                    ('Sword of Chaos','Marion Zimmer Bradley','1982-04-01', '477', 0),
                    ('The Darkness of Diamondia','A.E. van Vogt','1982-04-01', '478', 0),
                    ('The Coming Event','E.C. Tubb','1982-04-01', '479', 0),
                    ('The 1982 Annual World\'s Best Science Fiction','Donald A. Wollheim & Arthur W. Saha','1982-05-01', '480', 0),
                    ('Moongather','Jo Clayton','1982-05-01', '481', 0),
                    ('The Goblin Reservation','Clifford D. Simak','1982-05-01', '482', 0),
                    ('Eric of Zanthodon','Lin Carter','1982-05-01', '483', 0),
                    ('The Crystals of Mida','Sharon Green','1982-06-01', '484', 0),
                    ('Amazons II','Jessica Amanda Salmonson','1982-06-01', '485', 0),
                    ('The Dimensioners','Doris Piserchia','1982-06-01', '486', 0),
                    ('Mazes of Scorpio','Alan Burt Akers','1982-06-01', '487', 0),
                    ('Merchanter\'s Luck','C.J. Cherryh','1982-07-01', '488', 0),
                    ('Isaac Asimov Presents the Great SF Stories, 7','Isaac Asimov & Martin H. Greenberg','1982-07-01', '489', 0),
                    ('The Narrow Land','	Jack Vance','1982-07-01', '490', 0),
                    ('Big Bang','Ron Goulart','1982-07-01', '491', 0),
                    ('Journey to the Center','Brian M. Stableford','1982-08-01', '492', 0),
                    ('Year\'s Best Horror Stories: X','Karl Edward Wagner','1982-08-01', '493', 0),
                    ('The Battle of Forever','A.E. van Vogt','1982-08-01', '494', 0),
                    ('Summerfair','Ansen Dibell','1982-08-01', '495', 0),
                    ('Hawkmistress!','Marion Zimmer Bradley','1982-09-01', '496', 0),
                    ('I, Zombie','Curt Selby','1982-09-01', '497', 0),
                    ('Manshape','John Brunner','1982-09-01', '498', 0),
                    ('Cyrion','Tanith Lee','1982-09-01', '499', 0),
                    ('Port Eternity','C.J. Cherryh','1982-10-01', '500', 0),
                    ('The Year\'s Best Fantasy Stories: 8','Arthur W. Saha','1982-10-01', '501', 0),
                    ('Destiny Doll','Clifford D. Simak','1982-10-01', '502', 0),
                    ('The Steel Tsar','Michael Moorcock','1982-10-01', '503', 0),
                    ('Blood Brothers of Gor','John Norman','1982-11-01', '504', 0),
                    ('Home-To Avalon','Arthur Landis','1982-11-01', '505', 0),
                    ('Kesrick','Lin Carter','1982-11-01', '506', 0),
                    ('Isaac Asimov Presents the Great SF Stories, 8','Isaac Asimov & Martin H. Greenberg','1982-11-01', '507', 0),
                    ('Nifft the Lean','Michael Shea','1982-12-01', '508', 0),
                    ('Delia of Vallia','Alan Burt Akers','1982-12-01', '509', 0),
                    ('Earth is Heaven','E.C. Tubb','1982-12-01', '510', 0),
                    ('To Live Forever','Jack Vance','1982-12-01', '511', 0),
                    ('The Warrior Enchained','Sharon Green','1983-01-01', '512', 0),
                    ('Red as Blood','Tanith Lee','1983-01-01', '513', 0),
                    ('Out of Their Minds','Clifford D. Simak','1983-01-01', '514', 0),
                    ('The Deadly Sky','Doris Piserchia','1983-01-01', '515', 0),
                    ('Moonscatter','Jo Clayton','1983-02-01', '516', 0),
                    ('Prelude to Chaos','Edward Llewellyn','1983-02-01', '517', 0),
                    ('The Gates of Eden','Brian M. Stableford','1983-02-01', '518', 0),
                    ('Isaac Asimov Presents the Great SF Stories, 9','Isaac Asimov & Martin H. Greenberg','1983-02-01', '519', 0),
                    ('Kajira of Gor','John Norman','1983-03-01', '520', 0),
                    ('The Dreamstone','C.J. Cherryh','1983-03-01', '521', 0),
                    ('Mutants','Gordon R. Dickson','1983-03-01', '522', 0),
                    ('The Three Stigmata of Palmer Eldritch','Philip K. Dick','1983-03-01', '523', 0),
                    ('Transformer','M.A. Foster','1983-04-01', '524', 0),
                    ('Greyhaven','Marion Zimmer Bradley','1983-04-01', '525', 0),
                    ('Fires of Scorpio','Alan Burt Akers','1983-04-01', '526', 0),
                    ('The Blue World','Jack Vance','1983-04-01', '527', 0),
                    ('The 1983 Annual World\'s Best Science Fiction','Donald A. Wollheim & Arthur W. Saha','1983-05-01', '528', 0),
                    ('Ghost Hunt','Jo Clayton','1983-05-01', '529', 0),
                    ('Sung in Shadow','Tanith Lee','1983-05-01', '530', 0),
                    ('Cemetary World','Clifford D. Simak','1983-05-01', '531', 0),
                    ('An Oath to Mida','Sharon Green','1983-06-01', '532', 0),
                    ('A Maze of Death','Philip K. Dick','1983-06-01', '533', 0),
                    ('Melome','E.C. Tubb','1983-06-01', '534', 0),
                    ('Warlords of Xuma','David J. Lake','1983-06-01', '535', 0),
                    ('The Blackcollar','Timothy Zahn','1983-07-01', '536', 0),
                    ('Terra SF II','Richard D. Nolane','1983-07-01', '537', 0),
                    ('Matilda\'s Stepchildren','A. Bertram Chandler','1983-07-01', '538', 0),
                    ('The Galactiad','Gregory Kern','1983-07-01', '539', 0),
                    ('The Tree of Swords and Jewels','C.J. Cherryh','1983-08-01', '540', 0),
                    ('The Zen Gun','Barrington J. Bayley','1983-08-01', '541', 0),
                    ('The Diamond Contessa','Kenneth Bulmer','1983-08-01', '542', 0),
                    ('Isaac Asimov Presents the Great SF Stories, 10','Isaac Asimov & Martin H. Greenberg','1983-08-01', '543', 0),
                    ('Thendara House','Marion Zimmer Bradley','1983-09-01', '544', 0),
                    ('The Search for the Sun!','Colin Kapp','1983-09-01', '545', 0),
                    ('Ubik','Philip K. Dick','1983-09-01', '546', 0),
                    ('Emperor of Eridanus','Pierre Barbet','1983-09-01', '547', 0),
                    ('Anackire','Tanith Lee','1983-10-01', '548', 0),
                    ('The Mirror for Helen','Richard Purtill','1983-10-01', '549', 0),
                    ('The Year\'s Best Fantasy Stories: 9','Arthur W. Saha','1983-10-01', '550', 0),
                    ('An XT Called Stanley','Robert Trebor','1983-10-01', '551', 0),
                    ('Cheon of Weltenland','Charlotte Stone','1983-11-01', '552', 0),
                    ('Year\'s Best Horror Stories: XI','Karl Edward Wagner','1983-11-01', '553', 0),
                    ('Computerworld','A.E. van Vogt','1983-11-01', '454', 0),
                    ('Our Children\'s Children','Clifford D. Simak','1983-11-01', '555', 0),
                    ('Channel\'s Destiny','Jean Lorrah & Jacqueline Lichtenberg','1983-12-01', '556', 0),
                    ('The Lost Worlds of Cronus','Colin Kapp','1983-12-01', '557', 0),
                    ('Talons of Scorpio','Alan Burt Akers','1983-12-01', '558', 0),
                    ('Deus Irae','Philip K. Dick & Roger Zelazny','1983-12-01', '559', 0),
                    ('The Warrior Rearmed','Sharon Green','1984-01-01', '560', 0),
                    ('Manna','Lee Correy','1984-01-01', '561', 0),
                    ('Salvage and Destroy','Edward Llewellyn','1984-01-01', '562', 0),
                    ('The Book of Shai','Daniel Walther','1984-01-01', '563', 0),
                    ('Shapechangers','Jennifer Roberson','1984-02-01', '564', 0),
                    ('Angado','E.C. Tubb','1984-02-01', '565', 0),
                    ('The Quest for Kush','Charles R. Saunders','1984-02-01', '566', 0),
                    ('Beast','A.E. van Vogt','1984-02-01', '567', 0),
                    ('Players of Gor','John Norman','1984-03-01', '568', 0),
                    ('Tamastara','Tanith Lee','1984-03-01', '569', 0),
                    ('The Jagged Orbit','John Brunner','1984-03-01', '570', 0),
                    ('Isaac Asimov Presents the Great SF Stories, 11','Isaac Asimov & Martin H. Greenberg','1984-03-01', '571', 0),
                    ('Tyrant of Hades','Colin Kapp','1984-03-01', '572', 0),
                    ('Voyager in the Night','C.J. Cherryh','1984-04-01', '573', 0),
                    ('The Crystal Crown','B.W. Clough','1984-04-01', '574', 0),
                    ('Scanner Darkly','Philip K. Dick','1984-04-01', '575', 0),
                    ('Masks of Scorpio','Alan Burt Akers','1984-04-01', '576', 0),
                    ('Chosen of Mida','Sharon Green','1984-05-01', '577', 0),
                    ('Sword and Sorceress','Marion Zimmer Bradley','1984-05-01', '578', 0),
                    ('Star-Anchored, Star-Angered','Suzette Haden Elgin','1984-05-01', '579', 0),
                    ('The Book of Ptath','A.E. van Vogt','1984-05-01', '580', 0);";
                    
                    //execute the books table insert
                    $r = mysqli_query($dbc, $q);

                    // Send the email:
                    $body = "Thank you for installing Yellowspine. Don\'t forget to erase the install.php file from the server for security reasons. But first, to activate your account, please click on this link:\n\n";
                    $body .= BASE_URL . 'activate.php?x=' . urlencode($e) . "&y=$a";
                    mail($e, 'Installation Confirmation', $body, 'From:' . DEVEMAIL);
                    
                    // Finish the page:
                    echo '
                            <section class="fullPanel">
                                <div class="container-fluid">
                                    <div class="col-sm-2"></div>
                                    <div class="col-sm-8">
                                        <h3>Thank you for installing!</h3> <p>A confirmation email has been sent to your address. Please click on the link in that email in order to activate your account. This may take a few minutes.</p><p>After you have activated the account successfully, the install.php file should be removed from the server for security reasons.</p>
                                    </div>
                                    <div class="col-sm-2"></div>
                                </div>
                            </section>';
                    include ('includes/template_bottom.inc.php'); // Include the HTML footer.
                    exit(); // Stop the page.
                    
                } else { // If it did not run OK.
                    $errors[] = '<p class="error">The books table could not be created due to a system error. Delete the users table before trying again.</p>';
                }
				
			} else { // If it did not run OK.
				$errors[] = '<p class="error">You could not be registered due to a system error. We apologize for any inconvenience.</p>';
			}
			
		} else { // The email address is not available.
			$errors[] = '<p class="error">The users table could not be created.</p>';
		}
		
	} else { // If one of the data tests failed.
		$errors[] = '<p class="error">Please try again.</p>';
	}
    //close the statement
    mysqli_stmt_close($stmt);
    //close database connection
	mysqli_close($dbc);

    } // End of the main Submit conditional.

    echo '
    <section id="addUserForm" class="fullPanel">
        <div class="container-fluid">
            <div class="row">
            
                <div class="col-sm-2"></div>
                
                <div class="col-sm-8">
                    
                    <form class="form-horizontal" role="form" action="install.php" method="post">
                        
                        <div class="container-fluid">
                                <div class="row">
                                    <div class="col-sm-1"></div>
                                    <div class="col-sm-10">
                                        <h2 id="pageTitle">' . $page_title . '</h2>
                                    </div>
                                    <div class="col-sm-1"></div>
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
                                        <label class="control-label col-sm-3" for="first_name">First Name:</label>
                                        <div class="col-sm-6">
                                            <input type="text" class="form-control" id="first_name" name="first_name" placeholder="First Name">
                                        </div>
                                        <div class="col-sm-3"></div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group">
                                        <label class="control-label col-sm-3" for="last_name">Last Name:</label>
                                        <div class="col-sm-6"> 
                                            <input type="text" class="form-control" id="last_name" name="last_name" placeholder="Last Name">
                                        </div>
                                        <div class="col-sm-3"></div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group">
                                        <label class="control-label col-sm-3" for="email">Email:</label>
                                        <div class="col-sm-6"> 
                                            <input type="email" class="form-control" id="email" name="email" placeholder="Email Address">
                                        </div>
                                        <div class="col-sm-3"></div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group">
                                        <label class="control-label col-sm-3" for="password1">Password:</label>
                                        <div class="col-sm-6"> 
                                            <input type="text" class="form-control" id="password1" name="password1" placeholder="Password">
                                        </div>
                                        <div class="col-sm-3"></div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group">
                                        <label class="control-label col-sm-3" for="password2">Confirm Password:</label>
                                        <div class="col-sm-6"> 
                                            <input type="text" class="form-control" id="password2" name="password2" placeholder="Confirm Password">
                                        </div>
                                        <div class="col-sm-3"></div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group"> 
                                        <div class="col-sm-offset-3 col-sm-3">
                                            <input id="submit" type="submit" class="btn btn-block" value="Submit">
                                        </div>
                                        <div class="col-sm-3">
                                            <input id="reset" type="reset" class="btn btn-block" value="Reset">
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