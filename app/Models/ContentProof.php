<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ContentProof extends Model
{
    use HasFactory;

    protected $fillable = [
        'endorsement_id',
        'posted_at',
        'post_url',
        'notes',
        'review_status',
        'review_notes',
        'reviewed_by',
        'reviewed_at',
    ];

    protected function casts(): array
    {
        return [
            'posted_at' => 'date',
            'reviewed_at' => 'datetime',
        ];
    }

    public function endorsement(): BelongsTo
    {
        return $this->belongsTo(Endorsement::class);
    }

    public function reviewer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    public function files(): HasMany
    {
        return $this->hasMany(ContentProofFile::class);
    }
}
