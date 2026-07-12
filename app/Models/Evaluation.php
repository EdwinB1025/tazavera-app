<?php

namespace App\Models;

use Illuminate\Console\Attributes\Hidden;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['offering_id', 'evaluator_id', 'evaluator_role', 'extraction_method', 'status', 'descriptive', 'affective', 'note'])]
class Evaluation extends Model
{
    /** @use HasFactory<\Database\Factories\EvaluationFactory> */
    use HasFactory;

    protected function casts(): array
    {
        return [
            'descriptive' => 'array',
            'affective' => 'array',
        ];
    }
}
