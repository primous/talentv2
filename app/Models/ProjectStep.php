<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProjectStep extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected function casts(): array
    {
        return [
            'due_date' => 'date',
            'completed_at' => 'datetime',
            'client_validated_at' => 'datetime',
            'history' => 'array',
            'deliverable_file_size' => 'integer',
            'client_file_size' => 'integer',
            'deliverable_files' => 'array',
            'client_files' => 'array',
        ];
    }

    public function projectRequest(): BelongsTo
    {
        return $this->belongsTo(ProjectRequest::class, 'project_request_id');
    }

    public function getTypeLabel(): string
    {
        return match ($this->step_type) {
            'info' => 'Information à communiquer',
            'client_file' => 'Fichier / document attendu du client',
            'link' => 'Lien à consulter ou utiliser',
            'payment' => 'Paiement demandé',
            'deliverable' => 'Livrable transmis par le talent',
            'client_validation' => 'Validation attendue du client',
            'talent_task' => 'Tâche du talent',
            default => 'Étape de mission',
        };
    }

    public function getStatusLabel(): string
    {
        return match ($this->status) {
            'en_attente' => 'Planifiée (En attente)',
            'en_cours' => 'En cours de réalisation',
            'action_requise_client' => 'Action requise de votre part',
            'termine' => 'Terminée & validée',
            'annule' => 'Étape annulée',
            default => 'En attente',
        };
    }

    public function getStatusBadgeClass(): string
    {
        return match ($this->status) {
            'en_attente' => 'bg-neutral-100 text-neutral-700 border-neutral-300',
            'en_cours' => 'bg-amber-50 text-amber-900 border-amber-300',
            'action_requise_client' => 'bg-[#FFF8E1] text-[#9A6A00] border-[#FFE7A3]',
            'termine' => 'bg-emerald-50 text-emerald-800 border-emerald-300',
            'annule' => 'bg-neutral-100 text-neutral-500 border-neutral-200 opacity-60',
            default => 'bg-neutral-50 text-neutral-700 border-neutral-200',
        };
    }

    /**
     * Enregistre un événement dans l'historique de l'étape
     */
    public function logActivity(string $action, string $author, ?string $details = null): void
    {
        $currentHistory = $this->history ?? [];
        if (!is_array($currentHistory)) {
            $currentHistory = [];
        }

        $currentHistory[] = [
            'date' => now()->format('d/m/Y H:i'),
            'timestamp' => now()->timestamp,
            'author' => $author, // 'talent', 'client', 'admin', 'system'
            'action' => $action,
            'details' => $details,
        ];

        $this->history = $currentHistory;
        $this->save();
    }

    /**
     * Retourne la liste normalisée des livrables (fichiers multiples + rétrocompatibilité ancien fichier unique)
     *
     * @return array<int, array{index: int, name: string, path: string, size: ?int, uploaded_at: ?string}>
     */
    public function getDeliverableFilesList(): array
    {
        $files = [];
        $existing = $this->deliverable_files;

        if (is_array($existing) && !empty($existing)) {
            foreach (array_values($existing) as $idx => $item) {
                if (is_array($item) && !empty($item['path'])) {
                    $files[] = [
                        'index' => $idx,
                        'name' => $item['name'] ?? ('livrable_' . ($idx + 1)),
                        'path' => $item['path'],
                        'size' => isset($item['size']) ? (int) $item['size'] : null,
                        'uploaded_at' => $item['uploaded_at'] ?? null,
                    ];
                }
            }
        }

        // Si aucun fichier dans le tableau json mais un ancien fichier unique est présent
        if (empty($files) && !empty($this->deliverable_file_path)) {
            $files[] = [
                'index' => 0,
                'name' => $this->deliverable_file_name ?: 'livrable.bin',
                'path' => $this->deliverable_file_path,
                'size' => $this->deliverable_file_size,
                'uploaded_at' => $this->updated_at ? $this->updated_at->format('d/m/Y H:i') : null,
            ];
        }

        return $files;
    }

    /**
     * Retourne un livrable spécifique par son index (0, 1, 2...)
     */
    public function getDeliverableFileAtIndex(?int $index = null): ?array
    {
        $list = $this->getDeliverableFilesList();
        if (empty($list)) {
            return null;
        }

        if ($index === null) {
            return $list[0] ?? null;
        }

        foreach ($list as $file) {
            if ($file['index'] === $index) {
                return $file;
            }
        }

        return $list[$index] ?? null;
    }

    /**
     * Retourne la liste normalisée des fichiers clients (fichiers multiples + rétrocompatibilité)
     *
     * @return array<int, array{index: int, name: string, path: string, size: ?int, uploaded_at: ?string}>
     */
    public function getClientFilesList(): array
    {
        $files = [];
        $existing = $this->client_files;

        if (is_array($existing) && !empty($existing)) {
            foreach (array_values($existing) as $idx => $item) {
                if (is_array($item) && !empty($item['path'])) {
                    $files[] = [
                        'index' => $idx,
                        'name' => $item['name'] ?? ('document_client_' . ($idx + 1)),
                        'path' => $item['path'],
                        'size' => isset($item['size']) ? (int) $item['size'] : null,
                        'uploaded_at' => $item['uploaded_at'] ?? null,
                    ];
                }
            }
        }

        // Si aucun fichier dans le tableau json mais un ancien fichier unique est présent
        if (empty($files) && !empty($this->client_file_path)) {
            $files[] = [
                'index' => 0,
                'name' => $this->client_file_name ?: 'document_client.bin',
                'path' => $this->client_file_path,
                'size' => $this->client_file_size,
                'uploaded_at' => $this->updated_at ? $this->updated_at->format('d/m/Y H:i') : null,
            ];
        }

        return $files;
    }

    /**
     * Retourne un fichier client spécifique par son index (0, 1, 2...)
     */
    public function getClientFileAtIndex(?int $index = null): ?array
    {
        $list = $this->getClientFilesList();
        if (empty($list)) {
            return null;
        }

        if ($index === null) {
            return $list[0] ?? null;
        }

        foreach ($list as $file) {
            if ($file['index'] === $index) {
                return $file;
            }
        }

        return $list[$index] ?? null;
    }
}

