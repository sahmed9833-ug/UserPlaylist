<!doctype html>
<?php
/**
 * Author: Saeed Ahmed - w16024782
 * Version: 29/04/2017
 * Description: The Log in page, allows user to log in with their account details
 */

//Establish session
session_start();
?>
	<html lang="en">

	<head>
		<meta charset="UTF-8">
		<title>Log in - The Music Room</title>
		<link rel="stylesheet" href="styles/main.css" type="text/css">
		<link rel="stylesheet" href="other/font-awesome/css/font-awesome.min.css">
		<link href='https://fonts.googleapis.com/css?family=Raleway:400,600' rel='stylesheet' type='text/css'>
		<link rel="shortcut icon" href="images/logo.png" type="image/x-icon" />
	</head>

	<body>
		<header>
			<nav>
				<ul>
					<li style="width: 33%;"><a href="index.php"><i style="margin-right: 30px;" class="fa fa-home" aria-hidden="true"></i>Home</a></li>
					<li style="width: 33%;"><a href="myplaylist.php"><i style="margin-right: 30px;" class="fa fa-list" aria-hidden="true"></i>My Playlist</a></li>
					<?php if(isset($_SESSION['loggedin'])){ echo '<li style="width: 34%;"><a href="scripts/logout.php"><i style="margin-right: 30px;" class="fa fa-sign-out" aria-hidden="true"></i>Log out</a></li>';}else{ echo '<li style="width: 34%;"><a href="login.php"><i style="margin-right: 30px;" class="fa fa-sign-in" aria-hidden="true"></i>Log in</a></li>';}?>
				</ul>
			</nav>
		</header>
		<div id="container">
			<section class="loginform">
				<h2>Log in</h2>
				<!--Form to handle log in-->
				<form class="login" method="post" action="scripts/loginscript.php">
					<input type="text" class="w" name="loginemail" placeholder="Email address">
					<br>
					<input type="password" class="w" name="loginpassword" placeholder="Password">
					<br>
					<?php
						//If the loginErrors session variable is set, display it and then empty the variable
						if(isset($_SESSION['loginErrors'])){
								echo $_SESSION['loginErrors'];
								$_SESSION['loginErrors'] = "";
						}
          ?>
						<br><br>
						<input type="submit" class="submitbutton" name="loginsubmit" value="Log in"><br>
				</form>
			</section>
		</div>
		<footer>
			<h3 class="footer">
				The Music Room &trade;
			</h3>
		</footer>
	</body>

	</html>