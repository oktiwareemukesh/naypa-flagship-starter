<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Theme extends Model
{
    use HasFactory;

    protected $fillable = [
        'slug',
        'name',
        'version',
        'author',
        'description',
        'active',
        'manifest',
        'settings',
    ];

    protected $casts = [
        'active' => 'boolean',
        'manifest' => 'array',
        'settings' => 'array',
    ];
}