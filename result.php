<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Results - Bus Network</title>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary-teal: #00828e; 
            --light-bg: #f4f7f6;
            --text-dark: #333;
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

        .top-bar h2 {
            margin: 0;
            font-size: 20px;
        }

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

        .btn-back:hover {
            background-color: white;
            color: var(--primary-teal);
        }

        .container {
            max-width: 1000px;
            margin: 40px auto;
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        }

        .table-responsive {
            overflow-x: auto;
            margin-top: 20px;
        }

   
        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 14px;
            min-width: 800px; /* Đảm bảo các cột không bị ép quá nhỏ */
        }

        th, td {
            padding: 12px 15px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        th {
            background-color: var(--primary-teal);
            color: white;
            font-weight: 600;
            white-space: nowrap;
        }

        tr:hover {
            background-color: #f1f9f9;
        }

        .error { color: red; font-weight: bold; }
        .keyword-highlight { color: #f59f00; font-weight: bold; text-transform: uppercase; }
        h3 { margin-bottom: 5px; }
    </style>
</head>
<body>

    <div class="top-bar">
        <h2>Bus Network System</h2>
        <a href="web1.php" class="btn-back">⬅ Back to Home</a>
    </div>

    <div class="container">
        <?php
        
        ini_set('display_errors', 1);
        ini_set('display_startup_errors', 1);
        error_reporting(E_ALL);

     
        $type = isset($_POST["type"]) ? $_POST["type"] : '';
        $name = isset($_POST["name"]) ? $_POST["name"] : '';

        if (empty($type) || empty($name)) {
            echo "<p class='error'>Error: Please enter a keyword to search.</p>";
        } else {
         
            $servername = "localhost";
            $username = "tritin2805"; 
            $password = "tin280506";     
            $dbname = "bus_system";

            $conn = new mysqli($servername, $username, $password, $dbname);

            if ($conn->connect_error) {
                die("<p class='error'>Database connection failed: " . $conn->connect_error . "</p>");
            }

         
            $safe_type = $conn->real_escape_string($type);
            $safe_name = $conn->real_escape_string($name);

        
            echo "<h3>Search results in <span class='keyword-highlight'>" . strtoupper($safe_type) . "</span> table</h3>";
            echo "<p>Keyword: <span class='keyword-highlight'>" . htmlspecialchars($safe_name) . "</span></p>";

          
            $table_columns = [
                "driver"  => "driver_id, first_name, last_name",
                "bus"     => "bus_id, bus_num, plate_num",
                "route"   => "route_id, route_name",
                "station" => "station_id, station_name"
            ];

            $sql = "";
            
         
            if (array_key_exists($safe_type, $table_columns)) {
                $columns_to_search = $table_columns[$safe_type];
                
                
                $words = preg_split('/\s+/', trim($name)); 
                $conditions = [];
                
             
                foreach ($words as $word) {
                    $safe_word = $conn->real_escape_string($word);
                    $conditions[] = "CONCAT_WS(' ', $columns_to_search) LIKE '%$safe_word%'";
                }
                
         
                $where_clause = implode(' AND ', $conditions);
                
               
                $sql = "SELECT * FROM $safe_type WHERE " . $where_clause;
            }

     
            if ($sql != "") {
                $result = $conn->query($sql);

                if ($result && $result->num_rows > 0) {
                  
                    echo "<div class='table-responsive'>";
                    echo "<table>";
             
                    $fields = $result->fetch_fields();
                    echo "<thead><tr>";
                    foreach ($fields as $field) {
                        $normal_name = ucwords(str_replace('_', ' ', $field->name));
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
                    echo "</div>"; 
                } else {
                    echo "<p>No records found matching the keyword <strong>'$safe_name'</strong>.</p>";
                }
            } else {
                echo "<p class='error'>Invalid table selection.</p>";
            }

           
            $conn->close();
        }
        ?>
    </div>

</body>
</html>
