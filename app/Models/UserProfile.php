<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserProfile extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'full_name',
        'phone',
        'address',
    ];

    /**
     * Relasi ke User (Inverse One-to-One)
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
