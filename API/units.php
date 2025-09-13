<?php
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");

include 'connection.php';

$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
    case 'GET':
        $sql = "SELECT id, name FROM units ORDER BY id DESC";
        $result = $conn->query($sql);
        $units = [];
        while ($row = $result->fetch_assoc()) {
            $units[] = $row;
        }
        echo json_encode($units);
        break;

    case 'POST':
        $data = json_decode(file_get_contents("php://input"), true);
        $name = $conn->real_escape_string($data['name']);
        $sql = "INSERT INTO units (name, create_date, update_date) VALUES ('$name', NOW(), NOW())";
        $conn->query($sql);
        echo json_encode(["success" => true, "id" => $conn->insert_id]);
        break;

    case 'PUT':
        $data = json_decode(file_get_contents("php://input"), true);
        $id = intval($data['id']);
        $name = $conn->real_escape_string($data['name']);
        $sql = "UPDATE units SET name='$name', update_date=NOW() WHERE id=$id";
        $conn->query($sql);
        echo json_encode(["success" => true]);
        break;

    case 'DELETE':
        parse_str(file_get_contents("php://input"), $data);
        $id = intval($data['id']);
        $sql = "DELETE FROM units WHERE id=$id";
        $conn->query($sql);
        echo json_encode(["success" => true]);
        break;

    default:
        echo json_encode(["error" => "Invalid request method"]);
}
$conn->close();
