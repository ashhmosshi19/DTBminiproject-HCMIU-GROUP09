<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Result - Bus Network</title>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary-teal: #196b73; 
            --light-bg: #f4f7f6;
            --text-dark: #333;
            --success-green: #28a745;
        }

        body {
            font-family: 'Montserrat', sans-serif;
            margin: 0;
            padding: 0;
            background-color: var(--light-bg);
            color: var(--text-dark);
        }

        .top-bar {
            background-color: var(--primary-teal);
            padding: 15px 50px;
            color: white;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .top-bar h2 { margin: 0; font-size: 20px; }

        .btn-back {
            color: white;
            text-decoration: none;
            border: 1px solid white;
            padding: 8px 15px;
            border-radius: 5px;
            font-size: 14px;
            font-weight: 600;
            transition: 0.3s;
        }

        .btn-back:hover { background-color: white; color: var(--primary-teal); }

        .container {
            max-width: 1000px;
            margin: 40px auto;
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        }

        .table-responsive { overflow-x: auto; margin-top: 20px; }
        table { width: 100%; border-collapse: collapse; font-size: 14px; min-width: 800px; }
        th, td { padding: 12px 15px; text-align: left; border-bottom: 1px solid #ddd; }
        th { background-color: var(--primary-teal); color: white; font-weight: 600; white-space: nowrap; }
        tr:hover { background-color: #f1f9f9; }

        .success { color: var(--success-green); font-weight: bold; background: #e8f5e9; padding: 15px; border-left: 5px solid var(--success-green); margin-bottom: 20px;}
        .error { color: red; font-weight: bold; background: #ffe6e6; padding: 15px; border-left: 5px solid red; margin-bottom: 20px;}
        .highlight { font-weight: bold; color: var(--primary-teal); }
    </style>
</head>
<body>

    <div class="top-bar">
        <h2>Bus Network System</h2>
        <a href="web1.php" class="btn-back">⬅ Back to Home</a>
    </div>

    <div class="container">
        <?php
        // Enable error reporting
        ini_set('display_errors', 1);
        error_reporting(E_ALL);

        // Get input values
        $bus_id = isset($_POST["bus_id"]) ? $_POST["bus_id"] : '';
        $status = isset($_POST["status"]) ? $_POST["status"] : '';
        $maintenance_date = isset($_POST["maintenance"]) ? $_POST["maintenance"] : '';

        if (empty($bus_id) || empty($maintenance_date)) {
            die("<div class='error'>Error: Bus ID and Maintenance Date are required.</div>");
        }

        // Connect to server and use database
        $servername = "localhost";
        $username = "tritin2805"; 
        $password = "tin280506";     
        $dbname = "bus_system";

        $conn = new mysqli($servername, $username, $password, $dbname);

        if ($conn->connect_error) {
            die("<div class='error'>Connection failed: " . $conn->connect_error . "</div>");
        }

        // Sanitize inputs
        $safe_bus_id = $conn->real_escape_string($bus_id);
        $safe_status = $conn->real_escape_string($status);
        $safe_maintenance_date = $conn->real_escape_string($maintenance_date);

        // Execute UPDATE query for the date column
        $update_sql = "UPDATE bus SET status='$safe_status', maintenance='$safe_maintenance_date' WHERE bus_id='$safe_bus_id'";
        
        if ($conn->query($update_sql) === TRUE) {
            if ($conn->affected_rows > 0) {
                echo "<div class='success'>✅ Record updated successfully! Bus <span class='highlight'>$safe_bus_id</span> is now marked as '$safe_status' with the maintenance date of <span class='highlight'>$safe_maintenance_date</span>.</div>";
                
                // Fetch and display the updated record to prove it worked
                echo "<h3>Current Record Details:</h3>";
                $select_sql = "SELECT * FROM bus WHERE bus_id='$safe_bus_id'";
                $result = $conn->query($select_sql);

                if ($result && $result->num_rows > 0) {
                    echo "<div class='table-responsive'><table><thead><tr>";
                    $fields = $result->fetch_fields();
                    foreach ($fields as $field) {
                        $normal_name = str_replace('Id', 'ID', ucwords(str_replace('_', ' ', $field->name)));
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
                    echo "</tbody></table></div>";
                }
            } else {
                echo "<div class='error'>⚠️ No update made. Either the Bus ID '<span class='highlight'>$safe_bus_id</span>' does not exist, or you submitted the exact same data that is already in the database.</div>";
            }
        } else {
            echo "<div class='error'>Error updating record: " . $conn->error . "</div>";
        }

        $conn->close();
        ?>
    </div>

</body>
</html>