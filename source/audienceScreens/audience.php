<?php 
require 'base.php' ;
include_once 'dbconfig.php';
session_start();
if (isset($_SESSION["usertype"])){
  if($_SESSION["usertype"] != "audience"){
    header("Location: ./index.php");
    exit();
  }

}
else{
  header("Location: ./index.php");
    exit();
}
?>

<html>
    <body>
        <div class="container">
            <br>
        <ul class="nav text-center justify-content-center">
            <li class="nav-item dropdown">
              <a class="nav-link dropdown-toggle" data-bs-toggle="dropdown" href="#" role="button" aria-expanded="false">Tickets</a>
              <ul class="dropdown-menu">
                <li><a class="dropdown-item" href="?buy_tickets=true" >Buy Tickets</a></li>
                <li><a class="dropdown-item" href="?view_all_tickets=true" >View All Tickets</a></li>
              </ul>
            </li>
            <li class="nav-item dropdown">
              <a class="nav-link dropdown-toggle" data-bs-toggle="dropdown" href="#" role="button" aria-expanded="false">Movies</a>
              <ul class="dropdown-menu">
                <li><a class="dropdown-item" href="?view_all_movies=true">View All Movies</a></li>
                <li><a class="dropdown-item" href="?rate_movie=true">Rate Session</a></li>
              </ul>
            </li>
            <li class="nav-item dropdown">
              <a class="nav-link dropdown-toggle" data-bs-toggle="dropdown" href="#" role="button" aria-expanded="false">Rating Platforms</a>
              <ul class="dropdown-menu">
                <li><a class="dropdown-item" href="?list_rating_platforms=true" >List Subscribed Rating Platforms</a></li>
                <li><a class="dropdown-item" href="?subscribe_rating_platforms=true" >Subscribe Rating Platforms</a></li>
              </ul>
            </li>
            <li>
              <form action="./logout.php" method="POST">
                <button class="btn btn-primary"type= "submit">Log Out</a>
              </form>
            </li>
          </ul>
        </div>

        <?php
        $buy_tickets = "none";
        $view_all_tickets= "none";
        $rate_movie= "none";
        $view_all_movies= "none";
        $list_rating_platforms= "none";
        $subscribe_rating_platforms= "none";

        if(isset($_GET["buy_tickets"])){
            $buy_tickets = "";
        } 
        if(isset($_GET["view_all_tickets"])){
            $view_all_tickets= "";
        }
        if(isset($_GET["rate_movie"])){
            $rate_movie= "";
        }
        if(isset($_GET["view_all_movies"])){
            $view_all_movies= "";
        }
        if(isset($_GET["list_rating_platforms"])){
          $list_rating_platforms= "";
        }
        if(isset($_GET["subscribe_rating_platforms"])){
        $subscribe_rating_platforms= "";
        }
    ?>
    <div class = "container mt-5 col-5" style="display: <?php  echo $subscribe_rating_platforms ?>">
        <form class= "form-control" id="subscribe-rating-platforms" method="POST" action="audience.php">
            <div class="mb-3">
                <label for="platform_id" class="form-label">Platform ID</label>
                <select 
                      name="platform_id"
                      class="form-select"
                      required>
                      <?php
                      $query = "SELECT * FROM rating_platforms; ";
                      $result = mysqli_query($conn, $query);
                      if(mysqli_num_rows($result)>0){
                        while ( $row = mysqli_fetch_assoc($result)){
                          echo "<option value =".$row["platform_id"]." >".$row["platform_id"]." - ".$row["platform_name"]."</option>";
                        }
                      }
                      ?>
                </select>
              </div>
              <div  class="mb-3">
                <button class= "btn btn-primary col-4" type="submit" name="subscribe-rating-platform-submit">Subscribe Rating Platform</button>
              </div>
        </form>
    </div>
    <div class = "container mt-5 col-5" style="display: <?php  echo $buy_tickets ?>">
        <form class= "form-control" id="buy-tickets" method="POST" action="audience.php">
            <div class="mb-3">
                <label for="session_id" class="form-label">Session ID</label>
                <select 
                      name="session_id"
                      class="form-select"
                      required>
                      <?php
                      $query = "SELECT * FROM movie_sessions; ";
                      $result = mysqli_query($conn, $query);
                      if(mysqli_num_rows($result)>0){
                        while ( $row = mysqli_fetch_assoc($result)){
                          echo "<option value =".$row["session_id"]." >".$row["session_id"]."</option>";
                        }
                      }
                      ?>
                </select>
              </div>
              <div  class="mb-3">
                <button class= "btn btn-primary col-4" type="submit" name="buy-tickets-submit">Buy Ticket</button>
              </div>
        </form>
    </div>

    <div class = "container mt-5 " style="display: <?php  echo $view_all_tickets ?>">
      <?php
            $query = "SELECT m.movie_id, mo.movie_name, r.rating, mo.average_rating, h.session_id ,m.session_date FROM has_tickets h INNER JOIN movie_sessions m ON 
            m.session_id = h.session_id INNER JOIN movie mo ON mo.movie_id = m.movie_id 
            LEFT JOIN ratings r on r.movie_id = m.movie_id where h.username = '".$_SESSION["username"]."';";
            $result = mysqli_query($conn, $query);
            
            echo "<div class = \"container\"><table class=\"table table-striped\">
              <h1>My Tickets</h1>
                <thead>
              <tr>
                <th scope=\"col\">Session ID</th>
                <th scope=\"col\">Movie Name</th>
                <th scope=\"col\">Movie ID</th>
                <th scope=\"col\">Rating</th>
                <th scope=\"col\">Overall Rating</th>
                <th scope=\"col\">Date</th>
              </tr>
              </thead>
              <tbody>";
              while($row = mysqli_fetch_assoc($result)){
                echo "<tr>
                <th scope=\"row\">".$row["session_id"]."</th>
                <td>".$row["movie_name"]."</td>
                <td>".$row["movie_id"]."</td>
                <td>".$row["rating"]."</td>
                <td>".$row["average_rating"]."</td>
                <td>".$row["session_date"]."</td>
                </tr>";
              }
                echo "</tbody>
                </table> </div>";
                      ?>
    </div>

    <div class = "container mt-5 col-5" style="display: <?php  echo $rate_movie ?>">
        <form class= "form-control" id="rate-movie" method="POST" action="audience.php">
            <div class="mb-3">
                <label for="movie_id" class="form-label">Movie ID</label>
                <select 
                      name="movie_id"
                      class="form-select"
                      required>
                      <?php
                      $query = "SELECT m.movie_id FROM movie_sessions m WHERE m.session_id IN (SELECT session_id FROM has_tickets where username = '".$_SESSION["username"]."') and m.session_date < date(now()); ";
                      $result = mysqli_query($conn, $query);
                      if(mysqli_num_rows($result)>0){
                        while ( $row = mysqli_fetch_assoc($result)){
                          echo "<option value =".$row["movie_id"]." >".$row["movie_id"]." </option>";
                        }
                      }
                      ?>
                </select>
              </div>
              <div class="mb-3">
                <label for="session_id" class="form-label">Rating</label>
                <input
                  name="rating"
                  class="form-control"
                  value=""
                  required
                  type="number"
                />
              </div>
              <div  class="mb-3">
                <button class= "btn btn-primary col-4" type="submit" name="rate-movie-submit">Rate Movie</button>
              </div>
        </form>
    </div>
    <div class = "container mt-5 " style="display: <?php  echo $view_all_movies ?>">
      <?php
            $query = "SELECT ms.session_id, ms.movie_id, d.surname,ms.session_date, d.platform_id, ms.theatre_id, ms.time_slot, 
            m.movie_name FROM movie_sessions ms INNER JOIN movie m on m.movie_id = ms.movie_id INNER JOIN director d on d.username = m.director_name;";
            $result = mysqli_query($conn, $query);
            
            
            echo "<div class = \"container\"><table class=\"table table-striped\">
              <h1>All Movie Sessions</h1>
                <thead>
              <tr>
                <th scope=\"col\">Session ID</th>
                <th scope=\"col\">Movie Name</th>
                <th scope=\"col\">Movie ID</th>
                <th scope=\"col\">Director's Surname</th>
                <th scope=\"col\">Platform</th>
                <th scope=\"col\">Theatre ID</th>
                <th scope=\"col\">Time Slot</th>
                <th scope=\"col\">Date</th>
                <th scope=\"col\">Predecessors</th>
                
              </tr>
              </thead>
              <tbody>";
              while($row = mysqli_fetch_assoc($result)){
                echo "<tr>
                <th scope=\"row\">".$row["session_id"]."</th>
                <td>".$row["movie_name"]."</td>
                <td>".$row["movie_id"]."</td>
                <td>".$row["surname"]."</td>
                <td>".$row["platform_id"]."</td>
                <td>".$row["theatre_id"]."</td>
                <td>".$row["time_slot"]."</td>
                <td>".$row["session_date"]."</td>
                
                <td>";
                $predecessorsQuery = "SELECT pre_movie_id from predecessors where movie_id = '".$row["movie_id"]."';";
                $predecessorsResult = mysqli_query($conn,$predecessorsQuery);
                if (mysqli_num_rows($predecessorsResult)>0){
                  while ($row = mysqli_fetch_assoc($predecessorsResult)){
                    echo "".$row["pre_movie_id"]." , ";
                  }

                }
                
                echo "</td>
                
                </tr>";
              }
                echo "</tbody>
                </table> </div>";
                      ?>
    </div>
    <div class = "container mt-5 col-5" style="display: <?php  echo $list_rating_platforms ?>">
      <?php
            $query = "SELECT * FROM subscribes_rating_platform s INNER JOIN rating_platforms r on s.platform_id = r.platform_id where s.username = '".$_SESSION["username"]."';";
            $result = mysqli_query($conn, $query);
            
            
            echo "<div class = \"container\"><table class=\"table table-striped\">
              <h1>Subscribed Platforms</h1>
                <thead>
              <tr>
                <th scope=\"col\">Platform ID</th>
                <th scope=\"col\">Platform Name</th>
                
              </tr>
              </thead>
              <tbody>";
              while($row = mysqli_fetch_assoc($result)){
                echo "<tr>
                <th scope=\"row\">".$row["platform_id"]."</th>
                <td>".$row["platform_name"]."</td> </tr> " ;               
                
               } 
              echo" </tbody>
              </table> </div>";
              ?>
    </div>

    
    </body>
    <?php
    require 'audienceQueries.php'; 
    ?>
</html>