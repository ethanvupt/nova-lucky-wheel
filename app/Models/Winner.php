<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Winner extends Model
{
    use HasFactory;

    protected $table = 'winners';

    protected $fillable = [
        'spin_event_id',
        'product_id',
        'email',
        'event',
        'win_prize'
    ];

    public function spinEvent()
    {
      return $this->belongsTo(SpinEvent::class);
    }
}
