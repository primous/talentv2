<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CandidateVerification extends Model
{
    use HasFactory;

    protected $fillable = [
        'candidate_id',
        'admin_name',
        'action',
        'message',
        'score_snapshot',
    ];

    public function candidate(): BelongsTo
    {
        return $this->belongsTo(Candidate::class);
    }
}
