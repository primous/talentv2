<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PlatformContent extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function candidate(): BelongsTo
    {
        return $this->belongsTo(Candidate::class);
    }

    public function getStatusLabel(): string
    {
        return match ($this->status) {
            'publie' => 'En ligne & vérifié',
            'en_attente' => 'En attente d’audit',
            'masque' => 'Masqué du site public',
            'refuse' => 'Rejeté par la modération',
            'signale' => 'Signalé par un utilisateur',
            default => 'Non défini'
        };
    }

    public function getStatusBadgeClass(): string
    {
        return match ($this->status) {
            'publie' => 'bg-emerald-50 text-emerald-800 border-emerald-300',
            'en_attente' => 'bg-amber-50 text-amber-800 border-amber-300',
            'masque' => 'bg-neutral-100 text-neutral-700 border-neutral-300',
            'refuse' => 'bg-rose-50 text-rose-800 border-rose-300',
            'signale' => 'bg-rose-50 text-rose-800 border-rose-300 font-bold',
            default => 'bg-gray-100 text-gray-700 border-gray-300'
        };
    }
}
