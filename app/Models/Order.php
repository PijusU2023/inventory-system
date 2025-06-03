<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_number',
        'customer_id',
        'status',
        'total_amount',
        'notes',
        'shipped_at',
        'delivered_at'
    ];

    protected $casts = [
        'shipped_at' => 'datetime',
        'delivered_at' => 'datetime',
        'total_amount' => 'decimal:2'
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    // Generuoti užsakymo numerį
    public static function generateOrderNumber()
    {
        $year = date('Y');
        $month = date('m');

        // Get the last order number for this month by searching the order_number pattern
        $lastOrder = self::where('order_number', 'like', 'UZS-' . $year . $month . '-%')
            ->orderBy('order_number', 'desc')
            ->lockForUpdate() // Prevent race conditions
            ->first();

        if ($lastOrder) {
            // Extract the number from the last order
            $lastNumber = intval(substr($lastOrder->order_number, -4));
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }

        // Keep generating until we find a unique number
        $attempts = 0;
        do {
            $orderNumber = 'UZS-' . $year . $month . '-' . str_pad($newNumber + $attempts, 4, '0', STR_PAD_LEFT);
            $exists = self::where('order_number', $orderNumber)->exists();
            $attempts++;

            if ($attempts > 100) {
                throw new \Exception('Unable to generate unique order number');
            }
        } while ($exists);

        return $orderNumber;
    }

    // Gauti statuso spalvą Bootstrap badge
    public function getStatusBadgeAttribute()
    {
        $statuses = [
            'pending' => 'warning',
            'processing' => 'info',
            'shipped' => 'primary',
            'delivered' => 'success',
            'cancelled' => 'danger'
        ];

        return $statuses[$this->status] ?? 'secondary';
    }

    // Gauti statuso pavadinimą lietuviškai
    public function getStatusLabelAttribute()
    {
        $labels = [
            'pending' => 'Laukiama',
            'processing' => 'Vykdoma',
            'shipped' => 'Išsiųsta',
            'delivered' => 'Pristatyta',
            'cancelled' => 'Atšaukta'
        ];

        return $labels[$this->status] ?? $this->status;
    }
}
