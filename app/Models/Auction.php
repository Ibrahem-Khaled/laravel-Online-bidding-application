<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Auction extends Model
{
    use HasFactory;
    protected $appends = ['last_offer_price', 'count_offers'];

    protected $fillable = [
        'user_id',
        'category_id',
        'title',
        'slug',
        'images',
        'status',
        'start_price',
        'end_price',
    ];

    protected $casts = [
        'images' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function offers()
    {
        return $this->hasMany(AuctionOffer::class);
    }


    //this accessors
    public function getLastOfferPriceAttribute() // this method name after the accessor :
    {
        return $this->offers->last()->offer_price ?? 0;
    }

    public function getCountOffersAttribute()
    {
        return $this->offers->count();
    }

}
