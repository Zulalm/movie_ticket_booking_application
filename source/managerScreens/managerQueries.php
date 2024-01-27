<?php

  if(isset($_POST["add-audience-submit"])){
    $checkUniqueUsernameQuery = "SELECT * FROM audience a WHERE a.username = '".$_POST["audience_username"]."'; ";
    $checkResult = mysqli_query($conn, $checkUniqueUsernameQuery);

    if(mysqli_num_rows($checkResult) > 0){
      echo "<div class=\"alert alert-danger\" role=\"alert\">Username is already given.</div>";
    }
    else{
      $query = "INSERT INTO audience (username, user_password, name_, surname) VALUES ('".$_POST["audience_username"]."', '".$_POST["audience_password"]."', '".$_POST["audience_name"]."', '".$_POST["audience_surname"]."');";
      $result = mysqli_query($conn, $query);
      echo "<div class=\"alert alert-success\" role=\"alert\">Audience is created.</div>";
    }
  }

  if(isset($_POST["add-director-submit"])){
    $checkUniqueUsernameQuery = "SELECT * FROM director d WHERE d.username = '".$_POST["director_username"]."'; ";
    $checkResult1 = mysqli_query($conn, $checkUniqueUsernameQuery);
    // check if the username is unique
    if(mysqli_num_rows($checkResult1) > 0){
      echo "<div class=\"alert alert-danger\" role=\"alert\">Username is already given.</div>";
    }
    // check if platform id is given
    if($_POST["platform_id"]){
      $checkValidPlatformID = "SELECT * FROM rating_platforms r WHERE r.platform_id = '".$_POST["platform_id"]."'; " ;
      $checkResult2 = mysqli_query($conn, $checkValidPlatformID);
      if(mysqli_num_rows($checkResult2) == 0){
        echo "<div class=\"alert alert-danger\" role=\"alert\">Invalid platform ID.</div>";
      }
      else{
        // create director with platform ID
      $query = "INSERT INTO director (username, user_password, name_, surname, nation, platform_id) VALUES ('".$_POST["director_username"]."', '".$_POST["director_password"]."', '".$_POST["director_name"]."', '".$_POST["director_surname"]."', '".$_POST["director_nation"]."', '".$_POST["platform_id"]."');";
      $result = mysqli_query($conn, $query);
      echo "<div class=\"alert alert-success\" role=\"alert\">Director is created.</div>";
      }
    }
    else{
      //create director without platform ID
      $query = "INSERT INTO director (username, user_password, name_, surname, nation, platform_id) VALUES ('".$_POST["director_username"]."', '".$_POST["director_password"]."', '".$_POST["director_name"]."', '".$_POST["director_surname"]."', '".$_POST["director_nation"]."', NULL);";
      $result = mysqli_query($conn, $query);
      echo "<div class=\"alert alert-success\" role=\"alert\">Director is created.</div>";
    }
  }
  if(isset($_POST["view-all-ratings-submit"])){
    $query = "SELECT * FROM ratings r WHERE r.username = '".$_POST["audience_username"]."' ;";
    $result = mysqli_query($conn, $query);

    if(mysqli_num_rows($result) > 0) {
      echo "<div class = \"container mt-5\"><table class=\"table table-striped\">
      <h1>Ratings of ".$_POST["audience_username"]."</h1>
      <thead>
        <tr>
          <th scope=\"col\">Movie ID</th>
          <th scope=\"col\">Movie Name</th>
          <th scope=\"col\">Rating</th>
        </tr>
      </thead>
      <tbody>";
      while($row = mysqli_fetch_assoc($result)){
        $query = "SELECT movie_name FROM movie WHERE movie_id = '".$row["movie_id"]."';";
        $movie_name = mysqli_fetch_assoc(mysqli_query($conn,$query))["movie_name"];
        echo "<tr>
        <th scope=\"row\">".$row["movie_id"]."</th>
        <td>".$movie_name."</td>
        <td>".$row["rating"]."</td>
      </tr>";
      }
      echo "</tbody>
      </table> </div>";
    }
    else{
      echo "<div class=\"alert alert-danger\" role=\"alert\">Cannot find ratings.</div>";
    }
  }
  if(isset($_POST["delete-audience-submit"])){
    $checkUniqueUsernameQuery = "SELECT * FROM audience a WHERE a.username = '".$_POST["audience_username"]."'; ";
    $checkResult = mysqli_query($conn, $checkUniqueUsernameQuery);

    if(mysqli_num_rows($checkResult) == 0){
      echo "<div class=\"alert alert-danger\" role=\"alert\">Audience does not exist.</div>";
    }
    else{
      $query = "DELETE FROM audience WHERE username = '".$_POST["audience_username"]."';";
      mysqli_query($conn,$query);
      echo "<div class=\"alert alert-success\" role=\"alert\">Audience is deleted.</div>";
    }
    
  }
  if(isset($_POST["view-all-movies-submit"])){
    $query = "SELECT * FROM `movie` m INNER JOIN movie_sessions s ON s.movie_id = m.movie_id INNER JOIN theatre t ON s.theatre_id = t.theatre_id WHERE m.director_name = '".$_POST["director_username"]."';";
    $result = mysqli_query($conn, $query);

    if(mysqli_num_rows($result) > 0) {
      echo "<div class = \"container mt-5\"><table class=\"table table-striped\">
      <h1>Movies Directed by ".$_POST["director_username"]."</h1>
      <thead>
        <tr>
          <th scope=\"col\">Movie ID</th>
          <th scope=\"col\">Movie Name</th>
          <th scope=\"col\">Theatre ID</th>
          <th scope=\"col\">District</th>
          <th scope=\"col\">Time Slot</th>
        </tr>
      </thead>
      <tbody>";
      while($row = mysqli_fetch_assoc($result)){
        $query = "SELECT movie_name FROM movie WHERE movie_id = '".$row["movie_id"]."';";
        $movie_name = mysqli_fetch_assoc(mysqli_query($conn,$query))["movie_name"];
        echo "<tr>
        <th scope=\"row\">".$row["movie_id"]."</th>
        <td>".$row["movie_name"]."</td>
        <td>".$row["theatre_id"]."</td>
        <td>".$row["theatre_district"]."</td>
        <td>".$row["time_slot"]."</td>
        </tr>";
      }
      echo "</tbody>
      </table> </div>";
    }
    else{
      echo "<div class=\"alert alert-danger\" role=\"alert\">No movie that directed by this director is found.</div>";
    }
  }
  
  if(isset($_POST["update-platform-id-submit"])){
    $query = "UPDATE director SET platform_id = '".$_POST["platform_id"]."' WHERE director.username = '".$_POST["director_username"]."';";
    mysqli_query($conn,$query);
    echo"<div class=\"alert alert-success\" role=\"alert\">Platform id of ".$_POST["director_username"]." is updated as ".$_POST["platform_id"].".</div>";
  }
  if(isset($_POST["view-average-rating-submit"])){
    $query = "SELECT * FROM movie m WHERE m.movie_id = '".$_POST["movie_id"]."';";
    $result = mysqli_query($conn,$query);
    if(mysqli_num_rows($result) > 0) {
      echo "<div class = \"container mt-5\"><table class=\"table table-striped\">
      <h1>Rating</h1>
      <thead>
        <tr>
          <th scope=\"col\">Movie ID</th>
          <th scope=\"col\">Movie Name</th>
          <th scope=\"col\">Overall Rating</th>
        </tr>
      </thead>
      <tbody>";
      while($row = mysqli_fetch_assoc($result)){
        $query = "SELECT movie_name FROM movie WHERE movie_id = '".$row["movie_id"]."';";
        $movie_name = mysqli_fetch_assoc(mysqli_query($conn,$query))["movie_name"];
        echo "<tr>
        <th scope=\"row\">".$row["movie_id"]."</th>
        <td>".$row["movie_name"]."</td>
        <td>".$row["average_rating"]."</td>
        </tr>";
      }
      echo "</tbody>
      </table> </div>";
    }
  }    