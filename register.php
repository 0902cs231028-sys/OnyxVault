<?php
require_once "config.php";

$msg = "";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user = trim($_POST['username']);
    $pass = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $check = $pdo->prepare("SELECT id FROM users WHERE username = ?");
    $check->execute([$user]);

    if ($check->rowCount() > 0) {
        $msg = "Identity already exists in database.";
    } else {
        $stmt = $pdo->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
        if ($stmt->execute([$user, $pass])) {
            $msg = "Credentials stored. <a href='login.php' class='underline'>Login Now</a>";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <script src="https://cdn.tailwindcss.com"></script>
    <title>OnyxVault | Register</title>
</head>
<body class="bg-[#0f172a] min-h-screen flex items-center justify-center p-6 text-white">
    <div class="w-full max-w-md bg-white/5 backdrop-blur-xl border border-white/10 p-8 rounded-3xl shadow-2xl">
        <h2 class="text-2xl font-bold text-center mb-8 tracking-tight">ENROLL_NEW_USER</h2>
        
        <?php if($msg): ?>
            <div class="bg-cyan-500/10 border border-cyan-500/50 text-cyan-400 p-4 rounded-xl text-sm mb-6 text-center">
                <?php echo $msg; ?>
            </div>
        <?php endif; ?>

        <form method="POST" class="space-y-6">
            <input type="text" name="username" required class="w-full bg-slate-900/50 border border-slate-700 rounded-xl px-4 py-3 outline-none focus:ring-2 focus:ring-cyan-500" placeholder="New Username">
            <input type="password" name="password" required class="w-full bg-slate-900/50 border border-slate-700 rounded-xl px-4 py-3 outline-none focus:ring-2 focus:ring-cyan-500" placeholder="Strong Password">
            <button type="submit" class="w-full bg-white text-slate-950 py-4 rounded-xl font-black tracking-widest hover:bg-slate-200 transition-all">CREATE IDENTITY</button>
        </form>
    </div>
</body>
</html>
