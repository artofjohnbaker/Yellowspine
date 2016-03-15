Yellowspine v.1 README Document

The Yellowspine application allows the user to track the collection of the DAW Scifi "Yellowspine" novels published between 1972 and 1984 (580 in total).

Before you start:
-----------------

1.) Make sure you have hosted webspace

2.) Create a MYSQL(5.4 and up) database to use for the application (and possibly a sub-domain if you don't want to litter your primary domain)

3.) Be sure you know the username, password and hostname for the database you've created

Installation:
--------------

1.) Open config.inc.php in the includes folder. Fill in the domain name (and optionally folder path) for your BASE URL and the email address you'd like to use for your administrative email

2.) Open mysqli_connect.php in the includes folder. Fill in the username, password, hostname and database name for your database

3.) Upload the all the files to your server (usually the root of your domain, or subdomain)

4.) Access the install.php file and fill in the information you'll want for your administrative account

5.) Check the email you filled in on install.php for an activation link, then click it and you'll be directed to the log in page

6.) Log in

Use Cases:
----------

Case 1.) Collector of DAW Yellowspine novels checks boxes next to the books they already own. When that collector is online or in a used book store, they can check their collection using their mobile device/laptop to know whether they already own it.

Case 2.) A friend/partner of the collector can be given a "Guest" account and see the collection without being able to affect it. Thus gift giving becomes easier.

Case 3.) Each book has an Amazon link, which can be used to hunt and purchase books for ther collection.

Request for featues in Version 2
---------------------------------

- More accurate release dates (way more research)

- ISBN-10/ISBN-13/AISN numbers (more research required since many have all three) 

- Book covers (more disk space/image editing/research needed)

- Bookshelf mode (view collection like a virtual bookshelf)