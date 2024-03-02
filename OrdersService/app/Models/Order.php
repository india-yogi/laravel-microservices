<?php namespace App;
 
use Illuminate\Database\Eloquent\Model;
 
class Order extends Model
{ 
   protected $table = 'orders';

   protected $fillable = [
                'user_id', 
                'cart_id',
                'comment',
                'status'
               ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [];  

   
}