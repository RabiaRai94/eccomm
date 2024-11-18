<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ProductVariant extends Model
{
    use HasFactory;
    protected $fillable = [
        'product_id',
        'size',
        'price',
        'stock',
    ];
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
  
    public function attachments()
    {
        return $this->morphMany(Attachment::class, 'attachable');
    }
}
