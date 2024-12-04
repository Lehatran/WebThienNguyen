<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
class Address extends Model
{
    use HasFactory;

    protected $table = 'address';
    public $timestamps = false;

    protected $fillable = [
        'province',
        'district',
        'ward',
    ];
}
