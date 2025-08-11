<?php
// enviar.php - recibe formularios de evaluacion/analisis y envía correo con adjuntos
date_default_timezone_set('America/Lima');
$to = 'exequaturperu@gmail.com';

// simple validation
$nombre = isset($_POST['nombre']) ? trim($_POST['nombre']) : '';
$email = isset($_POST['email']) ? trim($_POST['email']) : '';
$servicio = isset($_POST['servicio']) ? trim($_POST['servicio']) : 'Formulario de EXEQUATUR';

if (!$nombre || !$email) {
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'error' => 'Falta nombre o correo']);
    exit;
}

// Prepare email
$subject = "Nuevo envío: " . $servicio;
$boundary = md5(uniqid(time()));
$fecha = date('Y-m-d H:i:s');

// Headers
$headers = "From: "EXEQUATUR PERU" <no-reply@exequatur.pe>\r\n";
$headers .= "MIME-Version: 1.0\r\n";
$headers .= "Content-Type: multipart/mixed; boundary="{$boundary}"\r\n";

// Body (text part)
$body = "--{$boundary}\r\n";
$body .= "Content-Type: text/plain; charset=UTF-8\r\n\r\n";
$body .= "Nuevo envío desde la web de EXEQUATUR PERÚ\r\n";
$body .= "Fecha: {$fecha}\r\n";
$body .= "Servicio: {$servicio}\r\n";
$body .= "Nombre: {$nombre}\r\n";
$body .= "Email: {$email}\r\n\r\n";

// Optional additional fields
foreach ($_POST as $k => $v) {
    if (in_array($k, ['nombre','email','servicio'])) continue;
    $body .= ucfirst($k) . ": " . strip_tags($v) . "\r\n";
}

// Handle file uploads
$files_attached = 0;
if (!empty($_FILES)) {
    foreach ($_FILES as $f) {
        if ($f['error'] == UPLOAD_ERR_OK) {
            $tmpName = $f['tmp_name'];
            $fileName = basename($f['name']);
            $fileSize = filesize($tmpName);
            $handle = fopen($tmpName, 'rb');
            $content = fread($handle, $fileSize);
            fclose($handle);
            $content = chunk_split(base64_encode($content));
            $body .= "--{$boundary}\r\n";
            $body .= "Content-Type: application/octet-stream; name=\"{$fileName}\"\r\n";
            $body .= "Content-Transfer-Encoding: base64\r\n";
            $body .= "Content-Disposition: attachment; filename=\"{$fileName}\"\r\n\r\n";
            $body .= $content . "\r\n\r\n";
            $files_attached++;
        }
    }
}

// If no attachments, still close boundary
$body .= "--{$boundary}--\r\n";

// Send mail
$sent = mail($to, $subject, $body, $headers);

// Return JSON response
header('Content-Type: application/json');
if ($sent) {
    echo json_encode(['success' => true, 'files' => $files_attached]);
} else {
    echo json_encode(['success' => false, 'error' => 'No se pudo enviar el correo (mail failed).']);
}
?>