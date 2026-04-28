<?php

namespace App\Helpers;

use App\Models\ProductSkuVariants;
use DB;

class ModelDetailHelper
{
    public static function calculateBaseUnitPrice($unit_price, $qty, $base_qty)
    {
        return $base_qty != 0 ? ($unit_price * $qty) / $base_qty : 0;
    }

    public static function createWithVariants(array $data_list, array $product_sku_ids, $variant_model_class, $foreign_key)
    {
        $product_sku_variant_by_ids = [];

        $product_sku_variants = ProductSkuVariants::whereIn('product_sku_id', $product_sku_ids)->get();

        foreach ($product_sku_variants as $variant) {
            $product_sku_variant_by_ids[$variant['product_sku_id']][] = $variant;
        }

        $ids = collect($data_list)->pluck('id')->toArray();

        $existing_variants = $variant_model_class::onlyTrashed()->whereIn($foreign_key, $ids)->get();

        $existing_map = [];
        foreach ($existing_variants as $item) {
            $existing_map[$item->$foreign_key][$item->variant_id] = $item;
        }

        $variant_insert = [];
        $variant_update = [];

        foreach ($data_list as $data) {
            $sku_id = $data['product_sku_id'] ?? null;
            $detail_id = $data['id'];

            if (!$sku_id || !isset($product_sku_variant_by_ids[$sku_id])) continue;

            foreach ($product_sku_variant_by_ids[$sku_id] as $variant) {
                $variant_id = $variant['variant_id'];

                if (isset($existing_map[$detail_id][$variant_id])) {
                    $existing = $existing_map[$detail_id][$variant_id];

                    $variant_update[] = [
                        'id' => $existing['id'],
                        'option_id' => $variant['option_id'],
                        'updated_at' => now()
                    ];
                } else {
                    $variant_insert[] = [
                        $foreign_key => $detail_id,
                        'variant_id' => $variant['variant_id'],
                        'option_id' => $variant['option_id'],
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                }
            }
        }

        if (!empty($variant_insert)) {
            $variant_model_class::insert($variant_insert);
        }

        foreach ($variant_update as $update) {
            $variant_model_class::withTrashed()->where('id', $update['id'])->update([
                'option_id' => $update['option_id'],
                'deleted_at' => null,
                'updated_at' => $update['updated_at']
            ]);
        }
    }
}