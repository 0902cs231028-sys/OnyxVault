<?php
session_start();
require_once "config.php";
require_once "includes/Auth.php";

$error = "";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $auth = new Auth($pdo);
    if ($auth->login($_POST['username'], $_POST['password'])) {
        header("Location: dashboard.php");
        exit;
    } else {
        $error = "Access Denied: Invalid Credentials";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <script src="https://cdn.tailwindcss.com"></script>
    <title>OnyxVault | Secure Authentication</title>
</head>
<body class="bg-[#0f172a] min-h-screen flex items-center justify-center p-6 text-white font-sans">
    <div class="w-full max-w-md bg-white/5 backdrop-blur-xl border border-white/10 p-8 rounded-3xl shadow-2xl">
        <div class="text-center mb-10">
            <h1 class="text-4xl font-black bg-gradient-to-r from-cyan-400 to-blue-500 bg-clip-text text-transparent italic">ONYX_VAULT</h1>
            <p class="text-slate-400 text-sm mt-3 tracking-widest uppercase">Level 4 Security Clearance Required</p>
        </div>

        <?php if($error): ?>
            <div class="bg-red-500/20 border border-red-500/50 text-red-400 p-3 rounded-lg text-sm mb-6 text-center italic">
                <?php echo $error; ?>
            </div>
        <?php endif; ?>

        <form method="POST" class="space-y-6">
            <div>
                <label class="block text-[10px] uppercase tracking-[0.2em] text-slate-500 mb-2 ml-1">Identity</label>
                <input type="text" name="username" required class="w-full bg-slate-900/50 border border-slate-700 rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-cyan-500 transition-all placeholder:text-slate-700" placeholder="Username">
            </div>
            <div>
                <label class="block text-[10px] uppercase tracking-[0.2em] text-slate-500 mb-2 ml-1">Access Key</label>
                <input type="password" name="password" required class="w-full bg-slate-900/50 border border-slate-700 rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-cyan-500 transition-all placeholder:text-slate-700" placeholder="••••••••">
            </div>
            <button type="submit" class="w-full bg-gradient-to-r from-cyan-600 to-blue-600 hover:from-cyan-500 hover:to-blue-500 py-4 rounded-xl font-bold tracking-widest shadow-lg shadow-cyan-900/20 transition-all active:scale-[0.98]">
                INITIALIZE SESSION
            </button>
        </form>
        <div class="mt-8 text-center">
            <a href="register.php" class="text-xs text-slate-500 hover:text-cyan-400 transition-colors">Register New Operative</a>
        </div>
    </div>
</body>
</html>
