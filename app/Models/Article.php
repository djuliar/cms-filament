<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Enums\Publish;

class Article extends Model
{
    use HasFactory, SoftDeletes;

    protected $dates = ['deleted_at'];

    protected $fillable = [
        'title',
        'slug',
        'content',
        'category_id',
        'tags',
        'image',
        'user_id',
        'editable',
        'user_id_last_edit',
        'meta_description',
        'meta_author',
        'meta_keyword',
        'og_image',
        'og_title',
        'og_description',
        'featured',
        'status',
        'read_count',
        'date',
    ];

    protected $casts = [
        'meta_keyword' => 'array',
        'date' => 'datetime',
        'status' => Publish::class,
    ];

    public function user()
    {
        return $this->belongsTo('App\Models\User', 'user_id', 'id');
    }

    public function category()
    {
        return $this->belongsTo('App\Models\Category', 'category_id');
    }

    public function tags()
    {
        return $this->belongsToMany('App\Models\Tag', 'article_tags', 'article_id', 'tag_id');
    }
}
