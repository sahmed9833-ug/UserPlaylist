<?php
/**
 * Author: Saeed Ahmed - w16024782
 * Version: 29/04/2017
 * Description: The Add Song script, contains functionality that gets the selected songs, fetches the details and adds a new row in the `saved_songs` table
 */

//Establish session, include dbconnection details and set the timezone.
session_start();
include '../other/dbconnect.php';
date_default_timezone_set('Europe/London');

//load xml file
$rss = simplexml_load_file('../files/playlist.xml');

//if the user got to this script by clicking on the addsongs button
if(isset($_POST['addsongs'])){
  //store member's id in a $_SESSION variable
  $memberid = $_SESSION['memberID'];
  
  //store datetime in a local variable
  $date = date_create('200-01-01');
  $dateSaved = date("Y-m-d h:i:s");
  
  //if songs were selected
  if(!empty($_POST['songids'])) {
    //loop through each selected song
    foreach($_POST['songids'] as $songid) {
      
      //Check song is not already in the user's playlist
      try{
        //Establish PDO connection
        $conn = new PDO('mysql:host='.$hostname.';dbname='.$databaseName.';charset=utf8mb4', $username,$password, array(PDO::ATTR_EMULATE_PREPARES => true,PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
        //prepare and execute query
        $sql = "SELECT * FROM `saved_songs` WHERE songID = '" . $songid . "' AND memberID = '" . $memberid . "'";
        $result = $conn->query($sql);
        
        //if the song is not already in the user's playlist
        if ($result !== false) {
          //loop through each item in the XML file
          foreach ($rss->channel->item as $songdetails){
            //if the song id matches with that of an item, get the rest of that item's details and store in local variables 
            if ($songid == $songdetails->songid){
              $songID = $songdetails->songid;
              $songtitle = $songdetails->songtitle;
              $songlink = $songdetails->link;
              $songGenre = $songdetails->genre;
              $songRelease = $songdetails->releaseyear;
            }
          }
          //prepare query to INSERT a new row in the `saved_songs` table
          $query=$conn->prepare("INSERT INTO saved_songs (songID, memberID, songtitle, link, genre, releaseYear, dateSaved) VALUES (:songID, :memberID, :songtitle, :link, :genre, :releaseYear, :dateSaved)");
          $query->bindParam(':songID', $songID);
          $query->bindParam(':memberID', $memberid);
          $query->bindParam(':songtitle', $songtitle);
          $query->bindParam(':link', $songlink);
          $query->bindParam(':genre', $songGenre);
          $query->bindParam(':releaseYear', $songRelease);
          $query->bindParam(':dateSaved', $dateSaved);
          //execute query
          $query->execute();
          
        }
        else{
          //if the song does already exist, do nothing
        }
       //close connection
       $conn = null;
      }
    catch(PDOException $e){
      echo "Error: " . $e->getMessage();
    }
      
  }
   //once all of the selected songs have been looped through, take user back to the myplaylist.php page
   header("Location: ../myplaylist.php");
   exit();
  }
  //if no songs were selected, display an error message
  else{
    echo "Error: No songs selected.";
  }
}
//if user did not click on the add songs button, display an error message
else{
  echo 'Error, please go back to <a href="../index.php">Home</a> and try again.';
}
