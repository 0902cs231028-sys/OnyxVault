<?php
session_start();
require_once "config.php";
require_once "includes/Cipher.php";

if (!isset($_SESSION['user_id'])) { header("Location: login.php"); exit; }

// Handle New Encrypted Entry
if ($_SERVER["REQUEST_METHOD"] == "POST" && !empty($_POST['note'])) {
    $encrypted = Cipher::encrypt($_POST['note']);
    $cat = $_POST['category'] ?? 'General';
    
    $stmt = $pdo->prepare("INSERT INTO notes (user_id, note_content, category) VALUES (?, ?, ?)");
    $stmt->execute([$_SESSION['user_id'], $encrypted, $cat]);
}

// Fetch Notes
$stmt = $pdo->prepare("SELECT * FROM notes WHERE user_id = ? ORDER BY created_at DESC");
$stmt->execute([$_SESSION['user_id']]);
$notes = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <script src="https://cdn.tailwindcss.com"></script>
    <title>OnyxVault | Command Center</title>
</head>
<body class="bg-[#020617] text-slate-300 min-h-screen">
    <div class="max-w-7xl mx-auto p-6">
        <header class="flex justify-between items-center py-6 border-b border-slate-800 mb-10">
            <div>
                <h1 class="text-2xl font-black tracking-tighter text-white">ONYX_VAULT <span class="text-cyan-500 text-xs font-mono ml-2">v2.0_SUPREME</span></h1>
            </div>
            <div class="flex items-center gap-6">
                <div class="hidden md:block text-right">
                    <p class="text-[10px] uppercase text-slate-500">Security Clearance</p>
                    <p class="text-xs font-mono text-cyan-400">ADMIN_LEVEL_04</p>
                </div>
                <a href="logout.php" class="bg-red-500/10 hover:bg-red-500 text-red-500 hover:text-white px-4 py-2 rounded-lg text-xs transition-all border border-red-500/20">LOGOUT</a>
            </div>
        </header>

        <div class="grid lg:grid-cols-12 gap-10">
            <aside class="lg:col-span-3 space-y-6">
                <div class="bg-slate-900/50 border border-slate-800 p-6 rounded-2xl">
                    <h3 class="text-white font-bold mb-4 text-sm uppercase tracking-widest">System_Health</h3>
                    <ul class="space-y-3 text-[11px] font-mono">
                        <li class="flex justify-between"><span>Protocol:</span> <span class="text-cyan-400">AES-256-CBC</span></li>
                        <li class="flex justify-between"><span>Session:</span> <span class="text-green-400">ACTIVE</span></li>
                        <li class="flex justify-between"><span>DB_Status:</span> <span class="text-green-400">CONNECTED</span></li>
                    </ul>
                </div>
                
                <div class="bg-cyan-500/5 border border-cyan-500/20 p-6 rounded-2xl">
                    <p class="text-[10px] text-cyan-500 uppercase font-bold mb-2 italic">Note:</p>
                    <p class="text-xs text-slate-400 italic leading-relaxed">All data is encrypted server-side before storage. Decryption happens only in your active session memory.</p>
                </div>
            </aside>

            <main class="lg:col-span-9 space-y-8">
                <section class="bg-white/5 border border-white/10 p-8 rounded-3xl backdrop-blur-md">
                    <form method="POST" class="space-y-4">
                        <div class="flex justify-between items-center mb-2">
                            <label class="text-xs font-bold text-slate-400 uppercase tracking-widest">Encrypt_New_Secret</label>
                            <select name="category" class="bg-slate-800 border-none text-[10px] rounded-full px-3 py-1 outline-none">
                                <option>General</option>
                                <option>Credentials</option>
                                <option>Finance</option>
                                <option>Private</option>
                            </select>
                        </div>
                        <textarea name="note" required class="w-full h-32 bg-slate-950/50 border border-slate-800 rounded-2xl p-4 focus:ring-1 focus:ring-cyan-500 outline-none transition-all" placeholder="Enter sensitive information..."></textarea>
                        <button type="submit" class="w-full bg-cyan-600 hover:bg-cyan-500 text-white font-bold py-4 rounded-2xl shadow-xl shadow-cyan-900/20 transition-all active:scale-[0.98]">STORE_ENCRYPTED_DATA</button>
                    </form>
                </section>

                <div class="relative">
                    <input type="text" id="vaultSearch" onkeyup="searchVault()" placeholder="SEARCH_ARCHIVE..." class="w-full bg-slate-900/50 border border-slate-800 rounded-xl px-4 py-3 text-sm focus:border-cyan-500 outline-none">
                </div>

                <div id="noteContainer" class="grid gap-4">
                    <?php foreach($notes as $n): 
                        $decrypted = Cipher::decrypt($n['note_content']); ?>
                        <div class="note-card bg-slate-900/40 border border-slate-800 p-6 rounded-2xl hover:border-cyan-500/30 transition-all group">
                            <div class="flex justify-between items-start mb-4">
                                <span class="bg-cyan-500/10 text-cyan-400 text-[10px] px-2 py-1 rounded-md border border-cyan-500/20 font-mono uppercase"><?php echo $n['category']; ?></span>
                                <span class="text-[10px] text-slate-600 font-mono italic">ID: <?php echo $n['id']; ?></span>
                            </div>
                            <p class="text-slate-200 text-sm leading-relaxed mb-4 searchable-content"><?php echo htmlspecialchars($decrypted); ?></p>
                            <div class="flex justify-between items-center opacity-0 group-hover:opacity-100 transition-opacity">
                                <button onclick="navigator.clipboard.writeText('<?php echo addslashes($decrypted); ?>'); alert('Copied!');" class="text-[10px] text-cyan-500 hover:underline">COPY_PLAINTEXT</button>
                                <span class="text-[9px] text-slate-600"><?php echo $n['created_at']; ?></span>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </main>
        </div>
    </div>

    <script>
    function searchVault() {
        let input = document.getElementById('vaultSearch').value.toLowerCase();
        let cards = document.getElementsByClassName('note-card');
        for (let i = 0; i < cards.length; i++) {
            let text = cards[i].querySelector('.searchable-content').innerText.toLowerCase();
            cards[i].style.display = text.includes(input) ? "block" : "none";
        }
    }
    </script>
</body>
</html>

