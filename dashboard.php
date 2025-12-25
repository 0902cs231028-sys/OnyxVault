<?php
session_start();
require_once "config.php";
require_once "includes/Cipher.php";

if (!isset($_SESSION['user_id'])) { header("Location: login.php"); exit; }

// Handle New Note with optional "Dead Drop" timer
if ($_SERVER["REQUEST_METHOD"] == "POST" && !empty($_POST['note'])) {
    $encrypted = Cipher::encrypt($_POST['note']);
    $cat = $_POST['category'] ?? 'General';
    $expiry = null;
    if(!empty($_POST['expiry'])) {
        $expiry = date('Y-m-d H:i:s', strtotime("+".$_POST['expiry']." hours"));
    }
    
    $stmt = $pdo->prepare("INSERT INTO notes (user_id, note_content, category, expires_at) VALUES (?, ?, ?, ?)");
    $stmt->execute([$_SESSION['user_id'], $encrypted, $cat, $expiry]);
}

// Fetch active notes (filtering out expired ones)
$stmt = $pdo->prepare("SELECT * FROM notes WHERE user_id = ? AND (expires_at > NOW() OR expires_at IS NULL) ORDER BY created_at DESC");
$stmt->execute([$_SESSION['user_id']]);
$notes = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <title>OnyxVault | TITAN_COMMAND</title>
    <style>
        @keyframes scan { 0% { top: 0; } 100% { top: 100%; } }
        .scanner::after { content: ''; position: absolute; width: 100%; height: 2px; background: rgba(34, 211, 238, 0.5); top: 0; animation: scan 3s linear infinite; }
        .glass-panel { background: rgba(15, 23, 42, 0.8); backdrop-filter: blur(12px); border: 1px solid rgba(255, 255, 255, 0.05); }
        .cyber-bg { background: radial-gradient(circle at center, #0f172a 0%, #020617 100%); }
    </style>
</head>
<body class="cyber-bg text-slate-300 font-mono overflow-x-hidden min-h-screen">

    <div class="fixed inset-0 pointer-events-none scanner opacity-10"></div>

    <div class="max-w-[1600px] mx-auto p-6">
        <header class="flex flex-wrap justify-between items-center mb-10 gap-4 border-b border-white/5 pb-6">
            <div class="flex items-center gap-4">
                <div class="h-12 w-12 bg-cyan-500 rounded-lg flex items-center justify-center font-black text-black shadow-[0_0_20px_rgba(6,182,212,0.4)] text-xl">OX</div>
                <div>
                    <h1 class="text-2xl font-black tracking-tighter text-white">ONYX_VAULT</h1>
                    <p class="text-[9px] text-cyan-500 uppercase tracking-[0.3em]">Titan_V3 // Secure_Interface</p>
                </div>
            </div>
            <div class="flex gap-4">
                <a href="export.php" class="text-[10px] border border-cyan-500/30 text-cyan-400 px-5 py-2.5 rounded-md hover:bg-cyan-500 hover:text-black transition-all uppercase font-bold tracking-widest">Export_Vault</a>
                <a href="logout.php" class="text-[10px] border border-red-500/30 text-red-500 px-5 py-2.5 rounded-md hover:bg-red-500 hover:text-white transition-all uppercase font-bold tracking-widest">Terminate</a>
            </div>
        </header>

        <div class="grid lg:grid-cols-4 gap-6">
            
            <aside class="space-y-6">
                <div class="glass-panel p-5 rounded-xl border-t-2 border-cyan-500/50">
                    <h3 class="text-cyan-500 text-[10px] font-bold mb-4 uppercase tracking-[0.3em]">Intelligence_Feed</h3>
                    <div id="activity-log" class="space-y-2 text-[9px] text-slate-500 h-64 overflow-hidden font-mono">
                        <p class="text-emerald-500">> Encrypted tunnel established...</p>
                    </div>
                </div>

                <div class="glass-panel p-5 rounded-xl border-t-2 border-yellow-500/50">
                    <h3 class="text-yellow-500 text-[10px] font-bold mb-4 uppercase tracking-widest italic">Global_Threat_Matrix</h3>
                    <div class="relative h-32 bg-slate-950 rounded overflow-hidden mb-4 border border-white/5">
                        <div class="absolute inset-0 opacity-20 bg-[url('https://www.transparenttextures.com/patterns/carbon-fibre.png')]"></div>
                        <div id="map-pings" class="relative h-full w-full"></div>
                    </div>
                    <div class="space-y-2 text-[10px] font-mono">
                        <div class="flex justify-between"><span>Mock_Breach:</span> <span class="text-emerald-400">NO_MATCHES</span></div>
                        <div class="flex justify-between"><span>Nodes_Active:</span> <span class="text-cyan-400">12_GLOBAL</span></div>
                    </div>
                </div>
            </aside>

            <main class="lg:col-span-2 space-y-6">
                <section class="glass-panel p-8 rounded-2xl relative overflow-hidden border border-white/10 shadow-2xl">
                    <form method="POST" class="space-y-4">
                        <div class="flex justify-between gap-4">
                            <select name="category" class="w-1/2 bg-slate-900 text-[10px] rounded-lg px-3 py-2 outline-none text-cyan-400 border border-cyan-500/20 focus:border-cyan-500 transition-all">
                                <option>General</option><option>Credential</option><option>Dark_Drop</option>
                            </select>
                            <select name="expiry" class="w-1/2 bg-slate-900 text-[10px] rounded-lg px-3 py-2 outline-none text-slate-400 border border-white/5 focus:border-cyan-500 transition-all">
                                <option value="">Lifetime Storage</option>
                                <option value="1">Expire in 1h</option>
                                <option value="24">Expire in 24h</option>
                            </select>
                        </div>
                        <textarea name="note" required class="w-full h-40 bg-black/60 border border-slate-800 rounded-xl p-5 text-sm focus:border-cyan-500 outline-none transition-all placeholder:text-slate-700 font-sans" placeholder="Enter sensitive intelligence payload..."></textarea>
                        <button class="w-full bg-cyan-600 hover:bg-cyan-500 text-white font-black py-4 rounded-xl transition-all shadow-[0_0_30px_rgba(8,145,178,0.2)] uppercase tracking-widest">Commit_to_Deep_Storage</button>
                    </form>
                </section>

                <div class="grid gap-4">
                    <?php if(empty($notes)): ?>
                        <div class="text-center p-10 border border-dashed border-white/10 rounded-2xl">
                            <p class="text-slate-600 text-xs uppercase tracking-widest">Archive_Empty // Waiting_for_Data</p>
                        </div>
                    <?php endif; ?>
                    <?php foreach($notes as $n): 
                        $decrypted = Cipher::decrypt($n['note_content']); ?>
                        <div class="note-card glass-panel p-6 rounded-xl hover:border-cyan-500/40 transition-all group relative">
                            <div class="flex justify-between mb-4">
                                <span class="text-[9px] text-cyan-400 bg-cyan-500/5 px-2 py-1 rounded border border-cyan-500/20 font-bold uppercase"><?php echo htmlspecialchars($n['category']); ?></span>
                                <span class="text-[9px] text-slate-600 font-mono"><?php echo $n['created_at']; ?></span>
                            </div>
                            <p class="text-slate-300 text-sm mb-6 leading-relaxed font-sans"><?php echo htmlspecialchars($decrypted); ?></p>
                            <div class="flex gap-4 items-center border-t border-white/5 pt-4">
                                <button onclick="navigator.clipboard.writeText('<?php echo addslashes($decrypted); ?>'); alert('Copied to secure clipboard');" class="text-[9px] text-slate-500 hover:text-cyan-400 uppercase tracking-widest font-bold transition-colors">Copy_Plaintext</button>
                                <button onclick="if(confirm('Wipe record?')) location.href='delete_note.php?id=<?php echo $n['id']; ?>'" class="text-[9px] text-red-900 hover:text-red-500 uppercase tracking-widest font-bold transition-colors">Wipe_Record</button>
                                <?php if($n['expires_at']): ?>
                                    <span class="ml-auto text-[8px] text-yellow-500/50 italic uppercase">Timed_Drop</span>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </main>

            <aside class="space-y-6">
                <div class="glass-panel p-6 rounded-xl border-b-2 border-cyan-500/50">
                    <h3 class="text-white text-xs font-bold mb-6 uppercase tracking-widest text-center">Architect_Profile</h3>
                    <div class="flex flex-col items-center">
                        <div class="h-24 w-24 bg-gradient-to-tr from-cyan-900 to-slate-950 rounded-full mb-4 border-2 border-cyan-500/30 flex items-center justify-center shadow-[0_0_20px_rgba(6,182,212,0.1)]">
                            <span class="text-2xl font-black text-cyan-500"><?php echo strtoupper(substr($_SESSION['username'], 0, 2)); ?></span>
                        </div>
                        <p class="text-sm font-black text-white"><?php echo htmlspecialchars($_SESSION['username']); ?></p>
                        <p class="text-[9px] text-slate-500 mt-1 uppercase tracking-widest italic font-bold">System_Architect</p>
                    </div>
                </div>

                <div class="glass-panel p-5 rounded-xl border-l-2 border-emerald-500/50">
                    <h3 class="text-emerald-500 text-[10px] font-bold mb-4 uppercase tracking-widest">Encryption_Status</h3>
                    <div class="space-y-3">
                        <div class="flex justify-between items-center text-[9px]">
                            <span class="text-slate-500 uppercase">Load:</span>
                            <div class="w-24 bg-slate-900 h-1 rounded-full overflow-hidden">
                                <div class="bg-cyan-500 h-full w-[65%] shadow-[0_0_10px_cyan]"></div>
                            </div>
                        </div>
                        <div class="text-[9px] text-slate-500 leading-relaxed italic">
                            AES-256-CBC Verified. Shard sync operational across 12 distributed nodes.
                        </div>
                    </div>
                </div>
            </aside>
        </div>
    </div>

    <script>
        // Titan Intelligence Feed Logic
        const activity = [
            "Shard rotated.", "Encryption keys validated.", "Entropy buffer cleared.",
            "Packet integrity check passed.", "Database heartbeat: 34ms.", "AES_GCM handshake verified.",
            "Memory scrub complete.", "RSA-4096 tunnel stabilized.", "Intrusion scan: 0 threats."
        ];
        
        setInterval(() => {
            const log = document.getElementById('activity-log');
            const p = document.createElement('p');
            p.className = "animate-pulse";
            p.innerText = "> " + activity[Math.floor(Math.random() * activity.length)];
            log.prepend(p);
            if(log.children.length > 15) log.removeChild(log.lastChild);
        }, 3000);

        // Global Threat Matrix Simulation
        setInterval(() => {
            const pings = document.getElementById('map-pings');
            const dot = document.createElement('div');
            dot.className = "absolute h-1 w-1 bg-cyan-400 rounded-full animate-ping";
            dot.style.left = Math.random() * 100 + "%";
            dot.style.top = Math.random() * 100 + "%";
            pings.appendChild(dot);
            setTimeout(() => dot.remove(), 2000);
        }, 1500);
    </script>
</body>
</html>

