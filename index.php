<?php
session_start();

if (isset($_REQUEST['login'])) {
    //if success or user exists redirect to the calculator page
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "user";

    // Create a connection to the MySQL server
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Check if the connection was successful
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $email = '"' . trim($_REQUEST['email']) . '"';
    $password = '"' . trim($_REQUEST['password']) . '"';

    $sql = "select * from user_details where email = " . $email . " and password = " . $password;

    $result = $conn->query($sql);

    // Check if the query was successful
    if ($result === false) {
        die("Error executing the query: " . $conn->error);
    }

    if ($result->num_rows > 0) {
        $result->close();
        $conn->close();
        $_SESSION['login'] = true;
        header("Location: /mycalci/calculator.html");
        exit;
    } else {
        $_SESSION['login'] = false;
        header("Location: http://localhost/mycalci/welcome.html?wrong=1");
        exit;
    }
} else if (isset($_POST['logout'])) {
    unset($_SESSION['login']);
    header('Content-Type: application/json');
    $output['success'] = 1;
    echo json_encode($output);
    exit();
}
