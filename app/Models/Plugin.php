<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Plugin extends Model
{
    use HasFactory;

    protected $fillable = [
        'slug',
        'name',
        'version',
        'author',
        'description',
        'enabled',
        'activated_at',
        'manifest',
        'settings',
    ];

    protected $casts = [
        'enabled' => 'boolean',
        'activated_at' => 'datetime',
        'manifest' => 'array',
        'settings' => 'array',
    ];

    public function scopeActive($query)
    {
        return $query->where('enabled', true);
    }
}