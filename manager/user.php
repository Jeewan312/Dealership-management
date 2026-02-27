<?php
// 1. Database Connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "dealership"; // Update this to your actual database name

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// 2. Fetch Users from the database
$sql = "SELECT id, name, email, phone, address, user_type FROM users";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" href="../design/css/admin.css">
    <meta charset="UTF-8">
    <title>Admin Dashboard - Users</title>
    <style>
        
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Segoe UI', sans-serif; }
        body { background: #f4f6f9; min-height: 100vh; width: 100vw; overflow-x: hidden; }
        .dashboard { background: #ffffff; padding: 25px 30px; width: 100%; min-height: 100vh; }
        .dashboard h2 { margin-bottom: 20px; color: #1e293b; font-size: 24px; }
        .user-table { width: 100%; border-collapse: collapse; background: #fff; border-radius: 8px; overflow: hidden; }
        .user-table th { background: #1e293b; color: #ffffff; padding: 14px; text-align: left; font-size: 15px; }
        .user-table td { padding: 14px; border-bottom: 1px solid #e5e7eb; color: #334155; font-size: 15px; }
        .user-table tr:hover { background: #f1f5f9; }
        .badge-admin { color: #1e293b; font-weight: bold; text-transform: capitalize; }
        .badge-user { color: #64748b; text-transform: capitalize; }

        /* Modal styles */
        .modal { display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); justify-content: center; align-items: center; }
        .modal-content { background: #fff; padding: 20px; border-radius: 8px; width: 500px; max-width: 90%; }
        .modal-header h2, .modal-header h3 { margin-bottom: 10px; }
        .close-modal { float: right; cursor: pointer; }
        .small-modal { width: 400px; }
        .warning-text { color: red; font-weight: bold; }
        .btn-cancel, .btn-save, .btn-delete-confirm { padding: 8px 15px; border: none; border-radius: 4px; cursor: pointer; }
        .btn-cancel { background: #cbd5e1; color: #1e293b; }
        .btn-save { background: #0ea5e9; color: #fff; }
        .btn-delete-confirm { background: #ef4444; color: #fff; }
        
    </style>
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
                    <a href="pending_booking.php"><i class="fas fa-clock"></i> Pending
                        <span class="badge">5</span>
                    </a>
                </li>
                <li>
                    <a href="confrom.php"><i class="fas fa-check"></i> Confirmed</a>
                </li>

                <div class="menu-category">Management</div>
                <li>
                    <a href="user.php"><i class="fas fa-users"></i> Users</a>
                </li>
                <li>
                    <a href="#"><i class="fas fa-tools"></i> Services</a>
                </li>

                <li class="logout">
                    <a href="../user/logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
                </li>
            </ul>
        </nav>
    </aside>

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

<div class="dashboard">
    <h2>Users Dashboard</h2>

    <table class="user-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Email</th>
                <th>Phone</th>
                <th>Address</th>
                <th>User Type</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($result->num_rows > 0): ?>
                <?php while($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?= $row["id"] ?></td>
                        <td><?= htmlspecialchars($row["name"]) ?></td>
                        <td><?= htmlspecialchars($row["email"]) ?></td>
                        <td>
                            <?= !empty($row["phone"]) ? htmlspecialchars($row["phone"]) : "<span style='color:#94a3b8; font-style:italic;'>Not provided</span>" ?>
                        </td>
                        <td>
                            <?= !empty($row["address"]) ? htmlspecialchars($row["address"]) : "<span style='color:#94a3b8; font-style:italic;'>Not provided</span>" ?>
                        </td>
                        <td>
                            <?php
                                $userType = strtolower($row["user_type"] ?? 'customer');
                                echo "<span class='badge badge-$userType'>" . htmlspecialchars($row["user_type"]) . "</span>";
                            ?>
                        </td>
                    <td>
                                    <a href="user_edit.php?id=<?php echo $row['id']; ?>">
                                        <i class="fa-solid fa-pen-to-square"></i> Edit
                                    </a>
                                </td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr><td colspan="7">No users found</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<script>
function openEditModal(id){
    document.getElementById('editCustomerModal').style.display = 'flex';
    document.getElementById('edit_customer_id').value = id;
}
function closeEditModal(){
    document.getElementById('editCustomerModal').style.display = 'none';
}
function openDeleteModal(name, id){
    document.getElementById('delete_customer_name').innerText = name;
    document.getElementById('deleteConfirmationModal').dataset.id = id;
    document.getElementById('deleteConfirmationModal').style.display = 'flex';
}
function closeDeleteModal(){
    document.getElementById('deleteConfirmationModal').style.display = 'none';
}
function performDelete(){
    const id = document.getElementById('deleteConfirmationModal').dataset.id;
    window.location.href = `delete_customer.php?id=${id}`;
}
</script>

</body>
</html>
<?php $conn->close(); ?>
