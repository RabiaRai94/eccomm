<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Product extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'description', 'price', 'stock', 'category_id'];

   
    public function category()
    {
        return $this->belongsTo(ProductCategory::class);
    }

    public function attachments()
    {
        return $this->morphMany(Attachment::class, 'attachable');
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function shoppingCart()
    {
        return $this->hasMany(ShoppingCart::class);
    }
}
