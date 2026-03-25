<?php
// ==================== DATABASE CONNECTION ====================
$host     = 'localhost';
$dbname   = 'dealership';           // change if needed
$username = 'root';                 
$password = '';                      

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("<div style='color:red;padding:20px;'>Database connection failed: " . htmlspecialchars($e->getMessage()) . "</div>");
}

// ==================== SESSION ====================
session_start();
if (!isset($_SESSION['admin'])) {
    $_SESSION['admin'] = true; // For testing only. Remove in production
}
$isAdmin = $_SESSION['admin'];

// ==================== LOGOUT ====================
if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: " . strtok($_SERVER["REQUEST_URI"], '?'));
    exit;
}

// ==================== ACTIONS ====================
$message = '';
$error = '';

if ($isAdmin) {

    // ---- CREATE / UPDATE USER ----
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
        $name = trim($_POST['name'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $phone = trim($_POST['phone'] ?? '');
        $address = trim($_POST['address'] ?? '');
        $type = $_POST['user_type'] ?? 'customer';
        $id = $_POST['id'] ?? null;

        if (empty($name) || empty($email)) {
            $error = "Name and Email are required.";
        } else {
            try {
                if ($_POST['action'] === 'create') {
                    $stmt = $pdo->prepare("INSERT INTO users (name,email,phone,address,user_type,created_at) VALUES (?,?,?,?,?,NOW())");
                    $stmt->execute([$name,$email,$phone,$address,$type]);
                    $message = "User '$name' created successfully.";
                } elseif ($_POST['action'] === 'update' && $id) {
                    $stmt = $pdo->prepare("UPDATE users SET name=?, email=?, phone=?, address=?, user_type=? WHERE id=?");
                    $stmt->execute([$name,$email,$phone,$address,$type,(int)$id]);
                    $message = "User '$name' updated successfully.";
                }
            } catch(PDOException $e){
                $error = "Database error: ".$e->getMessage();
            }
        }
    }

    // ---- DELETE USER ----
    if (isset($_GET['action']) && $_GET['action'] === 'delete_user' && isset($_GET['id'])) {
        try {
            $stmt = $pdo->prepare("DELETE FROM users WHERE id=?");
            $stmt->execute([(int)$_GET['id']]);
            $message = "User deleted successfully.";
            header("Location: " . strtok($_SERVER["REQUEST_URI"], '?') . "?msg=" . urlencode($message));
            exit;
        } catch(PDOException $e){
            $error = "Delete failed: " . $e->getMessage();
        }
    }

    // ---- BOOKING ACTIONS ----
    if (isset($_GET['booking_action']) && isset($_GET['booking_id'])) {
        $status = '';
        switch ($_GET['booking_action']) {
            case 'confirm': $status='confirmed'; break;
            case 'cancel': $status='cancelled'; break;
            case 'complete': $status='completed'; break;
        }
        if ($status) {
            $stmt = $pdo->prepare("UPDATE bookings SET status=? WHERE id=?");
            $stmt->execute([$status,(int)$_GET['booking_id']]);
            $message = "Booking #".$_GET['booking_id']." marked as $status.";
            header("Location: " . strtok($_SERVER["REQUEST_URI"], '?') . "?msg=" . urlencode($message));
            exit;
        }
    }
}

// ==================== FETCH DATA ====================

// User stats
$total_users     = $pdo->query("SELECT COUNT(*) FROM users")->fetchColumn();
$total_customers = $pdo->query("SELECT COUNT(*) FROM users WHERE user_type='customer'")->fetchColumn();
$total_admins    = $pdo->query("SELECT COUNT(*) FROM users WHERE user_type='admin'")->fetchColumn();

// Pending bookings
$pending_bookings = $pdo->query("SELECT COUNT(*) FROM bookings WHERE status='pending'")->fetchColumn();

// Users pagination
$page_users = $_GET['page_users'] ?? 1;
$limit_users = 10;
$offset_users = ($page_users-1)*$limit_users;
$total_users_all = $pdo->query("SELECT COUNT(*) FROM users")->fetchColumn();
$total_pages_users = ceil($total_users_all/$limit_users);

$stmt = $pdo->prepare("SELECT * FROM users ORDER BY created_at DESC LIMIT :limit OFFSET :offset");
$stmt->bindValue(':limit',$limit_users,PDO::PARAM_INT);
$stmt->bindValue(':offset',$offset_users,PDO::PARAM_INT);
$stmt->execute();
$users = $stmt->fetchAll();

// Bookings
$status_filter = $_GET['status'] ?? 'all';
$page_bookings = $_GET['page_bookings'] ?? 1;
$limit_bookings = 10;
$offset_bookings = ($page_bookings-1)*$limit_bookings;

$count_sql = "SELECT COUNT(*) FROM bookings";
$params = [];
if ($status_filter!=='all'){
    $count_sql .= " WHERE status=:status";
    $params[':status'] = $status_filter;
}
$stmt_count = $pdo->prepare($count_sql);
foreach($params as $k=>$v) $stmt_count->bindValue($k,$v);
$stmt_count->execute();
$total_bookings_all = $stmt_count->fetchColumn();
$total_pages_bookings = ceil($total_bookings_all/$limit_bookings);

$sql = "SELECT b.*, u.name as user_name, u.email as user_email FROM bookings b LEFT JOIN users u ON b.user_id=u.id";
if ($status_filter!=='all') $sql.=" WHERE b.status=:status";
$sql.=" ORDER BY b.appointment_date DESC LIMIT :limit OFFSET :offset";

$stmt = $pdo->prepare($sql);
if ($status_filter!=='all') $stmt->bindValue(':status',$status_filter);
$stmt->bindValue(':limit',$limit_bookings,PDO::PARAM_INT);
$stmt->bindValue(':offset',$offset_bookings,PDO::PARAM_INT);
$stmt->execute();
$bookings = $stmt->fetchAll();

// Message
if (isset($_GET['msg'])) $message = $_GET['msg'];
?>

<!-- ==================== HTML / UI ==================== -->
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>BIKE SVC - Admin Dashboard</title>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"/>
<style>
/* [Same CSS from your file] */
:root{--sidebar-bg:#1e293b;--primary:#3b82f6;--success:#10b981;--warning:#f59e0b;--danger:#ef4444;--text:#0f172a;--text-light:#64748b;--bg:#f1f5f9;--card:#ffffff}*{margin:0;padding:0;box-sizing:border-box}body{font-family:system-ui,sans-serif;background:var(--bg);color:var(--text);line-height:1.5}.layout{display:grid;grid-template-columns:260px 1fr;min-height:100vh}.sidebar{background:var(--sidebar-bg);color:white;padding:1.5rem 0;position:sticky;top:0;height:100vh;overflow-y:auto}.logo{padding:0 1.5rem 2rem;font-size:1.6rem;font-weight:bold}nav ul{list-style:none}nav a{display:flex;align-items:center;gap:12px;padding:.95rem 1.5rem;color:#cbd5e1;text-decoration:none;transition:.2s}nav a:hover,nav a.active{background:rgba(255,255,255,.08);color:white}.badge{background:var(--danger);font-size:.7rem;padding:3px 9px;border-radius:12px;margin-left:auto}.logout{margin-top:4rem;padding:0 1.5rem}.logout a{color:#f87171}.main-content{padding:2rem 2.5rem}.topbar{display:flex;justify-content:space-between;align-items:center;margin-bottom:2.2rem}.user-btn{display:flex;align-items:center;gap:10px;background:none;border:none;font-size:1rem;cursor:pointer;color:var(--text)}.avatar{width:38px;height:38px;background:var(--primary);color:white;border-radius:50%;display:grid;place-items:center;font-weight:bold}.welcome h2{font-size:2rem;margin-bottom:.4rem}.wave{animation:wave 2.2s infinite;display:inline-block}@keyframes wave{0%,100%{transform:rotate(0deg)}20%{transform:rotate(-14deg)}40%{transform:rotate(10deg)}60%{transform:rotate(-8deg)}80%{transform:rotate(6deg)}}.stats-grid{display:grid;grid-template-columns:repeat(auto-fit,minmax(220px,1fr));gap:1.5rem;margin:2.5rem 0}.card{background:var(--card);border-radius:12px;padding:1.6rem;box-shadow:0 4px 12px rgba(0,0,0,.08);text-align:center}.card .icon{font-size:2.4rem;margin-bottom:.8rem}.card .value{font-size:2.8rem;font-weight:700;margin:.3rem 0}.card .label{color:var(--text-light)}.card.total{border-left:5px solid var(--primary)}.card.customers{border-left:5px solid var(--success)}.card.admins{border-left:5px solid var(--warning)}.recent{background:var(--card);border-radius:12px;box-shadow:0 4px 12px rgba(0,0,0,.08);overflow:hidden;margin-top:2rem}.card-header{display:flex;justify-content:space-between;align-items:center;padding:1.3rem 1.8rem;border-bottom:1px solid #e2e8f0}.btn-add{background:var(--primary);color:white;border:none;padding:.6rem 1.4rem;border-radius:30px;font-size:.9rem;font-weight:500;cursor:pointer;display:inline-flex;align-items:center;gap:.5rem;transition:.2s}.btn-add:hover{background:#2563eb}table{width:100%;border-collapse:collapse}th,td{padding:1rem 1.2rem;text-align:left}th{background:#f8fafc;color:#475569;font-weight:600;font-size:.82rem;text-transform:uppercase}tr:nth-child(even){background:#f9fafb}.type{padding:5px 12px;border-radius:999px;font-size:.82rem;font-weight:500}.type.admin{background:#dbeafe;color:#1e40af}.type.customer{background:#d1fae5;color:#065f46}.status{padding:5px 12px;border-radius:999px;font-size:.8rem;font-weight:500}.status.pending{background:#fef3c7;color:#92400e}.status.confirmed{background:#dbeafe;color:#1e40af}.status.cancelled{background:#fee2e2;color:#991b1b}.status.completed{background:#d1fae5;color:#065f46}.action-btns{display:flex;gap:8px}.btn-edit,.btn-delete,.btn-confirm,.btn-cancel,.btn-complete{background:none;border:none;font-size:1.1rem;cursor:pointer;padding:4px 8px;border-radius:6px;transition:.2s}.btn-edit{color:var(--primary)}.btn-edit:hover{background:#dbeafe}.btn-delete{color:var(--danger)}.btn-delete:hover{background:#fee2e2}.btn-confirm{color:var(--success)}.btn-confirm:hover{background:#d1fae5}.btn-cancel{color:var(--danger)}.btn-cancel:hover{background:#fee2e2}.btn-complete{color:var(--warning)}.btn-complete:hover{background:#fef3c7}.pagination{display:flex;justify-content:flex-end;padding:1.5rem 2rem;gap:.5rem}.pagination a,.pagination span{padding:.5rem .9rem;border-radius:8px;background:white;border:1px solid #e2e8f0;color:var(--text);text-decoration:none;font-weight:500}.pagination a:hover{background:var(--primary);color:white;border-color:var(--primary)}.pagination .active{background:var(--primary);color:white;border-color:var(--primary)}.filter-bar{display:flex;gap:1rem;align-items:center}.filter-select{padding:.5rem;border-radius:6px;border:1px solid #cbd5e1}.modal{display:none;position:fixed;top:0;left:0;width:100%;height:100%;background:rgba(0,0,0,.5);align-items:center;justify-content:center;z-index:1000}.modal.active{display:flex}.modal-content{background:white;padding:2rem;border-radius:16px;width:90%;max-width:500px;max-height:80vh;overflow-y:auto;box-shadow:0 20px 30px rgba(0,0,0,.2)}.modal-content h3{margin-bottom:1.5rem;font-size:1.6rem}.modal-content label{display:block;margin:1rem 0 .3rem;font-weight:500}.modal-content input,.modal-content select,.modal-content textarea{width:100%;padding:.7rem 1rem;border:1px solid #cbd5e1;border-radius:8px;font-size:1rem}.modal-footer{display:flex;justify-content:flex-end;gap:1rem;margin-top:2rem}.btn{padding:.7rem 1.5rem;border:none;border-radius:8px;font-size:1rem;cursor:pointer;transition:.2s}.btn-primary{background:var(--primary);color:white}.btn-primary:hover{background:#2563eb}.btn-secondary{background:#e2e8f0;color:var(--text)}.btn-secondary:hover{background:#cbd5e1}.alert{padding:1rem;border-radius:8px;margin-bottom:1rem}.alert-success{background:#d1fae5;color:#065f46;border:1px solid #a7f3d0}.alert-error{background:#fee2e2;color:#991b1b;border:1px solid #fecaca}.tab-content{display:none}.tab-content.active{display:block}
</style>
</head>
<body>
<div class="layout">
  <!-- Sidebar -->
  <aside class="sidebar">
    <div class="logo">BIKE SVC</div>
    <nav>
      <ul>
        <li><a href="#" class="nav-link" data-tab="dashboard"><i class="fas fa-home"></i> Dashboard</a></li>
        <li><a href="#" class="nav-link" data-tab="bookings"><i class="fas fa-calendar-alt"></i> Bookings <span class="badge"><?= $pending_bookings ?></span></a></li>
        <li><a href="#" class="nav-link" data-tab="users"><i class="fas fa-users"></i> Users <span class="badge"><?= $total_users ?></span></a></li>
        <li><a href="#" class="nav-link" data-tab="services"><i class="fas fa-wrench"></i> Services</a></li>
      </ul>
    </nav>
    <div class="logout"><a href="?logout=1"><i class="fas fa-sign-out-alt"></i> Logout</a></div>
  </aside>

  <!-- Main -->
  <main class="main-content">
    <div class="topbar">
      <h1>Dashboard</h1>
    </div>
    <?php if($message): ?><div class="alert alert-success"><?= htmlspecialchars($message) ?></div><?php endif;?>
    <?php if($error): ?><div class="alert alert-error"><?= htmlspecialchars($error) ?></div><?php endif;?>

    <div class="tab-content active" id="tab-dashboard">
      <div class="stats-grid">
        <div class="card total"><div class="value"><?= $total_users ?></div><div class="label">Total Users</div></div>
        <div class="card customers"><div class="value"><?= $total_customers ?></div><div class="label">Customers</div></div>
        <div class="card admins"><div class="value"><?= $total_admins ?></div><div class="label">Admins</div></div>
        <div class="card" style="border-left:5px solid var(--warning);"><div class="value"><?= $pending_bookings ?></div><div class="label">Pending Bookings</div></div>
      </div>
    </div>

    <!-- Users tab -->
    <div class="tab-content" id="tab-users">
      <div class="recent">
        <div class="card-header">
          <h3>Manage Users</h3>
          <button class="btn-add" id="addUserBtn"><i class="fas fa-plus"></i> Add New User</button>
        </div>
        <table>
          <thead><tr><th>ID</th><th>Name</th><th>Email</th><th>Phone</th><th>Type</th><th>Actions</th></tr></thead>
          <tbody>
          <?php foreach($users as $u): ?>
          <tr>
            <td><?= $u['id'] ?></td>
            <td><?= htmlspecialchars($u['name']) ?></td>
            <td><?= htmlspecialchars($u['email']) ?></td>
            <td><?= htmlspecialchars($u['phone']) ?></td>
            <td><span class="type <?= $u['user_type'] ?>"><?= ucfirst($u['user_type']) ?></span></td>
            <td>
              <div class="action-btns">
                <button class="btn-edit" onclick='openEditModal(<?= json_encode($u) ?>)'><i class="fas fa-edit"></i></button>
                <a class="btn-delete" href="?action=delete_user&id=<?= $u['id'] ?>" onclick="return confirm('Are you sure?')"><i class="fas fa-trash"></i></a>
              </div>
            </td>
          </tr>
          <?php endforeach;?>
          </tbody>
        </table>
      </div>
    </div>

    <!-- Bookings tab -->
    <div class="tab-content" id="tab-bookings">
      <div class="recent">
        <div class="card-header">
          <h3>Manage Bookings</h3>
          <div class="filter-bar">
            <label>Status:</label>
            <select class="filter-select" onchange="window.location='?status='+this.value">
              <option value="all" <?= $status_filter==='all'?'selected':'' ?>>All</option>
              <option value="pending" <?= $status_filter==='pending'?'selected':'' ?>>Pending</option>
              <option value="confirmed" <?= $status_filter==='confirmed'?'selected':'' ?>>Confirmed</option>
              <option value="completed" <?= $status_filter==='completed'?'selected':'' ?>>Completed</option>
              <option value="cancelled" <?= $status_filter==='cancelled'?'selected':'' ?>>Cancelled</option>
            </select>
          </div>
        </div>
        <table>
          <thead><tr><th>ID</th><th>User</th><th>Email</th><th>Service</th><th>Date</th><th>Status</th><th>Actions</th></tr></thead>
          <tbody>
          <?php foreach($bookings as $b): ?>
          <tr>
            <td><?= $b['id'] ?></td>
            <td><?= htmlspecialchars($b['user_name']) ?></td>
            <td><?= htmlspecialchars($b['user_email']) ?></td>
            <td><?= htmlspecialchars($b['service'] ?? '-') ?></td>
            <td><?= htmlspecialchars($b['appointment_date'] ?? '-') ?></td>
            <td><span class="status <?= $b['status'] ?>"><?= ucfirst($b['status']) ?></span></td>
            <td class="action-btns">
              <?php if($b['status']=='pending'): ?>
              <a class="btn-confirm" href="?booking_action=confirm&booking_id=<?= $b['id'] ?>"><i class="fas fa-check"></i></a>
              <a class="btn-cancel" href="?booking_action=cancel&booking_id=<?= $b['id'] ?>"><i class="fas fa-times"></i></a>
              <?php elseif($b['status']=='confirmed'): ?>
              <a class="btn-complete" href="?booking_action=complete&booking_id=<?= $b['id'] ?>"><i class="fas fa-check-double"></i></a>
              <?php endif; ?>
            </td>
          </tr>
          <?php endforeach;?>
          </tbody>
        </table>
      </div>
    </div>
  </main>
</div>

<!-- ==================== MODAL ==================== -->
<div class="modal" id="userModal">
  <div class="modal-content">
    <h3 id="modalTitle">Add User</h3>
    <form method="POST">
      <input type="hidden" name="id" id="userId">
      <input type="hidden" name="action" id="userAction" value="create">
      <label>Name</label>
      <input type="text" name="name" id="userName" required>
      <label>Email</label>
      <input type="email" name="email" id="userEmail" required>
      <label>Phone</label>
      <input type="text" name="phone" id="userPhone">
      <label>Address</label>
      <textarea name="address" id="userAddress"></textarea>
      <label>Type</label>
      <select name="user_type" id="userType">
        <option value="customer">Customer</option>
        <option value="admin">Admin</option>
      </select>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" onclick="closeModal()">Cancel</button>
        <button type="submit" class="btn btn-primary">Save</button>
      </div>
    </form>
  </div>
</div>

<script>
// ==================== TAB SWITCH ====================
const tabs = document.querySelectorAll('.nav-link');
const contents = document.querySelectorAll('.tab-content');
tabs.forEach(tab=>{
  tab.addEventListener('click',e=>{
    e.preventDefault();
    tabs.forEach(t=>t.classList.remove('active'));
    tab.classList.add('active');
    contents.forEach(c=>c.classList.remove('active'));
    document.getElementById('tab-'+tab.dataset.tab).classList.add('active');
  });
});

// ==================== MODAL ====================
const modal = document.getElementById('userModal');
const addBtn = document.getElementById('addUserBtn');
const modalTitle = document.getElementById('modalTitle');
const userAction = document.getElementById('userAction');
const userId = document.getElementById('userId');
const userName = document.getElementById('userName');
const userEmail = document.getElementById('userEmail');
const userPhone = document.getElementById('userPhone');
const userAddress = document.getElementById('userAddress');
const userType = document.getElementById('userType');

addBtn.onclick = ()=>{
  modalTitle.textContent = 'Add User';
  userAction.value = 'create';
  userId.value = '';
  userName.value = '';
  userEmail.value = '';
  userPhone.value = '';
  userAddress.value = '';
  userType.value = 'customer';
  modal.classList.add('active');
}

function openEditModal(data){
  modalTitle.textContent = 'Edit User';
  userAction.value = 'update';
  userId.value = data.id;
  userName.value = data.name;
  userEmail.value = data.email;
  userPhone.value = data.phone;
  userAddress.value = data.address;
  userType.value = data.user_type;
  modal.classList.add('active');
}

function closeModal(){
  modal.classList.remove('active');
}
</script>
</body>
</html>