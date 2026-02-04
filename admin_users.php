<?php
session_start();
require_once 'db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'Admin') {
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['delete_user_id'])) {
        $stmt = $pdo->prepare("DELETE FROM users WHERE id = ?");
        $stmt->execute([$_POST['delete_user_id']]);
    } elseif (isset($_POST['update_role_id'])) {
        $stmt = $pdo->prepare("UPDATE users SET role = ? WHERE id = ?");
        $stmt->execute([$_POST['role'], $_POST['update_role_id']]);
    }
}

$stmt = $pdo->query("SELECT * FROM users ORDER BY created_at DESC");
$users = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Users - Gaming Store</title>
    <link rel="stylesheet" href="style.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>

<body>
    <div class="container animate-fade-in" style="max-width: 1000px; margin-top: 4rem;">
        <header style="justify-content: space-between; margin-bottom: 2rem;">
            <div class="logo">
                <i class="fa-solid fa-gamepad"></i> GAMING STORE <span
                    style="font-size: 0.8rem; background: var(--accent-primary); padding: 2px 6px; border-radius: 4px; margin-left: 0.5rem;">ADMIN</span>
            </div>
            <a href="index.php" class="btn btn-sm"
                style="background: transparent; border: 1px solid var(--text-secondary);">
                Back to Dashboard
            </a>
        </header>

        <h2 style="margin-bottom: 2rem;"><i class="fa-solid fa-users"></i> Manage Users</h2>

        <div
            style="background: var(--card-bg); border-radius: 12px; border: 1px solid rgba(255,255,255,0.05); overflow-x: auto;">
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>User</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Joined</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($users as $user): ?>
                        <tr>
                            <td>#<?php echo $user['id']; ?></td>
                            <td>
                                <div><?php echo htmlspecialchars($user['username']); ?></div>
                                <div style="font-size: 0.8rem; color: var(--text-secondary);">
                                    <?php echo htmlspecialchars($user['fullname']); ?></div>
                            </td>
                            <td><?php echo htmlspecialchars($user['email']); ?></td>
                            <td>
                                <form method="POST" style="display: flex; gap: 0.5rem;">
                                    <input type="hidden" name="update_role_id" value="<?php echo $user['id']; ?>">
                                    <select name="role" onchange="this.form.submit()"
                                        style="background: #333; color: white; border: none; padding: 4px; border-radius: 4px;">
                                        <option value="User" <?php echo $user['role'] === 'User' ? 'selected' : ''; ?>>User
                                        </option>
                                        <option value="Admin" <?php echo $user['role'] === 'Admin' ? 'selected' : ''; ?>>Admin
                                        </option>
                                    </select>
                                </form>
                            </td>
                            <td><?php echo date('M d, Y', strtotime($user['created_at'])); ?></td>
                            <td>
                                <?php if ($user['id'] != $_SESSION['user_id']): ?>
                                    <form method="POST" onsubmit="return confirm('Delete this user?');">
                                        <input type="hidden" name="delete_user_id" value="<?php echo $user['id']; ?>">
                                        <button type="submit" class="btn btn-sm btn-danger"><i
                                                class="fa-solid fa-trash"></i></button>
                                    </form>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>

</html>