<?php
if($cUser['role'] == "Admin") {
    // DB connection assumed in $conn

    // Query counts
    $totalUsers = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM users"))['total'];
    $totalStudents = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM users WHERE role='student'"))['total'];
    $totalTeachers = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM users WHERE role='teacher'"))['total'];
    // $totalExams = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM exams"))['total'];
    $activeUsers = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM users WHERE status='active'"))['total'];
    $inactiveUsers = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM users WHERE status='inactive'"))['total'];
?>
<div class="row">
    <div class="col-12">
        <div class="card widget-inline">
            <div class="card-body p-0">
                <div class="row g-0">

                    <div class="col-sm-6 col-xl-4">
                        <div class="card shadow-none m-0">
                            <div class="card-body text-center">
                                <i class="dripicons-user text-muted" style="font-size: 24px;"></i>
                                <h3><span><?= $totalUsers ?></span></h3>
                                <p class="text-muted font-15 mb-0">Total Users</p>
                            </div>
                        </div>
                    </div>

                    <div class="col-sm-6 col-xl-4 border-start">
                        <div class="card shadow-none m-0">
                            <div class="card-body text-center">
                                <i class="dripicons-user text-muted" style="font-size: 24px;"></i>
                                <h3><span><?= $totalStudents ?></span></h3>
                                <p class="text-muted font-15 mb-0">Total Students</p>
                            </div>
                        </div>
                    </div>

                    <div class="col-sm-6 col-xl-4 border-start">
                        <div class="card shadow-none m-0">
                            <div class="card-body text-center">
                                <i class="dripicons-user text-muted" style="font-size: 24px;"></i>
                                <h3><span><?= $totalTeachers ?></span></h3>
                                <p class="text-muted font-15 mb-0">Total Teachers</p>
                            </div>
                        </div>
                    </div>

                    <div class="col-sm-6 col-xl-4 border-start mt-3">
                        <div class="card shadow-none m-0">
                            <div class="card-body text-center">
                                <i class="dripicons-document text-muted" style="font-size: 24px;"></i>
                                <h3><span>20</span></h3>
                                <p class="text-muted font-15 mb-0">Total Exams</p>
                            </div>
                        </div>
                    </div>

                    <div class="col-sm-6 col-xl-4 border-start mt-3">
                        <div class="card shadow-none m-0">
                            <div class="card-body text-center">
                                <i class="dripicons-media-play text-success" style="font-size: 24px;"></i>
                                <h3><span><?= $activeUsers ?></span></h3>
                                <p class="text-muted font-15 mb-0">Active Users</p>
                            </div>
                        </div>
                    </div>

                    <div class="col-sm-6 col-xl-4 border-start mt-3">
                        <div class="card shadow-none m-0">
                            <div class="card-body text-center">
                                <i class="dripicons-media-pause text-danger" style="font-size: 24px;"></i>
                                <h3><span><?= $inactiveUsers ?></span></h3>
                                <p class="text-muted font-15 mb-0">Inactive Users</p>
                            </div>
                        </div>
                    </div>

                </div> <!-- end row -->
            </div>
        </div> <!-- end card-box-->
    </div> <!-- end col-->
</div>
<?php
$message = "";

// Handle insert or update
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    $role = $_POST['role'];
    $status = $_POST['status'];
    $id = $_POST['id'] ?? '';

    if (empty($name) || empty($email) || empty($role) || empty($status) || ($id == '' && empty($password))) {
        $message = "<div class='alert alert-danger'>All fields are required.</div>";
    } else {
        if ($id) {
            if (!empty($password)) {
                $hashed = password_hash($password, PASSWORD_DEFAULT);
                $stmt = $conn->prepare("UPDATE users SET name=?, email=?, password=?, role=?, status=? WHERE id=?");
                $stmt->bind_param("sssssi", $name, $email, $hashed, $role, $status, $id);
            } else {
                $stmt = $conn->prepare("UPDATE users SET name=?, email=?, role=?, status=? WHERE id=?");
                $stmt->bind_param("ssssi", $name, $email, $role, $status, $id);
            }
            $stmt->execute();
            $message = "<div class='alert alert-success'>User updated successfully.</div>";
        } else {
            $check = $conn->prepare("SELECT * FROM users WHERE email=?");
            $check->bind_param("s", $email);
            $check->execute();
            $result = $check->get_result();

            if ($result->num_rows > 0) {
                $message = "<div class='alert alert-warning'>Email already taken.</div>";
            } else {
                $hashed = password_hash($password, PASSWORD_DEFAULT);
                $stmt = $conn->prepare("INSERT INTO users (name, email, password, role, status) VALUES (?, ?, ?, ?, ?)");
                $stmt->bind_param("sssss", $name, $email, $hashed, $role, $status);
                $stmt->execute();
                $message = "<div class='alert alert-success'>User added successfully.</div>";
            }
        }
    }
}

// Handle delete




// Fetch single user for edit
$edit_user = null;
if (isset($_GET['edit'])) {
    $id = $_GET['edit'];
    $result = $conn->query("SELECT * FROM users WHERE id=$id");
    $edit_user = $result->fetch_assoc();
}

// Fetch all users
$users = $conn->query("SELECT * FROM users ORDER BY id DESC");
?>


<div class="container my-5">
    <div class="row">
        <div class="col-md-5">
            <?= $message ?>
            <?php // Hubi haddii ID la helay
if (isset($_GET['id'])) {
    $id = intval($_GET['id']);

    $sql = "DELETE FROM users WHERE id = $id";

    if ($conn->query($sql) === TRUE) {
            ?>
  <div class="alert alert-success">User deleted successfully.</div>
            <?php
    } else {
        echo "Error deleting user: " . $conn->error;
    }
} else {
    // echo "ID not provided!";
}
              ?>
         

            <form method="POST" class="bg-white p-4 rounded shadow">
                <h4 class="mb-3"><?= $edit_user ? 'Edit User' : 'Add New User' ?></h4>
                <input type="hidden" name="id" value="<?= $edit_user['id'] ?? '' ?>">
                <input class="form-control my-2" type="text" name="name" placeholder="Full Name" value="<?= $edit_user['name'] ?? '' ?>">
                <input class="form-control my-2" type="email" name="email" placeholder="Email" value="<?= $edit_user['email'] ?? '' ?>">
                <input class="form-control my-2" type="password" name="password" placeholder="Password <?= $edit_user ? '(leave blank to keep unchanged)' : '' ?>">
                <select name="role" class="form-select my-2">
                    <option disabled <?= !$edit_user ? 'selected' : '' ?>>Select Role</option>
                    <?php foreach (["Admin", "Teacher", "Student"] as $r): ?>
                        <option value="<?= $r ?>" <?= isset($edit_user['role']) && $edit_user['role'] == $r ? 'selected' : '' ?>><?= $r ?></option>
                    <?php endforeach; ?>
                </select>

                <div class="my-2">
                    <label class="form-label d-block">Status:</label>
                    <?php foreach (["Active", "Inactive"] as $s): ?>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="status" value="<?= $s ?>" id="<?= $s ?>" <?= isset($edit_user['status']) && $edit_user['status'] == $s ? 'checked' : (!$edit_user && $s == 'Active' ? 'checked' : '') ?>>
                            <label class="form-check-label" for="<?= $s ?>"><?= $s ?></label>
                        </div>
                    <?php endforeach; ?>
                </div>

                <button type="submit" name="save" class="btn btn-primary w-100">Save</button>
            </form>
        </div>

        <div class="col-md-7">
            <div class="card shadow">
                <div class="card-header bg-dark text-white text-center">
                    <h4>User List</h4>
                </div>
                <div class="card-body">
                    <table class="table table-hover table-bordered">
                        <thead class="table-dark">
                            <tr>
                                <th>#</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Role</th>
                                <th>Status</th>
                                <th colspan="2">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php $i = 1; while($row = $users->fetch_assoc()): ?>
                            <tr>
                                <td><?= $i++ ?></td>
                                <td><?= htmlspecialchars($row['name']) ?></td>
                                <td><?= htmlspecialchars($row['email']) ?></td>
                                <td><?= $row['role'] ?></td>
                                <td><?= $row['status'] ?></td>
                                <td><a href="dashboard.php?edit=<?= $row['id'] ?>" class="btn btn-sm btn-warning">Edit</a></td>
                                <td><a href="dashboard.php?id=<?php echo $row['id']; ?>" onclick="return confirm('Are you sure?')" class="btn btn-sm btn-danger">Delete</a></td>
                            </tr>
                        <?php endwhile ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function confirmDelete(id) {
    Swal.fire({
        title: 'Are you sure?',
        text: "You want to delete this user?",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Yes, delete it!'
    }).then((result) => {
        if (result.isConfirmed) {
            window.location = `dashboard.php?delete=${id}`;
        }
    });
}
</script>

<?php } ?>
