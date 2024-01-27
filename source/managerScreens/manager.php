<?php 
require 'base.php' ;
include_once 'dbconfig.php';
session_start();
if (isset($_SESSION["usertype"])){
  if($_SESSION["usertype"] != "manager"){
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
              <a class="nav-link dropdown-toggle" data-bs-toggle="dropdown" href="#" role="button" aria-expanded="false">Audiences</a>
              <ul class="dropdown-menu">
                <li><a class="dropdown-item" href="?add_audience=true" >Add Audience</a></li>
                <li><a class="dropdown-item" href="?delete_audience=true">Delete Audience</a></li>
                <li><a class="dropdown-item" href="?view_all_ratings=true">View All Ratings</a></li>
              </ul>
            </li>
            <li class="nav-item dropdown">
              <a class="nav-link dropdown-toggle" data-bs-toggle="dropdown" href="#" role="button" aria-expanded="false">Directors</a>
              <ul class="dropdown-menu">
                <li><a class="dropdown-item" href="?add_director=true" >Add Director</a></li>
                <li><a class="dropdown-item" href="?view_all_directors=true">View All Directors</a></li>
                <li><a class="dropdown-item" href="?view_all_movies=true">View All Movies</a></li>
                <li><a class="dropdown-item" href="?update_platform_id=true">Update Platform ID</a></li>
              </ul>
            </li>
            <li>
              <a class="nav-link dropdown-toggle" data-bs-toggle="dropdown" href="#" role="button" aria-expanded="false">Movies</a>
              <ul class="dropdown-menu">
                <li><a class="dropdown-item" href="?view_average_rating=true" >View Average Rating</a></li>
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
             $add_audience = "none";
             $delete_audience = "none";
             $view_all_ratings = "none";
             $add_director = "none";
             $view_all_directors = "none";
             $view_all_movies = "none";
             $update_platform_id = "none";
             $view_average_rating = "none";
            if(isset($_GET["add_audience"])){
                $add_audience = "";
            }
            if(isset($_GET["delete_audience"])){
                $delete_audience = "";
            }
            if(isset($_GET["view_all_ratings"])){
                $view_all_ratings = "";
            }
            if(isset($_GET["add_director"])){
                $add_director = "";
            }
            if(isset($_GET["view_all_directors"])){
                $view_all_directors = "";
            }
            if(isset($_GET["view_all_movies"])){
                $view_all_movies = "";
            }
            if(isset($_GET["update_platform_id"])){
                $update_platform_id = "";
            }
            if(isset($_GET["view_average_rating"])){
                $view_average_rating = "";
            }

        ?>
        <div class = "container mt-5 col-5" style="display: <?php  echo $add_audience ?>">
            <form class= "form-control" id="add-audience" method="POST" action="manager.php">
                <div class="mb-3">
                    <label for="audience_username" class="form-label">Username</label>
                    <input
                      name="audience_username"
                      class="form-control"
                      value=""
                      required
                      type="text"
                    />
                  </div>
                  <div class="mb-3">
                    <label for="audience_name" class="form-label">Name</label>
                    <input
                      name="audience_name"
                      class="form-control"
                      value=""
                      required
                      type="text"
                    />
                  </div>
                  <div class="mb-3">
                    <label for="audience_surname" class="form-label">Surname</label>
                    <input
                      name="audience_surname"
                      class="form-control"
                      value=""
                      required
                      type="text"
                    />
                  </div>
                  <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <input
                      name="audience_password"
                      class="form-control"
                      value=""
                      required
                      type="password"
                    />
                  </div>
                  <div  class="mb-3">
                    <button class= "btn btn-primary col-3" type="submit" name="add-audience-submit">Create Audience</button>
                  </div>
            </form>
        </div>

        <div class = "container mt-5 col-5" style="display: <?php  echo $delete_audience ?>">
            <form class= "form-control" id="delete-audience" method="POST" action="manager.php">
                <div class="mb-3">
                    <label for="audience_username" class="form-label">Username</label>
                    <select 
                      name="audience_username"
                      class="form-select"
                      required>
                      <?php
                      $query = "SELECT * FROM audience; ";
                      $result = mysqli_query($conn, $query);
                      if(mysqli_num_rows($result)>0){
                        while ( $row = mysqli_fetch_assoc($result)){
                          echo "<option value =".$row["username"]." >".$row["username"]."</option>";
                        }
                      }
                      ?>
                </select>
                  </div>
                  <div  class="mb-3">
                    <button class= "btn btn-primary col-3" type="submit" name="delete-audience-submit">Delete Audience</button>
                  </div>
            </form>
        </div>

        <div class = "container mt-5 col-5" style="display: <?php  echo $view_all_ratings ?>">
            <form class= "form-control" id="view-all-ratings" method="POST" action="manager.php">
                <div class="mb-3">
                    <label for="audience_username" class="form-label">Username</label>
                    <select 
                      name="audience_username"
                      class="form-select"
                      required>
                      <?php
                      $query = "SELECT * FROM audience; ";
                      $result = mysqli_query($conn, $query);
                      if(mysqli_num_rows($result)>0){
                        while ( $row = mysqli_fetch_assoc($result)){
                          echo "<option value =".$row["username"]." >".$row["username"]."</option>";
                        }
                      }
                      ?>
                </select>
                  </div>
                  <div  class="mb-3">
                    <button class= "btn btn-primary col-3" type="submit" name="view-all-ratings-submit">View All Ratings</button>
                  </div>
            </form>
        </div>

        <div class = "container mt-5 col-5" style="display: <?php  echo $add_director ?>">
            <form class= "form-control" id="add-director" method="POST" action="manager.php">
                <div class="mb-3">
                    <label for="director_username" class="form-label">Username</label>
                    <input
                      name="director_username"
                      class="form-control"
                      value=""
                      required
                      type="text"
                    />
                  </div>
                  <div class="mb-3">
                    <label for="director_name" class="form-label">Name</label>
                    <input
                      name="director_name"
                      class="form-control"
                      value=""
                      required
                      type="text"
                    />
                  </div>
                  <div class="mb-3">
                    <label for="director_surname" class="form-label">Surname</label>
                    <input
                      name="director_surname"
                      class="form-control"
                      value=""
                      required
                      type="text"
                    />
                  </div>
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
                          echo "<option value =".$row["platform_id"]." >".$row["platform_id"]."-".$row["platform_name"]."</option>";
                        }
                      }
                      ?>
                </select>
                  </div>
                  <div class="mb-3">
                    <label for="director_nation" class="form-label">Nation</label>
                    <input
                      name="director_nation"
                      class="form-control"
                      value=""
                      required
                      type="text"
                    />
                  </div>
                  <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <input
                      name="director_password"
                      class="form-control"
                      value=""
                      required
                      type="password"
                    />
                  </div>
                  <div  class="mb-3">
                    <button class= "btn btn-primary col-3" type="submit" name="add-director-submit">Create Director</button>
                  </div>
            </form>
        </div>
        <div class = "container mt-5 col-5" style="display: <?php  echo $view_all_movies ?>">
            <form class= "form-control" id="view-all-movies" method="POST" action="manager.php">
                <div class="mb-3">
                    <label for="director_username" class="form-label">Username</label>
                    <select 
                      name="director_username"
                      class="form-select"
                      required>
                      <?php
                      $directorsQuery = "SELECT * FROM director d; ";
                      $directorsResult = mysqli_query($conn, $directorsQuery);
                      if(mysqli_num_rows($directorsResult)>0){
                        while ( $row = mysqli_fetch_assoc($directorsResult)){
                          echo "<option value =".$row["username"]." >".$row["username"]."</option>";
                        }
                      }
                      ?>
                </select>
                  </div>
                  <div  class="mb-3">
                    <button class= "btn btn-primary col-3" type="submit" name="view-all-movies-submit">View All Movies</button>
                  </div>
            </form>
        </div>
        <div class = "container mt-5 col-5" style="display: <?php  echo $update_platform_id ?>">
            <form class= "form-control" id="update-platform-id" method="POST" action="manager.php">
                <div class="mb-3">
                    <label for="director_username" class="form-label">Username</label>
                    <select 
                      name="director_username"
                      class="form-select"
                      required>
                      <?php
                      $directorsQuery = "SELECT * FROM director d; ";
                      $directorsResult = mysqli_query($conn, $directorsQuery);
                      if(mysqli_num_rows($directorsResult)>0){
                        while ( $row = mysqli_fetch_assoc($directorsResult)){
                          echo "<option value =".$row["username"]." >".$row["username"]."</option>";
                        }
                      }
                      ?>
                </select>
                  </div>
                  <div class="mb-3">
                    <label for="platform_id" class="form-label">Platform ID</label>
                    <select
                      name="platform_id"
                      class="form-select"
                      required
                    >
                    <?php
                      $platformsQuery = "SELECT * FROM rating_platforms; ";
                      $platformsResult = mysqli_query($conn, $platformsQuery);
                      if(mysqli_num_rows($platformsResult)>0){
                        while ( $row = mysqli_fetch_assoc($platformsResult)){
                          echo "<option value =".$row["platform_id"]." >".$row["platform_id"]."</option>";
                        }
                      }
                      ?>
                    </select>
                  </div>
                  <div  class="mb-3">
                    <button class= "btn btn-primary col-4" type="submit" name="update-platform-id-submit">Update Platform ID</button>
                  </div>
            </form>
        </div>
        <div class = "container mt-5 col-5" style="display: <?php  echo $view_average_rating ?>">
            <form class= "form-control" id="view-average-rating" method="POST" action="manager.php">
                <div class="mb-3">
                    <label for="movie_id" class="form-label">Movie ID</label>
                    <select 
                      name="movie_id"
                      class="form-select"
                      required>
                      <?php
                      $query = "SELECT * FROM movie; ";
                      $result = mysqli_query($conn, $query);
                      if(mysqli_num_rows($result)>0){
                        while ( $row = mysqli_fetch_assoc($result)){
                          echo "<option value =".$row["movie_id"]." >".$row["movie_id"]."</option>";
                        }
                      }
                      ?>
                </select>
                  </div>
                  <div  class="mb-3">
                    <button class= "btn btn-primary col-4" type="submit" name="view-average-rating-submit">View Average Rating</button>
                  </div>
            </form>
        </div>
        <div class = "container mt-5" style="display: <?php  echo $view_all_directors ?>">
            <?php
            $query = "SELECT * FROM director d;";
            $result = mysqli_query($conn, $query);
            
            echo "<div class = \"container\"><table class=\"table table-striped\">
              <h1>Directors</h1>
                <thead>
              <tr>
                <th scope=\"col\">Username</th>
                <th scope=\"col\">Name</th>
                <th scope=\"col\">Surname</th>
                <th scope=\"col\">Nation</th>
                <th scope=\"col\">Platform ID</th>
              </tr>
              </thead>
              <tbody>";
              while($row = mysqli_fetch_assoc($result)){
                echo "<tr>
                <th scope=\"row\">".$row["username"]."</th>
                <td>".$row["name_"]."</td>
                <td>".$row["surname"]."</td>
                <td>".$row["nation"]."</td>
                <td>".$row["platform_id"]."</td>
                </tr>";
              }
                echo "</tbody>
                </table> </div>";
                      ?>
        </div>
    </body>

    <?php 
      require 'managerQueries.php';
    ?>


</html>