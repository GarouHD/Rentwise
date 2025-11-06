<?php

// This file is to render blocks using blade becuase ACF defaults to php

$slug = $block['name'] ?? '';
$slug = str_replace('acf/', '', $slug); // ACF prefixes custom blocks with acf/
$slug = str_replace('rentwise/', '', $slug); // remove your namespace if present

echo \Roots\view("blocks.{$slug}.view", [
  'block'      => $block ?? null,
  'is_preview' => $is_preview ?? false,
  'post_id'    => $post_id ?? 0,
])->render();
