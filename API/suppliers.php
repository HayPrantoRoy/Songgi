<?php
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");

include 'connection.php';

$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
    case 'GET':
        $sql = "SELECT * FROM suppliers ORDER BY id DESC";
        $result = $conn->query($sql);
        $suppliers = [];
        while ($row = $result->fetch_assoc()) {
            $suppliers[] = $row;
        }
        echo json_encode($suppliers);
        break;

    case 'POST':
        $data = json_decode(file_get_contents("php://input"), true);
        $name = $conn->real_escape_string($data['name']);
        $mobile_no = $conn->real_escape_string($data['mobile_no']);
        $address = isset($data['address']) ? $conn->real_escape_string($data['address']) : '';
        $is_active = isset($data['is_active']) ? intval($data['is_active']) : 1; // Get from request
        $user_id = 1; // Default user ID
        
        $sql = "INSERT INTO suppliers (name, mobile_no, address, is_active, user_id, create_date, update_date) 
                VALUES ('$name', '$mobile_no', '$address', $is_active, $user_id, NOW(), NOW())";
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
        
        $sql = "UPDATE suppliers SET name='$name', mobile_no='$mobile_no', address='$address', 
                is_active=$is_active, update_date=NOW() WHERE id=$id";
        $conn->query($sql);
        echo json_encode(["success" => true]);
        break;

    case 'DELETE':
        parse_str(file_get_contents("php://input"), $data);
        $id = intval($data['id']);
        $sql = "DELETE FROM suppliers WHERE id=$id"; // Permanent deletion
        $conn->query($sql);
        echo json_encode(["success" => true]);
        break;

    default:
        echo json_encode(["error" => "Invalid request method"]);
}
$conn->close();
?>