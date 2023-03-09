<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VaccineCenter extends Model
{
    use HasFactory;

    protected $table = 'vaccine_centers';

    protected $fillable = ['name', 'address', 'serve_users_per_day'];
}
