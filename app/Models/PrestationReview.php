<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PrestationReview extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected function casts(): array
    {
        return [
            'rating' => 'integer',
            'moderated_at' => 'datetime',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }

    /**
     * Prestation / compétence à laquelle se rapporte l'avis
     */
    public function competency(): BelongsTo
    {
        return $this->belongsTo(TalentCompetency::class, 'talent_competency_id');
    }

    /**
     * Candidat / talent proposant la prestation
     */
    public function candidate(): BelongsTo
    {
        return $this->belongsTo(Candidate::class, 'candidate_id');
    }

    /**
     * Scope des avis publiés
     */
    public function scopePublished($query)
    {
        return $query->where('status', 'publie');
    }

    /**
     * Scope des avis en attente de modération
     */
    public function scopePending($query)
    {
        return $query->where('status', 'en_attente');
    }

    /**
     * Scope des avis refusés
     */
    public function scopeRejected($query)
    {
        return $query->where('status', 'refuse');
    }

    /**
     * Libellé humain du statut de modération
     */
    public function getStatusLabel(): string
    {
        return match ($this->status) {
            'publie' => 'Publié',
            'en_attente' => 'En attente',
            'refuse' => 'Refusé',
            default => ucfirst(str_replace('_', ' ', (string) $this->status))
        };
    }

    /**
     * Classes CSS Tailwind du badge de statut
     */
    public function getStatusBadgeClass(): string
    {
        return match ($this->status) {
            'publie' => 'bg-emerald-50 text-emerald-800 border-emerald-300',
            'en_attente' => 'bg-amber-50 text-amber-800 border-amber-300',
            'refuse' => 'bg-rose-50 text-rose-800 border-rose-300',
            default => 'bg-neutral-100 text-neutral-700 border-neutral-300'
        };
    }
}
