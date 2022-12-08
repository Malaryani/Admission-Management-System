<?php
   include("config.php");
      session_start();
   $error = "";
   if($_SERVER["REQUEST_METHOD"] == "POST") {
      // username and password sent from form 
      
      $myusername = mysqli_real_escape_string($db,$_POST['username']);
      $mypassword = mysqli_real_escape_string($db,$_POST['password']); 
      
      $sql = "SELECT id FROM parent WHERE email = '$myusername' and password = '$mypassword'";
      $result = mysqli_query($db,$sql);
      $row = mysqli_fetch_array($result,MYSQLI_ASSOC);
      
      $count = mysqli_num_rows($result);
      
      // If result matched $myusername and $mypassword, table row must be 1 row
		
      if($count == 1) {
         $_SESSION['login_user'] = $_POST['username'];
         
         header("location: ParentPortalDashboard.php");
      }else {
         $error = "Your Login Name or Password is invalid";
      }
   }
?>
<html>
   
   <head>
      <title>Parent Login Page</title>
      <link rel="stylesheet" href="adminLogin.css">
      
   </head>
   
   <body bgcolor = "#34495e" color = "#fff">
	
      <div align = "center">
         <div style = "width:300px; border: solid 1px #fff; " align = "left">
            <div style = "background-color:#333333; color:#FFFFFF; padding:3px;"><b>Login</b></div>
				
            <div style = "margin:30px">
               
               <form action = "" method = "post">
                  <div class="form-group" >
                     <label >Email Address</label>
                     <input type="text" name="username" class="form-control" />
                  </div>
                  <div class="form-group">
                     <label>Password</label>
                     <input type="password" name="password" class="form-control" />
                  </div>
                  <input type = "submit" value = " Submit "/><br />
               </form>
               <div style = "font-size:11px; color:#cc0000; margin-top:10px"><?php echo $error; ?></div>
               <p>Back to Main Menu <a href="index.php">Click Here</a>.</p>
					
            </div>
				
         </div>
			
      </div>

   </body>
</html>
