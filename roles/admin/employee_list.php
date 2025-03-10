<?php
session_start();
require_once '../../config/database.php';
require_once '../../includes/header.php';

// Check if user is Admin
if (!isset($_SESSION['user_id']) || $_SESSION['job_name'] !== 'Admin') {
    header("Location: ../../auth/login.php");
    exit();
}

// Mock data for employees
$employees = [
    [
        'employee_id' => 'EMP001',
        'first_name' => 'John',
        'last_name' => 'Doe',
        'email' => 'john.doe@example.com',
        'phone' => '09123456789',
        'job_name' => 'Stock Clerk',
        'department' => 'Inventory',
        'status' => 'Active',
        'hire_date' => '2024-01-15',
        'last_login' => '2024-03-20 14:30:00'
    ],
    [
        'employee_id' => 'EMP002',
        'first_name' => 'Jane',
        'last_name' => 'Smith',
        'email' => 'jane.smith@example.com',
        'phone' => '09187654321',
        'job_name' => 'Cashier',
        'department' => 'Sales',
        'status' => 'Active',
        'hire_date' => '2024-02-01',
        'last_login' => '2024-03-20 15:45:00'
    ],
    [
        'employee_id' => 'EMP003',
        'first_name' => 'Mike',
        'last_name' => 'Johnson',
        'email' => 'mike.johnson@example.com',
        'phone' => '09111111111',
        'job_name' => 'Admin',
        'department' => 'Administration',
        'status' => 'Active',
        'hire_date' => '2024-02-15',
        'last_login' => '2024-03-20 16:20:00'
    ]
];

$status_badges = [
    'Active' => 'success',
    'Inactive' => 'danger',
    'On Leave' => 'warning'
];
?>

<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col">
            <h2 class="mb-4">Employee List</h2>
        </div>
        <div class="col text-end">
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addEmployeeModal">
                <i class="bi bi-plus-circle me-2"></i>Add New Employee
            </button>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="row align-items-center">
                        <div class="col">
                            <h5 class="mb-0">Employees</h5>
                        </div>
                        <div class="col-auto">
                            <div class="input-group">
                                <input type="text" class="form-control" placeholder="Search employees...">
                                <button class="btn btn-outline-secondary" type="button">
                                    <i class="bi bi-search"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Employee ID</th>
                                    <th>Name</th>
                                    <th>Contact</th>
                                    <th>Job Title</th>
                                    <th>Department</th>
                                    <th>Status</th>
                                    <th>Hire Date</th>
                                    <th>Last Login</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($employees as $employee): ?>
                                    <tr>
                                        <td><?php echo $employee['employee_id']; ?></td>
                                        <td><?php echo htmlspecialchars($employee['first_name'] . ' ' . $employee['last_name']); ?></td>
                                        <td>
                                            <div><?php echo htmlspecialchars($employee['email']); ?></div>
                                            <small class="text-muted"><?php echo htmlspecialchars($employee['phone']); ?></small>
                                        </td>
                                        <td><?php echo htmlspecialchars($employee['job_name']); ?></td>
                                        <td><?php echo htmlspecialchars($employee['department']); ?></td>
                                        <td>
                                            <span class="badge bg-<?php echo $status_badges[$employee['status']]; ?>">
                                                <?php echo $employee['status']; ?>
                                            </span>
                                        </td>
                                        <td><?php echo date('Y-m-d', strtotime($employee['hire_date'])); ?></td>
                                        <td><?php echo date('Y-m-d H:i', strtotime($employee['last_login'])); ?></td>
                                        <td>
                                            <button class="btn btn-sm btn-info" title="View Details">
                                                <i class="bi bi-eye"></i>
                                            </button>
                                            <button class="btn btn-sm btn-primary" title="Edit">
                                                <i class="bi bi-pencil"></i>
                                            </button>
                                            <button class="btn btn-sm btn-danger" title="Delete">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add Employee Modal -->
<div class="modal fade" id="addEmployeeModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add New Employee</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label">First Name</label>
                            <input type="text" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Last Name</label>
                            <input type="text" class="form-control" required>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Email</label>
                            <input type="email" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Phone</label>
                            <input type="tel" class="form-control" required>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Job Title</label>
                            <select class="form-select" required>
                                <option value="">Select Job Title</option>
                                <option value="Admin">Admin</option>
                                <option value="Stock Clerk">Stock Clerk</option>
                                <option value="Cashier">Cashier</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Department</label>
                            <select class="form-select" required>
                                <option value="">Select Department</option>
                                <option value="Administration">Administration</option>
                                <option value="Inventory">Inventory</option>
                                <option value="Sales">Sales</option>
                            </select>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Hire Date</label>
                            <input type="date" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Status</label>
                            <select class="form-select" required>
                                <option value="Active">Active</option>
                                <option value="Inactive">Inactive</option>
                                <option value="On Leave">On Leave</option>
                            </select>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Address</label>
                        <textarea class="form-control" rows="3"></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary">Save Employee</button>
            </div>
        </div>
    </div>
</div>

<?php require_once '../../includes/footer.php'; ?> 