<?php
require_once __DIR__ . '/../inc/config.php';
require_once __DIR__ . '/../inc/auth.php';

require_login();
$userId = $_SESSION['user']['id'];


$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$userId]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    die("User not found.");
}


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $fname    = trim($_POST['fname']);
    $lname    = trim($_POST['lname']);
    $email    = trim($_POST['email']);
    $password = $_POST['password'] ?? '';

    if (!empty($password)) {
        $hash = password_hash($password, PASSWORD_DEFAULT);
        $sql = "UPDATE users
                SET fname=?, lname=?, email=?, password=?
                WHERE id=?";
        $params = [$fname, $lname, $email, $hash, $userId];
    } else {
        $sql = "UPDATE users
                SET fname=?, lname=?, email=?
                WHERE id=?";
        $params = [$fname, $lname, $email, $userId];
    }

    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);

    header('Location: profile.php'); 
    exit;
}
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Edit Profile</title>
   
</head>
<body class="bg-light">
<div class="container py-5">
    <p>Edit Profile for <?= htmlspecialchars($user['fname']) ?></p>
    <form method="POST" class="mt-4">

        <div class="mb-3">
            <label for="fname" class="form-label">First Name</label>
            <input type="text" name="fname" id="fname" class="" required
                   value="<?= htmlspecialchars($user['fname']) ?>">
        </div>

        <div class="mb-3">
            <label for="lname" class="">Last Name</label>
            <input type="text" name="lname" id="lname" class="form-control" required
                   value="<?= htmlspecialchars($user['lname']) ?>">
        </div>

        <div class="mb-3">
            <label for="email" class="">Email</label>
            <input type="email" name="email" id="email" class="form-control" required
                   value="<?= htmlspecialchars($user['email']) ?>">
        </div>

        <div class="mb-3">
            <label for="password" class="form-label">Password leave blank kapag walang i lalagay</label>
            <input type="password" name="password" id="password" class="form-control">
        </div>

        <button type="submit" class="btn btn-primary">Save Changes</button>
        <a href="profile.php" class="btn btn-secondary">Cancel</a>
    </form>
</div>
</body>
</html>
