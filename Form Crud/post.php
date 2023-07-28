<?php
//Data get from database;
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "student";
$conn = new mysqli($servername,$username,$password,$dbname);
 if ($conn -> connect_errno)
 {
    echo "Failed to connect to MySQL: " . $conn -> connect_error;
    exit();
 }
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

//Data Add in database;
if ($_SERVER['REQUEST_METHOD'] === "POST") {
    $data = file_get_contents('php://input');
    $dataArray = json_decode($data, true);
    $hostname = 'localhost';
    $username = 'root';
    $password = '';
    $database = 'student';
    $conn = mysqli_connect($hostname, $username, $password, $database);
    // print_r($dataArray);
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }
    $usertable1 = "stutable";
    $stmt = $conn->prepare("INSERT INTO $usertable1 (`value`) VALUES (?)");
    $query = "SELECT * FROM `stutable`;";
    $result = $conn->query($query);
    if (!$stmt) {
        die("Error preparing the statement: " . $conn->error);
    }
    foreach ($dataArray as $data) {
        $value = $data['value'];
        $stmt->bind_param('s',  $value);
        if (!$stmt->execute()) {
            die("Error executing the query: " . $stmt->error);
        }
    }
    $stmt->close();
    $conn->close();
    $response = array('message' => 'Data received successfully');
    echo json_encode($response);
}
?>