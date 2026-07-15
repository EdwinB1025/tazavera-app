<?php

namespace App\Models;

use Illuminate\Console\Attributes\Hidden;
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
    /** @return \Illuminate\Database\Eloquent\Builder|\App\Models\OlfactoryTaxonomy */
    #[Scope]
    protected function level(Builder $query, ?int $level): void
    {
        $query->when(
            $level,
            fn($condition) => $condition->where('level', $level)
        );
    }
}
