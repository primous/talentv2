<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Candidate extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected function casts(): array
    {
        return [
            'languages' => 'array',
            'specialties' => 'array',
            'target_clients' => 'array',
            'intervention_modes' => 'array',
            'score_breakdown' => 'array',
            'has_business_entity' => 'boolean',
            'has_completed_prestation' => 'boolean',
            'has_portfolio_items' => 'boolean',
            'has_worked_for_client' => 'boolean',
            'has_main_realization' => 'boolean',
            'has_personal_contribution_details' => 'boolean',
            'has_additional_proof' => 'boolean',
            'has_diploma_certification' => 'boolean',
            'has_client_reference' => 'boolean',
            'accepts_practical_test' => 'boolean',
            'is_available_now' => 'boolean',
            'can_specify_deadlines' => 'boolean',
            'has_necessary_tools' => 'boolean',
            'has_intervention_zone' => 'boolean',
            'can_announce_pricing' => 'boolean',
            'has_transport_solution' => 'boolean',
            'confirmed_exact_info' => 'boolean',
            'confirmed_authentic_work' => 'boolean',
            'authorized_verification' => 'boolean',
            'agreed_public_profile_policy' => 'boolean',
            'decision_date' => 'datetime',
            'confirmation_email_sent_at' => 'datetime',
            'acceptance_email_sent_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function competencies(): HasMany
    {
        return $this->hasMany(TalentCompetency::class)->orderBy('score', 'desc');
    }

    public function projectRequests(): HasMany
    {
        return $this->hasMany(ProjectRequest::class)->orderBy('created_at', 'desc');
    }

    public function platformContents(): HasMany
    {
        return $this->hasMany(PlatformContent::class)->orderBy('created_at', 'desc');
    }

    public function verifications(): HasMany
    {
        return $this->hasMany(CandidateVerification::class)->orderBy('created_at', 'desc');
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(PrestationReview::class)->orderBy('created_at', 'desc');
    }

    public function getVisibilityBadgeClass(): string
    {
        return match ($this->visibility_status ?? 'publie') {
            'publie' => 'bg-emerald-50 text-emerald-800 border-emerald-300',
            'masque' => 'bg-amber-50 text-amber-800 border-amber-300',
            'suspendu' => 'bg-rose-50 text-rose-800 border-rose-300',
            'desactive' => 'bg-neutral-100 text-neutral-600 border-neutral-300',
            default => 'bg-emerald-50 text-emerald-800 border-emerald-300'
        };
    }

    public function getVisibilityLabel(): string
    {
        return match ($this->visibility_status ?? 'publie') {
            'publie' => 'Profil Publié',
            'masque' => 'Masqué temporairement',
            'suspendu' => 'Suspendu',
            'desactive' => 'Désactivé',
            default => 'Profil Publié'
        };
    }

    public function getStatusLabel(): string
    {
        return match ($this->status) {
            'recue' => 'Candidature reçue',
            'en_cours' => 'En cours de vérification',
            'complements_requis' => 'Compléments demandés',
            'valide' => 'Profil validé',
            'refuse' => 'Dossier non retenu',
            default => 'Statut en attente'
        };
    }

    public function getStatusBadgeClass(): string
    {
        return match ($this->status) {
            'recue' => 'bg-blue-50 text-blue-800 border-blue-200',
            'en_cours' => 'bg-[#FFF8E1] text-[#9A6A00] border-[#FFE7A3]',
            'complements_requis' => 'bg-amber-50 text-amber-800 border-amber-300',
            'valide' => 'bg-emerald-50 text-emerald-800 border-emerald-300',
            'refuse' => 'bg-rose-50 text-rose-800 border-rose-200',
            default => 'bg-gray-100 text-gray-800 border-gray-200'
        };
    }

    /**
     * Calcule le score indicatif basé sur la grille d'évaluation officielle
     */
    public function computeIndicativeScore(): array
    {
        $breakdown = [
            'info_points' => 0,          // Max 10
            'metier_points' => 0,        // Max 10
            'realisations_points' => 0,  // Max 25
            'contribution_points' => 0,  // Max 15
            'evaluation_points' => 0,    // Max 30
            'operationnel_points' => 0,  // Max 10
        ];

        // 1. Informations cohérentes et coordonnées vérifiables (10 pts)
        if (!empty($this->full_name) && !empty($this->phone) && !empty($this->city) && !empty($this->country)) {
            $breakdown['info_points'] += 6;
        }
        if (!empty($this->profile_photo)) {
            $breakdown['info_points'] += 2;
        }
        if (!empty($this->languages) && count($this->languages) > 0) {
            $breakdown['info_points'] += 2;
        }

        // 2. Métier, spécialités et services clairement définis (10 pts)
        if (!empty($this->category) && !empty($this->job_title)) {
            $breakdown['metier_points'] += 4;
        }
        if (!empty($this->specialties) && count($this->specialties) > 0) {
            $breakdown['metier_points'] += 3;
        }
        if (!empty($this->concrete_services) && strlen($this->concrete_services) > 20) {
            $breakdown['metier_points'] += 3;
        }

        // 3. Pertinence et qualité des réalisations fournies (25 pts)
        if ($this->has_main_realization) {
            $breakdown['realisations_points'] += 10;
            if (!empty($this->realization_file) || !empty($this->realization_link)) {
                $breakdown['realisations_points'] += 10;
            }
            if (!empty($this->realization_description) && strlen($this->realization_description) > 15) {
                $breakdown['realisations_points'] += 5;
            }
        }

        // 4. Preuves de la contribution personnelle du candidat (15 pts)
        if ($this->has_personal_contribution_details && !empty($this->personal_contribution_text)) {
            $breakdown['contribution_points'] += 8;
        }
        if ($this->has_additional_proof && !empty($this->additional_proof_file)) {
            $breakdown['contribution_points'] += 7;
        }

        // 5. Évaluation pratique ou technique selon métier (30 pts)
        if ($this->accepts_practical_test) {
            $breakdown['evaluation_points'] += 15;
        }
        if ($this->has_diploma_certification && !empty($this->diploma_certification_title)) {
            $breakdown['evaluation_points'] += 10;
        }
        if ($this->has_client_reference && !empty($this->client_reference_contact)) {
            $breakdown['evaluation_points'] += 5;
        }

        // 6. Informations opérationnelles et qualifications nécessaires (10 pts)
        if ($this->is_available_now) {
            $breakdown['operationnel_points'] += 3;
        }
        if ($this->has_necessary_tools || !empty($this->tools_workaround)) {
            $breakdown['operationnel_points'] += 3;
        }
        if ($this->has_intervention_zone) {
            $breakdown['operationnel_points'] += 2;
        }
        if ($this->can_announce_pricing) {
            $breakdown['operationnel_points'] += 2;
        }

        $total = array_sum($breakdown);

        return [
            'total' => min(100, $total),
            'breakdown' => $breakdown,
        ];
    }
}
