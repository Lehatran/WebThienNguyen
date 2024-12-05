<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Product extends Model
{
    use HasFactory;
    //khai báo các trường
    protected $table = 'products';
    public $primaryKey = 'id';
    public $timestamps = false;

    protected $fillable = [
        'name',
        'description',
        'price',
        'status',
        'id_category',
        'create_day',
        'id_address',
        'id_user',
        'img',
        'isExist'
    ];
}
