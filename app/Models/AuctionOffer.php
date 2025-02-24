<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AuctionOffer extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'auction_id',
        'offer_price',
        'note',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function auction()
    {
        return $this->belongsTo(Auction::class);
    }

    public function offers()
    {
        return $this->hasMany(AuctionOffer::class);
    }
}
