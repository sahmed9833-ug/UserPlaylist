<?php
/**
 * Author: Saeed Ahmed - w16024782
 * Version: 29/04/2017
 * Description: The Remove Song script, contains functionality that gets the selected songs, and removes them from the user's playlist
 */

//Establish session, include dbconnection details.
session_start();
include '../other/dbconnect.php';

//load xml file
$rss = simplexml_load_file('../files/playlist.xml');

//if the user got to this script by clicking on the removesongs button
if(isset($_POST['removesongs'])){
  //store member's id in a local variable
  $memberid = $_SESSION['memberID'];
  
  //if songs were selected
  if(!empty($_POST['songids'])) {
    //loop through each of the songs
    foreach($_POST['songids'] as $songid) {
      try{
        //Establish PDO connection
        $conn = new PDO('mysql:host='.$hostname.';dbname='.$databaseName.';charset=utf8mb4', $username,$password, array(PDO::ATTR_EMULATE_PREPARES => true,PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
        //prepare and execute query
        $sql = "DELETE FROM saved_songs WHERE songID = '$songid' AND memberID = '$memberid'";
        $conn->exec($sql);
        
        //close connection
        $conn = null;
      }
      catch (PDOException $e){
        //catch exceptions
        echo "Error: " . $e->getMessage();
      }
    }
    //Once all selected songs have been looped through, take user back to the myplaylist.php page
    header("Location: ../myplaylist.php");
    exit();
  }
  //if no songs were selected, echo an error message
  else{
    echo "Error: No songs selected.";
  }
}
//if user did not click on the remove songs button, display an error message
else{
  echo 'Error, please go back to <a href="../myplaylist.php">My Playlist</a> and try again.';
}
//close connection
$conn = null;