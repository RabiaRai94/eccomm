<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ProductCategory extends Model
{
    use HasFactory;
    protected $fillable = ['name','category_id', 'description'];

    public function products()
    {
        return $this->hasMany(Product::class);
    }
}
