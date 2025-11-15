<?php
session_start();
require_once '../../config/check_login.php';
require_once '../../repos/Image.php';
require_once '../../repos/Orders.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    // Only allow POST requests
    header('Location: /clients/services.php');
    exit();
}

// --- 1. Handle Image Uploads ---
$uploadedImagePaths = [];
$uploadDir = __DIR__ . '/../../assets/images/orders/';

if (!file_exists($uploadDir)) {
    mkdir($uploadDir, 0777, true);
}

if (isset($_FILES['images']) && !empty($_FILES['images']['name'][0])) {
    $imageFiles = $_FILES['images'];
    $fileCount = count($imageFiles['name']);

    // Limit to 5 images
    for ($i = 0; $i < min($fileCount, 5); $i++) {
        // Check for upload errors
        if ($imageFiles['error'][$i] === UPLOAD_ERR_OK) {
            $tmpName = $imageFiles['tmp_name'][$i];
            
            // Generate a unique filename to prevent overwrites
            $fileExtension = pathinfo($imageFiles['name'][$i], PATHINFO_EXTENSION);
            $newFileName = uniqid('order_', true) . '.' . $fileExtension;
            $destination = $uploadDir . $newFileName;

            if (move_uploaded_file($tmpName, $destination)) {
                // Store the relative path for the database
                $uploadedImagePaths[] = 'orders/' . $newFileName;
            }
        }
    }
}

// --- 2. Save Image Paths to Database ---
$imageId = null;
if (!empty($uploadedImagePaths)) {
    $imageRepo = new Image();
    // The saveImages method in Image.php needs to be adjusted to handle an array of paths
    // and return the last insert ID. The current implementation is already good for this.
    $imageId = $imageRepo->saveImages($uploadedImagePaths);
}

// --- 3. Gather Form Data ---
$userId = $_SESSION['user_id']; // From check_login.php
$username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_STRING);
$phone = filter_input(INPUT_POST, 'phone', FILTER_SANITIZE_STRING);
$problemId = filter_input(INPUT_POST, 'problem', FILTER_VALIDATE_INT);
$problemTxt = filter_input(INPUT_POST, 'problem_text', FILTER_SANITIZE_STRING);
$latitude = filter_input(INPUT_POST, 'latitude', FILTER_SANITIZE_STRING);
$longitude = filter_input(INPUT_POST, 'longitude', FILTER_SANITIZE_STRING);
$province = filter_input(INPUT_POST, 'province', FILTER_VALIDATE_INT);
$district = filter_input(INPUT_POST, 'district', FILTER_VALIDATE_INT);
$commune = filter_input(INPUT_POST, 'commune', FILTER_VALIDATE_INT);
$village = filter_input(INPUT_POST, 'village', FILTER_VALIDATE_INT);

// Basic validation
if (!$username || !$phone || !$problemId || !$problemTxt || !$province || !$district || !$commune || !$village) {
    // Handle validation error, maybe redirect back with an error message
    $_SESSION['error_message'] = "Please fill all required fields.";
    header('Location: ' . $_SERVER['HTTP_REFERER']);
    exit();
}

// --- 4. Create the Order ---
$orderData = [
    'userId' => $userId,
    'username' => $username,
    'phone' => $phone,
    'problemId' => $problemId,
    'problemTxt' => $problemTxt,
    'imageId' => $imageId, // Can be null if no images were uploaded
    'latitude' => $latitude,
    'longitude' => $longitude,
    'province' => $province, 'district' => $district, 'commune' => $commune, 'village' => $village
];

$orderRepo = new Orders();
$success = $orderRepo->createOrder($orderData);

// --- 5. Redirect on Success ---
if ($success) {
    header('Location: /clients/ordered.php?status=success');
    exit();
} else {
    // Handle DB insertion error
    $_SESSION['error_message'] = "There was a problem submitting your order. Please try again.";
    header('Location: ' . $_SERVER['HTTP_REFERER']);
    exit();
}
?>