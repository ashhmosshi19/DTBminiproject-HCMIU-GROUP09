<?php
// 1. ENABLE ERROR REPORTING TO PREVENT WHITE SCREEN
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// 2. DISABLE INTERNAL MYSQLI ERROR REPORTING TO PREVENT CRASHES ON PHP 8.1+
mysqli_report(MYSQLI_REPORT_OFF);

// 3. DATABASE CONNECTION
$servername = "localhost";
$username = "tritin2805";
$password = "tin280506"; // Change to "root" if you are using MAMP on Mac
$dbname = "bus_system";

$conn = new mysqli($servername, $username, $password, $dbname);
$db_connected = true;
$error_msg = "";

if ($conn->connect_error) {
    $db_connected = false;
    $error_msg = "DATABASE CONNECTION ERROR: " . $conn->connect_error . " <br> (Please verify if you have created the database named 'bus_system' or checked the MySQL password).";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bus Network Management System</title>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        :root { --primary-teal: #00828e; --dark-blue: #092c4c; --accent-orange: #f59f00; --light-bg: #f4f7f6; --text-dark: #333; }
        body { font-family: 'Montserrat', sans-serif; margin: 0; padding: 0; background-color: var(--light-bg); color: var(--text-dark); }
        .top-bar { background-color: var(--primary-teal); display: flex; justify-content: space-between; align-items: center; padding: 10px 50px; }
        .logo { color: white; font-size: 24px; font-weight: 700; font-style: italic; }
        .nav-links { display: flex; gap: 30px; }
        .nav-links button { background: none; border: none; color: white; font-family: inherit; font-size: 14px; font-weight: 600; text-transform: uppercase; cursor: pointer; padding: 10px 0; border-bottom: 2px solid transparent; transition: 0.3s; }
        .nav-links button:hover, .nav-links button.active { color: var(--accent-orange); border-bottom: 2px solid var(--accent-orange); }
        .hero { 
            background-color: var(--dark-blue); 
            background-image: radial-gradient(circle at right, #114b79 0%, var(--dark-blue) 50%); 
            height: 300px; 
            display: flex; 
            align-items: center; 
            padding: 0 10%; 
            gap: 40px; /* Tạo khoảng cách 40px giữa hình và chữ */
        }
        .hero h1 { 
            color: white; 
            font-size: 42px; 
            line-height: 1.3; 
            text-transform: uppercase; 
            margin: 0;
        }
        .hero img { 
            height: 180px; 
            border-radius: 50%; 
            /* Đã xóa position: absolute để ảnh và chữ tự đứng cạnh nhau */
        }
        .container { max-width: 1000px; margin: -60px auto 50px; background: white; border-radius: 10px; box-shadow: 0 10px 30px rgba(0,0,0,0.1); padding: 30px; position: relative; z-index: 10; overflow-x: auto; }
        .section-title { font-size: 18px; font-weight: 700; margin-bottom: 20px; border-bottom: 2px solid #eee; padding-bottom: 10px; text-transform: uppercase; }
        
        /* Form Layout Styles */
        .form-group { display: flex; gap: 15px; margin-bottom: 20px; align-items: center; }
        .form-group select, .form-group input { padding: 12px 15px; border: 1px solid #ddd; border-radius: 5px; font-family: inherit; font-size: 14px; flex: 1; }
        .btn-submit { background-color: #4a6ee0; color: white; border: none; padding: 12px 30px; border-radius: 5px; font-weight: 600; cursor: pointer; transition: 0.3s; }
        .btn-submit:hover { background-color: #3554b5; }
        
        /* Table Controls & Tabs */
        .data-controls { display: flex; gap: 10px; margin-bottom: 20px; flex-wrap: wrap; }
        .btn-table { background-color: #eee; border: none; padding: 8px 20px; border-radius: 20px; cursor: pointer; font-weight: 600; transition: 0.2s; font-family: inherit;}
        .btn-table:hover { background-color: #ccc; }
        .btn-table.active { background-color: var(--primary-teal); color: white; }
        
        /* Data Tables */
        table { width: 100%; border-collapse: collapse; margin-top: 10px; font-size: 14px; }
        th, td { padding: 12px 15px; text-align: left; border-bottom: 1px solid #ddd; }
        th { background-color: var(--primary-teal); color: white; font-weight: 600; white-space: nowrap; }
        tr:hover { background-color: #f1f9f9; }
        
        /* Visibility Toggling */
        .view-section, .data-table-container { display: none; }
        .view-section.active, .data-table-container.active { display: block; }
        .error-box { background-color: #ffe6e6; border-left: 5px solid red; padding: 15px; color: #cc0000; font-weight: bold; margin-bottom: 20px; line-height: 1.5; }
    </style>
</head>
<body>

    <div class="top-bar">
        <div class="logo">SaigonBus</div>
        <div class="nav-links">
            <button onclick="switchMainView('show-data', this)" class="active">Show Data</button>
            <button onclick="switchMainView('search', this)">Search</button>
            <button onclick="switchMainView('update', this)">Update</button>
        </div>
    </div>

    <div class="hero">
        <img src="SaiGonBus.png" alt="Bus Driver">
        <h1>Bus Network<br>Management System</h1>
    </div>

    <div class="container">
        
        <div id="show-data" class="view-section active">
            <div class="section-title">System Database Tables</div>
            
            <?php if (!$db_connected): ?>
                <div class="error-box">
                    ⚠️ <?php echo $error_msg; ?> <br><br>
                    <strong>Troubleshooting:</strong> Please open phpMyAdmin (<code>http://localhost/phpmyadmin</code>), ensure you have created a database named <code>bus_system</code>, and executed the SQL schema script.
                </div>
            <?php else: ?>
                <div class="data-controls">
                    <button class="btn-table active" onclick="switchTable('table-driver', this)">Driver Table</button>
                    <button class="btn-table" onclick="switchTable('table-bus', this)">Bus Table</button>
                    <button class="btn-table" onclick="switchTable('table-route', this)">Route Table</button>
                    <button class="btn-table" onclick="switchTable('table-station', this)">Station Table</button>
                </div>

                <?php
                // Helper Function to Fetch and Render SQL Tables
                function renderTable($conn, $tableName, $containerId, $isActive) {
                    $class = $isActive ? "data-table-container active" : "data-table-container";
                    echo "<div id='$containerId' class='$class' style='overflow-x:auto;'>";
                    
                    $sql = "SELECT * FROM $tableName";
                    $result = $conn->query($sql);

                    if ($result && $result->num_rows > 0) {
                        echo "<table><thead><tr>";
                        $fields = $result->fetch_fields();
                        foreach ($fields as $field) {
    // Thay thế dấu '_' thành khoảng trắng và in hoa chữ cái đầu mỗi từ
                            $normal_name = ucwords(str_replace('_', ' ', $field->name));
    
    // Sửa riêng chữ "Id" thành "ID" cho đẹp mắt
                            $normal_name = str_replace('Id', 'ID', $normal_name); 
    
                            echo "<th>" . $normal_name . "</th>";
}
                            echo "</tr></thead><tbody>";
                        
                        while($row = $result->fetch_assoc()) {
                            echo "<tr>";
                            foreach ($row as $data) {
                                echo "<td>" . htmlspecialchars($data ?? '') . "</td>";
                            }
                            echo "</tr>";
                        }
                        echo "</tbody></table>";
                    } else {
                        echo "<p>No records found in table: $tableName.</p>";
                    }
                    echo "</div>";
                }

                // Render All 4 Tables dynamically from MySQL
                renderTable($conn, 'driver', 'table-driver', true);
                renderTable($conn, 'bus', 'table-bus', false);
                renderTable($conn, 'route', 'table-route', false);
                renderTable($conn, 'station', 'table-station', false);
                ?>
            <?php endif; ?>
        </div>

        <div id="search" class="view-section">
            <div class="section-title">Search Information</div>
            <form action="result.php" method="post">
                <div class="form-group">
                    <select name="type" style="max-width: 200px;">
                        <option value="driver">Driver</option>
                        <option value="bus">Bus</option>
                        <option value="route">Route</option>
                        <option value="station">Station</option>
                    </select>
                    <input type="text" name="name" placeholder="Enter ID, name, or keyword to search..." required>
                    <button type="submit" class="btn-submit">🔍 Search</button>
                </div>
            </form>
        </div>

        <div id="update" class="view-section">
            <div class="section-title">Update Bus Operational Status</div>
            <form action="update.php" method="post">
                <div class="form-group">
                    <input type="text" name="bus_id" placeholder="Enter Bus ID (e.g., B01)..." required style="max-width: 250px dirige;">
                    <select name="status">
                        <option value="Active">Active</option>
                        <option value="Repair">Repair</option>
                    </select>
                </div>
                <div class="form-group">
                <input type="date" name="maintenance" required style="max-width: 200px;">
                <button type="submit" class="btn-submit" style="background-color: var(--accent-yellow); color: black;">Save Update</button>
                </div>
            </form>
        </div>

    </div>

    <script>
        // Main Navigation Toggling (Show Data / Search / Update)
        function switchMainView(viewId, btnElement) {
            document.querySelectorAll('.view-section').forEach(el => el.classList.remove('active'));
            document.querySelectorAll('.nav-links button').forEach(btn => btn.classList.remove('active'));
            document.getElementById(viewId).classList.add('active');
            btnElement.classList.add('active');
        }

        // Sub-Table Toggling (Driver / Bus / Route / Station)
        function switchTable(tableId, btnElement) {
            document.querySelectorAll('.data-table-container').forEach(el => el.classList.remove('active'));
            document.querySelectorAll('.btn-table').forEach(btn => btn.classList.remove('active'));
            document.getElementById(tableId).classList.add('active');
            btnElement.classList.add('active');
        }
    </script>
</body>
</html>