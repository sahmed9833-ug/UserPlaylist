<!doctype html>
<?php
/**
 * Author: Saeed Ahmed - w16024782
 * Version: 29/04/2017
 * Description: The My Playlist page, displays the user's playlist and allows them to select and remove song(s).
 */

//Establish session, include dbconnection details, and usercheck file.
session_start();
include 'other/dbconnect.php';
include 'other/usercheck.php';

//Call function from usercheck.php, which checks that user is logged in, if not, they are redirected to home page
userLoggedIn();

?>
	<html lang="en">

	<head>
		<meta charset="UTF-8">
		<title>My Playlist - The Music Room</title>
		<link rel="stylesheet" href="styles/main.css" type="text/css">
		<link rel="stylesheet" href="other/font-awesome/css/font-awesome.min.css">
		<link href='https://fonts.googleapis.com/css?family=Raleway:400,600' rel='stylesheet' type='text/css'>
		<link rel="shortcut icon" href="images/logo.png" type="image/x-icon" />
		<script language="JavaScript">
			function toggle(source) {
			checkboxes = document.getElementsByName('songids[]');
			for(var i=0, n=checkboxes.length;i<n;i++) {
				checkboxes[i].checked = source.checked;
			}
		}
		</script>
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
			<section class="loginform" style="height: 900px;">
				<!--Heading displaying the member's name via PHP-->
				<h2><?php echo preg_replace('/\W\w+\s*(\W*)$/', '$1', $_SESSION['memberName']);?>'s Playlist</h2>
				<!--Form handling the remove song(s) function-->
				<form method="post" action="scripts/removesongscript.php">
					<div style="overflow-y: scroll; height:500px; width: 1000px; margin-left: auto; margin-right: auto;">
							<?php
								//attempt to retrieve user's saved songs from the database
								try{
									//Establish PDO connection
									$conn = new PDO('mysql:host='.$hostname.';dbname='.$databaseName.';charset=utf8mb4', $username,$password, array(PDO::ATTR_EMULATE_PREPARES => true,PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
									$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
									
									//prepare query
									$sql = "SELECT * FROM `saved_songs` WHERE memberID = '" . $_SESSION['memberID'] . "'";
									$result = $conn->query($sql);
									
									//initially, the status variable will equal 0
									$status = 0;
									
									//store table in string variable
									$table = '<table class="songs" style="width: 975px;">
														<tr>
															<th style="width: 30px;">Select</th>
															<th>Title</th>
															<th>Genre</th>
															<th>Release Year</th>
															<th>Date Added</th>
														</tr>';
									
									//if query executes successfully
									if ($result !== false) {
										//loop through each row, displaying them as a row in the table with each iteration
										while($row = $result->fetch()) {
											//if query returns results, status will equal 1
											$status = 1;
											//each row will be formatted and added onto the table string as a new row
											$table.= "<tr>";
											$table.= '<td style="width: 30px;"><input type="checkbox" name="songids[]" value="' . $row['songID'] . '"></td>';
											$table.= '<td><a class="hyperlink" href="' . $row['link'] . '"><strong>' . $row['songtitle'] . '</strong></a></td>';
											$table.= "<td>{$row['genre']}</td>";
											$table.= "<td>{$row['releaseYear']}</td>";
											$table.= "<td>" . date_format(date_create($row['dateSaved']),'d/m/Y') ."</td>";
											$table.= "</tr>";
											
										}
										//if the status still equals 0, display a message
										if ($status == 0){
											echo '<h3>Nothing here! To add music to your playlist, please go to <a class="hyperlink" href="index.php">Home</a></h3>';
										}
										//if not, display the table
										else{
											echo $table;
										}
									}
									//if query does not execute successfully, display error message
									else{
										echo "Error, please try again.";
									}
								//close connection
								$conn = null;
								}
								//if it fails, catch the exception and display the error
								catch(PDOException $e){
									echo "Error: " . $e->getMessage();
								}
								
					?>
						</table>
					</div>
					<br>
					<input type="submit" class="submitbutton" style="width: 400px; height: 100px; float: right; margin-right: 150px;" name="removesongs" value="Removed selected songs from playlist">

					<input type="button" class="submitbutton" style="float: left; width: 250px; margin-left: 150px;" onClick="toggle(this)" name="selectall" value="Unselect all">
					<br>
					<br>
					<br><br><br><br>
				</form>
				<!--form for exporting the playlist as an XML file-->
				<form method="post" action="scripts/export.php">
					<input type="submit" class="submitbutton" action="scripts/export.php" style="width: 400px; height: 50px; float: right; margin-right: 150px;" name="export" value="Export playlist as XML">
				</form>
				<p style="margin-top: 70px; margin-left: 740px; width: 250px;">
					Note: to save the XML file to your computer, right-click on the page and select "Save as...".
				</p>
			</section>
		</div>
		<footer>
			<h3 class="footer">
				The Music Room &trade;
			</h3>
		</footer>
	</body>

	</html>