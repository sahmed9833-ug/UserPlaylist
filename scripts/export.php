<?php
/**
 * Author: Saeed Ahmed - w16024782
 * Version: 29/04/2017
 * Description: The export script, contains functionality that takes the user's playlist, turns it into an XML file and then displays said file.
 */

//Establish session, include dbconnection details
session_start();
include '../other/dbconnect.php';

//load xml file
$rss = simplexml_load_file('../files/playlist.xml');

//if the user got to this page by clicking on the "Export" button
if (isset($_POST['export'])){
  //create a string variable containing the XML
  $xmlString = '<?xml version="1.0" encoding="ISO-8859-1" ?>
              <rss version="0.91">
              <channel>
              <title>' . $_SESSION['memberName'] . '\'s music playlist</title>
              <description>' . $_SESSION['memberName'] . '\'s music playlist - created and exported via The Music Room TM</description>
              <language>en-us</language>';

  //store member's ID in a local variable
  $memberid = $_SESSION['memberID'];
  
  try{
    //Establish PDO connection
    $conn = new PDO('mysql:host='.$hostname.';dbname='.$databaseName.';charset=utf8mb4', $username,$password, array(PDO::ATTR_EMULATE_PREPARES => true,PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    //Prepare and execute SELECT query to find user's saved songs
    $sql = "SELECT * FROM `saved_songs` WHERE memberID = '" . $memberid . "'";
    $result = $conn->query($sql);

    //if query executes successfully
    if ($result !== false) {
      //loop through each row
      while($row = $result->fetch()) {
        //loop through contents of the original XML file
        foreach ($rss->channel->item as $songdetails){
          //if the song id matches with that of an item, get that item's artist value and store in a local variable
          if ($row['songID'] == $songdetails->songid){
            $songArtist = $songdetails->artist;
          }
        }
        //concatenate string containing the item's details formatted in XML, onto the $xmlString string
        $xmlString .= '<item>
                       <songid>'. $row['songID'] . '</songid>
                       <songtitle>' . $row['songtitle'] . '</songtitle>
                       <artist>' . $songArtist . '</artist>
                       <genre>' . $row['genre'] . '</genre>
                       <link>' . $row['link'] . '</link> 
                       <releaseyear>' . $row['releaseYear'] . '</releaseyear>
                       </item>';
      }
    }
    //after looping through the items, add the below onto the end of the string.
    $xmlString .= '</channel>
                   </rss>';

    //turns tring into XML file.
    file_put_contents('../files/exported_playlist.xml', $xmlString);
    
    //close connection
    $conn = null;
    //take user to XML file
    header("Location: ../files/exported_playlist.xml");
    exit();
    
  }
  catch(PDOException $e){
    echo "Error: " . $e->getMessage();
  }
}
//if user did not click on the export button, display an error message
else{
  echo 'Error, please go back to <a href="../myplaylist.php">My Playlist</a> and try again.';
}

?>