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
        $id = $_POST['delete_pro'];
        if (!empty($id)) {
          $sql = 'DELETE FROM projects WHERE id = ?';
          $stmt = $conn->prepare($sql);
          $stmt->bind_param('i', $id);
          $res = $stmt->execute();
          $stmt->close();
          mysqli_close($conn);
          header("Location: ?path=projects");
          die();
        }
    }
?>
<?php
    // -------- Insert
    if (isset($_POST['insert_pro'])) {
        $project = $_POST['project'];
        if (empty($project)) {
            echo "Project name is empty";
        } else {
            $sql = 'INSERT INTO projects (project) VALUES (?)';
            $stmt = $conn->prepare($sql);
            $stmt->bind_param('s', $project);
            $res = $stmt->execute();
            $stmt->close();
            mysqli_close($conn);
            header("Location: ?path=projects");
            die();
        }
    }
?>
<?php
    // ------ Table data to associative array
    $sql = "SELECT projects.id, projects.project FROM projects";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) > 0) {
        print('<table>
                    <thead>
                        <tr>
                            <th>Id</th>
                            <th>Project</th>
                            <th>Employer</th>
                            <th>Actions</th>
                    </thead>
                    <tbody>');
    $i = 1;
        while($row = mysqli_fetch_assoc($result)) {
        print(          '<tr>
                            <td>' . $i . '</td>
                            <td>' . $row["project"] . '</td>
                            <td>' . $row["employer"] . '</td>
                            <td>' . 
                                '<form action="'. $_SERVER['PHP_SELF'] .'" method="post">
                                    <button type="submit" name="delete_pro" class="btn btn-outline-primary btn-sm" value="'. $row['id'] .'">Delete</button>
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
<!-- Insert project -->
<form method="POST">
  <input type="text" name="project" value="<?php echo $row['project'] ?>" placeholder="Insert new project name" Required>
  <input type="submit" name="insert_pro" value="Add">
</form>
<?php
    // ----- Disconnection
    mysqli_close($conn);
?>
</body>
</html>