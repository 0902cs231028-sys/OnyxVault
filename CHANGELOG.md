# Changelog - OnyxVault Titan

## [Auto-Log] - 2025-12-28
- **🐘 Backend (PHP):** ⚡ Update in `index.php`

All notable changes to this project will be documented in this file.

## [V3.0.0] - Titan Upgrade - 2025-12-25
### Added
- **Global Threat Matrix**: Real-time simulated global ping system for visual security monitoring.
- **Intelligence Feed**: Reactive terminal log simulating system heartbeats and encryption audits.
- **Dead-Drop Protocol**: Implemented `expires_at` logic allowing users to set self-destruct timers on notes.
- **Data Portability**: Added `export.php` for Zero-Knowledge JSON data exports.
- **Titan UI**: Transitioned to a wide-screen command center layout with Glassmorphism and CSS animations.

## [V2.0.0] - Supreme Refactor - 2024-12-20
### Added
- **AES-256-CBC Encryption**: Integrated OpenSSL for symmetric encryption of all stored payloads.
- **OOP Architecture**: Refactored logic into `Auth` and `Cipher` classes within an `includes/` directory.
- **Note Categorization**: Added ability to tag notes (Credentials, Finance, etc.).
- **Live Search**: Integrated JavaScript-based real-time vault filtering.

## [V1.0.0] - Initial Release - 2024-12-10
### Added
- Core PHP Authentication system (Login/Register).
- MySQL database integration using PDO prepared statements.
- Basic CRUD functionality for notes.
- Secure password hashing using `password_hash()`.
