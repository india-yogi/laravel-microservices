<?php namespace App;
 
use Illuminate\Database\Eloquent\Model;
 
class Product extends Model
{ 
   protected $table = 'products';

   protected $fillable = [
                'sku', 
                'category_id', 
                'title', 
                'desciption',
                'image',
                'unit_price',
                'status'
               ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [];  

   
}