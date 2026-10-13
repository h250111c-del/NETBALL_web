<?php
require_once 'includes/db_connect.php';
$status  = '';
$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $full_name = is_string($_POST['full_name'] ?? null) ? trim($_POST['full_name']) : '';
    $email     = is_string($_POST['email'] ?? null) ? trim($_POST['email']) : '';
    $phone     = is_string($_POST['phone'] ?? null) ? trim($_POST['phone']) : '';
    $reason    = is_string($_POST['reason'] ?? null) ? trim($_POST['reason']) : '';
    $user_msg  = is_string($_POST['message'] ?? null) ? trim($_POST['message']) : '';

    $errors = [];
    if ($full_name === '' || strlen($full_name) < 2) {
        $errors[] = 'Full name is required.';
    }
    if ($email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'A valid email is required.';
    }
    if ($phone !== '' && !preg_match('/^[0-9+ ()-]{7,20}$/', $phone)) {
        $errors[] = 'Enter a valid phone number or leave the phone field blank.';
    }
    if (!in_array($reason, ['General Inquiry', 'Join', 'Sponsorship'], true)) {
        $errors[] = 'A valid reason is required.';
    }

    if (empty($errors)) {
        $db = get_db_connection();
        $saved = false;
        if ($db !== null) {
            try {
                $stmt = $db->prepare(
                    "INSERT INTO submissions (full_name, email, phone, reason, message)
                     VALUES (:full_name, :email, :phone, :reason, :message)");
                $stmt->execute([
                    ':full_name' => $full_name,
                    ':email'     => $email,
                    ':phone'     => $phone,
                    ':reason'    => $reason,
                    ':message'   => $user_msg
                ]);
                $saved = true;
            } catch (PDOException $e) {
                error_log('HAS: Database insert failed: ' . $e->getMessage());
            }
        }

        if ($saved) {
            $status  = 'success';
            $message = 'Your submission has been received. Thank you!'
                . ($reason === 'Join' ? ' We will contact you with more information about joining.' : '');
        } else {
            $status  = 'error';
            $message = 'Something went wrong saving your submission. Please try again.';
        }
    } else {
        $status  = 'error';
        $message = implode(' ', $errors);
    }
} else {
    $status  = 'error';
    $message = 'Invalid request method.';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Submission Result | Dolphin Netball Club</title>
    <link rel="stylesheet" href="Css/styles.css">
</head>
<body>
    <header class="site-header">
        <nav class="nav">
            <a href="index.html" class="brand">Dolphin Netball Club</a>
            <button class="nav-toggle" id="navToggle" aria-label="Toggle navigation" aria-expanded="false">nav-toggle</button>
            <ul class="nav-links" id="navLinks">
                <li><a href="index.html" class="active">Home</a></li>
                <li><a href="about.html">about us</a></li>
                <li><a href="activities.html">activities/events</a></li>
                <li><a href="gallery.html">gallery</a></li>
                <li><a href="contact.html">contact us</a></li>
            </ul>
        </nav>
    </header>
    <section style="padding: 4rem 0;">
        <div class="container" style="max-width: 620px;">
            <div class="form-note <?php echo $status === 'success' ? 'success' : 'form-error'; ?>" role="status" aria-live="polite">
                <?php echo htmlspecialchars($message); ?>
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
</body>
</html>