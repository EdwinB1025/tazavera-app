<?php

namespace App\Models;

use Illuminate\Console\Attributes\Hidden;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['id', 'parent_id', 'level', 'name_en', 'name_es', 'description_en', 'description_es', 'color_base', 'color'])]
class OlfactoryTaxonomy extends Model
{
    //
}
