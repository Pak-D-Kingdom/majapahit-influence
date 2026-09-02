<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RegistrationFile extends Model
{
    use HasFactory;

    protected $fillable = [
        'registration_id',
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

    public function registration(): BelongsTo
    {
        return $this->belongsTo(KolRegistration::class, 'registration_id');
    }
}
