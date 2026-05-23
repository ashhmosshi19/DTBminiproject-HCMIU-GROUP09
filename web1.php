<?php
// 1. ENABLE ERROR REPORTING
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// 2. DISABLE INTERNAL MYSQLI ERROR REPORTING
mysqli_report(MYSQLI_REPORT_OFF);

// 3. DATABASE CONNECTION
$servername = "localhost";
$username = "tritin2805";
$password = "tin280506"; 
$dbname = "bus_system";

$conn = new mysqli($servername, $username, $password, $dbname);
$db_connected = true;
$error_msg = "";

if ($conn->connect_error) {
    $db_connected = false;
    $error_msg = "DATABASE CONNECTION ERROR: " . $conn->connect_error;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bus Network Management System</title>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root { 
            --primary-teal: #196b73; 
            --dark-teal: #13555c;
            --dark-blue: #092c4c; 
            --accent-yellow: #fbb03b; 
            --light-bg: #f4f7f6; 
            --text-dark: #333; 
        }
        body { font-family: 'Montserrat', sans-serif; margin: 0; padding: 0; background-color: var(--light-bg); color: var(--text-dark); display: flex; flex-direction: column; min-height: 100vh; }
        
        /* ========================================== */
        /* HEADER                      */
        /* ========================================== */
        header { background-color: var(--primary-teal); color: white; padding: 0 50px; }
        
        /* Top Utility Bar */
        .header-top { display: flex; justify-content: flex-end; align-items: center; padding: 15px 0; border-bottom: 1px solid rgba(255,255,255,0.2); gap: 25px; font-size: 13px; }
        .header-top span { cursor: pointer; transition: 0.2s; }
        .header-top span:hover { color: var(--accent-yellow); }
        .phone-btn { background-color: var(--accent-yellow); color: #000; text-decoration: none; padding: 8px 20px; border-radius: 25px; font-weight: 700; font-size: 16px; display: flex; align-items: center; gap: 8px; }
        .phone-btn:hover { background-color: #e59e2f; }

        /* Bottom Navigation Bar */
        .header-bottom { display: flex; justify-content: space-between; align-items: center; padding: 15px 0; }
        .logo { font-size: 26px; font-weight: 800; font-style: italic; letter-spacing: 1px; }
        .nav-links { display: flex; gap: 30px; }
        .nav-links button, .nav-links a { background: none; border: none; color: white; font-family: inherit; font-size: 15px; font-weight: 600; text-transform: uppercase; cursor: pointer; padding: 10px 0; border-bottom: 2px solid transparent; transition: 0.3s; text-decoration: none; }
        .nav-links button:hover, .nav-links button.active, .nav-links a:hover { color: var(--accent-yellow); border-bottom: 2px solid var(--accent-yellow); }

        /* ========================================== */
        /* HERO & MAIN CONTENT                        */
        /* ========================================== */
        .hero { background-color: var(--dark-blue); background-image: radial-gradient(circle at right, #114b79 0%, var(--dark-blue) 50%); height: 280px; display: flex; align-items: center; padding: 0 10%; gap: 40px; }
        .hero h1 { color: white; font-size: 42px; line-height: 1.2; text-transform: uppercase; margin: 0; }
        .hero img { height: 160px; border-radius: 50%; }
        
        .main-wrapper { flex: 1; }
        .container { max-width: 1300px; margin: -50px auto 50px; background: white; border-radius: 10px; box-shadow: 0 10px 30px rgba(0,0,0,0.1); padding: 30px; position: relative; z-index: 10; }
        .section-title { font-size: 18px; font-weight: 700; margin-bottom: 20px; border-bottom: 2px solid #eee; padding-bottom: 10px; text-transform: uppercase; }
        
        /* Home Content Styles */
        .home-content { line-height: 1.6; font-size: 15px; }
        .home-content h3 { color: var(--primary-teal); margin-top: 25px; margin-bottom: 10px; }
        .home-content ul { padding-left: 20px; }
        .home-content li { margin-bottom: 8px; }

        /* Forms & Tables */
        .form-group { display: flex; gap: 15px; margin-bottom: 20px; align-items: center; }
        .form-group select, .form-group input { padding: 12px 15px; border: 1px solid #ddd; border-radius: 5px; font-family: inherit; font-size: 14px; flex: 1; }
        .btn-submit { background-color: var(--dark-blue); color: white; border: none; padding: 12px 30px; border-radius: 5px; font-weight: 600; cursor: pointer; transition: 0.3s; }
        .btn-submit:hover { background-color: #114b79; }
        
        .data-controls { display: flex; gap: 10px; margin-bottom: 20px; flex-wrap: wrap; }
        .btn-table { background-color: #eee; border: none; padding: 8px 20px; border-radius: 20px; cursor: pointer; font-weight: 600; transition: 0.2s; font-family: inherit;}
        .btn-table:hover { background-color: #ccc; }
        .btn-table.active { background-color: var(--primary-teal); color: white; }
        
        .table-responsive { overflow-x: auto; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; font-size: 14px; min-width: 800px; }
        th, td { padding: 12px 15px; text-align: left; border-bottom: 1px solid #ddd; }
        th { background-color: var(--primary-teal); color: white; font-weight: 600; white-space: nowrap; }
        tr:hover { background-color: #f1f9f9; }
        
        .view-section, .data-table-container { display: none; }
        .view-section.active, .data-table-container.active { display: block; }
        .error-box { background-color: #ffe6e6; border-left: 5px solid red; padding: 15px; color: #cc0000; font-weight: bold; margin-bottom: 20px; }

        /* ========================================== */
        /* FOOTER DESIGN                          */
        /* ========================================== */
        footer { background-color: var(--dark-teal); color: white; padding: 50px 10% 20px; font-size: 14px; margin-top: auto; }
        .footer-top { display: flex; justify-content: space-between; align-items: flex-end; margin-bottom: 40px; border-bottom: 1px solid rgba(255,255,255,0.1); padding-bottom: 30px;}
        .footer-brand h2 { font-size: 32px; font-style: italic; margin: 0 0 5px 0; }
        .footer-brand p { margin: 0; font-weight: 500; letter-spacing: 1px; }
        
        .newsletter-box { display: flex; align-items: center; }
        .newsletter-box input { padding: 12px 20px; border: none; outline: none; width: 300px; font-family: inherit; }
        .newsletter-box button { background-color: var(--primary-teal); border: 1px solid white; color: white; padding: 11px 25px; cursor: pointer; font-weight: 600; transition: 0.3s; }
        .newsletter-box button:hover { background-color: white; color: var(--primary-teal); }
        
        .footer-grid { display: grid; grid-template-columns: 1.5fr 1fr 1fr 1fr; gap: 30px; margin-bottom: 30px; }
        .footer-col h3 { font-size: 16px; margin-bottom: 20px; text-transform: uppercase; }
        .footer-col p, .footer-col li { margin-bottom: 12px; color: #ddd; line-height: 1.5; }
        .footer-col ul { list-style: none; padding: 0; margin: 0; }
        .footer-col li { cursor: pointer; transition: 0.2s; }
        .footer-col li:hover { color: var(--accent-yellow); }
        
        .footer-bottom { text-align: left; padding-top: 20px; border-top: 1px solid rgba(255,255,255,0.1); color: #bbb; font-size: 12px; }
    </style>
</head>
<body>

    <header>
        
        <div class="header-bottom">
            <div class="logo">SaigonBus DB</div>
            <div class="nav-links">
                <button onclick="switchMainView('home', this)" class="active">HOME</button>
                
                <button onclick="switchMainView('show-data', this)">DATABASE</button>
                <button onclick="switchMainView('search', this)">SEARCH</button>
                <button onclick="switchMainView('update', this)">UPDATE</button>
                
            </div>
        </div>
    </header>

    <div class="hero">
        <img src="SaiGonBus.png" alt="Saigon Bus" onerror="this.src='https://cdn-icons-png.flaticon.com/512/2038/2038006.png';">
        <h1>Bus Network<br>Management System</h1>
    </div>

    <div class="main-wrapper">
        <div class="container">
            
            <div id="home" class="view-section active">
                <div class="section-title">Welcome to SaigonBus</div>
                <div class="home-content">
                    <p>Welcome to the <strong>Saigon Passenger Transport Joint Stock Company (SaigonBus)</strong> Information Management System.</p>
                    <p>SaigonBus is a leading public transportation provider in Ho Chi Minh City, committed to delivering safe, reliable, and comfortable bus services to millions of passengers across the metropolitan area.</p>
                    
                    <h3>Our Mission</h3>
                    <p>To continuously improve the quality of public transport, reduce traffic congestion, and contribute to a greener environment for our city. We leverage modern technology to ensure our operations are efficient and accessible to everyone.</p>
                    
                    <h3>System Features</h3>
                    <ul>
                        <li><strong>Database:</strong> View detailed records of our Drivers, Buses, Routes, and Stations.</li>
                        <li><strong>Search:</strong> Quickly look up operational information using IDs or Keywords.</li>
                        <li><strong>Update:</strong> Maintain real-time logs of bus statuses and maintenance schedules.</li>
                    </ul>

                    <h3>Contact Information</h3>
                    <ul>
                        <li><strong>Head Office:</strong> 1646A Vo Van Kiet Binh Phu Ward, Ho Chi Minh City</li>
                        <li><strong>Hotline:</strong> 0946225822</li>
                        <li><strong>Email:</strong> dhqt.tvts@hcmiu.edu.vn</li>
                    </ul>
                </div>
            </div>

            <div id="show-data" class="view-section">
                <div class="section-title">System Database Tables</div>
                
                <?php if (!$db_connected): ?>
                    <div class="error-box">⚠️ <?php echo $error_msg; ?></div>
                <?php else: ?>
                    <div class="data-controls">
                        <button class="btn-table active" onclick="switchTable('table-driver', this)">Driver Table</button>
                        <button class="btn-table" onclick="switchTable('table-bus', this)">Bus Table</button>
                        <button class="btn-table" onclick="switchTable('table-route', this)">Route Table</button>
                        <button class="btn-table" onclick="switchTable('table-station', this)">Station Table</button>
                    </div>

                    <?php
                    function renderTable($conn, $tableName, $containerId, $isActive) {
                        $class = $isActive ? "data-table-container active" : "data-table-container";
                        echo "<div id='$containerId' class='$class'><div class='table-responsive'>";
                        $sql = "SELECT * FROM $tableName";
                        $result = $conn->query($sql);
                        if ($result && $result->num_rows > 0) {
                            echo "<table><thead><tr>";
                            foreach ($result->fetch_fields() as $field) {
                                $normal_name = str_replace('Id', 'ID', ucwords(str_replace('_', ' ', $field->name)));
                                echo "<th>" . $normal_name . "</th>";
                            }
                            echo "</tr></thead><tbody>";
                            while($row = $result->fetch_assoc()) {
                                echo "<tr>";
                                foreach ($row as $data) { echo "<td>" . htmlspecialchars($data ?? '') . "</td>"; }
                                echo "</tr>";
                            }
                            echo "</tbody></table>";
                        } else {
                            echo "<p>No records found in table: $tableName.</p>";
                        }
                        echo "</div></div>";
                    }
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
                        <input type="text" name="bus_id" placeholder="Enter Bus ID (e.g., B01)..." required style="max-width: 250px;">
                        <select name="status">
                            <option value="Active">Active</option>
                            <option value="Inactive">Inactive</option>
                            <option value="Maintenance">Maintenance</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <input type="date" name="maintenance" placeholder="Enter maintenance details (e.g., Oil change)..." required>
                        <button type="submit" class="btn-submit" style="background-color: var(--accent-yellow); color: black;">Save Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <footer>
        <div class="footer-top">
            <div class="footer-brand">
                <h2>SaigonBus</h2>
                <p>SAIGON PASSENGER TRANSPORT JOINT STOCK COMPANY.</p>
            </div>
            <div class="newsletter-box">
                <input type="email" placeholder="Enter email to receive news...">
                <button>SUBSCRIBE</button>
            </div>
        </div>
        
        <div class="footer-grid">
            <div class="footer-col">
                <p>📍 1646A Vo Van Kiet Binh Phu Ward, Ho Chi Minh City</p>
                <p>📞 0946225822</p>
                <p>✉️ dhqt.tvts@hcmiu.edu.vn</p>
                <p>🕒 08h00 - 17h00</p>
            </div>
            
                
            
    </footer>

    <script>
        function switchMainView(viewId, btnElement) {
            // Ẩn tất cả các sections
            document.querySelectorAll('.view-section').forEach(el => el.classList.remove('active'));
            // Xóa class active của tất cả các nút
            document.querySelectorAll('.nav-links button').forEach(btn => btn.classList.remove('active'));
            // Hiện section được chọn và làm sáng nút
            document.getElementById(viewId).classList.add('active');
            btnElement.classList.add('active');
        }
        
        function switchTable(tableId, btnElement) {
            document.querySelectorAll('.data-table-container').forEach(el => el.classList.remove('active'));
            document.querySelectorAll('.btn-table').forEach(btn => btn.classList.remove('active'));
            document.getElementById(tableId).classList.add('active');
            btnElement.classList.add('active');
        }
    </script>
</body>
</html>