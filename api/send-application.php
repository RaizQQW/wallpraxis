<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';

try {
    // Get POST data
    $data = json_decode(file_get_contents('php://input'), true);
    
    if (!$data) {
        throw new Exception('No data received');
    }

    $mail = new PHPMailer(true);

    // SMTP Configuration for IONOS
    $mail->isSMTP();
    $mail->Host = 'smtp.ionos.de';  // IONOS SMTP server
    $mail->SMTPAuth = true;
    $mail->Username = 'social@wallpraxis-winsen.de';  // Your IONOS email
    $mail->Password = 'YOUR_PASSWORD_HERE';  // Your IONOS email password
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port = 587;

    // Email settings
    $mail->setFrom('social@wallpraxis-winsen.de', 'Wallpraxis Website');
    $mail->addAddress('social@wallpraxis-winsen.de', 'Wallpraxis Team');
    $mail->addReplyTo($data['email'] ?? 'social@wallpraxis-winsen.de', $data['name'] ?? 'Website Bewerber');

    // Set email content
    $mail->isHTML(true);
    $mail->CharSet = 'UTF-8';
    $mail->Subject = $data['subject'] ?? 'Neue Bewerbung über Online-Formular';

    // Create HTML message
    $messageHtml = "
        <h2>Neue Bewerbung eingegangen</h2>
        <table style='border-collapse: collapse; width: 100%;'>
            <tr>
                <td style='padding: 8px; border-bottom: 1px solid #ddd;'><strong>Name:</strong></td>
                <td style='padding: 8px; border-bottom: 1px solid #ddd;'>{$data['name']}</td>
            </tr>
            <tr>
                <td style='padding: 8px; border-bottom: 1px solid #ddd;'><strong>Email:</strong></td>
                <td style='padding: 8px; border-bottom: 1px solid #ddd;'>{$data['email']}</td>
            </tr>
            <tr>
                <td style='padding: 8px; border-bottom: 1px solid #ddd;'><strong>Telefon:</strong></td>
                <td style='padding: 8px; border-bottom: 1px solid #ddd;'>{$data['phone']}</td>
            </tr>";

    // Add education if present (for Ausbildung applications)
    if (isset($data['education'])) {
        $messageHtml .= "
            <tr>
                <td style='padding: 8px; border-bottom: 1px solid #ddd;'><strong>Schulabschluss:</strong></td>
                <td style='padding: 8px; border-bottom: 1px solid #ddd;'>{$data['education']}</td>
            </tr>";
    }

    $messageHtml .= "
            <tr>
                <td style='padding: 8px; border-bottom: 1px solid #ddd;'><strong>Nachricht:</strong></td>
                <td style='padding: 8px; border-bottom: 1px solid #ddd;'>" . nl2br($data['message'] ?? '-') . "</td>
            </tr>
        </table>
        <p style='margin-top: 20px; color: #666;'>Diese Bewerbung wurde über das Online-Formular auf der Website eingereicht.</p>";

    $mail->Body = $messageHtml;
    $mail->AltBody = strip_tags(str_replace(['<br>', '</tr>'], "\n", $messageHtml));

    // Handle file attachment if present
    if (isset($_FILES['resume']) && $_FILES['resume']['error'] === UPLOAD_ERR_OK) {
        $mail->addAttachment(
            $_FILES['resume']['tmp_name'],
            'Lebenslauf-' . preg_replace('/[^a-zA-Z0-9]/', '', $data['name']) . '.' . pathinfo($_FILES['resume']['name'], PATHINFO_EXTENSION)
        );
    }

    // Send email
    $mail->send();

    // Return success response
    echo json_encode([
        'success' => true,
        'message' => 'Bewerbung erfolgreich gesendet'
    ]);

} catch (Exception $e) {
    // Return error response
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Fehler beim Senden der Bewerbung: ' . $e->getMessage()
    ]);
} 