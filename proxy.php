<?php
/**
 * ERASE - Backend Proxy
 * Ye file API Key ko safe rakhti hai.
 */

// Agar direct access karne ki koshish ho toh block karein
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    die("Access Denied");
}

// --- APNI API KEY YAHAN DALIE ---
$API_KEY = '1NbHVfmvT3f94f5Z4mEbsYPg'; 

if (isset($_FILES['image_file'])) {
    $file_path = $_FILES['image_file']['tmp_name'];
    $file_type = $_FILES['image_file']['type'];
    $file_name = $_FILES['image_file']['name'];

    $ch = curl_init();

    curl_setopt($ch, CURLOPT_URL, 'https://api.remove.bg/v1.0/removebg');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_POST, 1);
    
    // Headers mein key bhejein (User ko nahi dikhegi)
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'X-Api-Key: ' . $API_KEY
    ]);

    $post_data = [
        'image_file' => new CURLFile($file_path, $file_type, $file_name),
        'size' => 'auto'
    ];

    curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);

    $result = curl_exec($ch);
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);

    if ($http_code == 200) {
        header('Content-Type: image/png');
        echo $result;
    } else {
        header('HTTP/1.1 500 Internal Server Error');
        echo "Error: API returned code " . $http_code;
    }

    curl_close($ch);
}
?>
