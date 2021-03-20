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
    $dbname = "project"; 

        //Create connection
    $conn = mysqli_connect($servername, $username, $password, $dbname);

        //Check the connection
    if(!$conn){
        die("Connection failed:" . mysqli_connect_error());
    }
    
    // ------ Table data to associative array
    $sql = "SELECT id, project FROM projects";
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
        while($row = mysqli_fetch_assoc($result)) {
        print(          '<tr>
                            <td>' . $row["id"] . '</td>
                            <td>' . $row["project"] . '</td>
                        </tr>');
        }
        print(     '</tbody>
              </table>');
    } else {
    echo '0 results';
    }

    // ----- Disconnection
    mysqli_close($conn);
?>
</body>
</html>