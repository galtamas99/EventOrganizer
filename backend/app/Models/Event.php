<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    /** @use HasFactory<\Database\Factories\EventFactory> */
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'starts_at',
        'location',
        'capacity',
        'ticket_price',
        'category',
        'status',
        'organizer_id',
    ];

    protected $casts = [
        'starts_at' => 'datetime',
        'capacity' => 'integer',
        'ticket_price' => 'integer',
    ];

    public function organizer()
    {   
        return $this->belongsTo(User::class, 'organizer_id');
    }
}
