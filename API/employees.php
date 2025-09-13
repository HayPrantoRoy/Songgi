<?php
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");

include 'connection.php';

$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
    case 'GET':
        $sql = "SELECT * FROM employees ORDER BY id DESC";
        $result = $conn->query($sql);
        $employees = [];
        while ($row = $result->fetch_assoc()) {
            $employees[] = $row;
        }
        echo json_encode($employees);
        break;

    case 'POST':
    $data = json_decode(file_get_contents("php://input"), true);
    $name = $conn->real_escape_string($data['name']);
    $mobile_no = $conn->real_escape_string($data['mobile_no']);
    $address = isset($data['address']) ? $conn->real_escape_string($data['address']) : '';
    $is_active = isset($data['is_active']) ? intval($data['is_active']) : 1;
    $user_id = 0; // Default user ID
    
    // Set a default role_id (e.g., 3 for Staff)
    $role_id = 0;
    
    $sql = "INSERT INTO employees (role_id, name, mobile_no, address, is_active, user_id, create_date, update_date) 
            VALUES ($role_id, '$name', '$mobile_no', '$address', $is_active, $user_id, NOW(), NOW())";
    $conn->query($sql);
    echo json_encode(["success" => true, "id" => $conn->insert_id]);
    break;

case 'PUT':
    $data = json_decode(file_get_contents("php://input"), true);
    $id = intval($data['id']);
    $name = $conn->real_escape_string($data['name']);
    $mobile_no = $conn->real_escape_string($data['mobile_no']);
    $address = $conn->real_escape_string($data['address']);
    $is_active = intval($data['is_active']);
    
    // Keep the existing role_id, don't update it
    $sql = "UPDATE employees SET name='$name', mobile_no='$mobile_no', address='$address', 
            is_active=$is_active, update_date=NOW() WHERE id=$id";
    $conn->query($sql);
    echo json_encode(["success" => true]);
    break;

    case 'DELETE':
        parse_str(file_get_contents("php://input"), $data);
        $id = intval($data['id']);
        $sql = "DELETE FROM employees WHERE id=$id"; // Permanent deletion
        $conn->query($sql);
        echo json_encode(["success" => true]);
        break;

    default:
        echo json_encode(["error" => "Invalid request method"]);
}
$conn->close();
?>