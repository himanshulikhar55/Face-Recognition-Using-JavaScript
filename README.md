1) pdo.php: MySQL connection file. Please make changes to the password, username and database name accordingly. Table name used is user_data and dbname used is: user_profile. The name of the data base can be kept according the user's taste and the corresponding changes should be made in the "pdo.php" file.
Please make sure the "user_data" table in this database has the following columns:
  a) username
  b) email
  c) pass
  d) path
  e) user_id
2) index.php: Index page of the login web application.
3) login.php: Login page of the web application.
4) functions.js: Contains all the functions required in the pages. The problem is in runFacialRecognition() function used in login.php. I have hard coded the reference image name as of now. It will be changed once this works for atleast one image.
