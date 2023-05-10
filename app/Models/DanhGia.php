<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DanhGia extends Model
{
    use HasFactory;
    protected $table='danh_gias';

    protected $fillable=[
        'content',
        'rate',
        'email',
        'children_content',
        'id_san_pham'
    ];
}
