<?php
// API/register.php
include("connection.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST["name"];
    $company = $_POST["company_name"];
    $email = $_POST["email"];
    $mobile = $_POST["mobile_no"];
    $password = $_POST["password"];

    $sql = "INSERT INTO users (name, company_name, email, mobile_no, password) 
            VALUES ('$name', '$company', '$email', '$mobile', '$password')";
    if (mysqli_query($conn, $sql)) {
        echo "Registration successful!";
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}
?>
