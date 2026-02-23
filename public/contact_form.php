<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Contact Us</title>
<!-- abcde -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
<div class="contact-container">
<div class="contact-header">
<h2><i class="fas fa-envelope me-2"></i>Contact Us</h2>
<p>We'd love to hear from you! Send us a message and we'll respond as soon as possible.</p>
</div>

<?php if (isset($_GET['status']) && $_GET['status'] == 'success'): ?>
<div class="alert alert-success">
<i class="fas fa-check-circle"></i>
<span>Thank you! Your message has been sent successfully. We'll get back to you soon.</span>
</div>
<?php endif; ?>

<?php if (isset($_GET['status']) && $_GET['status'] == 'error'): ?>
<div class="alert alert-error">
<i class="fas fa-exclamation-circle"></i>
<span>Sorry, there was an error sending your message. Please try again.</span>
</div>
<?php endif; ?>

<form action="../inc/process_contact.php" method="POST" id="contactForm">
<div class="form-group">
<label for="name"><i class="fas fa-user me-2"></i>Your Name:</label>
<input type="text" id="name" name="name" placeholder="Enter your full name" required>
</div>

<div class="form-group">
<label for="email"><i class="fas fa-envelope me-2"></i>Your Email:</label>
<input type="email" id="email" name="email" placeholder="Enter your email address" required>
</div>

<div class="form-group">
<label for="subject"><i class="fas fa-tag me-2"></i>Subject:</label>
<input type="text" id="subject" name="subject" placeholder="What is this about?" required>
</div>

<div class="form-group">
<label for="message"><i class="fas fa-comment me-2"></i>Message:</label>
<textarea id="message" name="message" placeholder="Type your message here..." required></textarea>
</div>

<button type="submit" class="btn-submit" id="submitBtn">
<i class="fas fa-paper-plane me-2"></i>Send Message
</button>
</form>

<div class="back-link">
<a href="index.php"><i class="fas fa-arrow-left me-1"></i>Back to Home</a>
</div>
</div>

<script>
document.getElementById('contactForm').addEventListener('submit', function(e) {
const btn = document.getElementById('submitBtn');
btn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Sending...';
btn.disabled = true;
});
</script>
</body>
</html>