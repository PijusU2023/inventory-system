<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    use HasFactory;

    protected $fillable = [
        'title', 'type', 'filters', 'data', 'records_count', 'user_id'
    ];

    protected $casts = [
        'filters' => 'array',
        'data' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getTypeNameAttribute()
    {
        $types = [
            'products' => 'Produktų ataskaita',
            'categories' => 'Kategorijų ataskaita',
            'low_stock' => 'Mažų atsargų ataskaita',
            'inventory_value' => 'Inventoriaus vertės ataskaita',
        ];

        return $types[$this->type] ?? $this->type;
    }
}
