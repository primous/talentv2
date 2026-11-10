<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;

class BackupEncryptedDatabase extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:backup-encrypted {--keep=30 : Nombre de jours de rétention}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Génère une sauvegarde complète et chiffrée (AES-256) de la base de données';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $this->info('Début de la sauvegarde chiffrée de la base de données...');

        $backupDir = storage_path('app/backups');
        if (!File::exists($backupDir)) {
            File::makeDirectory($backupDir, 0750, true);
            File::put($backupDir . '/.gitignore', "*\n!.gitignore\n");
        }

        try {
            $connection = config('database.default');
            $tables = [];

            if ($connection === 'sqlite') {
                $tablesList = DB::select("SELECT name FROM sqlite_master WHERE type='table' AND name NOT LIKE 'sqlite_%'");
                foreach ($tablesList as $t) {
                    $tableName = $t->name;
                    $tables[$tableName] = DB::table($tableName)->get()->toArray();
                }
            } else {
                $tablesList = DB::select('SHOW TABLES');
                $key = 'Tables_in_' . config("database.connections.{$connection}.database");
                foreach ($tablesList as $t) {
                    $tableName = $t->$key ?? array_values((array) $t)[0];
                    $tables[$tableName] = DB::table($tableName)->get()->toArray();
                }
            }

            $payload = json_encode([
                'timestamp' => now()->toIso8601String(),
                'connection' => $connection,
                'tables' => $tables,
            ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);

            $encryptedData = Crypt::encryptString($payload);

            $filename = 'backup-' . now()->format('Y-m-d_H-i-s') . '.enc';
            $filePath = $backupDir . '/' . $filename;
            File::put($filePath, $encryptedData);

            Log::info("SAUVEGARDE_CHIFFREE_REUSSIE : Fichier {$filename} généré avec succès (" . strlen($encryptedData) . " octets).");
            $this->info("Sauvegarde chiffrée générée avec succès : {$filename}");

            // Nettoyage des anciennes sauvegardes selon rétention
            $retentionDays = (int) $this->option('keep');
            $files = File::files($backupDir);
            $deletedCount = 0;
            foreach ($files as $file) {
                if ($file->getExtension() === 'enc' && now()->diffInDays(now()->setTimestamp($file->getMTime())) > $retentionDays) {
                    File::delete($file->getPathname());
                    $deletedCount++;
                }
            }

            if ($deletedCount > 0) {
                $this->line("Purge des sauvegardes : {$deletedCount} archive(s) expirée(s) supprimée(s).");
            }

            return Command::SUCCESS;
        } catch (\Throwable $e) {
            Log::error('ERREUR_SAUVEGARDE_CHIFFREE : ' . $e->getMessage());
            $this->error('Erreur lors de la sauvegarde : ' . $e->getMessage());

            return Command::FAILURE;
        }
    }
}

