<?php
// database/check_table.php
require_once 'connection.php';

$result = $conn->query("DESCRIBE bookings");

echo "<h2>Bookings Table Structure</h2>";
echo "<table border='1'>";
echo "<tr><th>Field</th><th>Type</th><th>Null</th><th>Key</th><th>Default</th><th>Extra</th></tr>";

while ($row = $result->fetch_assoc()) {
    echo "<tr>";
    echo "<td>" . $row['Field'] . "</td>";
    echo "<td>" . $row['Type'] . "</td>";
    echo "<td>" . $row['Null'] . "</td>";
    echo "<td>" . $row['Key'] . "</td>";
    echo "<td>" . $row['Default'] . "</td>";
    echo "<td>" . $row['Extra'] . "</td>";
    echo "</tr>";
}

echo "</table>";

// Also check if we have any data
$result2 = $conn->query("SELECT * FROM bookings LIMIT 1");
if ($result2->num_rows > 0) {
    echo "<h2>Sample Data</h2>";
    $row = $result2->fetch_assoc();
    echo "<pre>" . print_r($row, true) . "</pre>";
}

$conn->close();
?>