<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $table = 'products';

    protected $fillable = [
        'name',
        'spin_event_id',
        'short_description',
        'fixed_percent'
    ];

    public function spinEvent()
    {
        return $this->belongsTo(SpinEvent::class);
    }
}
