<?php

namespace App\Models;

use Database\Factories\CoffeeFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['name', 'roastery', 'roast_level', 'extrinsics'])]
class Coffee extends Model
{
    /** @use HasFactory<CoffeeFactory> */
    use HasFactory;

    public function offering(): HasMany
    {
        return $this->hasMany(Offering::class);
    }

    #[Scope]
    protected function nameLike(Builder $query, string $name): void
    {
        foreach (explode(' ', trim($name)) as $word) {
            $query->where('name', 'like', '%' . $word . '%');
        }
    }

    #[Scope]
    protected function origin(Builder $query, string $origin): void
    {
        $query->where('extrinsics->origin->country', $origin);
    }

    #[Scope]
    protected function process(Builder $query, string $process): void
    {
        $query->where('extrinsics->process', $process);
    }

    protected function casts(): array
    {
        return [
            'extrinsics' => 'array',
        ];
    }
}
