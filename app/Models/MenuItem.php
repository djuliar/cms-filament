<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MenuItem extends Model
{
    protected $fillable = [
        'name',
        'subject',
        'type',
        'link',
        'parent_id',
        'page_id',
        'order',
        'status',
    ];

    protected $guarded = [];

    protected $casts = [
        'subject' => 'array',
        'status' => 'boolean',
    ];
}
