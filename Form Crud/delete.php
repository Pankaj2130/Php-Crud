<?php

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $jsonData = file_get_contents('php://input');
    $dataArray = json_decode($jsonData, true);
   if (isset($dataArray['id'])) {
    $id = $dataArray['id'];
   }
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "student";
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    } 
    $conn = new mysqli($servername, $username, $password, $dbname);
    $sql = "DELETE FROM stutable WHERE id = $id";
    if ($conn->query($sql) !== TRUE) {  
        $sql = "select * from stutable";
        $result = ($conn->query($sql));
       $row = [];
        if ($result->num_rows > 0) {
           $row = $result->fetch_all(MYSQLI_ASSOC); 
           $jsonData = json_encode($row);
       }
       else{
           $jsonData = '[]';
       }
    }
    else{
        echo "deleting data with id :- $id" . $conn->error;
    }
    $conn->close();
    echo "Data delete successfully";
  }








?>