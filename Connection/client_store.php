<?php

// Capture data from the form
$fullName = $_POST['full_name'];
$email = $_POST['email'];
$password = password_hash($_POST['password'], PASSWORD_BCRYPT); // Secure password
$companyName = $_POST['company_name'];
$industryType = $_POST['industry_type'];
$contactNumber = $_POST['contact_number'];

// Prepare the SQL query to insert data into the 'clients' table
$sql = "INSERT INTO clients (full_name, email, password, company_name, industry_type, contact_number) 
        VALUES (?, ?, ?, ?, ?, ?)";

$stmt = $conn->prepare($sql);
if ($stmt) {
    // Bind parameters to the SQL query
    $stmt->bind_param("ssssss", $fullName, $email, $password, $companyName, $industryType, $contactNumber);

    // Execute the query
    if ($stmt->execute()) {
        echo "Registration successful!";
        // Redirect to a success page or login page
        header("Location: success.php");
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close(); // Close the statement
} else {
    echo "Error preparing statement: " . $conn->error;
}

// Close the database connection
$conn->close();
?>
