<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VaccineDate extends Model
{
    use HasFactory;
    protected $fillable = ['user_id', 'vaccine_center_id', 'vaccination_date'];
}
