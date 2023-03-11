<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class VaccineDate extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'vaccine_center_id', 'vaccination_date'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function vaccine_center(): BelongsTo
    {
        return $this->belongsTo(VaccineCenter::class, 'vaccine_center_id', 'id');
    }
}
