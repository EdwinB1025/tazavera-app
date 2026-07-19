<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Model;

#[Table(name: 'cata_attributes', key: 'id')]
#[Fillable('id', 'dimension', 'level', 'category', 'sub_category', 'attribute', 'attribute_en', 'color')]
class CataAttribute extends Model {}
