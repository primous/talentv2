<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CompetencyDecisionLog extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'talent_competency_id',
        'candidate_name',
        'competency_name',
        'admin_name',
        'action',
        'proofs_consulted',
        'score_given',
        'decision_motif',
        'created_at',
    ];

    protected function casts(): array
    {
        return [
            'created_at' => 'datetime',
        ];
    }

    public function competency(): BelongsTo
    {
        return $this->belongsTo(TalentCompetency::class, 'talent_competency_id');
    }
}
