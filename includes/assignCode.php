<div class="container py-5">
  <?php if (isset($_GET['success'])): ?>
    <div class="alert alert-success">Users successfully assigned to department.</div>
  <?php endif; ?>

  <?php if (isset($_GET['error'])): ?>
    <div class="alert alert-danger">Please select users and a department.</div>
  <?php endif; ?>

  <h4 class="mb-4">Select Users (Teachers or Students)</h4>

  <!-- Filters & Search -->
  <div class="row mb-3">
    <div class="col-md-4">
      <select id="filterOption" class="form-select">
        <option value="all">All Users</option>
        <option value="have">Have Departments</option>
        <option value="no">Don't Have Departments</option>
      </select>
    </div>
    <div class="col-md-4">
      <select id="departmentFilter" class="form-select">
        <option selected disabled>Filter by Department</option>
        <option value="">All</option>
        <?php
        $deptFilter = $conn->query("SELECT * FROM departments");
        while ($d = $deptFilter->fetch_assoc()):
        ?>
          <option value="<?= htmlspecialchars($d['name']) ?>"><?= htmlspecialchars($d['name']) ?></option>
        <?php endwhile; ?>
      </select>
    </div>
    <div class="col-md-4">
      <input type="text" id="searchInput" class="form-control" placeholder="Search by name or email...">
    </div>
  </div>

  <form id="assignForm" method="post" action="assign_submit.php">
    <table class="table table-bordered bg-white" id="userTable">
      <thead>
        <tr>
          <th><input type="checkbox" id="selectAll"></th>
          <th>Name</th>
          <th>Email</th>
          <th>Role</th>
          <th>Department</th>
        </tr>
      </thead>
      <tbody>
        <?php
        $users = $conn->query("SELECT u.*, d.name AS department_name FROM users u LEFT JOIN departments d ON u.department_id = d.id WHERE u.role IN ('Teacher', 'Student')");
        $hasUsers = false;
        while ($user = $users->fetch_assoc()):
          $hasUsers = true;
        ?>
        <tr>
          <td><input type="checkbox" name="user_ids[]" value="<?= $user['id'] ?>"></td>
          <td><?= htmlspecialchars($user['name']) ?></td>
          <td><?= htmlspecialchars($user['email']) ?></td>
          <td><?= htmlspecialchars($user['role']) ?></td>
          <td><?= $user['department_name'] ?? '—' ?></td>
        </tr>
        <?php endwhile; ?>
        <?php if (!$hasUsers): ?>
          <tr id="noResultRow">
            <td colspan="5" class="text-center text-muted">No users found.</td>
          </tr>
        <?php endif; ?>
      </tbody>
    </table>

    <!-- Button to trigger modal -->
    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#departmentModal">
      Assign Department
    </button>

    <!-- Modal -->
    <div class="modal fade" id="departmentModal" tabindex="-1" aria-labelledby="departmentModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="departmentModalLabel">Choose Department</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <select name="department_id" class="form-select" required>
              <option selected disabled>Select Department</option>
              <?php
              $depts = $conn->query("SELECT * FROM departments");
              while ($dept = $depts->fetch_assoc()):
              ?>
                <option value="<?= $dept['id'] ?>"><?= htmlspecialchars($dept['name']) ?></option>
              <?php endwhile; ?>
            </select>
          </div>
          <div class="modal-footer">
            <button type="submit" class="btn btn-success">Assign</button>
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
          </div>
        </div>
      </div>
    </div>
  </form>
</div>

<script>
  // Select all checkbox
  document.getElementById("selectAll").onclick = function() {
    document.querySelectorAll('input[name="user_ids[]"]').forEach(cb => cb.checked = this.checked);
  };

  // Filter + search logic
  const searchInput = document.getElementById("searchInput");
  const filterOption = document.getElementById("filterOption");
  const departmentFilter = document.getElementById("departmentFilter");
  const tableRows = document.querySelectorAll("#userTable tbody tr");
  const noResultRow = document.getElementById("noResultRow");

  function filterUsers() {
    const searchTerm = searchInput.value.toLowerCase();
    const filterVal = filterOption.value;
    const deptVal = departmentFilter.value.toLowerCase();

    let visibleCount = 0;

    tableRows.forEach(row => {
      const tds = row.querySelectorAll("td");
      const name = tds[1]?.textContent.toLowerCase();
      const email = tds[2]?.textContent.toLowerCase();
      const dept = tds[4]?.textContent.toLowerCase();

      const hasDept = dept !== '—';

      const matchSearch = name.includes(searchTerm) || email.includes(searchTerm);
      const matchFilter =
        filterVal === "all" ||
        (filterVal === "have" && hasDept) ||
        (filterVal === "no" && !hasDept);

      const matchDept = deptVal === "" || dept === deptVal;

      if (matchSearch && matchFilter && matchDept) {
        row.style.display = "";
        visibleCount++;
      } else {
        row.style.display = "none";
      }
    });

    // Show or hide the no result message
    if (noResultRow) noResultRow.style.display = visibleCount === 0 ? "" : "none";
  }

  searchInput.addEventListener("input", filterUsers);
  filterOption.addEventListener("change", filterUsers);
  departmentFilter.addEventListener("change", filterUsers);
</script>
