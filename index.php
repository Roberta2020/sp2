<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
<header>
<a href= 'index.php'>Employees</a>
<a href= 'projects.php'>Projects</a>
</header>

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
        $id = $_POST['submit'];
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
    // --------- Update
    // if(isset($_POST['update_emp'])){
    //     if ( $_POST['employer'] == '') {
    //         print 'Employer name can not be empty. Enter employer name';
    //    } else {
    //         $sql = 'INSERT INTO employees (employer) VALUES (?)';
    //         $stmt = $conn->prepare($sql);
    //         $stmt->bind_param('s', $employer);
    //         $res = $stmt->execute();
    //         $stmt->close();
    //         mysqli_close($conn);
    //         header("Location: ?path=employees");
    //         die();
    //    }
    // }
    //   if (isset($_POST['update-emp'])) {
        // $stmt = $conn->prepare("UPDATE emploees SET `firstname`=?, `lastname`=?, `pid`=? WHERE `id`=?");
        // $stmt->bind_param("ssii", $firstname, $lastname, $pid, $eid);
        // $firstname = $_POST['firstname'];
        // $lastname = $_POST['lastname'];
        // $pid = $_POST['project-id'] === '' ? null : $_POST['project-id'];
        // $eid = $_POST['employee-id'];
        // $stmt->execute();
    //   }
?>
<?php
    // ------ Table data
    $sql = "SELECT employees.id, employees.employer, projects.project 
            FROM employees
            LEFT JOIN projects
            ON employees.projects_id = projects.id;";
    $result = mysqli_query($conn, $sql);
    
    if (mysqli_num_rows($result) > 0) {
        print('<table>
                    <thead>
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
                            <td>' . $row["project"] . '</td>
                            <td>' . 
                                '<form action="'. $_SERVER['PHP_SELF'] .'" method="post">
                                    <button type="submit" name="submit" class="btn btn-outline-primary btn-sm" value="'. $row['id'] .'">Delete</button>
                                    <button type="button" name="submit" class="btn btn-primary btn-sm"">Update</button>
                                </form>' .  
                            '</td>
                        </tr>'); 
                            
    $i++;
        }
        print(     '</tbody>
              </table>');
    } else {
    echo '0 results';
    }
?>
<!-- Insert employer -->
<form method="POST">
  <input type="text" name="employer" value="<?php echo $row['employer'] ?>" placeholder="Insert new employer name" Required>
  <input type="submit" name="insert_emp" value="Add">
</form>
<?php
    // ----- Disconnection
    mysqli_close($conn);
?>
</body>
</html>