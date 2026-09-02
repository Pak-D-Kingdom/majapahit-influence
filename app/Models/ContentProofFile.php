<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ContentProofFile extends Model
{
    use HasFactory;

    protected $fillable = [
        'content_proof_id',
        'file_path',
        'file_name',
        'file_size',
        'mime_type',
    ];

    protected function casts(): array
    {
        return [
            'file_size' => 'integer',
        ];
    }

    public function contentProof(): BelongsTo
    {
        return $this->belongsTo(ContentProof::class);
    }
}
