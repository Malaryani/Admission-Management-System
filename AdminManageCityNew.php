<?php
// Include config file
require_once "config.php";
 
// Define variables and initialize with empty values
 $name = "";
 $name_err = "";
 $sname = "";
 $sname_err = "";
 $sID=-1;
// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){
 
        // Prepare a select Cityment
        $sql = "SELECT id FROM city WHERE name = ?";
        
        if($stmt = mysqli_prepare($db, $sql)){
            // Bind variables to the prepared Cityment as parameters
            mysqli_stmt_bind_param($stmt, "s", $param_name);
            
            // Set parameters
            $param_name = trim($_POST["name"]);
            
            // Attempt to execute the prepared Cityment
            if(mysqli_stmt_execute($stmt)){
                /* store result */
                mysqli_stmt_store_result($stmt);
                
                if(mysqli_stmt_num_rows($stmt) == 1){
                    $name_err = "This City Already Created.";
                } else{
                    $name = trim($_POST["name"]);
                }
            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }

            // Close Cityment
            mysqli_stmt_close($stmt);
        }
        // Prepare a select Cityment
        $sql = "SELECT id FROM state WHERE name = ?";
            
        $query = $db->query("SELECT * FROM `state` WHERE name = '".$_POST["sname"]."'");
        while($fetch = $query->fetch_array()){
            $sID = $fetch['id'];
        }
    // Validate confirm State Name
    if(empty(trim($_POST["sname"]))){
        $sname_err = "Please enter valid state name.";     
    } else if($sID==-1){
        $sname_err = "Please enter valid state name.";     
    }else{
        $sname = trim($_POST["sname"]);
    }
    
    // Check input errors before inserting in database
    if(empty($name_err) && empty($sname_err) && $sID!=-1){
        
        // Prepare an insert Cityment
        $sql = "INSERT INTO city (name,state_id) VALUES (?,?)";
         
        if($stmt = mysqli_prepare($db, $sql)){
            // Bind variables to the prepared Cityment as parameters
            mysqli_stmt_bind_param($stmt, "si", $param_name,$param_sname);
            
            // Set parameters
            $param_name = $name;
            $param_sname = $sID;
            // Attempt to execute the prepared Cityment
            if(mysqli_stmt_execute($stmt)){
                // Redirect to login page
                header("location: AdminManageCity.php");
            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }

            // Close Cityment
            mysqli_stmt_close($stmt);
        }
    }
    
}
?>
 
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add New City</title>
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
        <h2>Add New City</h2>
        <p>Please fill this form to create a new City record.</p>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="form-group">
                <label>Name</label>
                <input type="text" name="name" class="form-control <?php echo (!empty($name_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $name; ?>">
                <span class="invalid-feedback"><?php echo $name_err; ?></span>
            </div>
            <div class="form-group">
                <label>State</label>
                <input type="text" name="sname" class="form-control <?php echo (!empty($sname_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $sname; ?>">
                <span class="invalid-feedback"><?php echo $sname_err; ?></span>
            </div>
            <div class="form-group">
                <input type="submit" class="btn btn-primary" value="Submit">
            </div>
            <p>Back to City Dashboard <a href="AdminManageCity.php">Click Here</a>.</p>
        </form>
    </div>    
    </div>    
</body>
</html>