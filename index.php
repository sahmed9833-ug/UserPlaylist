<!doctype html>
<?php
/**
 * Author: Saeed Ahmed - w16024782
 * Version: 29/04/2017
 * Description: The Home page, displays songs in the XML file and allows user to add them to their playlist.
 */

//Establish session
session_start();

//load XML file
$rss = simplexml_load_file('files/playlist.xml');

?>
	<html lang="en">

	<head>
		<meta charset="UTF-8">
		<title>Home - The Music Room</title>
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
			<section class="loginform">
				<h2>Welcome to The Music Room</h2>

				<!--Form which handles search-->
				<form class="w" method="post" action="index.php">
					<input type="text" name="term" placeholder="Search" style="margin-left: 350px;">
					<select name="field" style="height: 36px; margin-left: -450px; margin-right: 500px; float:right;">
						<option value="songtitle">Title</option>
						<option value="artist">Artist</option>
						<option value="genre">Genre</option>
					</select>
					<input class="submitbutton" type="submit" name="search" value="Go" style="width: 50px; height: 36px; float: right; margin-right: 325px;">
				</form>

				<!--Form which handles adding song(s) to user's playlist-->
				<form method="post" action="scripts/addsongscript.php">
					<div style="overflow-y: scroll; height:500px; width: 1000px; margin-left: auto; margin-right: auto;">
						<!--Table to display the contents of the XML file-->
						<table class="songs" style="width: 975px;">
							<tr>
								<th style="width: 30px;">Select</th>
								<th>Title</th>
								<th>Artist</th>
								<th>Genre</th>
								<th>Year</th>
							</tr>
							<?php
								//If user attempted a search, use xpath to display results
								if (isset($_POST['search'])){
									
									//xpath query, contains() and translate() are used to allow for partial, case-insensitive searches
									$searchQuery = "/rss/channel/item[contains(translate(" . $_POST['field'] . ", 'ABCDEFGHIJKLMNOPQRSTUVWXYZ', 'abcdefghijklmnopqrstuvwxyz'),'" . $_POST['term'] . "')]";
									$rss = $rss->xpath($searchQuery);
									
									//loop through results
									foreach ($rss as $song){
										echo "<tr>";
										echo '<td style="width: 30px;"><input type="checkbox" name="songids[]" value="' . $song->songid . '"></td>';
										//links for the songs are stored in a hyperlink, which displays as the song's title
										echo '<td><a class="hyperlink" href="' . $song->link . '"><strong>' . $song->songtitle . '</strong></a></td>';
										echo '<td>' . $song->artist . '</td>';
										echo '<td>' . $song->genre . '</td>';
										echo '<td>' . $song->releaseyear .'</td>';
										echo '</tr>';
									}
								}
								//else just display all contents of the XML file
								else{
									//loop through items in the XML file and output as rows in the table
									foreach ($rss->channel->item as $song){
										echo "<tr>";
										echo '<td style="width: 30px;"><input type="checkbox" name="songids[]" value="' . $song->songid . '"></td>';
										echo '<td><a class="hyperlink" href="' . $song->link . '"><strong>' . $song->songtitle . '</strong></a></td>';
										echo "<td>{$song->artist}</td>";
										echo "<td>{$song->genre}</td>";
										echo "<td>{$song->releaseyear}</td>";
										echo "</tr>";
									}	
								}
							?>
						</table>
					</div>
					<br>
					<?php
						//if user is logged in, display the song panel controls and the Add Song(s) button
						if (isset($_SESSION['loggedin'])){
							echo '<input type="submit" class="submitbutton" style="width: 400px; height: 100px; float: right; margin-right: 150px;" name="addsongs" value="Add selected songs to playlist">
										<input type="button" class="submitbutton" style="float: left; width: 250px; margin-left: 150px;" name="selectall" onClick="toggle(this)" value="Unselect all">
										<br>
										<br>
										<br>';
						}
						//Else, display a link directing the user to the log in page
						else{
							echo '<a class="hyperlink" href="login.php">To add songs to your playlist, please log in</a>';
						}
					?>
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