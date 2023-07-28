<?php
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $jsonData = file_get_contents('php://input');
    $dataArray = json_decode($jsonData, true);
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "student";
    $conn = new mysqli($servername, $username, $password, $dbname);
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    foreach ($dataArray as $data) {
        $id = $data["id"]; 
        $value = $data["value"]; 
        $sql = "UPDATE stutable SET value='$value' WHERE id='$id'";
        if ($conn->query($sql) !== TRUE) {  
            echo "Error updating record: " . $conn->error;
        }
    }
    $conn->close();
    echo "Data update successfully";
  }
?>