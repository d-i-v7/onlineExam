<?php
include 'includes/conn.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $department_id = intval($_POST['department_id'] ?? 0);
    $user_ids = $_POST['user_ids'] ?? [];

    if ($department_id && !empty($user_ids)) {
        foreach ($user_ids as $uid) {
            $uid = intval($uid);
            $conn->query("UPDATE users SET department_id = $department_id WHERE id = $uid");
        }
        header("Location: assign.php?success=1");
        exit();
    } else {
        header("Location: assign.php?error=1");
        exit();
    }
}
?>
