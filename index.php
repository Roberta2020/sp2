<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Document</title>
</head>
<body>
<header>
<a href= 'index.php'>Employees</a>
<a href= 'projects.php'>Projects</a>
</header>
<div class="container">
<?php
    //---- Mysqli installation
    $servername = "localhost";
    $username = "root";
    $password = "mysql";
    $dbname = "sprint"; 

        //Create connection
    $conn = mysqli_connect($servername, $username, $password, $dbname);

        //Check the connection
    if(!$conn){
        die("Connection failed:" . mysqli_connect_error());
    }
?>
<?php
//-------- Delete
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $id = $_POST['delete_emp'];
        if (!empty($id)) {
          $sql = 'DELETE FROM employees WHERE id = ?';
          $stmt = $conn->prepare($sql);
          $stmt->bind_param('i', $id);
          $res = $stmt->execute();
          $stmt->close();
          mysqli_close($conn);
          header("Location: ?path=employees");
          die();
        }
    }
?>
<?php
    // -------- Insert
    if (isset($_POST['insert_emp'])) {
        $employer = $_POST['employer'];
        if (empty($employer)) {
            echo "Employer name is empty";
        } else {
            $sql = 'INSERT INTO employees (employer) VALUES (?)';
            $stmt = $conn->prepare($sql);
            $stmt->bind_param('s', $employer);
            $res = $stmt->execute();
            $stmt->close();
            mysqli_close($conn);
            header("Location: ?path=employees");
            die();
        }
    }
?>

<?php
    // ------ Table data
    $sql = "SELECT employees.id, employees.employer, employees.projects_id,  
            GROUP_CONCAT(projects.project separator \", \") as pr_id
            FROM employees
            LEFT JOIN projects
            ON employees.projects_id = projects.id
            GROUP BY employees.id;";
    $result = mysqli_query($conn, $sql);
    
    if (mysqli_num_rows($result) > 0) {
        print('<table class="table">
                    <thead class="table-header">
                        <tr>
                            <th>Id</th>
                            <th>Employer</th>
                            <th>Projects</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>');
    $i = 1;
        while($row = mysqli_fetch_assoc($result)) {
        print(          '<tr>
                            <td>' . $i . '</td>
                            <td>' . $row["employer"] . '</td>
                            <td>' . $row["pr_id"] . '</td>
                            <td>' . 
                                '<form action="'. $_SERVER['PHP_SELF'] .'" method="post">
                                    <button type="submit" name="delete_emp" class="btn btn-outline-primary btn-sm" value="'. $row['id'] .'">Delete</button>
                                    <button>
                                        <a href="?path=employees&update_emp='. $row['id'].'" type="button" name="submit" class="btn btn-primary btn-sm">Update</a></button>    
                                    </form>' .  
                            '</td>
                        </tr>'); 
                            
    $i++;
        }
        print(     '</tbody>
              </table>
</div>');
    } else {
    echo '0 results';
    }
?>
<!-- Insert employer -->
<form method="POST">
  <input type="text" name="employer" value="<?php echo $row['employer'] ?>" placeholder="Insert new employer name" Required>
  <input type="submit" name="insert_emp" value="Add">
</form>

<?php if(isset($_GET["update_emp"])): ?>
                <?php $id = $_GET["update_emp"]; ?>
           <form method="post" action="<?php echo $_SERVER['PHP_SELF'];?>?path=employees">  
          
<?php 
$sql = 'SELECT employer FROM Employees 
        WHERE id = '. $id;
        $result = mysqli_query($conn, $sql);

        if (mysqli_num_rows($result) > 0) {
            while($row = mysqli_fetch_assoc($result)) {
                echo '<div class="container">
                <div class="row">
                 <div class="col-4">
                <input type="text" name="update_emp" class="form-control" id="employer" placeholder="" value="'. $row['employer'] .'">
                </div>
                </div>
                </div>';
            }
        } 
        ?>

        <div class="container">
            <div class="row">
                <div class="col-4">
                    <select class="form-select" aria-label="Default select example">
                        <option selected>Projects</option>
<?php
                $sql ='SELECT projects.id, projects.project
                       FROM Projects';
                $result = mysqli_query($conn, $sql);
 
                if (mysqli_num_rows($result) > 0) {
                    while($row = mysqli_fetch_assoc($result)) {
                    echo '<option value="'.$row['id'].'">'.$row['projects.project'].'</option>';
                    }
                } else {
                    echo 'no records';
                }
?>
                </div>
            </div>
        </div>

<?php
              if ($_SERVER["REQUEST_METHOD"] == "POST") {
                $update_emp = $_POST['update_emp'];
              }
?>

<?php
    $sql = 'UPDATE employee set id=' . $_POST['id'] .',
                                employer=' . $_POST['employer'] . ',
                                WHERE id=' . $_POST['id'] . ');'
               
?>

        </select>
               <form method="post" action="<?php echo $_SERVER['PHP_SELF'];?>?path=employees"> 
                  <button type="submit" name="update_emp" class="btn btn-primary">Update</button>
                  </form>
    <?php endif; ?>
<?php
    // ----- Disconnection
    mysqli_close($conn);
?>
</body>
</html>