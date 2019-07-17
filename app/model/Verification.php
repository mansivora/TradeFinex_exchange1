<?php

namespace App\model;

use Illuminate\Database\Eloquent\Model;

class Verification extends Model
{
    //

     protected $table = 'verification';
     protected $fillable = array('user_id','proof1','proof2','proof3','proof1_status','proof2_status','proof3_status','reason');
}
