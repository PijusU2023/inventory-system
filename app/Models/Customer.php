<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',  // svarbu pridėti, kad saugotų nuorodą į vartotoją
        'phone',
        'address',
        'company',
    ];

    // Kiekvienas klientas priklauso vartotojui
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }
}
