/**
 * OnyxVault Titan - Intelligence Feed Engine
 */

const activityLogs = [
    "Shard rotated successfully.",
    "AES_GCM handshake verified.",
    "Entropy buffer cleared.",
    "Packet integrity check: 100% stable.",
    "Database heartbeat: 22ms.",
    "Memcached scrub complete.",
    "RSA-4096 tunnel stabilized."
];

// Initialize Intelligence Feed
function startIntelligenceFeed() {
    const logContainer = document.getElementById('activity-log');
    if (!logContainer) return;

    setInterval(() => {
        const p = document.createElement('p');
        p.className = "text-emerald-500/80 animate-pulse";
        p.innerText = "> " + activityLogs[Math.floor(Math.random() * activityLogs.length)];
        logContainer.prepend(p);
        
        if (logContainer.children.length > 12) {
            logContainer.removeChild(logContainer.lastChild);
        }
    }, 3500);
}

// Initialize Threat Matrix Pings
function startThreatMatrix() {
    const matrix = document.getElementById('map-pings');
    if (!matrix) return;

    setInterval(() => {
        const ping = document.createElement('div');
        ping.className = "absolute h-1.5 w-1.5 bg-cyan-400 rounded-full animate-ping shadow-[0_0_8px_cyan]";
        ping.style.left = Math.random() * 100 + "%";
        ping.style.top = Math.random() * 100 + "%";
        matrix.appendChild(ping);
        
        setTimeout(() => ping.remove(), 2500);
    }, 2000);
}

document.addEventListener('DOMContentLoaded', () => {
    startIntelligenceFeed();
    startThreatMatrix();
});
