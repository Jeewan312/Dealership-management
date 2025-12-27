<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <!-- Your CSS file -->
    <link rel="stylesheet" href="../design/css/admin.css">
</head>
<body>

<div class="app-layout">

    <!-- Sidebar -->
    <aside class="sidebar-nav">
        <div class="logo-area">
            <i class="fas fa-motorcycle"></i> BIKE SVC
        </div>

        <nav class="main-menu">
            <ul>
                <li class="is-active">
                    <a href="#"><i class="fas fa-home"></i> Dashboard</a>
                </li>

                <div class="menu-category">Bookings</div>
                <li>
                    <a href="#"><i class="fas fa-clock"></i> Pending
                        <span class="badge">5</span>
                    </a>
                </li>
                <li>
                    <a href="#"><i class="fas fa-check"></i> Confirmed</a>
                </li>

                <div class="menu-category">Management</div>
                <li>
                    <a href="user.php"><i class="fas fa-users"></i> Users</a>
                </li>
                <li>
                    <a href="#"><i class="fas fa-tools"></i> Services</a>
                </li>

                <li class="logout">
                    <a href="#"><i class="fas fa-sign-out-alt"></i> Logout</a>
                </li>
            </ul>
        </nav>
    </aside>

    <!-- Main Content -->
    <div class="main-content-area">

        <!-- Top Bar -->
        <div class="top-bar">
            <div style="display:flex;align-items:center;">
                <button class="mobile-menu-btn" onclick="toggleMenu()">
                    <i class="fas fa-bars"></i>
                </button>
                <div class="page-title">Dashboard</div>
            </div>

            <div class="user-info">
                <i class="fas fa-user-circle"></i>
                <span>Admin</span>
                <i class="fas fa-caret-down"></i>
            </div>
        </div>

        <!-- Dashboard -->
        <div class="dashboard-panel">
            <h1>Welcome Back 👋</h1>
            <p class="subtitle">Here’s what’s happening today</p>

            <!-- Stats -->
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-icon" style="background:#3498db;">
                        <i class="fas fa-calendar"></i>
                    </div>
                    <div class="stat-info">
                        <h3>24</h3>
                        <p>Total Bookings</p>
                    </div>
                </div>

                <div class="stat-card">
                    <div class="stat-icon" style="background:#28a745;">
                        <i class="fas fa-check"></i>
                    </div>
                    <div class="stat-info">
                        <h3>18</h3>
                        <p>Completed</p>
                    </div>
                </div>

                <div class="stat-card">
                    <div class="stat-icon" style="background:#f39c12;">
                        <i class="fas fa-clock"></i>
                    </div>
                    <div class="stat-info">
                        <h3>6</h3>
                        <p>Pending</p>
                    </div>
                </div>
            </div>

            <!-- Table Section -->
            <div class="section-card">
                <div class="section-header">
                    <h2><i class="fas fa-list"></i> Recent Bookings</h2>
                    <a href="#" class="view-all">View All</a>
                </div>

                <div class="table-container">
                    <table>
                        <thead>
                            <tr>
                                <th>User</th>
                                <th>Service</th>
                                <th>Date</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Jeevan</td>
                                <td>Bike Repair</td>
                                <td>2025-01-20</td>
                                <td>Pending</td>
                                <td class="action-buttons">
                                    <a href="#" class="btn-confirm">
                                        <i class="fas fa-check"></i> Confirm
                                    </a>
                                    <a href="#" class="btn-cancel">
                                        <i class="fas fa-times"></i> Cancel
                                    </a>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
</div>

<div class="overlay" onclick="toggleMenu()"></div>

<script>
function toggleMenu() {
    document.querySelector('.sidebar-nav').classList.toggle('active');
    document.querySelector('.overlay').classList.toggle('active');
}
</script>

</body>
</html>
