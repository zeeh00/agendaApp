<?php
// Database configuration
$db_config = array(
    'host' => 'localhost',
    'username' => 'root',
    'password' => '',
    'dbname' => 'agenda_app'
);

// Establish database connection
$conn = new mysqli($db_config['host'], $db_config['username'], $db_config['password'], $db_config['dbname']);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Function to handle database errors
function handle_db_error($conn, $message = 'Database error') {
    // Log the error
    error_log($message . ': ' . $conn->error);

    // Display a generic error message to the user
    die('Oops! Something went wrong. Please try again later.');
}

// Function to execute prepared statement and handle errors
function execute_query($conn, $query, $params = array()) {
    $stmt = $conn->prepare($query);
    if (!$stmt) {
        handle_db_error($conn, 'Failed to prepare statement');
    }

    // Bind parameters if any
    if (!empty($params)) {
        $types = '';
        $bind_params = array();
        foreach ($params as $param) {
            if (is_int($param)) {
                $types .= 'i'; // integer
            } elseif (is_float($param)) {
                $types .= 'd'; // double
            } else {
                $types .= 's'; // string
            }
            $bind_params[] = &$param;
        }
        array_unshift($bind_params, $types);
        call_user_func_array(array($stmt, 'bind_param'), $bind_params);
    }

    // Execute the statement
    $result = $stmt->execute();
    if (!$result) {
        handle_db_error($conn, 'Failed to execute statement');
    }

    return $result;
}
?>
