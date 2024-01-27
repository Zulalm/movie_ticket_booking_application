<?php 
require 'base.php' ;
include_once 'dbconfig.php';
session_start();
 if (isset($_SESSION["usertype"])){
  if($_SESSION["usertype"] != "director"){
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
              <a class="nav-link dropdown-toggle" data-bs-toggle="dropdown" href="#" role="button" aria-expanded="false">Theatres</a>
              <ul class="dropdown-menu">
                <li><a class="dropdown-item" href="?list_available_theatres=true" >List Available Theatres</a></li>
              </ul>
            </li>
            <li class="nav-item dropdown">
              <a class="nav-link dropdown-toggle" data-bs-toggle="dropdown" href="#" role="button" aria-expanded="false">Movies</a>
              <ul class="dropdown-menu">
                <li><a class="dropdown-item" href="?add_movie=true" >Add Movie</a></li>
                <li><a class="dropdown-item" href="?add_session=true" >Add Session</a></li>
                <li><a class="dropdown-item" href="?update_movie_name=true" >Update Movie Name</a></li>
                <li><a class="dropdown-item" href="?add_predecessors=true">Add Predecessors</a></li>
                <li><a class="dropdown-item" href="?view_all_movies=true">View All Movies</a></li>
                <li><a class="dropdown-item" href="?view_audiences=true">View Audiences</a></li>
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
            $list_available_theatres = "none";
            $add_movie= "none";
            $add_session= "none";
            $update_movie_name= "none";
            $add_predecessors= "none";
            $view_all_movies= "none";
            $view_audiences= "none";


            if(isset($_GET["list_available_theatres"])){
                $list_available_theatres = "";
            } 
            if(isset($_GET["add_movie"])){
                $add_movie= "";
            }
            if(isset($_GET["add_session"])){
              $add_session= "";
            }
            if(isset($_GET["update_movie_name"])){
                $update_movie_name= "";
            }
            if(isset($_GET["add_predecessors"])){
                $add_predecessors= "";
            }
            if(isset($_GET["view_all_movies"])){
                $view_all_movies= "";
            }
            if(isset($_GET["view_audiences"])){
                $view_audiences= "";
            }

            
        ?>
        <div class = "container mt-5 col-5" style="display: <?php  echo $add_session ?>">
            <form class= "form-control" id="add-movie" method="POST" action="director.php">
                <div class="mb-3">
                    <div class="mb-3">
                        <label for="movie_id" class="form-label">Movie ID</label>
                        <select 
                      name="movie_id"
                      class="form-select"
                      required>
                      <?php
                      $query = "SELECT * FROM movie where director_name = '".$_SESSION["username"]."'; ";
                      $result = mysqli_query($conn, $query);
                      if(mysqli_num_rows($result)>0){
                        while ( $row = mysqli_fetch_assoc($result)){
                          echo "<option value =".$row["movie_id"]." >".$row["movie_id"]." - ".$row["movie_name"]."</option>";
                        }
                      }
                      ?>
                </select>
                      </div>
                      <div class="mb-3">
                        <label for="theatre_id" class="form-label">Theatre ID</label>
                        <select 
                      name="theatre_id"
                      class="form-select"
                      required>
                      <?php
                      $query = "SELECT * FROM theatre; ";
                      $result = mysqli_query($conn, $query);
                      if(mysqli_num_rows($result)>0){
                        while ( $row = mysqli_fetch_assoc($result)){
                          echo "<option value =".$row["theatre_id"]." >".$row["theatre_id"]." - ".$row["theatre_name"]."</option>";
                        }
                      }
                      ?>
                </select>
                      </div>
                    <label for="session_date" class="form-label">Session Date</label>
                    <input
                      name="session_date"
                      class="form-control"
                      value=""
                      required
                      type="date"
                    />
                  </div>
                  <div class="mb-3">
                    <label for="time_slot" class="form-label">Time Slot</label>
                    <select 
                    name="time_slot"
                    class="form-select"
                    required>
                    <option value = "1">1</option>
                    <option value = "2">2</option>
                    <option value ="3">3</option>
                    <option value = "4">4</option>
              </select>
                  </div>
                  <div  class="mb-3">
                    <button class= "btn btn-primary col-4" type="submit" name="add-session-submit">Add Session</button>
                  </div>
            </form>
        </div>
        <div class = "container mt-5 col-5" style="display: <?php  echo $list_available_theatres ?>">
            <form class= "form-control" id="list-available-theatres" method="POST" action="director.php">
                <div class="mb-3">
                    <label for="session_date" class="form-label">Session Date</label>
                    <input
                      name="session_date"
                      class="form-control"
                      value=""
                      required
                      type="date"
                    />
                  </div>
                  <div class="mb-3">
                    <label for="time_slot" class="form-label">Time Slot</label>
                    <select 
                      name="time_slot"
                      class="form-select"
                      required>
                      <option value = "1">1</option>
                      <option value = "2">2</option>
                      <option value ="3">3</option>
                      <option value = "4">4</option>
                </select>
                  </div>
                  <div  class="mb-3">
                    <button class= "btn btn-primary col-4" type="submit" name="list-available-theatres-submit">List Available Theatres</button>
                  </div>
            </form>
        </div>
        <div class = "container mt-5 col-5" style="display: <?php  echo $add_predecessors ?>">
            <form class= "form-control" id="add-predecessors" method="POST" action="director.php">
                <div class="mb-3">
                    <label for="movie_id" class="form-label">Movie ID</label>
                    <select 
                    name="movie_id"
                    class="form-select"
                    required>
                    <?php
                    $query = "SELECT * FROM movie m where m.director_name = '".$_SESSION["username"]."'; ";
                    $result = mysqli_query($conn, $query);
                    if(mysqli_num_rows($result)>0){
                      while ( $row = mysqli_fetch_assoc($result)){
                        echo "<option value =".$row["movie_id"]." >".$row["movie_id"]." - ".$row["movie_name"]."</option>";
                      }
                    }
                    ?>
              </select>
                  </div>
                  <div class="mb-3">
                    <label for="predecessor_movie_id" class="form-label">Movie ID of the Predecessor</label>
                    <select 
                    name="pre_movie_id"
                    class="form-select"
                    required>
                    <?php
                    $query = "SELECT * FROM movie m; ";
                    $result = mysqli_query($conn, $query);
                    if(mysqli_num_rows($result)>0){
                      while ( $row = mysqli_fetch_assoc($result)){
                        echo "<option value =".$row["movie_id"]." >".$row["movie_id"]." - ".$row["movie_name"]."</option>";
                      }
                    }
                    ?>
              </select>
                  </div>
                  <div  class="mb-3">
                    <button class= "btn btn-primary col-4" type="submit" name="add-predecessor-submit">Add Predecessor</button>
                  </div>
            </form>
        </div>
        <div class = "container mt-5 col-5" style="display: <?php  echo $view_all_movies ?>">
          <?php
            $query = "SELECT ms.session_id, ms.movie_id, ms.theatre_id, ms.time_slot, 
            m.movie_name FROM movie_sessions ms INNER JOIN movie m on m.movie_id = ms.movie_id where m.director_name = '".$_SESSION["username"]."' ORDER BY ms.movie_id ASC;";
            $result = mysqli_query($conn, $query);
            
            
            echo "<div class = \"container\"><table class=\"table table-striped\">
              <h1>All Movie Sessions</h1>
                <thead>
              <tr>
                <th scope=\"col\">Session ID</th>
                <th scope=\"col\">Movie Name</th>
                <th scope=\"col\">Movie ID</th>
                <th scope=\"col\">Theatre ID</th>
                <th scope=\"col\">Time Slot</th>
                <th scope=\"col\">Predecessors</th>
              </tr>
              </thead>
              <tbody>";
              while($row = mysqli_fetch_assoc($result)){
                echo "<tr>
                <th scope=\"row\">".$row["session_id"]."</th>
                <td>".$row["movie_name"]."</td>
                <td>".$row["movie_id"]."</td>
                <td>".$row["theatre_id"]."</td>
                <td>".$row["time_slot"]."</td>
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
        <div class = "container mt-5 col-5" style="display: <?php  echo $view_audiences ?>">
            <form class= "form-control" id="view-audiences" method="POST" action="director.php">
                <div class="mb-3">
                    <label for="session_id" class="form-label">Movie ID</label>
                    <select 
                    name="session_id"
                    class="form-select"
                    required>
                    <?php
                    $query = "SELECT ms.session_id, m.movie_name  FROM movie m INNER JOIN movie_sessions ms ON ms.movie_id = m.movie_id where m.director_name = '".$_SESSION["username"]."'; ";
                    $result = mysqli_query($conn, $query);
                    if(mysqli_num_rows($result)>0){
                      while ( $row = mysqli_fetch_assoc($result)){
                        echo "<option value =".$row["session_id"]." >".$row["session_id"]." - ".$row["movie_name"]."</option>";
                      }
                    }
                    ?>
              </select>
                  </div>
                  <div  class="mb-3">
                    <button class= "btn btn-primary col-4" type="submit" name="view-audience-submit">View Audience</button>
                  </div>
            </form>
        </div>
        <div class = "container mt-5 col-5" style="display: <?php  echo $add_movie ?>">
          <form class= "form-control" id="add-movie" method="POST" action="director.php">
              
                  <div class="mb-3">
                      <label for="movie_id" class="form-label">Movie ID</label>
                      <input 
                        name="movie_id"
                        class="form-control"
                        value = ""
                        required
                        type = "number">
                    </div>
                  <div class="mb-3">
                  <label for="movie_name" class="form-label">Movie Name</label>
                  <input
                    name="movie_name"
                    class="form-control"
                    value=""
                    required
                    type="text"
                  />
                </div>
                    <div class="mb-3">
                      <label for="genre_id" class="form-label">Genre ID</label>
                      <select 
                    name="genre_id"
                    class="form-select"
                    required>
                    <?php
                    $query = "SELECT * FROM genre; ";
                    $result = mysqli_query($conn, $query);
                    if(mysqli_num_rows($result)>0){
                      while ( $row = mysqli_fetch_assoc($result)){
                        echo "<option value =".$row["genre_id"]." >".$row["genre_id"]." - ".$row["genre_name"]."</option>";
                      }
                    }
                    ?>
              </select>
                    </div>
                <div class="mb-3">
                  <label for="duration" class="form-label">Duration</label>
                  <select 
                  name="duration"
                  class="form-select"
                  required>
                  <option value = "1">1</option>
                  <option value = "2">2</option>
                  <option value ="3">3</option>
                  <option value = "4">4</option>
            </select>
                </div>
                <div  class="mb-3">
                  <button class= "btn btn-primary col-4" type="submit" name="add-movie-submit">Add Movie</button>
                </div>
          </form>
      </div>
    <div class = "container mt-5 col-5" style="display: <?php  echo $update_movie_name ?>">
        <form class= "form-control" id="update-movie-name" method="POST" action="director.php">
            <div class="mb-3">
                <label for="movie_id" class="form-label">Movie ID</label>
                <select 
                    name="movie_id"
                    class="form-select"
                    required>
                    <?php
                    $query = "SELECT * FROM movie m where m.director_name = '".$_SESSION["username"]."'; ";
                    $result = mysqli_query($conn, $query);
                    if(mysqli_num_rows($result)>0){
                      while ( $row = mysqli_fetch_assoc($result)){
                        echo "<option value =".$row["movie_id"]." >".$row["movie_id"]." - ".$row["movie_name"]."</option>";
                      }
                    }
                    ?>
              </select>
              </div>
              <div class="mb-3">
                <label for="movie_name" class="form-label">Movie Name</label>
                <input
                  name="movie_name"
                  class="form-control"
                  value=""
                  required
                  type="text"
                />
              </div>
              <div  class="mb-3">
                <button class= "btn btn-primary col-4" type="submit" name="update-movie-name-submit">Update Movie Name</button>
              </div>
        </form>
    </div>
    <?php
    require 'directorQueries.php';
    ?>
</body>
</html>