<?php /** * Legacy Booking System * This file contains legacy code that needs to be refactored *  * DO NOT use this code in production - it contains security vulnerabilities * and code quality issues that need to be addressed. */ 
// Include database configuration 
include_once 'config.php'; 
// Get parameters from URL 
$booking_id = isset($_GET['id']) ? $_GET['id'] : 0; 
$site_id = isset($_GET['site_id']) ? $_GET['site_id'] : 1; 
// Get booking from main database
 $sql = "SELECT * FROM bookings WHERE id=" . $booking_id; 
 $result = mysqli_query($con, $sql); 
 $booking = mysqli_fetch_array($result); 
 $customer = null; 
 if ($booking) { 
    // Connect to site-specific database based on site_id 
    $db_config = getSiteDbConfig($site_id); 
    if ($db_config) { 
        // Create connection to site database 
        $con2 = mysqli_connect( $db_config['host'],  $db_config['username'],  $db_config['password'],  $db_config['dbname'] );        
         // Get customer details from site database         
         $sql2 = "SELECT * FROM customers WHERE email='" . $booking['email'] . "'";        
          $result2 = mysqli_query($con2, $sql2);        
           $customer = mysqli_fetch_array($result2);                  
           // Update booking status in main database         
           $update_sql = "UPDATE bookings SET status='confirmed', updated_at=NOW() WHERE id=" . $booking_id;         
           mysqli_query($con, $update_sql);                  
           // Close site database connection         
           mysqli_close($con2);     
           } 
           }  
           // Display results as JSON 
           header('Content-Type: application/json'); 
           echo json_encode([     'booking' => $booking,     'customer' => $customer ]); 
           ?> 