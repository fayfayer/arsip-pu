<?php

namespace App\Model;


use App\Helper\Model;

class PPK extends Model
{
    public $timestamps = false;
    protected $table = 'ppk';
    protected $fillable = ['ppkid', 'name', 'admin_id', 'year'];
}
