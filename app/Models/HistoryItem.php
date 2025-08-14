<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\{User, Safact};

class HistoryItem extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $table = 'historyitems';

    public function safact()
    {
        return $this->belongsTo(Safact::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
