<?php
session_start();
require("includes/conn.php");

// Hubi in admin user yahay
if (!isset($_SESSION['isActive']) || $_SESSION['activeRole'] !== 'Admin') {
    echo json_encode(['success' => false, 'msg' => 'Unauthorized']);
    exit();
}

$action = $_POST['action'] ?? '';

if ($action == 'fetch_all') {
    $sql = "SELECT users.id, users.name, users.email, users.role, users.status, users.department_id, departments.name AS department_name
            FROM users LEFT JOIN departments ON users.department_id = departments.id";
    $result = mysqli_query($conn, $sql);
    $users = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $users[] = $row;
    }
    echo json_encode($users);
    exit();
}

if ($action == 'fetch_single') {
    $id = intval($_POST['id']);
    $sql = "SELECT * FROM users WHERE id = $id LIMIT 1";
    $result = mysqli_query($conn, $sql);
    if ($user = mysqli_fetch_assoc($result)) {
        echo json_encode($user);
    } else {
        echo json_encode(null);
    }
    exit();
}

if ($action == 'save_user') {
    // Collect and sanitize
    $id = intval($_POST['id'] ?? 0);
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $role = mysqli_real_escape_string($conn, $_POST['role']);
    $status = mysqli_real_escape_string($conn, $_POST['status']);
    $department_id = intval($_POST['department_id']);
    $password = $_POST['password'] ?? '';


// Validate email unique except for current id
$checkEmailSQL = "SELECT id FROM users WHERE email = '$email' AND id != $id LIMIT 1";
$res = mysqli_query($conn, $checkEmailSQL);
if (mysqli_num_rows($res) > 0) {
    echo json_encode(['success' => false, 'msg' => 'Email already exists.']);
    exit();
}

if ($id > 0) {
    // Update existing user
    $updateSQL = "UPDATE users SET 
        name='$name', email='$email', role='$role', status='$status', department_id=$department_id";

    if (!empty($password)) {
        $passwordHash = password_hash($password, PASSWORD_DEFAULT);
        $updateSQL .= ", password='$passwordHash'";
    }

    $updateSQL .= " WHERE id=$id LIMIT 1";
    if (mysqli_query($conn, $updateSQL)) {
        echo json_encode(['success' => true, 'msg' => 'User updated successfully.']);
    } else {
        echo json_encode(['success' => false, 'msg' => 'Failed to update user.']);
    }
} else {
    // Insert new user - password is required
    if (empty($password)) {
        echo json_encode(['success' => false, 'msg' => 'Password is required for new user.']);
        exit();
    }
    $passwordHash = password_hash($password, PASSWORD_DEFAULT);
    $insertSQL = "INSERT INTO users (name, email, password, role, status, department_id) VALUES 
        ('$name', '$email', '$passwordHash', '$role', '$status', $department_id)";
    if (mysqli_query($conn, $insertSQL)) {
        echo json_encode(['success' => true, 'msg' => 'User added successfully.']);
    } else {
        echo json_encode(['success' => false, 'msg' => 'Failed to add user.']);
    }
}
exit();
}

if ($action == 'delete_user') {
$id = intval($_POST['id']);
if ($id > 0) {
$delSQL = "DELETE FROM users WHERE id = $id LIMIT 1";
if (mysqli_query($conn, $delSQL)) {
echo json_encode(['success' => true, 'msg' => 'User deleted successfully.']);
} else {
echo json_encode(['success' => false, 'msg' => 'Failed to delete user.']);
}
} else {
echo json_encode(['success' => false, 'msg' => 'Invalid user ID.']);
}
exit();
}

echo json_encode(['success' => false, 'msg' => 'Invalid action.']);
exit();