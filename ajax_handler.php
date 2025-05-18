<?php

header('Content-Type: application/json');

// DB connection
require("includes/conn.php");

$action = $_GET['action'] ?? '';

switch ($action) {
    case 'list':
        $result = $conn->query("SELECT * FROM departments ORDER BY id DESC");
        $departments = [];
        while ($row = $result->fetch_assoc()) {
            $departments[] = $row;
        }
        echo json_encode(['status' => 'success', 'data' => $departments]);
        break;

    case 'create':
        $name = trim($_POST['name'] ?? '');
        if (empty($name)) {
            echo json_encode(['status' => 'error', 'message' => 'Department name is required']);
            break;
        }
        $stmt = $conn->prepare("INSERT INTO departments (name) VALUES (?)");
        $stmt->bind_param("s", $name);
        if ($stmt->execute()) {
            echo json_encode(['status' => 'success', 'message' => 'Department added successfully']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Failed to add department']);
        }
        $stmt->close();
        break;

    case 'update':
        $id = intval($_POST['id'] ?? 0);
        $name = trim($_POST['name'] ?? '');
        if ($id <= 0 || empty($name)) {
            echo json_encode(['status' => 'error', 'message' => 'Invalid data']);
            break;
        }
        $stmt = $conn->prepare("UPDATE departments SET name = ? WHERE id = ?");
        $stmt->bind_param("si", $name, $id);
        if ($stmt->execute()) {
            echo json_encode(['status' => 'success', 'message' => 'Department updated']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Update failed']);
        }
        $stmt->close();
        break;

    case 'delete':
        $id = intval($_POST['id'] ?? 0);
        if ($id <= 0) {
            echo json_encode(['status' => 'error', 'message' => 'Invalid ID']);
            break;
        }
        $stmt = $conn->prepare("DELETE FROM departments WHERE id = ?");
        $stmt->bind_param("i", $id);
        if ($stmt->execute()) {
            echo json_encode(['status' => 'success', 'message' => 'Department deleted']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Delete failed']);
        }
        $stmt->close();
        break;

    default:
        echo json_encode(['status' => 'error', 'message' => 'Invalid action']);
}

$conn->close();
