<?php
session_start();

if (!isset($_SESSION['login'])) {
    header('Content-Type: application/json');
    $output['replace'] = 1;
    echo json_encode($output);
    exit();
} else if (isset($_POST['data'])) {
    $argument = escapeshellarg($_POST['data']);

    # path to Python 3.11 executable
    $pythonExecutable = 'C:\Users\Krishna\AppData\Local\Programs\Python\Python311\python.exe';
    # path to actual python script
    $pythonScript = 'C:\xampp\htdocs\mycalci\calculate.py';

    $command = '"' . $pythonExecutable . '" "' . $pythonScript . '" ' . $argument;

    exec($command, $output, $return_var);

    header('Content-Type: application/json');
    echo json_encode($output);
    exit();
} else {
    $output['msg'] = 'success';
    header('Content-Type: application/json');
    echo json_encode($output);
    exit();
}
