<?php
// Home Page
Breadcrumbs::register('homepage', function ($breadcrumbs) {
    $breadcrumbs->push('Главная', route('home'));
});

// Catalog
Breadcrumbs::register('catalog.view', function ($breadcrumbs, $category) {
    $breadcrumbs->parent('homepage');

    $ancestors = null;
    if ($category != null) {
        $ancestors = \App\Models\Category::select(['id', 'title', 'alias', 'parent_id'])->defaultOrder()->ancestorsOf($category->id);
    }

    if ($ancestors != null) {
        foreach ($ancestors as $item) {
            $breadcrumbs->push($item->title, route('catalog.view', ['alias' => $item->alias]));
        }
    }

    $breadcrumbs->push($category->title, route('catalog.view', ['alias' => $category->alias]));
});

// Product
Breadcrumbs::register('product.index', function ($breadcrumbs, $product) {
    $breadcrumbs->parent('catalog.view', $product->category);
    $breadcrumbs->push($product->title);
});