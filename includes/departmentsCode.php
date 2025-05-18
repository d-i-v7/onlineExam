
<div class="container py-5">
    <h2 class="mb-4">Departments Management (AJAX CRUD)</h2>

    <div id="message"></div>

    <form id="deptForm" class="mb-4">
        <input type="hidden" id="dept_id" name="dept_id" value="" />
        <div class="mb-3">
            <label for="name" class="form-label">Department Name</label>
            <input type="text" id="name" name="name" class="form-control" required />
        </div>
        <button type="submit" class="btn btn-primary" id="submitBtn">Add Department</button>
        <button type="button" class="btn btn-secondary" id="cancelBtn" style="display:none;">Cancel</button>
    </form>

    <table class="table table-bordered table-hover bg-white">
        <thead class="table-dark">
            <tr>
                <th>#</th>
                <th>Name</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody id="deptTableBody">
            <!-- Departments will load here -->
        </tbody>
    </table>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const form = document.getElementById('deptForm');
    const nameInput = document.getElementById('name');
    const deptIdInput = document.getElementById('dept_id');
    const submitBtn = document.getElementById('submitBtn');
    const cancelBtn = document.getElementById('cancelBtn');
    const messageDiv = document.getElementById('message');
    const deptTableBody = document.getElementById('deptTableBody');

    function showMessage(message, type = 'success') {
        messageDiv.innerHTML = `<div class="alert alert-${type}">${message}</div>`;
        setTimeout(() => { messageDiv.innerHTML = ''; }, 4000);
    }

    function loadDepartments() {
        fetch('ajax_handler.php?action=list')
            .then(res => res.json())
            .then(data => {
                if(data.status === 'success') {
                    deptTableBody.innerHTML = '';
                    data.data.forEach((dept, index) => {
                        deptTableBody.innerHTML += `
                            <tr>
                                <td>${index + 1}</td>
                                <td>${escapeHtml(dept.name)}</td>
                                <td>
                                    <button class="btn btn-sm btn-warning editBtn" data-id="${dept.id}" data-name="${escapeHtml(dept.name)}">Edit</button>
                                    <button class="btn btn-sm btn-danger deleteBtn" data-id="${dept.id}">Delete</button>
                                </td>
                            </tr>
                        `;
                    });
                    attachEventListeners();
                } else {
                    showMessage(data.message, 'danger');
                }
            });
    }

    function escapeHtml(text) {
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }

    function attachEventListeners() {
        document.querySelectorAll('.editBtn').forEach(btn => {
            btn.addEventListener('click', () => {
                deptIdInput.value = btn.dataset.id;
                nameInput.value = btn.dataset.name;
                submitBtn.textContent = 'Update Department';
                cancelBtn.style.display = 'inline-block';
            });
        });

        document.querySelectorAll('.deleteBtn').forEach(btn => {
            btn.addEventListener('click', () => {
                if (confirm('Are you sure you want to delete this department?')) {
                    deleteDepartment(btn.dataset.id);
                }
            });
        });
    }

    function deleteDepartment(id) {
        const formData = new FormData();
        formData.append('id', id);

        fetch('ajax_handler.php?action=delete', {
            method: 'POST',
            body: formData
        })
        .then(res => res.json())
        .then(data => {
            if (data.status === 'success') {
                showMessage(data.message);
                loadDepartments();
                resetForm();
            } else {
                showMessage(data.message, 'danger');
            }
        });
    }

    function resetForm() {
        deptIdInput.value = '';
        nameInput.value = '';
        submitBtn.textContent = 'Add Department';
        cancelBtn.style.display = 'none';
    }

    cancelBtn.addEventListener('click', () => {
        resetForm();
    });

    form.addEventListener('submit', e => {
        e.preventDefault();

        const id = deptIdInput.value;
        const name = nameInput.value.trim();

        if (!name) {
            showMessage('Department name is required', 'danger');
            return;
        }

        const formData = new FormData();
        formData.append('name', name);
        if (id) {
            formData.append('id', id);
        }

        const action = id ? 'update' : 'create';

        fetch(`ajax_handler.php?action=${action}`, {
            method: 'POST',
            body: formData
        })
        .then(res => res.json())
        .then(data => {
            if (data.status === 'success') {
                showMessage(data.message);
                loadDepartments();
                resetForm();
            } else {
                showMessage(data.message, 'danger');
            }
        });
    });

    // Initial load
    loadDepartments();
});
</script>
