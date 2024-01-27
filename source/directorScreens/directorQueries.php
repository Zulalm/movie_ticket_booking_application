<?php
  if(isset($_POST["list-available-theatres-submit"])){
    //change date from d-m-y format to y-m-d format 
    $date = date('Y-m-d', strtotime($_POST["session_date"]));
    $query = "select * from theatre where theatre_id not in (SELECT theatre_id FROM not_available_slots 
    n where n.time_slot = '".$_POST["time_slot"]."' and n.session_date = '".$date."');";

    $result = mysqli_query($conn, $query);

    echo "<div class = \"container\"><table class=\"table table-striped\">
              <h1>Available Theatres</h1>
                <thead>
              <tr>
                <th scope=\"col\">Theatre ID</th>
                <th scope=\"col\">Theatre Name</th>
                <th scope=\"col\">Theatre District</th>
                <th scope=\"col\">Theatre Capacity</th>
              </tr>
              </thead>
              <tbody>";
              while($row = mysqli_fetch_assoc($result)){
                echo "<tr>
                <th scope=\"row\">".$row["theatre_id"]."</th>
                <td>".$row["theatre_name"]."</td>
                <td>".$row["theatre_district"]."</td>
                <td>".$row["theatre_capacity"]."</td>
                </tr>";
              }
                echo "</tbody>
                </table> </div>";
                      
  }

  if(isset($_POST["add-session-submit"])){

    //change date from d-m-y format to y-m-d format 
    $date = date('Y-m-d', strtotime($_POST["session_date"]));
    
    //find duration of the movie 
    $durationQuery = "SELECT duration from movie where movie_id = '".$_POST["movie_id"]."';";
    $duration = mysqli_fetch_assoc(mysqli_query($conn,$durationQuery))["duration"];
    //check if time slot is available
    for($n = 0; $n < $duration; $n++){
        $checkQuery = "SELECT * FROM not_available_slots WHERE time_slot = '".($_POST["time_slot"] + $n) ."' and theatre_id = '".$_POST["theatre_id"]."' and session_date = '".$date."' ;";
        $checkResult = mysqli_query($conn, $checkQuery);
        if(mysqli_num_rows($checkResult)>0){
            echo "<div class=\"alert alert-danger\" role=\"alert\">Time slot is not available.</div>";
            exit();
        }
    }
    $query = "INSERT INTO movie_sessions( movie_id,theatre_id,time_slot,session_date) 
    values('".$_POST["movie_id"]."', '".$_POST["theatre_id"]."', '".$_POST["time_slot"]."', '".$date."' );";
    mysqli_query($conn, $query);
    echo "<div class=\"alert alert-success\" role=\"alert\">Movie session is added.</div>";
    exit();
    
  }
  if (isset($_POST["add-movie-submit"])){
      // check if the movie_id is unique
      $checkQuery = "SELECT * FROM movie WHERE movie_id = '".$_POST["movie_id"]."';";
      $checkResult = mysqli_query($conn, $checkQuery);

      if(mysqli_num_rows($checkResult)> 0 ){
        echo "<div class=\"alert alert-danger\" role=\"alert\">Another movie exists with the given movie ID.</div>";
        exit();
      }
      $query = "INSERT INTO movie(movie_id,director_name, movie_name, genre_id,  duration) values('".$_POST["movie_id"]."', '".$_SESSION["username"]."', '".$_POST["movie_name"]."', '".$_POST["genre_id"]."', '".$_POST["duration"]."');";
      mysqli_query($conn, $query);
      echo "<div class=\"alert alert-success\" role=\"alert\">Movie is added.</div>";
    exit();
   }
   if (isset($_POST["update-movie-name-submit"])){
      $query = "UPDATE movie SET movie_name = '".$_POST["movie_name"]."' WHERE movie_id = '".$_POST["movie_id"]."';";
      mysqli_query($conn, $query);
      echo "<div class=\"alert alert-success\" role=\"alert\">Movie's name is updated.</div>";
      exit();
   }

   if (isset($_POST["add-predecessor-submit"])){
    // check if the movie and the predecessor movie are not same
    if($_POST["movie_id"] == $_POST["pre_movie_id"]){
      echo "<div class=\"alert alert-danger\" role=\"alert\">Movie ID's should be different.</div>";
      exit();
    }
    // check if the predecessor already added
    $checkQuery = "SELECT * from predecessors where movie_id = '".$_POST["movie_id"]."' and pre_movie_id = '".$_POST["pre_movie_id"]."';";
    $checkResult = mysqli_query($conn, $checkQuery);
    if(mysqli_num_rows($checkResult)>0){
      echo "<div class=\"alert alert-danger\" role=\"alert\">Predecessor is already added.</div>";
      exit();
    }

    $query = "INSERT INTO predecessors (movie_id, pre_movie_id) values ('".$_POST["movie_id"]."','".$_POST["pre_movie_id"]."' );";
    mysqli_query($conn, $query);
    echo "<div class=\"alert alert-success\" role=\"alert\">Predecessor is added.</div>";
    exit();
    
   }
   if (isset($_POST["view-audience-submit"])){
    $query = "SELECT username, name_, surname from audience WHERE username IN (SELECT h.username from has_tickets h INNER JOIN movie_sessions m ON m.session_id = h.session_id where h.session_id = '".$_POST["session_id"]."')";
    $result = mysqli_query($conn, $query);

    echo "<div class = \"container\"><table class=\"table table-striped\">
              <h1>All Audience of '".$_POST["session_id"]."'</h1>
                <thead>
              <tr>
                <th scope=\"col\">Username</th>
                <th scope=\"col\">Name</th>
                <th scope=\"col\">Surname</th>
              </tr>
              </thead>
              <tbody>";
              while($row = mysqli_fetch_assoc($result)){
                echo "<tr>
                <th scope=\"row\">".$row["username"]."</th>
                <td>".$row["name_"]."</td>
                <td>".$row["surname"]."</td>
                </tr>";
              }
                echo "</tbody>
                </table> </div>";

   }
