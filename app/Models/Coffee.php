<?php

namespace App\Models;

use Illuminate\Console\Attributes\Hidden;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['name', 'roastery', 'roast_level', 'extrinsics'])]
class Coffee extends Model
{
    /** @use HasFactory<\Database\Factories\CoffeeFactory> */
    use HasFactory;

    public function offering(): HasMany
    {
        return $this->hasMany(Offering::class);
    }

    protected function casts(): array
    {
        return [
            'extrinsics' => 'array',
        ];
    }
}
