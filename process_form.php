<?php
require_once 'includes/db_connect.php';

function redirectToContact(string $status, string $message): never
{
    $query = http_build_query([
        'submission' => $status,
        'message' => $message,
    ]);
    header('Location: contact.html?' . $query . '#formNotice', true, 303);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $full_name_input = $_POST['full_name'] ?? '';
    $full_name = is_string($full_name_input) ? trim($full_name_input) : '';
    $email_input = $_POST['email'] ?? '';
    $email = is_string($email_input) ? trim($email_input) : '';
    $phone_input = $_POST['phone'] ?? '';
    $phone = is_string($phone_input) ? trim($phone_input) : '';
    $reason_input = $_POST['reason'] ?? '';
    $reason_input = is_string($reason_input) ? strtolower(trim($reason_input)) : '';
    $user_message_input = $_POST['message'] ?? '';
    $user_message = is_string($user_message_input) ? trim($user_message_input) : '';

    $reason_values = [
        'join' => 'Join',
        'i want to join as a netballer' => 'Join',
        'general inquiry' => 'General Inquiry',
        'general enquiry' => 'General Inquiry',
        'general_enquiry' => 'General Inquiry',
        'sponsorship' => 'Sponsorship',
        'sponsorship/ partnership' => 'Sponsorship',
        'sponsorship/partnership' => 'Sponsorship',
    ];
    $reason = $reason_values[$reason_input] ?? '';

    $errors = [];
    if ($full_name === '' || strlen($full_name) < 2) {
        $errors[] = 'Full name is required.';
    }

    if (strlen($full_name) > 150) {
        $errors[] = 'Full name must be 150 characters or fewer.';
    }
    if ($email === '' || strlen($email) > 254 || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'A valid email is required.';
    }
    if ($phone !== '' && !preg_match('/^[0-9+ ()-]{7,20}$/', $phone)) {
        $errors[] = 'Please enter a valid phone number.';
    }
    if ($reason === '' || !in_array($reason, ['General Inquiry', 'Join', 'Sponsorship'], true)) {
        $errors[] = 'A valid reason is required.';
    }

    if (empty($errors)) {
        $db = get_db_connection();
        if ($db !== null) {
            try {
                $stmt = $db->prepare(
                    "INSERT INTO submissions (full_name, email, phone, reason, message)
                     VALUES (:full_name, :email, :phone, :reason, :message)");
                $stmt->execute([
                    ':full_name' => $full_name,
                    ':email' => $email,
                    ':phone' => $phone,
                    ':reason' => $reason,
                    ':message' => $user_message
                ]);
                $status = 'success';
                $message = 'Your submission has been received. Thank you!'
                    . ($reason === 'Join' ? ' We will contact you with more information about joining.' : '');
            } catch (PDOException $e) {
                error_log('Dolphin Netball: Database insert failed: ' . $e->getMessage());
                $status = 'error';
                $message = 'We could not save your submission. Please try again later.';
            }
        } else {
            $status = 'error';
            $message = 'The contact form is temporarily unavailable. Please try again later.';
        }
    } else {
        $status = 'error';
        $message = implode(' ', $errors);
    }
    redirectToContact($status, $message);
} else {
    header('Location: contact.html', true, 303);
    exit;
}