<?php
// Include config file
require_once "config.php";
 
// Define variables and initialize with empty values
$code = $duration = $name = "";
$code_err = $duration_err = $name_err = "";
 
// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){
 
        // Prepare a select statement
        $sql = "SELECT id FROM degree WHERE code = ?";
        
        if($stmt = mysqli_prepare($db, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "s", $param_username);
            
            // Set parameters
            $param_username = trim($_POST["code"]);
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                /* store result */
                mysqli_stmt_store_result($stmt);
                
                if(mysqli_stmt_num_rows($stmt) == 1){
                    $code_err = "This Degree Code is already taken.";
                } else{
                    $code = trim($_POST["code"]);
                }
            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }

            // Close statement
            mysqli_stmt_close($stmt);
        }
    
    
    // Validate name
    if(empty(trim($_POST["name"]))){
        $name_err = "Please enter a name.";     
    }else{
        $name = trim($_POST["name"]);
    }
    
    // Validate confirm duration
    if(empty(trim($_POST["duration"]))){
        $duration_err = "Please enter valid duration.";     
    } else{
        $duration = trim($_POST["duration"]);
    }
    
    // Check input errors before inserting in database
    if(empty($name_err) && empty($code_err) && empty($duration_err)){
        
        // Prepare an insert statement
        $sql = "INSERT INTO degree (name,duration, code) VALUES (?, ?, ?)";
         
        if($stmt = mysqli_prepare($db, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "sis", $param_name, $param_duration, $param_code);
            
            // Set parameters
            $param_name = $name;
            $param_duration = $duration;
            $param_code = $code;
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                // Redirect to login page
                header("location: AdminManageDegree.php");
            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }

            // Close statement
            mysqli_stmt_close($stmt);
        }
    }
    
    // Close connection
    mysqli_close($db);
}
?>
 
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Sign Up</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="adminSignup.css">
    <style>
        body{ font: 14px sans-serif; }
        .wrapper{ width: 360px; padding: 20px; }
    </style>
</head>
<body>
    <div class="bodyDiv">
    <div class="wrapper">
        <h2>Add New Degree</h2>
        <p>Please fill this form to create a new degree program.</p>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="form-group">
                <label>Name</label>
                <input type="text" name="name" class="form-control <?php echo (!empty($name_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $name; ?>">
                <span class="invalid-feedback"><?php echo $name_err; ?></span>
            </div>    
            <div class="form-group">
                <label>Duration</label>
                <input type="text" name="duration" class="form-control <?php echo (!empty($duration_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $duration; ?>">
                <span class="invalid-feedback"><?php echo $duration_err; ?></span>
            </div>
            <div class="form-group">
                <label>Code</label>
                <input type="text" name="code" class="form-control <?php echo (!empty($code_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $code; ?>">
                <span class="invalid-feedback"><?php echo $code_err; ?></span>
            </div>
            <div class="form-group">
                <input type="submit" class="btn btn-primary" value="Submit">
            </div>
            <p>Back to Degree Dashboard <a href="AdminManageDegree.php">Click Here</a>.</p>
        </form>
    </div>    
    </div>    
</body>
</html>