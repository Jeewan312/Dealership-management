<?php
session_start();
include '../database/connection.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ..logout.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$success = "";
$error = "";

/* =========================
   INSERT BOOKING
========================= */
if (isset($_POST['book_service'])) {
    $make   = mysqli_real_escape_string($conn, $_POST['vehicle_make']);
    $model  = mysqli_real_escape_string($conn, $_POST['vehicle_model']);
    $year   = mysqli_real_escape_string($conn, $_POST['vehicle_year']);
    $plate  = mysqli_real_escape_string($conn, $_POST['license_plate']);
    $color  = mysqli_real_escape_string($conn, $_POST['vehicle_color']);
    $vin    = mysqli_real_escape_string($conn, $_POST['vin']);
    $issues = mysqli_real_escape_string($conn, $_POST['issues']);
    $service = mysqli_real_escape_string($conn, $_POST['service_type']);
    $date   = mysqli_real_escape_string($conn, $_POST['appointment_date']);

    // Validate appointment date (today to today+7 days)
    $today = date('Y-m-d');
    $maxDate = date('Y-m-d', strtotime('+7 days'));
    if ($date < $today || $date > $maxDate) {
        $error = "Appointment date must be between today and 7 days from today.";
    } else {
        $sql = "INSERT INTO bookings 
                (user_id, vehicle_make, vehicle_model, vehicle_year, license_plate, vehicle_color, vin, issues, service_type, appointment_date, status)
                VALUES ('$user_id','$make','$model','$year','$plate','$color','$vin','$issues','$service','$date','pending')";

        if (mysqli_query($conn, $sql)) {
            $success = "Booking Successful!";
        } else {
            $error = "Database error: " . mysqli_error($conn);
        }
    }
}

/* =========================
   DELETE BOOKING
========================= */
if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    mysqli_query($conn, "DELETE FROM bookings WHERE id='$id' AND user_id='$user_id'");
    header("Location: client.php#my");
    exit;
}

/* =========================
   FETCH BOOKINGS
========================= */
$result = mysqli_query($conn, "SELECT * FROM bookings WHERE user_id='$user_id' ORDER BY id DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>AutoCare - My Dashboard</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"/>
  <style>
    :root {
      --sidebar-bg: #1e293b;
      --primary: #3b82f6;
      --success: #10b981;
      --warning: #f59e0b;
      --danger: #ef4444;
      --text: #0f172a;
      --text-light: #64748b;
      --bg: #f1f5f9;
      --card: #ffffff;
    }

    * { margin:0; padding:0; box-sizing:border-box; }
    body {
      font-family: system-ui, sans-serif;
      background: var(--bg);
      color: var(--text);
      line-height: 1.5;
    }

    .layout {
      display: grid;
      grid-template-columns: 260px 1fr;
      min-height: 100vh;
    }

    /* Sidebar */
    .sidebar {
      background: var(--sidebar-bg);
      color: white;
      padding: 1.5rem 0;
      position: sticky;
      top: 0;
      height: 100vh;
      overflow-y: auto;
    }

    .logo {
      padding: 0 1.5rem 2rem;
      font-size: 1.6rem;
      font-weight: bold;
    }

    nav ul { list-style: none; }
    nav a {
      display: flex;
      align-items: center;
      gap: 12px;
      padding: 0.95rem 1.5rem;
      color: #cbd5e1;
      text-decoration: none;
      transition: 0.2s;
    }
    nav a:hover, nav a.active {
      background: rgba(255,255,255,0.08);
      color: white;
    }

    .logout-link {
      margin-top: 3rem;
    }

    /* Main */
    .main-content {
      padding: 2rem 2.5rem;
    }

    .topbar {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 2rem;
    }

    .card {
      background: var(--card);
      border-radius: 12px;
      box-shadow: 0 4px 12px rgba(0,0,0,0.08);
      padding: 1.8rem;
      margin-bottom: 2rem;
    }

    .card h3 {
      font-size: 1.5rem;
      margin-bottom: 1.5rem;
      border-left: 5px solid var(--primary);
      padding-left: 1rem;
    }

    .form-group {
      margin-bottom: 1.2rem;
    }

    label {
      display: block;
      font-weight: 500;
      margin-bottom: 0.3rem;
    }

    input, select, textarea {
      width: 100%;
      padding: 0.7rem 1rem;
      border: 1px solid #cbd5e1;
      border-radius: 8px;
      font-size: 1rem;
      font-family: inherit;
    }

    input:focus, select:focus, textarea:focus {
      outline: none;
      border-color: var(--primary);
      box-shadow: 0 0 0 3px rgba(59,130,246,0.2);
    }

    .btn-primary {
      background: var(--primary);
      color: white;
      border: none;
      padding: 0.7rem 1.5rem;
      border-radius: 8px;
      font-size: 1rem;
      font-weight: 500;
      cursor: pointer;
      transition: 0.2s;
    }
    .btn-primary:hover {
      background: #2563eb;
    }

    .btn-danger {
      background: var(--danger);
      color: white;
      border: none;
      padding: 0.4rem 1rem;
      border-radius: 6px;
      font-size: 0.9rem;
      cursor: pointer;
      text-decoration: none;
      display: inline-block;
    }
    .btn-danger:hover {
      background: #dc2626;
    }

    table {
      width: 100%;
      border-collapse: collapse;
    }
    th, td {
      padding: 1rem 1rem;
      text-align: left;
    }
    th {
      background: #f8fafc;
      color: #475569;
      font-weight: 600;
      font-size: 0.82rem;
      text-transform: uppercase;
    }
    tr:nth-child(even) { background: #f9fafb; }

    .status {
      padding: 5px 12px;
      border-radius: 999px;
      font-size: 0.8rem;
      font-weight: 500;
    }
    .status.pending    { background: #fef3c7; color: #92400e; }
    .status.confirmed  { background: #dbeafe; color: #1e40af; }
    .status.cancelled  { background: #fee2e2; color: #991b1b; }
    .status.completed  { background: #d1fae5; color: #065f46; }

    .alert-success {
      background: #d1fae5;
      color: #065f46;
      border: 1px solid #a7f3d0;
      padding: 1rem;
      border-radius: 8px;
      margin-bottom: 1rem;
    }

    .alert-error {
      background: #fee2e2;
      color: #991b1b;
      border: 1px solid #fecaca;
      padding: 1rem;
      border-radius: 8px;
      margin-bottom: 1rem;
    }

    @media (max-width: 768px) {
      .layout {
        grid-template-columns: 1fr;
      }
      .sidebar {
        position: relative;
        height: auto;
      }
      .main-content {
        padding: 1rem;
      }
    }
  </style>
</head>
<body>

<div class="layout">

  <!-- SIDEBAR -->
  <aside class="sidebar">
    <div class="logo">🔧 AutoCare</div>
    <nav>
      <ul>
        <li><a href="#book" class="nav-link" data-tab="book"><i class="fas fa-calendar-plus"></i> Book Service</a></li>
        <li><a href="#my" class="nav-link" data-tab="my"><i class="fas fa-list"></i> My Bookings</a></li>
        <li class="logout-link"><a href="?logout=1"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
      </ul>
    </nav>
  </aside>

  <!-- MAIN CONTENT -->
  <main class="main-content">

    <div class="topbar">
      <h1>Dashboard</h1>
      <div></div>
    </div>

    <?php if ($success): ?>
      <div class="alert-success"><?= htmlspecialchars($success) ?></div>
    <?php elseif ($error): ?>
      <div class="alert-error"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <!-- BOOK SERVICE CARD -->
    <div class="card" id="book">
      <h3><i class="fas fa-wrench"></i> Book a Service</h3>
      <form method="POST">
        <div class="form-group">
          <label>Vehicle Make *</label>
          <select name="vehicle_make" required>
            <option value="">Select</option>
            <option>Toyota</option><option>Honda</option><option>Ford</option>
            <option>BMW</option><option>Mercedes</option><option>Hyundai</option>
          </select>
        </div>

        <div class="form-group">
          <label>Vehicle Model *</label>
          <input type="text" name="vehicle_model" required>
        </div>

        <div class="form-group">
          <label>Year *</label>
          <input type="number" name="vehicle_year" required>
        </div>

        <div class="form-group">
          <label>License Plate *</label>
          <input type="text" name="license_plate" required>
        </div>

        <div class="form-group">
          <label>Color</label>
          <input type="text" name="vehicle_color">
        </div>

        <div class="form-group">
          <label>VIN</label>
          <input type="text" name="vin">
        </div>

        <div class="form-group">
          <label>Issues (optional)</label>
          <textarea name="issues" rows="3"></textarea>
        </div>

        <div class="form-group">
          <label>Service Type *</label>
          <select name="service_type" required>
            <option value="">Select</option>
            <option>Oil Change</option>
            <option>Brake Service</option>
            <option>Full Service</option>
            <option>Engine Tune-up</option>
          </select>
        </div>

        <div class="form-group">
          <label>Appointment Date *</label>
          <input type="date" name="appointment_date" id="appointment_date" required>
        </div>

        <button type="submit" name="book_service" class="btn-primary">Book Now</button>
      </form>
    </div>

    <!-- MY BOOKINGS CARD -->
    <div class="card" id="my">
      <h3><i class="fas fa-history"></i> My Bookings</h3>
      <?php if (mysqli_num_rows($result) == 0): ?>
        <p style="text-align:center; padding:2rem;">No bookings yet. Schedule your first service!</p>
      <?php else: ?>
        <div style="overflow-x:auto;">
           <table>
            <thead>
               <tr>
                <th>ID</th>
                <th>Vehicle</th>
                <th>Date</th>
                <th>Status</th>
                <th>Action</th>
               </tr>
            </thead>
            <tbody>
              <?php while ($row = mysqli_fetch_assoc($result)): ?>
                <tr>
                  <td><?= $row['id'] ?></td>
                  <td><?= htmlspecialchars($row['vehicle_make'] . ' ' . $row['vehicle_model']) ?></td>
                  <td><?= $row['appointment_date'] ?></td>
                  <td><span class="status <?= $row['status'] ?>"><?= ucfirst($row['status']) ?></span></td>
                  <td>
                    <a href="?delete=<?= $row['id'] ?>" onclick="return confirm('Delete this booking?')" class="btn-danger">Delete</a>
                  </td>
                </tr>
              <?php endwhile; ?>
            </tbody>
           </table>
        </div>
      <?php endif; ?>
    </div>

  </main>
</div>

<script>
  // Set min and max for appointment date (today to today+7 days)
  const today = new Date().toISOString().split('T')[0];
  const maxDate = new Date();
  maxDate.setDate(maxDate.getDate() + 7);
  const maxDateStr = maxDate.toISOString().split('T')[0];

  const dateInput = document.getElementById('appointment_date');
  if (dateInput) {
    dateInput.setAttribute('min', today);
    dateInput.setAttribute('max', maxDateStr);
  }

  // Highlight active sidebar link based on current hash
  function setActiveLink() {
    const hash = window.location.hash.substring(1) || 'book';
    document.querySelectorAll('.nav-link').forEach(link => {
      if (link.getAttribute('data-tab') === hash) {
        link.classList.add('active');
      } else {
        link.classList.remove('active');
      }
    });
  }
  window.addEventListener('load', setActiveLink);
  window.addEventListener('hashchange', setActiveLink);
</script>

</body>
</html>