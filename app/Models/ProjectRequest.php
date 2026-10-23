<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProjectRequest extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected function casts(): array
    {
        return [
            'client_contacted_at' => 'datetime',
            'talent_responded_at' => 'datetime',
            'mission_confirmed_at' => 'datetime',
            'completed_at' => 'datetime',
            'client_confirmation_sent_at' => 'datetime',
            'talent_notification_sent_at' => 'datetime',
            'client_decision_notified_at' => 'datetime',
            'craft_questions' => 'array',
        ];
    }

    public function candidate(): BelongsTo
    {
        return $this->belongsTo(Candidate::class, 'candidate_id');
    }

    public function competency(): BelongsTo
    {
        return $this->belongsTo(TalentCompetency::class, 'talent_competency_id');
    }

    public function getStatusLabel(): string
    {
        return match ($this->status) {
            'recue' => 'Demande reçue (Non traitée)',
            'contacte' => 'Client recontacté',
            'reponse_talent' => 'Réponse du talent reçue',
            'mission_confirmee' => 'Mission confirmée (Acompte validé)',
            'en_cours' => 'Projet en cours de réalisation',
            'terminee' => 'Mission terminée & livrée',
            'annulee' => 'Demande sans suite / annulée',
            default => 'En attente'
        };
    }

    public function getStatusBadgeClass(): string
    {
        return match ($this->status) {
            'recue' => 'bg-blue-50 text-blue-800 border-blue-200',
            'contacte' => 'bg-indigo-50 text-indigo-800 border-indigo-200',
            'reponse_talent' => 'bg-purple-50 text-purple-800 border-purple-200',
            'mission_confirmee' => 'bg-[#FFF8E1] text-[#9A6A00] border-[#FFE7A3]',
            'en_cours' => 'bg-amber-50 text-amber-800 border-amber-300',
            'terminee' => 'bg-emerald-50 text-emerald-800 border-emerald-300',
            'annulee' => 'bg-neutral-100 text-neutral-600 border-neutral-300',
            default => 'bg-neutral-50 text-neutral-700 border-neutral-200'
        };
    }

    /**
     * Génère une référence unique respectant le format officiel TC-000XXX
     */
    public static function generateUniqueReference(): string
    {
        // Recherche du numéro maximal existant commençant par TC-
        $latest = self::where('reference', 'like', 'TC-%')
            ->orderBy('id', 'desc')
            ->first();

        $nextNumber = 1000;
        if ($latest && preg_match('/TC-(\d+)/', $latest->reference, $matches)) {
            $nextNumber = ((int) $matches[1]) + 1;
        } else {
            $count = self::count();
            $nextNumber = 184 + $count;
        }

        $reference = sprintf('TC-%06d', $nextNumber);

        // Sécurité anti-collision
        while (self::where('reference', $reference)->exists()) {
            $nextNumber++;
            $reference = sprintf('TC-%06d', $nextNumber);
        }

        return $reference;
    }
}
