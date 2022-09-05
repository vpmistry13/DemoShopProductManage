<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;


    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name', 'detail','price','image','categories_id','user_id'
    ];

    //belongs to category
    function getCategory(){
        return $this->hasOne(Category::class,'id','categories_id');
    }
}
