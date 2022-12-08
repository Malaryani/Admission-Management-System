<?php
// Include config file
require_once "config.php";
 
// Define variables and initialize with empty values
$name = "";
$name_err  = "";
 
// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){
 
        // Prepare a select Cityment
        $sql = "SELECT id FROM City WHERE name = ?";
        
        if($stmt = mysqli_prepare($db, $sql)){
            // Bind variables to the prepared Cityment as parameters
            mysqli_stmt_bind_param($stmt, "s", $param_username);
            
            // Set parameters
            $param_username = trim($_POST["name"]);
            
            // Attempt to execute the prepared Cityment
            if(mysqli_stmt_execute($stmt)){
                /* store result */
                mysqli_stmt_store_result($stmt);
                
                if(mysqli_stmt_num_rows($stmt) == 1){
                    $name = trim($_POST["name"]);
                    $delSql = "DELETE FROM City WHERE name = ?";
                    if($stmtDel = mysqli_prepare($db, $delSql)){

                    mysqli_stmt_bind_param($stmtDel, "s", $param_username);
                    $param_username = $name;
                    if(mysqli_stmt_execute($stmtDel)){
                        header("location: AdminManageCity.php");

                      } else {
                        echo "<script type='text/javascript'>alert('Error deleting record.');</script>";
                      }
                    }
                      
                } else{
                    $name_err = "This City Not Found.";
                }
            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }

        }
    
    
   
    
}
?>
 
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Remove</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="adminManageDegreeRemove.css">
    <style>
        body{ font: 14px sans-serif; }
        .wrapper{ width: 360px; padding: 20px; }
    </style>
</head>


<body>
    <div class="bodyDiv">
        <div class="listContianer">
        
        <h1>City List</h1>
            <br />
            <table class="table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>State Name</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        $query = $db->query("SELECT c.name as name, s.name as sname from city c INNER JOIN state s on c.state_id=s.id");
                        $count = 1;
                        while($fetch = $query->fetch_array()){
                    ?>
                    <tr>
                        <td><?php echo $count++?></td>
                        <td><?php echo $fetch['name']?></td>
                        <td><?php echo $fetch['sname']?></td>
                    </tr>
                    <?php
                        }
                    ?>
                </tbody>
            </table>
        </div>
    <div class="wrapper">
        <h1>Remove City</h1>
        <p>Please Enter Name of the City to remove.</p>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="form-group">
                <label>City Name </label>
                <input type="text" name="name" class="form-control <?php echo (!empty($name_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $name; ?>">
                <span class="invalid-feedback"><?php echo $name_err; ?></span>
            </div>    
            <div class="form-group">
                <input type="submit" class="btn btn-primary" value="Remove">
            </div>
            <p>Back to City Dashboard <a href="AdminManageCity.php">Click Here</a>.</p>
        </form>
    </div>    
    </div>    
</body>
</html>


