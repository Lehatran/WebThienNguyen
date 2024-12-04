<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Products extends Model
{
    use HasFactory;

    protected $table = 'products';

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
