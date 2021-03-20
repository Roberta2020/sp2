<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
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
    $sql = "SELECT id, employer FROM employees";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) > 0) {
        while($row = mysqli_fetch_assoc($result)) {
        echo "id: " . $row["id"]. " - Employer: " . $row["employer"]. "<br>";
        }
    } else {
    echo "0 results";
    }

    // ----- Disconnection
    mysqli_close($conn);
?>
</body>
</html>