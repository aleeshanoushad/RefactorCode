<?php
declare(strict_types=1);

header('Content-Type: application/json');
require_once 'config.php';

/**
 * Send JSON response and exit
 */
function respond(int $statusCode, array $data): void
{
    http_response_code($statusCode);
    echo json_encode($data);
    exit;
}

/**
 * Validate integer input
 */
function getIntParam(string $key, int $default = 0): int
{
    return filter_input(INPUT_GET, $key, FILTER_VALIDATE_INT) ?? $default;
}

$bookingId = getIntParam('id');
$siteId    = getIntParam('site_id', 1);

if ($bookingId <= 0) {
    respond(400, ['error' => 'Invalid booking ID']);
}

/**
 * Fetch booking from main DB
 */
$bookingStmt = $con->prepare(
    "SELECT id, email, booking_date, status FROM bookings WHERE id = ?"
);
$bookingStmt->bind_param('i', $bookingId);
$bookingStmt->execute();
$bookingResult = $bookingStmt->get_result();
$booking = $bookingResult->fetch_assoc();

if (!$booking) {
    respond(404, ['error' => 'Booking not found']);
}

/**
 * Connect to site DB
 */
$siteConfig = getSiteDbConfig($siteId);
if (!$siteConfig) {
    respond(400, ['error' => 'Invalid site ID']);
}

$con2 = @mysqli_connect(
    $siteConfig['host'],
    $siteConfig['username'],
    $siteConfig['password'],
    $siteConfig['dbname']
);

if (!$con2) {
    respond(500, ['error' => 'Site database connection failed']);
}

/**
 * Fetch customer
 */
$customerStmt = $con2->prepare(
    "SELECT id, name, email FROM customers WHERE email = ?"
);
$customerStmt->bind_param('s', $booking['email']);
$customerStmt->execute();
$customerResult = $customerStmt->get_result();
$customer = $customerResult->fetch_assoc();

/**
 * Update booking status only if needed
 */
if ($booking['status'] !== 'confirmed') {
    $updateStmt = $con->prepare(
        "UPDATE bookings SET status = 'confirmed', updated_at = NOW() WHERE id = ?"
    );
    $updateStmt->bind_param('i', $bookingId);
    $updateStmt->execute();
}

/**
 * Cleanup
 */
$bookingStmt->close();
$customerStmt->close();
mysqli_close($con2);

/**
 * Response (backward compatible)
 */
respond(200, [
    'booking'  => $booking,
    'customer' => $customer
]);
