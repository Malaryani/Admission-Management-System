<?php
// Include config file
require_once "config.php";
 
// Define variables and initialize with empty values
$email = "";
$email_err  = "";
 
// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){
 
        // Prepare a select statement
        $sql = "SELECT id FROM student WHERE email = ?";
        
        if($stmt = mysqli_prepare($db, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "s", $param_username);
            
            // Set parameters
            $param_username = trim($_POST["email"]);
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                /* store result */
                mysqli_stmt_store_result($stmt);
                
                if(mysqli_stmt_num_rows($stmt) == 1){
                    $email = trim($_POST["email"]);
                    $delSql = "DELETE FROM student WHERE email = ?";
                    if($stmtDel = mysqli_prepare($db, $delSql)){

                    mysqli_stmt_bind_param($stmtDel, "s", $param_username);
                    $param_username = $email;
                    if(mysqli_stmt_execute($stmtDel)){
                        echo "<script type='text/javascript'>alert('Record deleted successfully');</script>";
                        header("location: AdminManageStudent.php");

                      } else {
                        echo "<script type='text/javascript'>alert('Error deleting record.');</script>";
                      }
                    }
                      
                } else{
                    $email_err = "This email is not found.";
                }
            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }

            // Close statement
            mysqli_stmt_close($stmt);
        }
    
    
   
    
    // Close connection
    mysqli_close($db);
}
?>
 
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Remove</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="adminStudentRemove.css">
    <style>
        body{ font: 14px sans-serif; }
        .wrapper{ width: 360px; padding: 20px; }
    </style>
</head>
<body>
    <div class="bodyDiv">
    <div class="wrapper">
        <h1>Remove Student</h1>
        <p>Please Email of the student to remove.</p>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="form-group">
                <label>Email</label>
                <input type="text" name="email" class="form-control <?php echo (!empty($email_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $email; ?>">
                <span class="invalid-feedback"><?php echo $email_err; ?></span>
            </div>    
            <div class="form-group">
                <input type="submit" class="btn btn-primary" value="Remove">
            </div>
        <p>Back to Admin Management Student Dashboard <a href="AdminManageStudent.php">Click Here</a>.</p>
        </form>
    </div>    
    </div>    
</body>
</html>