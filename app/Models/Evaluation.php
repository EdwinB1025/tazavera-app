<?php

namespace App\Models;

use Database\Factories\EvaluationFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['offering_id', 'evaluator_id', 'evaluator_role', 'extraction_method', 'status', 'descriptive', 'affective', 'note'])]
class Evaluation extends Model
{
    /** @use HasFactory<EvaluationFactory> */
    use HasFactory;

    public function offering(): BelongsTo
    {
        return $this->belongsTo(Offering::class);
    }

    protected function casts(): array
    {
        return [
            'descriptive' => 'array',
            'affective' => 'array',
        ];
    }
}
