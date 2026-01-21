<?php 
/**  * Database Configuration File  *   * This is a sample configuration file for the screening test.  * Candidates should create their own database setup.  */  
// Main database connection 
$servername = "localhost"; 
$username = "your_username"; 
$password = "your_password"; 
$dbname = "main_db";  
// Create main database connection 
$con = mysqli_connect($servername, $username, $password, $dbname);  
// Check connection 
if (!$con) {     
    die("Connection failed: " . mysqli_connect_error()); }  
    /**  * Get site-specific database configuration  *   * 
     * @param int $site_id The site ID  * 
     * @return array|false Database configuration array or false if not found  */ 
    function getSiteDbConfig($site_id) {     
        // Sample site database configurations     
        $site_configs = [ 1 => [ 'host' => 'localhost', 'username' => 'site1_user',  'password' => 'site1_pass',  'dbname' => 'site1_db' ]
        ,         2 => ['host' => 'localhost','username' => 'site2_user','password' => 'site2_pass', 'dbname' => 'site2_db' ],     
        ];          
        return isset($site_configs[$site_id]) ? $site_configs[$site_id] : false; } ?>