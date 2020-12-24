<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SpinEvent extends Model
{
    use HasFactory;

    protected $table = 'spin_events';

    protected $fillable = [
        'name'
    ];

    public function products()
    {
        return $this->hasMany(Product::class);
    }

    public function winners(){
        return $this->hasMany(Winner::class);
    }
}
