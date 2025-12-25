<?php
session_start();
require_once "config.php";
require_once "includes/Cipher.php";

if (!isset($_SESSION['user_id'])) { header("Location: login.php"); exit; }

if ($_SERVER["REQUEST_METHOD"] == "POST" && !empty($_POST['note'])) {
    $encrypted = Cipher::encrypt($_POST['note']);
    $stmt = $pdo->prepare("INSERT INTO notes (user_id, note_content) VALUES (?, ?)");
    $stmt->execute([$_SESSION['user_id'], $encrypted]);
}

$stmt = $pdo->prepare("SELECT note_content, created_at FROM notes WHERE user_id = ? ORDER BY created_at DESC");
$stmt->execute([$_SESSION['user_id']]);
$notes = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <script src="https://cdn.tailwindcss.com"></script>
    <title>Vault Dashboard</title>
</head>
<body class="bg-[#020617] text-slate-200 min-h-screen p-4 md:p-10">
    <div class="max-w-5xl mx-auto">
        <nav class="flex justify-between items-center mb-16">
            <div class="flex items-center gap-3">
                <div class="h-3 w-3 bg-cyan-500 rounded-full animate-pulse"></div>
                <span class="font-mono text-sm tracking-tighter text-slate-500">SYSTEM_ACTIVE // <?php echo $_SESSION['username']; ?></span>
            </div>
            <a href="logout.php" class="text-[10px] border border-red-500/30 text-red-500 px-4 py-2 rounded-full hover:bg-red-500 hover:text-white transition-all uppercase tracking-widest">Terminate</a>
        </nav>

        <section class="grid lg:grid-cols-3 gap-10">
            <div class="lg:col-span-1">
                <h2 class="text-3xl font-bold mb-6">Input_Data</h2>
                <form method="POST" class="space-y-4">
                    <textarea name="note" class="w-full bg-slate-900 border border-slate-800 rounded-2xl p-5 h-40 focus:ring-2 focus:ring-cyan-500 outline-none transition-all resize-none" placeholder="Enter sensitive information..."></textarea>
                    <button class="w-full bg-cyan-600 py-4 rounded-xl font-bold hover:bg-cyan-500 transition-all shadow-lg shadow-cyan-900/20">ENCRYPT_DATA</button>
                </form>
            </div>

            <div class="lg:col-span-2">
                <h2 class="text-3xl font-bold mb-6">Encrypted_Archive</h2>
                <div class="space-y-4">
                    <?php foreach($notes as $n): 
                        $decrypted = Cipher::decrypt($n['note_content']); ?>
                        <div class="group bg-slate-900/40 border border-slate-800 p-6 rounded-2xl hover:border-cyan-500/50 transition-all">
                            <p class="text-slate-300 leading-relaxed"><?php echo htmlspecialchars($decrypted); ?></p>
                            <div class="mt-4 pt-4 border-t border-slate-800 flex justify-between items-center opacity-40 group-hover:opacity-100 transition-opacity">
                                <span class="text-[9px] font-mono tracking-widest text-cyan-500">AES-256-CBC_VERIFIED</span>
                                <span class="text-[9px] font-mono"><?php echo $n['created_at']; ?></span>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </section>
    </div>
</body>
</html>
