<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Gaming Store</title>
    <link rel="stylesheet" href="style.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>

<body>
    <div class="container animate-fade-in" style="max-width: 500px; margin-top: 5rem;">
        <header style="justify-content: center; margin-bottom: 2rem;">
            <div class="logo">
                <i class="fa-solid fa-gamepad"></i> GAMING STORE
            </div>
        </header>

        <div class="product-card" style="padding: 2rem;">
            <h2 style="text-align: center; margin-bottom: 1.5rem;">Create Account</h2>

            <form action="auth.php" method="POST">
                <input type="hidden" name="action" value="register">

                <div class="form-group" style="margin-bottom: 1rem;">
                    <label style="display: block; margin-bottom: 0.5rem; color: var(--text-secondary);">Username</label>
                    <input type="text" name="username" required
                        style="width: 100%; padding: 0.8rem; border-radius: 8px; border: 1px solid #333; background: var(--bg-primary); color: var(--text-primary);">
                </div>

                <div class="form-group" style="margin-bottom: 1rem;">
                    <label style="display: block; margin-bottom: 0.5rem; color: var(--text-secondary);">Fullname</label>
                    <input type="text" name="fullname" required
                        style="width: 100%; padding: 0.8rem; border-radius: 8px; border: 1px solid #333; background: var(--bg-primary); color: var(--text-primary);">
                </div>

                <div class="form-group" style="margin-bottom: 1rem;">
                    <label style="display: block; margin-bottom: 0.5rem; color: var(--text-secondary);">Address</label>
                    <textarea name="address" required
                        style="width: 100%; padding: 0.8rem; border-radius: 8px; border: 1px solid #333; background: var(--bg-primary); color: var(--text-primary);"></textarea>
                </div>

                <div class="form-group" style="margin-bottom: 1rem;">
                    <label style="display: block; margin-bottom: 0.5rem; color: var(--text-secondary);">Tel</label>
                    <input type="tel" name="tel" required
                        style="width: 100%; padding: 0.8rem; border-radius: 8px; border: 1px solid #333; background: var(--bg-primary); color: var(--text-primary);">
                </div>

                <div class="form-group" style="margin-bottom: 1rem;">
                    <label style="display: block; margin-bottom: 0.5rem; color: var(--text-secondary);">Email</label>
                    <input type="email" name="email" required
                        style="width: 100%; padding: 0.8rem; border-radius: 8px; border: 1px solid #333; background: var(--bg-primary); color: var(--text-primary);">
                </div>

                <div class="form-group" style="margin-bottom: 1.5rem;">
                    <label style="display: block; margin-bottom: 0.5rem; color: var(--text-secondary);">Password</label>
                    <input type="password" name="password" required
                        style="width: 100%; padding: 0.8rem; border-radius: 8px; border: 1px solid #333; background: var(--bg-primary); color: var(--text-primary);">
                </div>

                <button type="submit" class="btn" style="width: 100%; justify-content: center;">Register</button>
            </form>

            <p style="text-align: center; margin-top: 1.5rem; color: var(--text-secondary);">
                Already have an account? <a href="login.php" style="color: var(--accent-primary);">Login</a>
            </p>
        </div>
    </div>
</body>

</html>