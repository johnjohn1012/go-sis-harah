<?php
session_start();
require_once '../../config/database.php';

// Check if user is Cashier
if (!isset($_SESSION['user_id']) || $_SESSION['job_name'] !== 'Cashier') {
    header("Location: ../../auth/login.php");
    exit();
}

// Set role path for cashier
$role_path = '../../roles/cashier';
$current_page = basename($_SERVER['PHP_SELF']);
?>

<div class="sidebar">
    <div class="sidebar-header">
        <a class="text-decoration-none" href="<?php echo $role_path; ?>/dashboard.php">
            <h4 class="mb-0 d-flex align-items-center">
                <i class="bi bi-cash-stack me-2"></i>
                <span class="fs-6">Cashier Panel</span>
            </h4>
        </a>
    </div>

    <div class="nav flex-column mt-3">
        <!-- Dashboard -->
        <a class="nav-link <?php echo $current_page === 'dashboard.php' ? 'active' : ''; ?>" 
           href="<?php echo $role_path; ?>/dashboard.php">
            <i class="bi bi-speedometer2"></i>
            <span>Dashboard</span>
        </a>

        <!-- Sales Section -->
        <div class="nav-item">
            <a class="nav-link <?php echo in_array($current_page, ['new_sale.php', 'order_history.php', 'pending_orders.php']) ? 'active' : ''; ?>" 
               data-bs-toggle="collapse" 
               href="#salesSubmenu" 
               role="button">
                <i class="bi bi-cart"></i>
                <span>Sales</span>
                <i class="bi bi-chevron-down ms-auto"></i>
            </a>
            <div class="collapse <?php echo in_array($current_page, ['new_sale.php', 'order_history.php', 'pending_orders.php']) ? 'show' : ''; ?>" 
                 id="salesSubmenu">
                <div class="submenu">
                    <a class="nav-link <?php echo $current_page === 'new_sale.php' ? 'active' : ''; ?>" 
                       href="<?php echo $role_path; ?>/new_sale.php">
                        <i class="bi bi-plus-circle"></i>
                        <span>New Sale</span>
                    </a>
                    <a class="nav-link <?php echo $current_page === 'order_history.php' ? 'active' : ''; ?>" 
                       href="<?php echo $role_path; ?>/order_history.php">
                        <i class="bi bi-clock-history"></i>
                        <span>Order History</span>
                    </a>
                    <a class="nav-link <?php echo $current_page === 'pending_orders.php' ? 'active' : ''; ?>" 
                       href="<?php echo $role_path; ?>/pending_orders.php">
                        <i class="bi bi-hourglass-split"></i>
                        <span>Pending Orders</span>
                    </a>
                </div>
            </div>
        </div>

        <!-- Payments Section -->
        <div class="nav-item">
            <a class="nav-link <?php echo in_array($current_page, ['process_payment.php', 'payment_history.php', 'refunds.php']) ? 'active' : ''; ?>" 
               data-bs-toggle="collapse" 
               href="#paymentsSubmenu" 
               role="button">
                <i class="bi bi-credit-card"></i>
                <span>Payments</span>
                <i class="bi bi-chevron-down ms-auto"></i>
            </a>
            <div class="collapse <?php echo in_array($current_page, ['process_payment.php', 'payment_history.php', 'refunds.php']) ? 'show' : ''; ?>" 
                 id="paymentsSubmenu">
                <div class="submenu">
                    <a class="nav-link <?php echo $current_page === 'process_payment.php' ? 'active' : ''; ?>" 
                       href="<?php echo $role_path; ?>/process_payment.php">
                        <i class="bi bi-cash"></i>
                        <span>Process Payment</span>
                    </a>
                    <a class="nav-link <?php echo $current_page === 'payment_history.php' ? 'active' : ''; ?>" 
                       href="<?php echo $role_path; ?>/payment_history.php">
                        <i class="bi bi-receipt"></i>
                        <span>Payment History</span>
                    </a>
                    <a class="nav-link <?php echo $current_page === 'refunds.php' ? 'active' : ''; ?>" 
                       href="<?php echo $role_path; ?>/refunds.php">
                        <i class="bi bi-arrow-counterclockwise"></i>
                        <span>Refunds</span>
                    </a>
                </div>
            </div>
        </div>

        <!-- Customer Management Section -->
        <div class="nav-item">
            <a class="nav-link <?php echo in_array($current_page, ['customer_list.php', 'qr_registration.php', 'customer_history.php']) ? 'active' : ''; ?>" 
               data-bs-toggle="collapse" 
               href="#customerSubmenu" 
               role="button">
                <i class="bi bi-people"></i>
                <span>Customer Management</span>
                <i class="bi bi-chevron-down ms-auto"></i>
            </a>
            <div class="collapse <?php echo in_array($current_page, ['customer_list.php', 'qr_registration.php', 'customer_history.php']) ? 'show' : ''; ?>" 
                 id="customerSubmenu">
                <div class="submenu">
                    <a class="nav-link <?php echo $current_page === 'customer_list.php' ? 'active' : ''; ?>" 
                       href="<?php echo $role_path; ?>/customer_list.php">
                        <i class="bi bi-person-lines-fill"></i>
                        <span>Customer List</span>
                    </a>
                    <a class="nav-link <?php echo $current_page === 'qr_registration.php' ? 'active' : ''; ?>" 
                       href="<?php echo $role_path; ?>/qr_registration.php">
                        <i class="bi bi-qr-code"></i>
                        <span>QR Registration</span>
                    </a>
                    <a class="nav-link <?php echo $current_page === 'customer_history.php' ? 'active' : ''; ?>" 
                       href="<?php echo $role_path; ?>/customer_history.php">
                        <i class="bi bi-clock-history"></i>
                        <span>Customer History</span>
                    </a>
                </div>
            </div>
        </div>

        <!-- Reports Section -->
        <div class="nav-item">
            <a class="nav-link <?php echo in_array($current_page, ['daily_sales.php', 'payment_summary.php', 'customer_transactions.php']) ? 'active' : ''; ?>" 
               data-bs-toggle="collapse" 
               href="#reportsSubmenu" 
               role="button">
                <i class="bi bi-file-earmark-text"></i>
                <span>Reports</span>
                <i class="bi bi-chevron-down ms-auto"></i>
            </a>
            <div class="collapse <?php echo in_array($current_page, ['daily_sales.php', 'payment_summary.php', 'customer_transactions.php']) ? 'show' : ''; ?>" 
                 id="reportsSubmenu">
                <div class="submenu">
                    <a class="nav-link <?php echo $current_page === 'new_sale.php' ? 'active' : ''; ?>" 
                       href="<?php echo $role_path; ?>/new_sale.php">
                        <i class="bi bi-graph-up"></i>
                        <span>Daily Sales</span>
                    </a>
                    <a class="nav-link <?php echo $current_page === 'payment_summary.php' ? 'active' : ''; ?>" 
                       href="<?php echo $role_path; ?>/payment_summary.php">
                        <i class="bi bi-cash-stack"></i>
                        <span>Payment Summary</span>
                    </a>
                    <a class="nav-link <?php echo $current_page === 'customer_transactions.php' ? 'active' : ''; ?>" 
                       href="<?php echo $role_path; ?>/customer_transactions.php">
                        <i class="bi bi-person-vcard"></i>
                        <span>Customer Transactions</span>
                    </a>
                </div>
            </div>
        </div>

        <!-- Logout -->
        <a class="nav-link" href="../../auth/logout.php">
            <i class="bi bi-box-arrow-right"></i>
            <span>Logout</span>
        </a>
    </div>
</div> 