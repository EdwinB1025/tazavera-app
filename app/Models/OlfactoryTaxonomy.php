<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * @method static \Illuminate\Database\Eloquent\Builder|OlfactoryTaxonomy tastes(int|array|null $level, ?string $category=null)
 */
#[Fillable(['id', 'parent_id', 'level', 'name_en', 'name_es', 'description_en', 'description_es', 'color_base', 'color', 'categories'])]
class OlfactoryTaxonomy extends Model
{
    protected $casts = [
        'categories' => 'array',
    ];

    /** @return Builder|OlfactoryTaxonomy */
    #[Scope]
    protected function tastes(Builder $query, int|array|null $level, ?string $category = null): void
    {
        $category ??= 'aromatics';
        $level ??= [0, 1, 2];
        $query->whereIn('level', (array) $level)->whereJsonContains('categories', $category);
    }

    /** @return Builder|OlfactoryTaxonomy
     * Retrieve colors by references of the cata attributes */
    #[Scope]
    protected function byRefs(Builder $query, array $refs): void
    {
        $query->when($refs, fn($condition) => $condition->whereIn('id', $refs));
    }
}
