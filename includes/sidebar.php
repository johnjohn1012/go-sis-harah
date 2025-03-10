<style>
.sidebar {
    width: 250px;
    background: var(--topbar-bg);
    box-shadow: 2px 0 5px var(--border-color);
    position: fixed;
    top: 0;
    left: 0;
    height: 100%;
    z-index: 1000;
    transition: transform 0.3s ease, background-color 0.3s ease;
    overflow-y: auto;
}
.sidebar-header {
    height: 60px;
    padding: 0 1rem;
    border-bottom: 1px solid var(--border-color);
    display: flex;
    align-items: center;
    position: sticky;
    top: 0;
    background: var(--topbar-bg);
    z-index: 1;
}
.sidebar-header a {
    color: var(--text-color) !important;
}
.sidebar .nav-link {
    color: var(--text-color);
    padding: 0.7rem 1rem;
    display: flex;
    align-items: center;
    transition: all 0.3s;
}
.sidebar .nav-link:hover {
    background-color: var(--body-bg);
    color: #0d6efd;
}
.sidebar .nav-link.active {
    background-color: var(--body-bg);
    color: #0d6efd;
    font-weight: 500;
}
.sidebar .nav-link i {
    width: 1.5rem;
    margin-right: 0.5rem;
    font-size: 1.1rem;
}
.submenu {
    margin-left: 2.5rem;
    border-left: 1px solid var(--border-color);
    padding-left: 0.5rem;
}
.submenu .nav-link {
    padding: 0.5rem 1rem;
}
@media (max-width: 992px) {
    .sidebar {
        transform: translateX(-100%);
    }
    .sidebar.active {
        transform: translateX(0);
    }
}
</style>

<!-- Sidebar -->
<div class="sidebar">
    <div class="sidebar-header">
        <a class="text-decoration-none" href="<?php echo $role_path; ?>/dashboard.php">
            <h4 class="mb-0 d-flex align-items-center">
                <i class="bi bi-box-seam me-2"></i>
                <span class="fs-6">SIS Harah Rubina Del Dios Farm</span>
            </h4>
        </a>
    </div>

    <div class="nav flex-column mt-3">
        <?php if ($_SESSION['job_name'] !== 'Stock Clerk' && $_SESSION['job_name'] !== 'Cashier'): ?>
        <a class="nav-link <?php echo $current_page === 'dashboard.php' ? 'active' : ''; ?>" 
           href="<?php echo $role_path; ?>/dashboard.php">
            <i class="bi bi-speedometer2"></i>
            <span>Dashboard</span>
        </a>

        <!-- Reports Section -->
        <div class="nav-item">
            <a class="nav-link <?php echo in_array($current_page, ['sales_reports.php', 'inventory_reports.php', 'purchase_reports.php', 'system_logs.php']) ? 'active' : ''; ?>" 
               data-bs-toggle="collapse" 
               href="#reportsSubmenu" 
               role="button">
                <i class="bi bi-file-earmark-text"></i>
                <span>Reports</span>
                <i class="bi bi-chevron-down ms-auto"></i>
            </a>
            <div class="collapse <?php echo in_array($current_page, ['sales_reports.php', 'inventory_reports.php', 'purchase_reports.php', 'system_logs.php']) ? 'show' : ''; ?>" 
                 id="reportsSubmenu">
                <div class="submenu">
                    <a class="nav-link <?php echo $current_page === 'sales_reports.php' ? 'active' : ''; ?>" 
                       href="<?php echo $role_path; ?>/sales_reports.php">
                        <i class="bi bi-graph-up"></i>
                        <span>Sales Reports</span>
                    </a>
                    <a class="nav-link <?php echo $current_page === 'inventory_reports.php' ? 'active' : ''; ?>" 
                       href="<?php echo $role_path; ?>/inventory_reports.php">
                        <i class="bi bi-box-seam"></i>
                        <span>Inventory Reports</span>
                    </a>
                    <a class="nav-link <?php echo $current_page === 'purchase_reports.php' ? 'active' : ''; ?>" 
                       href="<?php echo $role_path; ?>/purchase_reports.php">
                        <i class="bi bi-cart-check"></i>
                        <span>Purchase Reports</span>
                    </a>
                    <a class="nav-link <?php echo $current_page === 'system_logs.php' ? 'active' : ''; ?>" 
                       href="<?php echo $role_path; ?>/system_logs.php">
                        <i class="bi bi-journal-text"></i>
                        <span>System Logs</span>
                    </a>
                </div>
            </div>
        </div>

        <!-- Master Data Section -->
        <div class="nav-item">
            <a class="nav-link <?php echo in_array($current_page, ['product_categories.php', 'material_categories.php', 'supplier_management.php']) ? 'active' : ''; ?>" 
               data-bs-toggle="collapse" 
               href="#masterDataSubmenu" 
               role="button">
                <i class="bi bi-database"></i>
                <span>Master Data</span>
                <i class="bi bi-chevron-down ms-auto"></i>
            </a>
            <div class="collapse <?php echo in_array($current_page, ['product_categories.php', 'material_categories.php', 'supplier_management.php']) ? 'show' : ''; ?>" 
                 id="masterDataSubmenu">
                <div class="submenu">
                    <a class="nav-link <?php echo $current_page === 'product_categories.php' ? 'active' : ''; ?>" 
                       href="<?php echo $role_path; ?>/product_categories.php">
                        <i class="bi bi-tags"></i>
                        <span>Product Categories</span>
                    </a>
                    <a class="nav-link <?php echo $current_page === 'material_categories.php' ? 'active' : ''; ?>" 
                       href="<?php echo $role_path; ?>/material_categories.php">
                        <i class="bi bi-box-seam"></i>
                        <span>Material Categories</span>
                    </a>
                    <a class="nav-link <?php echo $current_page === 'supplier_management.php' ? 'active' : ''; ?>" 
                       href="<?php echo $role_path; ?>/supplier_management.php">
                        <i class="bi bi-truck"></i>
                        <span>Supplier Management</span>
                    </a>
                </div>
            </div>
        </div>

        <!-- Monitoring Section -->
        <div class="nav-item">
            <a class="nav-link <?php echo in_array($current_page, ['activity_logs.php', 'stock_levels.php', 'system_performance.php']) ? 'active' : ''; ?>" 
               data-bs-toggle="collapse" 
               href="#monitoringSubmenu" 
               role="button">
                <i class="bi bi-speedometer"></i>
                <span>Monitoring</span>
                <i class="bi bi-chevron-down ms-auto"></i>
            </a>
            <div class="collapse <?php echo in_array($current_page, ['activity_logs.php', 'stock_levels.php', 'system_performance.php']) ? 'show' : ''; ?>" 
                 id="monitoringSubmenu">
                <div class="submenu">
                    <a class="nav-link <?php echo $current_page === 'activity_logs.php' ? 'active' : ''; ?>" 
                       href="<?php echo $role_path; ?>/activity_logs.php">
                        <i class="bi bi-journal-text"></i>
                        <span>Activity Logs</span>
                    </a>
                    <a class="nav-link <?php echo $current_page === 'stock_levels.php' ? 'active' : ''; ?>" 
                       href="<?php echo $role_path; ?>/stock_levels.php">
                        <i class="bi bi-box-seam"></i>
                        <span>Stock Levels</span>
                    </a>
                    <a class="nav-link <?php echo $current_page === 'system_performance.php' ? 'active' : ''; ?>" 
                       href="<?php echo $role_path; ?>/system_performance.php">
                        <i class="bi bi-graph-up"></i>
                        <span>System Performance</span>
                    </a>
                </div>
            </div>
        </div>
        <?php endif; ?>

        <?php if ($_SESSION['job_name'] === 'Stock Clerk'): ?>
            <!-- Dashboard -->
            <a class="nav-link <?php echo $current_page === 'dashboard.php' ? 'active' : ''; ?>" 
               href="../../roles/stock_clerk/dashboard.php">
                <i class="bi bi-speedometer2"></i>
                <span>Dashboard</span>
            </a>

            <!-- Inventory Section -->
            <div class="nav-item">
                <a class="nav-link <?php echo in_array($current_page, ['raw_materials.php', 'finished_products.php', 'stock_adjustments.php']) ? 'active' : ''; ?>" 
                   data-bs-toggle="collapse" 
                   href="#inventorySubmenu" 
                   role="button">
                    <i class="bi bi-box-seam"></i>
                    <span>Inventory</span>
                    <i class="bi bi-chevron-down ms-auto"></i>
                </a>
                <div class="collapse <?php echo in_array($current_page, ['raw_materials.php', 'finished_products.php', 'stock_adjustments.php']) ? 'show' : ''; ?>" 
                     id="inventorySubmenu">
                    <div class="submenu">
                        <a class="nav-link <?php echo $current_page === 'raw_materials.php' ? 'active' : ''; ?>" 
                           href="../../roles/stock_clerk/raw_materials.php">
                            <i class="bi bi-egg"></i>
                            <span>Raw Materials</span>
                        </a>
                        <a class="nav-link <?php echo $current_page === 'finished_products.php' ? 'active' : ''; ?>" 
                           href="../../roles/stock_clerk/finished_products.php">
                            <i class="bi bi-box"></i>
                            <span>Finished Products</span>
                        </a>
                        <a class="nav-link <?php echo $current_page === 'stock_adjustments.php' ? 'active' : ''; ?>" 
                           href="../../roles/stock_clerk/stock_adjustments.php">
                            <i class="bi bi-sliders"></i>
                            <span>Stock Adjustments</span>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Purchasing Section -->
            <div class="nav-item">
                <a class="nav-link <?php echo in_array($current_page, ['purchase_orders.php', 'receive_orders.php', 'back_orders.php']) ? 'active' : ''; ?>" 
                   data-bs-toggle="collapse" 
                   href="#purchasingSubmenu" 
                   role="button">
                    <i class="bi bi-cart"></i>
                    <span>Purchasing</span>
                    <i class="bi bi-chevron-down ms-auto"></i>
                </a>
                <div class="collapse <?php echo in_array($current_page, ['purchase_orders.php', 'receive_orders.php', 'back_orders.php']) ? 'show' : ''; ?>" 
                     id="purchasingSubmenu">
                    <div class="submenu">
                        <a class="nav-link <?php echo $current_page === 'purchase_orders.php' ? 'active' : ''; ?>" 
                           href="../../roles/stock_clerk/purchase_orders.php">
                            <i class="bi bi-cart-plus"></i>
                            <span>Purchase Orders</span>
                        </a>
                        <a class="nav-link <?php echo $current_page === 'receive_orders.php' ? 'active' : ''; ?>" 
                           href="../../roles/stock_clerk/receive_orders.php">
                            <i class="bi bi-box-seam"></i>
                            <span>Receive Orders</span>
                        </a>
                        <a class="nav-link <?php echo $current_page === 'back_orders.php' ? 'active' : ''; ?>" 
                           href="../../roles/stock_clerk/back_orders.php">
                            <i class="bi bi-clock-history"></i>
                            <span>Back Orders</span>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Supplier Management Section -->
            <div class="nav-item">
                <a class="nav-link <?php echo in_array($current_page, ['suppliers.php', 'returns_management.php']) ? 'active' : ''; ?>" 
                   data-bs-toggle="collapse" 
                   href="#supplierSubmenu" 
                   role="button">
                    <i class="bi bi-truck"></i>
                    <span>Supplier Management</span>
                    <i class="bi bi-chevron-down ms-auto"></i>
                </a>
                <div class="collapse <?php echo in_array($current_page, ['suppliers.php', 'returns_management.php']) ? 'show' : ''; ?>" 
                     id="supplierSubmenu">
                    <div class="submenu">
                        <a class="nav-link <?php echo $current_page === 'suppliers.php' ? 'active' : ''; ?>" 
                           href="../../roles/stock_clerk/suppliers.php">
                            <i class="bi bi-people"></i>
                            <span>Supplier List</span>
                        </a>
                        <a class="nav-link <?php echo $current_page === 'returns_management.php' ? 'active' : ''; ?>" 
                           href="../../roles/stock_clerk/returns_management.php">
                            <i class="bi bi-arrow-return-left"></i>
                            <span>Returns Management</span>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Production Section -->
            <div class="nav-item">
                <a class="nav-link <?php echo in_array($current_page, ['product_recipes.php', 'material_usage.php']) ? 'active' : ''; ?>" 
                   data-bs-toggle="collapse" 
                   href="#productionSubmenu" 
                   role="button">
                    <i class="bi bi-gear"></i>
                    <span>Production</span>
                    <i class="bi bi-chevron-down ms-auto"></i>
                </a>
                <div class="collapse <?php echo in_array($current_page, ['product_recipes.php', 'material_usage.php']) ? 'show' : ''; ?>" 
                     id="productionSubmenu">
                    <div class="submenu">
                        <a class="nav-link <?php echo $current_page === 'product_recipes.php' ? 'active' : ''; ?>" 
                           href="../../roles/stock_clerk/product_recipes.php">
                            <i class="bi bi-book"></i>
                            <span>Product Recipes</span>
                        </a>
                        <a class="nav-link <?php echo $current_page === 'material_usage.php' ? 'active' : ''; ?>" 
                           href="../../roles/stock_clerk/material_usage.php">
                            <i class="bi bi-box-arrow-right"></i>
                            <span>Material Usage</span>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Reports Section -->
            <div class="nav-item">
                <a class="nav-link <?php echo in_array($current_page, ['inventory_status.php', 'low_stock_alerts.php', 'purchase_history.php']) ? 'active' : ''; ?>" 
                   data-bs-toggle="collapse" 
                   href="#reportsSubmenu" 
                   role="button">
                    <i class="bi bi-file-earmark-text"></i>
                    <span>Reports</span>
                    <i class="bi bi-chevron-down ms-auto"></i>
                </a>
                <div class="collapse <?php echo in_array($current_page, ['inventory_status.php', 'low_stock_alerts.php', 'purchase_history.php']) ? 'show' : ''; ?>" 
                     id="reportsSubmenu">
                    <div class="submenu">
                        <a class="nav-link <?php echo $current_page === 'inventory_status.php' ? 'active' : ''; ?>" 
                           href="../../roles/stock_clerk/inventory_status.php">
                            <i class="bi bi-boxes"></i>
                            <span>Inventory Status</span>
                        </a>
                        <a class="nav-link <?php echo $current_page === 'low_stock_alerts.php' ? 'active' : ''; ?>" 
                           href="../../roles/stock_clerk/low_stock_alerts.php">
                            <i class="bi bi-exclamation-triangle"></i>
                            <span>Low Stock Alerts</span>
                        </a>
                        <a class="nav-link <?php echo $current_page === 'purchase_history.php' ? 'active' : ''; ?>" 
                           href="../../roles/stock_clerk/purchase_history.php">
                            <i class="bi bi-clock-history"></i>
                            <span>Purchase History</span>
                        </a>
                    </div>
                </div>
            </div>
        <?php endif; ?>

        <?php if ($_SESSION['job_name'] === 'Cashier'): ?>
            <!-- Dashboard -->
            <a class="nav-link <?php echo $current_page === 'dashboard.php' ? 'active' : ''; ?>" 
               href="../../roles/cashier/dashboard.php">
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
                           href="../../roles/cashier/new_sale.php">
                            <i class="bi bi-cart-plus"></i>
                            <span>New Sale</span>
                        </a>
                        <a class="nav-link <?php echo $current_page === 'order_history.php' ? 'active' : ''; ?>" 
                           href="../../roles/cashier/order_history.php">
                            <i class="bi bi-clock-history"></i>
                            <span>Order History</span>
                        </a>
                        <a class="nav-link <?php echo $current_page === 'pending_orders.php' ? 'active' : ''; ?>" 
                           href="../../roles/cashier/pending_orders.php">
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
                           href="../../roles/cashier/process_payment.php">
                            <i class="bi bi-cash"></i>
                            <span>Process Payment</span>
                        </a>
                        <a class="nav-link <?php echo $current_page === 'payment_history.php' ? 'active' : ''; ?>" 
                           href="../../roles/cashier/payment_history.php">
                            <i class="bi bi-clock-history"></i>
                            <span>Payment History</span>
                        </a>
                        <a class="nav-link <?php echo $current_page === 'refunds.php' ? 'active' : ''; ?>" 
                           href="../../roles/cashier/refunds.php">
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
                           href="../../roles/cashier/customer_list.php">
                            <i class="bi bi-person-lines-fill"></i>
                            <span>Customer List</span>
                        </a>
                        <a class="nav-link <?php echo $current_page === 'qr_registration.php' ? 'active' : ''; ?>" 
                           href="../../roles/cashier/qr_registration.php">
                            <i class="bi bi-qr-code-scan"></i>
                            <span>QR Registration</span>
                        </a>
                        <a class="nav-link <?php echo $current_page === 'customer_history.php' ? 'active' : ''; ?>" 
                           href="../../roles/cashier/customer_history.php">
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
                        <a class="nav-link <?php echo $current_page === 'daily_sales.php' ? 'active' : ''; ?>" 
                           href="../../roles/cashier/daily_sales.php">
                            <i class="bi bi-calendar-check"></i>
                            <span>Daily Sales</span>
                        </a>
                        <a class="nav-link <?php echo $current_page === 'payment_summary.php' ? 'active' : ''; ?>" 
                           href="../../roles/cashier/payment_summary.php">
                            <i class="bi bi-credit-card"></i>
                            <span>Payment Summary</span>
                        </a>
                        <a class="nav-link <?php echo $current_page === 'customer_transactions.php' ? 'active' : ''; ?>" 
                           href="../../roles/cashier/customer_transactions.php">
                            <i class="bi bi-graph-up"></i>
                            <span>Customer Transactions</span>
                        </a>
                    </div>
                </div>
            </div>
        <?php endif; ?>

        <?php if ($_SESSION['job_name'] === 'Main Admin'): ?>
            <!-- Dashboard -->
            <a class="nav-link <?php echo $current_page === 'dashboard.php' ? 'active' : ''; ?>" 
               href="../../roles/main_admin/dashboard.php">
                <i class="bi bi-speedometer2"></i>
                <span>Dashboard</span>
            </a>

            <!-- User Management Section -->
            <div class="nav-item">
                <a class="nav-link <?php echo in_array($current_page, ['employee_list.php', 'user_accounts.php', 'create_user.php', 'create_employee.php', 'edit_employee.php']) ? 'active' : ''; ?>" 
                   data-bs-toggle="collapse" 
                   href="#userManagementSubmenu" 
                   role="button">
                    <i class="bi bi-people"></i>
                    <span>User Management</span>
                    <i class="bi bi-chevron-down ms-auto"></i>
                </a>
                <div class="collapse <?php echo in_array($current_page, ['employee_list.php', 'user_accounts.php', 'create_user.php', 'create_employee.php', 'edit_employee.php']) ? 'show' : ''; ?>" 
                     id="userManagementSubmenu">
                    <div class="submenu">
                        <a class="nav-link <?php echo $current_page === 'employee_list.php' ? 'active' : ''; ?>" 
                           href="../../roles/main_admin/employee_list.php">
                            <i class="bi bi-person-badge"></i>
                            <span>Employees</span>
                        </a>
                        <a class="nav-link <?php echo $current_page === 'user_accounts.php' ? 'active' : ''; ?>" 
                           href="../../roles/main_admin/user_accounts.php">
                            <i class="bi bi-person"></i>
                            <span>Users</span>
                        </a>
                        <a class="nav-link <?php echo $current_page === 'create_user.php' ? 'active' : ''; ?>" 
                           href="../../roles/main_admin/create_user.php">
                            <i class="bi bi-person-plus"></i>
                            <span>Create User</span>
                        </a>
                        <a class="nav-link <?php echo $current_page === 'create_employee.php' ? 'active' : ''; ?>" 
                           href="../../roles/main_admin/create_employee.php">
                            <i class="bi bi-person-plus-fill"></i>
                            <span>Create Employee</span>
                        </a>
                        <a class="nav-link <?php echo $current_page === 'edit_employee.php' ? 'active' : ''; ?>" 
                           href="../../roles/main_admin/edit_employee.php">
                            <i class="bi bi-pencil-square"></i>
                            <span>Edit Employee</span>
                        </a>
                    </div>
                </div>
            </div>

            <!-- System Settings Section -->
            <div class="nav-item">
                <a class="nav-link <?php echo in_array($current_page, ['system_settings.php', 'backup_restore.php', 'logs.php']) ? 'active' : ''; ?>" 
                   data-bs-toggle="collapse" 
                   href="#systemSubmenu" 
                   role="button">
                    <i class="bi bi-gear"></i>
                    <span>System Settings</span>
                    <i class="bi bi-chevron-down ms-auto"></i>
                </a>
                <div class="collapse <?php echo in_array($current_page, ['system_settings.php', 'backup_restore.php', 'logs.php']) ? 'show' : ''; ?>" 
                     id="systemSubmenu">
                    <div class="submenu">
                        <a class="nav-link <?php echo $current_page === 'system_settings.php' ? 'active' : ''; ?>" 
                           href="../../roles/main_admin/system_settings.php">
                            <i class="bi bi-sliders"></i>
                            <span>System Settings</span>
                        </a>
                        <a class="nav-link <?php echo $current_page === 'backup_restore.php' ? 'active' : ''; ?>" 
                           href="../../roles/main_admin/backup_restore.php">
                            <i class="bi bi-database-check"></i>
                            <span>Backup & Restore</span>
                        </a>
                        <a class="nav-link <?php echo $current_page === 'logs.php' ? 'active' : ''; ?>" 
                           href="../../roles/main_admin/logs.php">
                            <i class="bi bi-journal-text"></i>
                            <span>System Logs</span>
                        </a>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div> 