<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Click extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function listing(): BelongsTo
    {
        return $this->belongsTo(Listing::class);
    }
}
