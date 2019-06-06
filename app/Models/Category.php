<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Cviebrock\EloquentSluggable\Sluggable;
use Kalnoy\Nestedset\NodeTrait;
use Cviebrock\EloquentSluggable\Services\SlugService;

class Category extends Model
{
    use NodeTrait, Sluggable {
        NodeTrait::replicate as replicateNode;
        Sluggable::replicate as replicateSlug;
    }

    protected $table = 'categories';

    protected $fillable = [
        'title', 'alias', 'position', 'active', 'parent_id', 'lft', 'rgt', 'depth'
    ];

    protected $dates = [
        'created_at', 'updated_at'
    ];

    public function getLftName()
    {
        return 'lft';
    }

    public function getRgtName()
    {
        return 'rgt';
    }

    public function getParentIdName()
    {
        return 'parent_id';
    }

    public function replicate(array $except = null)
    {
        $instance = $this->replicateNode($except);
        (new SlugService())->slug($instance, true);

        return $instance;
    }

    /**
     * Return the sluggable configuration array for this model.
     *
     * @return array
     */
    public function sluggable()
    {
        return [
            'alias' => [
                'source' => 'title'
            ]
        ];
    }

    public function parent()
    {
        return $this->belongsTo(static::class, "parent_id");
    }

    public function url()
    {
        return url(route('catalog.view', ['alias' => $this->alias]));
    }

    public function products()
    {
        return $this->hasMany('App\Models\Product', 'category_id');
    }

    public function parents()
    {
        return $this->hasMany(static::class, 'parent_id');
    }
}
