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
// session_start();
// require("includes/conn.php");


?>


<div class="container mt-5">
  <h2>User Management (Admin)</h2>
  <div id="message"></div>
  <button class="btn btn-primary mb-3" id="addUserBtn">Add New User</button>
  
  <table class="table table-bordered table-striped" id="usersTable">
    <thead class="table-dark">
      <tr>
        <th>#ID</th>
        <th>Name</th>
        <th>Email</th>
        <th>Role</th>
        <th>Status</th>
        <th>Department</th>
        <th>Actions</th>
      </tr>
    </thead>
    <tbody>
      <!-- Users will load here by AJAX -->
    </tbody>
  </table>
</div>

<!-- Modal for Add/Edit User -->
<div class="modal fade" id="userModal" tabindex="-1" aria-labelledby="userModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <form id="userForm">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="userModalLabel">Add/Edit User</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <input type="hidden" name="id" id="userId" />
          <div class="mb-3">
            <label for="name" class="form-label">Name</label>
            <input type="text" class="form-control" id="name" name="name" required />
          </div>
          <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" class="form-control" id="email" name="email" required />
          </div>
          <div class="mb-3">
            <label for="password" class="form-label">Password <small>(Leave empty to keep unchanged)</small></label>
            <input type="password" class="form-control" id="password" name="password" />
          </div>
          <div class="mb-3">
            <label for="role" class="form-label">Role</label>
            <select class="form-select" id="role" name="role" required>
              <option value="">-- Select Role --</option>
              <option value="admin">Admin</option>
              <option value="teacher">Teacher</option>
              <option value="student">Student</option>
            </select>
          </div>
          <div class="mb-3">
            <label for="status" class="form-label">Status</label>
            <select class="form-select" id="myStatus" name="status" required>
              <option value="Active">Active</option>
              <option value="Inactive">Inactive</option>
            </select>
          </div>
          <div class="mb-3">
            <label for="department" class="form-label">Department</label>
            <select class="form-select" id="department" name="department_id" required>
              <option value="">-- Select Department --</option>
              <?php
              // Load departments from database
              $deps = mysqli_query($conn, "SELECT id, name FROM departments");
              while($dep = mysqli_fetch_assoc($deps)) {
                  echo "<option value='{$dep['id']}'>" . htmlspecialchars($dep['name']) . "</option>";
              }
              ?>
            </select>
          </div>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-primary" id="saveUserBtn">Save</button>
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        </div>
      </div>
    </form>
  </div>
</div>


<script
  src="https://code.jquery.com/jquery-3.7.1.min.js"
  integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo="
  crossorigin="anonymous"></script>
<script>
$(document).ready(function() {

  // Load users on page load
  loadUsers();

  function loadUsers() {
    $.ajax({
      url: 'user-actions.php',
      method: 'POST',
      data: { action: 'fetch_all' },
      dataType: 'json',
      success: function(response) {
        let rows = '';
        $.each(response, function(i, user) {
          rows += `<tr>
            <td>${user.id}</td>
            <td>${user.name}</td>
            <td>${user.email}</td>
            <td>${capitalize(user.role)}</td>
            <td><span class="badge ${user.status === 'Active' ? 'bg-success' : 'bg-secondary'}">${user.status}</span></td>
            <td>${user.department_name || 'N/A'}</td>
            <td>
              <button class="btn btn-sm btn-warning editBtn" data-id="${user.id}">Edit</button>
              <button class="btn btn-sm btn-danger deleteBtn" data-id="${user.id}">Delete</button>
            </td>
          </tr>`;
        });
        $('#usersTable tbody').html(rows);
      }
    });
  }

  // Capitalize first letter helper
  function capitalize(s) {
    return s.charAt(0).toUpperCase() + s.slice(1);
  }

  // Open modal to add new user
  $('#addUserBtn').click(function() {
    $('#userForm')[0].reset();
    $('#userId').val('');
    $('#userModalLabel').text('Add New User');
    $('#userModal').modal('show');
  });

  // Open modal for edit user
  $(document).on('click', '.editBtn', function() {
    const id = $(this).data('id');
    $.ajax({
      url: 'user-actions.php',
      method: 'POST',
      data: { action: 'fetch_single', id: id },
      dataType: 'json',
      success: function(user) {
        $('#userModalLabel').text('Edit User');
        $('#userId').val(user.id);
        $('#name').val(user.name);
        $('#email').val(user.email);
        $('#role').val(user.role);
        $('#status').val(user.status);
        $('#department').val(user.department_id);
        $('#password').val('');
        $('#userModal').modal('show');
      }
    });
  });

  // Save user (Add or Edit)
  $('#userForm').submit(function(e) {
    e.preventDefault();
    const formData = $(this).serialize() + '&action=save_user';
    $.ajax({
      url: 'user-actions.php',
      method: 'POST',
      data: formData,
      dataType: 'json',
      success: function(resp) {
        if (resp.success) {
          $('#message').html(`<div class="alert alert-success">${resp.msg}</div>`);
          $('#userModal').modal('hide');
          loadUsers();
        } else {
          $('#message').html(`<div class="alert alert-danger">${resp.msg}</div>`);
        }
      }
    });
  });

  // Delete user
  $(document).on('click', '.deleteBtn', function() {
    if (!confirm('Are you sure you want to delete this user?')) return;
    const id = $(this).data('id');
    $.ajax({
      url: 'user-actions.php',
      method: 'POST',
      data: { action: 'delete_user', id: id },
      dataType: 'json',
      success: function(resp) {
        if (resp.success) {
          $('#message').html(`<div class="alert alert-success">${resp.msg}</div>`);
          loadUsers();
        } else {
          $('#message').html(`<div class="alert alert-danger">${resp.msg}</div>`);
        }
      }
    });
  });

});
</script>



<?php } else if($cUser['role'] == "Teacher")
{

} ?>
