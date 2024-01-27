<?php 
require 'base.php' ;
include_once 'dbconfig.php';
?>

<html>
<body> 
     <div class = "container mt-5 col-5"> 
        <label class="form-label">Audience Log In</label>
        <form class= "form-control" id="userLogin" method="POST" action="userlogin.php">
            <div class="mb-3">
                <label for="username" class="form-label">Username</label>
                <input
                  name="username"
                  class="form-control"
                  value=""
                  required
                  type="text"
                />
              </div>
              <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input
                  name="password"
                  class="form-control"
                  value=""
                  required
                  type="password"
                />
              </div>
              <div  class="mb-3">
                <button class= "btn btn-primary col-3" type="submit" name="login-submit">Log in</button>
              </div>
        </form>
     </div>
</body>
</html>
<?php
if (isset($_POST["login-submit"])){
    $sql = "SELECT * from audience a where a.username = '".$_POST["username"]."' and a.user_password = '".$_POST["password"]."'; ";
    $result = mysqli_query($conn, $sql);
    $numOfResults = mysqli_num_rows($result);
    if($numOfResults > 0){
      while($row = mysqli_fetch_assoc($result)){
        session_start();
        $_SESSION["username"] = $row["username"];
        $_SESSION["usertype"] = "audience";
        header("Location: ./audience.php");
        exit();
      }

    }
    else{
      echo "<div class=\"alert alert-danger\" role=\"alert\">Invalid username or password.</div>";
    }
}
?>