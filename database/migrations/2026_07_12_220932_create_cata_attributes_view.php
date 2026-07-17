<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::statement("CREATE OR REPLACE VIEW cata_attributes AS

-- 1) OLFATIVAS: cada hoja (nivel 2) en ambas dimensiones (fragrance_aroma y flavor_aftertaste).
SELECT
    dim.dimension      AS dimension,
    leaf.id            AS id,
    leaf.level         AS level,
    root.name_es       AS category,
    sub.name_es        AS sub_category,
    leaf.name_es       AS attribute,
    leaf.name_en       AS attribute_en,
    leaf.color         AS color
FROM olfactory_taxonomies AS leaf
JOIN olfactory_taxonomies AS sub  ON leaf.parent_id = sub.id
JOIN olfactory_taxonomies AS root ON sub.parent_id  = root.id
CROSS JOIN (
    SELECT 'fragrance_aroma'   AS dimension
    UNION ALL
    SELECT 'flavor_aftertaste' AS dimension
) AS dim
WHERE leaf.level = 2

UNION ALL

-- 2) ACIDITY: solo la rama 'Sour/Acid', hojas (nivel 2).
SELECT
    'acidity'          AS dimension,
    leaf.id            AS id,
    leaf.level         AS level,
    root.name_es       AS category,
    sub.name_es        AS sub_category,
    leaf.name_es       AS attribute,
    leaf.name_en       AS attribute_en,
    leaf.color         AS color
FROM olfactory_taxonomies AS leaf
JOIN olfactory_taxonomies AS sub  ON leaf.parent_id = sub.id
JOIN olfactory_taxonomies AS root ON sub.parent_id  = root.id
WHERE leaf.level = 2
  AND root.name_en = 'Sour/Acid'

UNION ALL

-- 3) GENERAL (consumidor): nivel <= 1.
SELECT
    'general'          AS dimension,
    node.id            AS id,
    node.level         AS level,
    parent.name_es     AS category,
    node.name_es       AS sub_category,
    NULL               AS attribute,
    node.name_en       AS attribute_en,
    node.color         AS color
FROM olfactory_taxonomies AS node
LEFT JOIN olfactory_taxonomies AS parent ON node.parent_id = parent.id
WHERE node.level <= 1;");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement('DROP VIEW IF EXISTS cata_attributes');
    }
};
