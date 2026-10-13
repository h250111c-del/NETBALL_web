<?php
require_once 'includes/db_connect.php';
$status = '';
$message = '';

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
} else {
    $status = 'error';
    $message = 'Invalid request method.';
}
?>
<!DOCTYPE html>
<html lang="en">
    <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Form Result | Dolphin Netball Club</title>
    <link rel="stylesheet" href="Css/styles.css">
    </head>
    <body>
        <header class="site-header">
            <nav class="nav">
                <a href="home.html" class="brand">
                    Dolphin Netball Club
                </a>
                <button class="nav-toggle" id="navToggle" type="button" aria-label="Toggle navigation" aria-controls="navLinks" aria-expanded="false"><span class="nav-toggle-bar" aria-hidden="true"></span><span class="nav-toggle-bar" aria-hidden="true"></span><span class="nav-toggle-bar" aria-hidden="true"></span></button>
                <ul  class="nav-links" id="navLinks">
                    <li> <a href="home.html"class="active">Home</a></li>
                    <li> <a href="about.html">about us</a></li>
                    <li> <a href="activities.html">activities/events</a></li>
                    <li> <a href="gallery.html">gallery</a></li>
                    <li> <a href="contact.html">contact us</a></li>
                </ul>
            </nav>
        </header>

        <section style="padding: 4rem 0;">
            <div class="container" style="max-width: 620px;">
               <div class="form-note <?php echo $status === 'success' ? 'success' : 'form-error'; ?>" role="status" aria-live="polite">
                    <?php echo htmlspecialchars($message, ENT_QUOTES, 'UTF-8'); ?>
               </div>

               <p style="text-align: center;">
                <a href="contact.html" class="btn btn-ghost">Back to Contact Form</a>
                <a href="index.html" class="btn btn-primary">Back to Home</a>
                </p>
            </div>
        </section>

        <footer class="site-footer">
            <div class="container">
                <div class="footer-bottom">
                    &copy; <?php echo date("Y"); ?> Dolphin Netball Club.
                </div>
            </div>
        </footer>

        <script src="Js/script.js"></script>
        </body>
         </html>