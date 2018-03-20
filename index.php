<?php

// Start the session
session_start();

echo '
    <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
    <html xmlns="http://www.w3.org/1999/xhtml">
      <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Coursera Cybersecurity - Manuel Munoz</title>
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css"
              integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
        <!-- Optional JavaScript -->
        <!-- jQuery first, then Popper.js, then Bootstrap JS -->
        <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"
                integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"
                integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"
                integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>

      </head>
      <body>

        <div class="container-fluid">

          <div onclick="location.href=\'./index.php\';" class="row">
            <div class="col justify-content-center" style="margin-top: 25px; text-align: center">
              <h1 class=".display-2">Supersecure messenger</h2>
            </div>
          </div>

          <div onclick="location.href=\'./index.php\';" class="row">
            <div class="col justify-content-center" style="margin-top: 25px; margin-bottom: 10px; text-align: center">
              <img src="./img/envelope.png">
            </div>
          </div>
';

if (!empty($_SESSION['login_user'])) {
   echo '
       <div style="text-align: center; margin-top: 25px;" class="alert alert-success">
          <strong>Welcome,</strong> ' . $_SESSION["login_user"] . '.
       </div>

       <form action="./" method="post">
       <div class="row">
          <div class="col" style="margin-top: 25px; text-align: center">
             <input type="submit" class="btn btn-outline-info btn-lg" name="menu" value="View messages">
             <input type="submit" class="btn btn-outline-info btn-lg" name="menu" value="Send message">
             <input type="submit" class="btn btn-outline-info btn-lg" name="menu" value="Logout">
          </div>
       </div>
       </form>
   ';
}

else {
   echo '
          <form action="./" method="post">
          <div class="row" style="text-align: center; margin-top: 25px;">
             <div class="col-xs-4" style="width: 25%; margin: 0 auto;">
                 <label for="usr">Username&nbsp;</label><input id="usr" style="margin-bottom: 15px;" type="text" class="form-control input-sm" name="username">
                 <label for="pss">Password&nbsp;</label><input id="pss" style="margin-bottom: 15px;" type="password" class="form-control input-sm" name="password">
             </div>
          </div>

          <div class="row">
            <div class="col" style="margin-top: 25px; text-align: center">
                <input type="submit" class="btn btn-outline-info btn-lg" name="menu" value="Login">
                <input type="submit" class="btn btn-outline-info btn-lg" name="menu" value="Register">
            </div>
          </div>
          </form>
   ';
}

if ($_POST["menu"] == "View messages") {

  $DBUSER="root";
  $DBPASSWD="tomaal-el5%";
  $DATABASE="securemessenger";
  $KEY = "AgGVnx4XP1rtJ4NLb1aX";

  // Create connection
  $conn = new mysqli("localhost", $DBUSER, $DBPASSWD, $DATABASE);

  // Check connection
  if ($conn->connect_error) {
     die('
         <div style="text-align: center; margin-top: 25px;" class="alert alert-danger">
            <strong>Error connecting with DB,</strong> try again.
         </div>
     ');
  }

  $query = "SELECT AES_DECRYPT(users.username, '" . $KEY . "') AS username, AES_DECRYPT(message, '" . $KEY . "') AS mess FROM messages, users
            WHERE messages.id_sender = users.id AND messages.id_user =
               (SELECT id FROM users
                WHERE username = AES_ENCRYPT('" . $_SESSION["login_user"] . "', '" . $KEY . "'))";

  $result = mysqli_query($conn, $query);

  while ($row = mysqli_fetch_array($result)) {
     echo '
        <label style="margin-top: 10px;" for="mes"><b>From:&nbsp;</b>' . $row['username'] . '</label>
        <div id="mes" style="text-align: center;" class="alert alert-info">
           ' . $row['mess'] . '
        </div>
     ';
  }
}

if ($_POST["menu"] == "Send message") {

   echo '
      <form action="./" method="post">
      <div class="form-group" style="margin-top: 10px;">
        <b>Send to:</b>
        <input style="margin-top: 10px;" type="text" class="form-control" id="usr" name="destination">
      </div>
      <div class="form-group" style="margin-top: 25px;">
         <textarea class="form-control" rows="3" name="mensaje"></textarea>
      </div>
      <div class="row">
        <div class="col" style="margin-top: 5px; text-align: center">
            <input type="submit" class="btn btn-outline-info btn-lg" name="menu" value="Send">
        </div>
      </div>
      </form>
   ';
}

if ($_POST["menu"] == "Send") {

    $DBUSER="root";
    $DBPASSWD="tomaal-el5%";
    $DATABASE="securemessenger";
    $KEY = "AgGVnx4XP1rtJ4NLb1aX";

    // Create connection
    $conn = new mysqli("localhost", $DBUSER, $DBPASSWD, $DATABASE);

    // Check connection
    if ($conn->connect_error) {
       die('
           <div style="text-align: center; margin-top: 25px;" class="alert alert-danger">
              <strong>Error connecting with DB,</strong> try again.
           </div>
       ');
    }

    $query = "INSERT INTO messages (message, id_user, id_sender)
              VALUES (AES_ENCRYPT('" . $_POST["mensaje"] . "', '" . $KEY . "'),
                     (SELECT id FROM users WHERE username = AES_ENCRYPT('" . $_POST["destination"] . "', '" . $KEY . "')),
                     (SELECT id FROM users WHERE username = AES_ENCRYPT('" . $_SESSION["login_user"] . "', '" . $KEY . "')))";

    $result = mysqli_query($conn, $query);

}

if ($_POST["menu"] == "Register") {

   if (!empty($_POST["username"]) && !empty($_POST["password"])) {

      if (!ctype_alnum($_POST["username"])) {
         die('
             <div style="text-align: center; margin-top: 25px;" class="alert alert-danger">
                <strong>Only letters and numbers allowed in usernames,</strong> try again.
             </div>
         ');
      }

      $DBUSER="root";
      $DBPASSWD="tomaal-el5%";
      $DATABASE="securemessenger";
      $KEY = "AgGVnx4XP1rtJ4NLb1aX";

      // Create connection
      $conn = new mysqli("localhost", $DBUSER, $DBPASSWD, $DATABASE);

      // Check connection
      if ($conn->connect_error) {
         die('
              <div style="text-align: center; margin-top: 25px;" class="alert alert-danger">
                 <strong>Error connecting with DB,</strong> try again.
              </div>
         ');
      }

      $query = "INSERT INTO `users` (username, password)
                VALUES (AES_ENCRYPT('" . $_POST["username"] . "', '" . $KEY . "'), AES_ENCRYPT('" . $_POST["password"] . "', '" . $KEY . "'))";

      mysqli_query($conn, $query);

      if ($conn->errno) {
         echo '
              <div style="text-align: center; margin-top: 25px;" class="alert alert-danger">
                 <strong>Error registering,</strong> try again.
              </div>
         ';
      }
      else {
         echo '
              <div style="text-align: center; margin-top: 25px;" class="alert alert-success">
                 <strong>Success,</strong> now you can log in.
              </div>
         ';
      }
   }
   else {
      echo '
             <div style="text-align: center; margin-top: 25px;" class="alert alert-warning">
                <strong>Warning,</strong> missing username or password.
             </div>
      ';
   }
}

elseif ($_POST["menu"] == "Login") {

   if (!empty($_POST["username"]) && !empty($_POST["password"])) {

      if (!ctype_alnum($_POST["username"])) {
         die('
            <div style="text-align: center; margin-top: 25px;" class="alert alert-danger">
               <strong>Only letters and numbers allowed in usernames,</strong> try again.
            </div>
         ');
      }

      $DBUSER="root";
      $DBPASSWD="tomaal-el5%";
      $DATABASE="securemessenger";
      $KEY = "AgGVnx4XP1rtJ4NLb1aX";

      // Create connection
      $conn = new mysqli("localhost", $DBUSER, $DBPASSWD, $DATABASE);

      // Check connection
      if ($conn->connect_error) {
         die('
             <div style="text-align: center; margin-top: 25px;" class="alert alert-danger">
                <strong>Error connecting with DB,</strong> try again.
             </div>
         ');
      }

      $query = "SELECT * FROM users
                WHERE username = AES_ENCRYPT('" . $_POST['username'] . "', '" . $KEY . "')
                AND password = AES_ENCRYPT('" . $_POST['password'] . "', '" . $KEY . "')";

      $result = mysqli_query($conn, $query);
      $count = mysqli_num_rows($result);

      // If result matched $myusername and $mypassword, table row must be 1 row
      if ($count == 1) {
         $_SESSION['login_user'] = $_POST["username"];
         echo '
              <div style="text-align: center; margin-top: 25px;" class="alert alert-success">
                 <strong>Success,</strong> you are in.
              </div>
         ';
         header("Refresh:1");
      }
      else {
         echo '
              <div style="text-align: center; margin-top: 25px;" class="alert alert-danger">
                 <strong>Password not correct,</strong> try again.
              </div>
         ';
      }
   }
   else {
      echo '
             <div style="text-align: center; margin-top: 25px;" class="alert alert-warning">
                <strong>Warning,</strong> missing username or password.
             </div>
      ';
   }
}

elseif ($_POST["menu"] == "Logout") {
   session_destroy();

   echo '
       <div style="text-align: center; margin-top: 25px;" class="alert alert-warning">
          <strong>See you again,</strong> ' . $_SESSION["login_user"] . '.
       </div>
   ';

   header("Refresh:1");
}

echo '
        </div>
      </body>
    </html>
';

exit(0);

?>
