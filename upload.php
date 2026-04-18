<?php
header("Content-Type: application/json");

$targetDir = "uploads/";
if (!file_exists($targetDir)) {
    mkdir($targetDir, 0777, true);
}

function generateRandomString($length = 5) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyz';
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, strlen($characters) - 1)];
    }
    return $randomString;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(["status" => "error", "message" => "Metode tidak diizinkan"]);
    exit;
}

if (!isset($_FILES["fileToUpload"])) {
    echo json_encode(["status" => "error", "message" => "Tidak ada file yang dipilih"]);
    exit;
}

$originalName = $_FILES["fileToUpload"]["name"];
$fileType = strtolower(pathinfo($originalName, PATHINFO_EXTENSION));

do {
    $extension = $fileType ? "." . $fileType : "";
    $newFileName = generateRandomString(5) . $extension;
    $targetFile = $targetDir . $newFileName;
} while (file_exists($targetFile));

if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $targetFile)) {
    echo json_encode([
        "status" => "success",
        "author" => "breaksek",
        "message" => "Berhasil diunggah",
        "file_name" => $newFileName,
        "url" => $targetFile
    ]);
} else {
    echo json_encode([
        "status" => "error", 
        "author" => "breaksek", 
        "message" => "Gagal menyimpan file di server"
    ]);
}
