<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', '1');

function sendOTP($email, $otp)
{
    ini_set("SMTP", "localhost");
    ini_set("smtp_port", "587");

    $to = $email;
    $from = 'mailertest992@gmail.com';
    $fromName = 'Krishna Nakkum';

    $subject = "Krishna's OTP";

    $message = "Hi, \n please use the following otp for verification \n $otp";

    // Additional headers 
    $headers = 'From: ' . $fromName . '<' . $from . '>';

    // Send email 
    if (mail($to, $subject, $message, $headers)) {
        return true;
    } else {
        return false;
    }
}

if (isset($_REQUEST['action']) and trim($_REQUEST['action']) === 'getOTP') {

    $email = trim($_REQUEST['email']);
    $password = trim($_REQUEST['password']);

    $check = checkifUSerAlreadyExists();

    $otp = generateOTP();

    if (sendOTP($email, $otp)) {
        $_SESSION['email_otp'] = [
            'email' =>  $email,
            'otp' => $otp
        ];

        header('Content-Type: application/json');
        $output['success'] = true;
        echo json_encode($output);
        exit();
    } else {
        $_SESSION['email_otp'] = null;
        header('Content-Type: application/json');
        $output['success'] = false;
        echo json_encode($output);
        exit();
    }
}

if (isset($_REQUEST['action']) and trim($_REQUEST['action']) === 'verifyOTP') {
    $email = trim($_REQUEST['email']);
    $otp = trim($_REQUEST['otp']);
    $password = trim($_REQUEST['password']);

    if (isset($_SESSION['email_otp'])) {
        $emailValue = $_SESSION['email_otp']['email'];
        $otpValue = $_SESSION['email_otp']['otp'];

        if ($email === $emailValue and $otp === $otpValue) {

            //create account
            createAccount($email, $password);
        } else {
            header('Content-Type: application/json');
            $output['success'] = false;
            echo json_encode($output);
            exit();
        }
    }
}

// Function to generate a random 6-digit OTP
function generateOTP()
{
    return str_pad(mt_rand(0, 999999), 6, '0', STR_PAD_LEFT);
}


function createAccount()
{

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

    $sql = 'insert into user_details values (' . $email . ',' . $password . ')';

    if ($conn->query($sql) === true) {

        header('Content-Type: application/json');
        $output['success'] = true;
        echo json_encode($output);
        exit();
    }
}


function checkifUSerAlreadyExists()
{
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

    //user exists already
    if ($result->num_rows > 0) {
        $_SESSION['login'] = false;
        header('Content-Type: application/json');
        $output['success'] = true;
        $output['exists'] = true;
        echo json_encode($output);
        exit();
    }
}
