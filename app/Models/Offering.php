<?php

namespace App\Models;

use Illuminate\Console\Attributes\Hidden;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['location_id', 'coffee_id', 'evaluation_count', 'defective_evaluation_count', 'consensus', 'concordance', 'verification_status'])]
class Offering extends Model
{
    /** @use HasFactory<\Database\Factories\OfferingFactory> */
    use HasFactory;

    protected function casts(): array
    {
        return [
            'consensus' => 'array',
        ];
    }

    public function location(): BelongsTo
    {
        return $this->belongsTo(Location::class);
    }

    public function coffee(): BelongsTo
    {
        return $this->belongsTo(Coffee::class);
    }

    public function evaluations(): BelongsTo
    {
        return $this->belongsTo(Evaluation::class);
    }
}
