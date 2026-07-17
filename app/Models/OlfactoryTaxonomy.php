<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * @method static \Illuminate\Database\Eloquent\Builder|OlfactoryTaxonomy level(?int $level)
 */
#[Fillable(['id', 'parent_id', 'level', 'name_en', 'name_es', 'description_en', 'description_es', 'color_base', 'color'])]
class OlfactoryTaxonomy extends Model
{
    /** @return Builder|OlfactoryTaxonomy */
    #[Scope]
    protected function level(Builder $query, int|array|null $level): void
    {
        $query->when(
            $level !== null,
            fn ($condition) => is_array($level)
                ? $condition->whereIn('level', $level)
                : $condition->where('level', $level)
        );
    }

    /** @return Builder|OlfactoryTaxonomy
     * Retrieve colors by references of the cata attributes */
    #[Scope]
    protected function byRefs(Builder $query, array $refs): void
    {
        $query->when($refs, fn ($condition) => $condition->whereIn('id', $refs));
    }
}
