<?php
session_start();
require_once '../../config/database.php';
require_once '../../includes/header.php';

// Check if user is Cashier
if (!isset($_SESSION['user_id']) || $_SESSION['job_name'] !== 'Cashier') {
    header("Location: ../../auth/login.php");
    exit();
}

// Mock data for recently registered customers
$recent_registrations = [
    [
        'customer_id' => 'CUST004',
        'name' => 'Alice Brown',
        'registration_date' => '2024-03-07 16:00:00',
        'qr_code' => 'QR-004'
    ],
    [
        'customer_id' => 'CUST005',
        'name' => 'Bob Wilson',
        'registration_date' => '2024-03-07 15:45:00',
        'qr_code' => 'QR-005'
    ]
];
?>

<div class="container-fluid py-4">
    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col">
            <h2>QR Registration</h2>
            <p class="text-muted">Register new customers with QR codes</p>
        </div>
        <div class="col text-end">
            <button type="button" class="btn btn-outline-secondary" onclick="window.location.href='customer_list.php'">
                <i class="bi bi-arrow-left me-2"></i>Back to Customer List
            </button>
        </div>
    </div>

    <div class="row">
        <!-- QR Scanner Section -->
        <div class="col-md-6">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">QR Code Scanner</h5>
                </div>
                <div class="card-body">
                    <div class="text-center mb-4">
                        <div id="qrScanner" class="mb-3">
                            <!-- QR Scanner will be initialized here -->
                            <div class="border rounded p-4 bg-light">
                                <i class="bi bi-qr-code-scan display-1"></i>
                                <p class="text-muted mb-0">Camera feed will appear here</p>
                            </div>
                        </div>
                        <button type="button" class="btn btn-primary" onclick="startScanner()">
                            <i class="bi bi-camera me-2"></i>Start Scanner
                        </button>
                        <button type="button" class="btn btn-outline-secondary" onclick="stopScanner()">
                            <i class="bi bi-stop-circle me-2"></i>Stop Scanner
                        </button>
                    </div>
                    <div class="alert alert-info">
                        <i class="bi bi-info-circle me-2"></i>
                        Position the QR code within the camera frame to scan
                    </div>
                </div>
            </div>

            <!-- Manual QR Entry -->
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Manual QR Entry</h5>
                </div>
                <div class="card-body">
                    <form id="manualQrForm">
                        <div class="mb-3">
                            <label class="form-label">QR Code</label>
                            <div class="input-group">
                                <input type="text" class="form-control" id="qrCode" 
                                       placeholder="Enter QR code manually">
                                <button type="button" class="btn btn-outline-secondary" onclick="validateQrCode()">
                                    <i class="bi bi-check-circle me-2"></i>Validate
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Registration Form -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Customer Registration</h5>
                </div>
                <div class="card-body">
                    <form id="registrationForm">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">First Name</label>
                                <input type="text" class="form-control" id="firstName" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Last Name</label>
                                <input type="text" class="form-control" id="lastName" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Email</label>
                                <input type="email" class="form-control" id="email" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Phone</label>
                                <input type="tel" class="form-control" id="phone" required>
                            </div>
                            <div class="col-12">
                                <label class="form-label">Address</label>
                                <textarea class="form-control" id="address" rows="2"></textarea>
                            </div>
                            <div class="col-12">
                                <div class="form-check">
                                    <input type="checkbox" class="form-check-input" id="termsAccepted" required>
                                    <label class="form-check-label" for="termsAccepted">
                                        I agree to the terms and conditions
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="mt-4">
                            <button type="submit" class="btn btn-primary" id="registerButton" disabled>
                                <i class="bi bi-person-plus me-2"></i>Register Customer
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Recent Registrations -->
            <div class="card mt-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">Recent Registrations</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Customer ID</th>
                                    <th>Name</th>
                                    <th>QR Code</th>
                                    <th>Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($recent_registrations as $registration): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($registration['customer_id']); ?></td>
                                        <td><?php echo htmlspecialchars($registration['name']); ?></td>
                                        <td><?php echo htmlspecialchars($registration['qr_code']); ?></td>
                                        <td><?php echo date('M d, Y H:i', strtotime($registration['registration_date'])); ?></td>
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

<!-- Success Modal -->
<div class="modal fade" id="successModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Registration Successful</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center">
                <i class="bi bi-check-circle-fill text-success display-1 mb-3"></i>
                <h5>Customer Registered Successfully</h5>
                <p class="mb-0">Customer ID: <span id="successCustomerId"></span></p>
                <p>QR Code: <span id="successQrCode"></span></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" onclick="printQrCode()">
                    <i class="bi bi-printer me-2"></i>Print QR Code
                </button>
            </div>
        </div>
    </div>
</div>

<script>
let successModal;
let scanner = null;

document.addEventListener('DOMContentLoaded', function() {
    successModal = new bootstrap.Modal(document.getElementById('successModal'));
    
    // Initialize form validation
    document.getElementById('registrationForm').addEventListener('input', validateForm);
});

function startScanner() {
    // Here you would typically initialize the QR code scanner
    alert('Starting QR scanner...');
    // Example: scanner = new QRScanner('qrScanner');
}

function stopScanner() {
    // Here you would typically stop the QR code scanner
    alert('Stopping QR scanner...');
    // Example: scanner.stop();
}

function validateQrCode() {
    const qrCode = document.getElementById('qrCode').value;
    if (qrCode) {
        // Here you would typically validate the QR code with the server
        alert('Validating QR code: ' + qrCode);
        // If valid, enable the registration form
        document.getElementById('registerButton').disabled = false;
    }
}

function validateForm() {
    const form = document.getElementById('registrationForm');
    const registerButton = document.getElementById('registerButton');
    registerButton.disabled = !form.checkValidity();
}

document.getElementById('registrationForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    // Here you would typically send the registration data to the server
    const customerData = {
        firstName: document.getElementById('firstName').value,
        lastName: document.getElementById('lastName').value,
        email: document.getElementById('email').value,
        phone: document.getElementById('phone').value,
        address: document.getElementById('address').value,
        qrCode: document.getElementById('qrCode').value
    };
    
    // Mock successful registration
    document.getElementById('successCustomerId').textContent = 'CUST006';
    document.getElementById('successQrCode').textContent = 'QR-006';
    successModal.show();
    
    // Reset form
    this.reset();
    document.getElementById('registerButton').disabled = true;
});

function printQrCode() {
    alert('Printing QR code...');
    // Implement QR code printing functionality
}
</script>

<?php require_once '../../includes/footer.php'; ?> 