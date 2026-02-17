<?php

namespace App\Console\Commands;

use App\Models\Configuration;
use Carbon\Carbon;
use Illuminate\Console\Command;

class BackupDatabase extends Command
{
    protected $signature   = 'db:backup {--force : Skip time and change checks}';
    protected $description = 'Create a SQLite database backup if changes were detected';

    public function handle(): int
    {
        // ── 1. Check if backups are enabled ──────────────────────────────
        if (! $this->option('force') && ! filter_var(Configuration::get('backup_enabled', '1'), FILTER_VALIDATE_BOOLEAN)) {
            $this->info('Backups desactivados en la configuración.');
            return 0;
        }

        $intervalHours = (int) Configuration::get('backup_interval_hours', 1);
        $lastRun       = Configuration::get('backup_last_run');

        // ── 2. Check interval (skip if not enough time has passed) ───────
        if (! $this->option('force') && $lastRun) {
            $nextRun = Carbon::parse($lastRun)->addHours($intervalHours);
            if (Carbon::now()->lt($nextRun)) {
                $this->info('Aún no es hora del próximo backup (próximo: ' . $nextRun->format('d/m/Y H:i') . ').');
                return 0;
            }
        }

        // ── 3. Check for DB changes since last backup ────────────────────
        $dbPath = database_path('database.sqlite');

        if (! file_exists($dbPath)) {
            $this->error('Archivo de base de datos no encontrado: ' . $dbPath);
            return 1;
        }

        $dbMtime = filemtime($dbPath);

        if (! $this->option('force') && $lastRun && $dbMtime <= strtotime($lastRun)) {
            $this->info('Sin cambios en la base de datos desde el último backup. Se omite.');
            // Update last_run so the interval resets from now
            Configuration::set('backup_last_run', now()->toDateTimeString());
            return 0;
        }

        // ── 4. Create backup ──────────────────────────────────────────────
        $backupDir = storage_path('app/backups');
        if (! is_dir($backupDir)) {
            mkdir($backupDir, 0755, true);
        }

        $filename = 'backup-' . now()->format('Y-m-d-H-i-s') . '.sqlite';
        $dest     = $backupDir . '/' . $filename;

        if (! copy($dbPath, $dest)) {
            $this->error('No se pudo copiar el archivo de base de datos.');
            return 1;
        }

        // ── 5. Prune old backups ──────────────────────────────────────────
        $keepCount = (int) Configuration::get('backup_keep_count', 10);
        $existing  = glob($backupDir . '/backup-*.sqlite') ?: [];
        usort($existing, fn ($a, $b) => filemtime($b) <=> filemtime($a)); // newest first

        foreach (array_slice($existing, $keepCount) as $old) {
            @unlink($old);
        }

        // ── 6. Persist metadata ───────────────────────────────────────────
        Configuration::set('backup_last_run', now()->toDateTimeString());
        Configuration::set('backup_latest_file', $filename);

        $size = round(filesize($dest) / 1024, 1);
        $this->info("Backup creado: {$filename} ({$size} KB)");

        return 0;
    }
}
