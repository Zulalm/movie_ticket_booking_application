<?php

  if(isset($_POST["buy-tickets-submit"])){
    //find movie id
    $movieIDQuery = "SELECT movie_id FROM movie_sessions WHERE session_id = '".$_POST["session_id"]."';";
    $movie_id = mysqli_fetch_assoc(mysqli_query($conn,$movieIDQuery))["movie_id"];

    //check if the audience has watched the predecessor movies:
    $checkPredecessorsQuery = "SELECT p.pre_movie_id from predecessors p where p.movie_id = '".$movie_id."' and p.pre_movie_id not in (SELECT h.session_id from has_tickets h
     INNER JOIN movie_sessions m ON m.session_id = h.session_id where h.username = '".$_SESSION["username"]."' and m.session_date < (SELECT session_date FROM movie_sessions WHERE session_id = '".$_POST["session_id"]."'));";
    $checkResult = mysqli_query($conn,$checkPredecessorsQuery);
    if(mysqli_num_rows($checkResult)>0){
        while ($row = mysqli_fetch_assoc($checkResult)){
            echo "<div class=\"alert alert-danger\" role=\"alert\">".$row["pre_movie_id"]." should be seen before.</div>";
            exit();
        }
    }
    //check if tickets remain
    $capacityQuery = "SELECT
    CASE WHEN (SELECT t.theatre_capacity from theatre t where t.theatre_id = (SELECT s.theatre_id from movie_sessions s where s.session_id = '".$_POST["session_id"]."')) > (SELECT COUNT(*) as sold_tickets FROM has_tickets where session_id = '".$_POST["session_id"]."') THEN 'TRUE'
    ELSE 'FALSE' END AS 'TicketsRemain';";
    $capacityResult = mysqli_query($conn, $capacityQuery);
    while($row = mysqli_fetch_assoc($capacityResult)){
        if($row["TicketsRemain"]== "FALSE"){
            echo "<div class=\"alert alert-danger\" role=\"alert\">Tickets for ".$_POST["session_id"]." sold out.</div>";
            exit();
        }
    }
    //check if the audience has bought a ticket for the given session
    $checkUniqueTicketQuery = "SELECT * from has_tickets where username = '".$_SESSION["username"]."' and session_id = '".$_POST["session_id"]."';";
    $uniqueTicketResult = mysqli_query($conn,$checkUniqueTicketQuery);
    if(mysqli_num_rows($uniqueTicketResult)>0){
        echo "<div class=\"alert alert-warning\" role=\"alert\">You have already bought a ticket for this session.</div>";
        exit();
    }
    $query = "INSERT INTO has_tickets (username, session_id ) VALUES ('".$_SESSION["username"]."', '".$_POST["session_id"]."');";
    $result = mysqli_query($conn, $query);
    echo "<div class=\"alert alert-success\" role=\"alert\">You have bought the ticket.</div>";
    exit();
  }

  if(isset($_POST["rate-movie-submit"])){
    // check if the user has rated the movie before
    $checkQuery = "SELECT * FROM ratings  where username = '".$_SESSION["username"]."' and movie_id = '".$_POST["movie_id"]."';";
    $checkResult = mysqli_query($conn, $checkQuery);
    if(mysqli_num_rows($checkResult) > 0){
        echo "<div class=\"alert alert-danger\" role=\"alert\">You have already rated this movie.</div>"; 
        exit();
    }

    //check if the user subscribes the platform of the movie

    $checkQuery =     "SELECT * FROM (SELECT platform_id from director d where d.username IN (SELECT director_name FROM movie where movie_id = '".$_POST["movie_id"]."')) 
    t1 INNER JOIN (SELECT platform_id from subscribes_rating_platform WHERE username = '".$_SESSION["username"]."') t2 ON t1.platform_id = t2.platform_id;";
    $checkResult = mysqli_query($conn, $checkQuery);
    if(mysqli_num_rows($checkResult)==0){
        echo "<div class=\"alert alert-danger\" role=\"alert\">You have to subscribe to the rating platform of the movie.</div>"; 
        exit();
    }
    $query = "INSERT INTO ratings (username ,movie_id, rating) values('".$_SESSION["username"]."' ,'".$_POST["movie_id"]."','".$_POST["rating"]."' );";
    mysqli_query($conn,$query);
    echo "<div class=\"alert alert-success\" role=\"alert\">You have rated the movie.</div>";
    
    
  }

  if(isset($_POST["subscribe-rating-platform-submit"])){
    // check if the user has subscribed the platform before
    $checkQuery = "SELECT * FROM subscribes_rating_platform  where username = '".$_SESSION["username"]."' and platform_id = '".$_POST["platform_id"]."';";
    $checkResult = mysqli_query($conn, $checkQuery);
    if(mysqli_num_rows($checkResult) > 0){
        echo "<div class=\"alert alert-warning\" role=\"alert\">You have already subscribed to the platform.</div>"; 
        exit();
    }
    $query = "INSERT INTO subscribes_rating_platform (username , platform_id) values('".$_SESSION["username"]."' ,'".$_POST["platform_id"]."' );";
    mysqli_query($conn,$query);
    echo "<div class=\"alert alert-success\" role=\"alert\">You have subscribed to the platform.</div>";

  }