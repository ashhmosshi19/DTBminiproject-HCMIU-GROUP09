<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Problem 10.1: Show Data</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        table { border-collapse: collapse; width: 100%; margin-bottom: 30px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
    </style>
</head>
<body>

    <h2>Bus System Database Data</h2>

    <?php
    // Database credentials
    $servername = "localhost";
    $username = "tritin2805";
    $password = "tin280506";
    $dbname = "bus_system";

    // Create connection
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Array of tables to display
    $tables = ['route', 'station', 'bus', 'driver', 'contain'];

    foreach ($tables as $table) {
        echo "<h3>Table: " . ucfirst($table) . "</h3>";
        
        $sql = "SELECT * FROM $table";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            echo "<table>";
            // Fetch field names for table headers
            $fields = $result->fetch_fields();
            echo "<tr>";
            foreach ($fields as $field) {
                echo "<th>" . $field->name . "</th>";
            }
            echo "</tr>";

            // Fetch data rows
            while($row = $result->fetch_assoc()) {
                echo "<tr>";
                foreach ($row as $data) {
                    echo "<td>" . $data . "</td>";
                }
                echo "</tr>";
            }
            echo "</table>";
        } else {
            echo "<p>0 results found in $table table.</p>";
        }
    }

    $conn->close();
    ?>

</body>
</html>

