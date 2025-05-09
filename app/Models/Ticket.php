<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\{Saclie};

class Ticket extends Model
{
    use HasFactory;

    public function saclie(){
        return $this->belongsTo(Saclie::class, 'codclie', 'codclie');
    }

    public function scopeBySaclie($query, $codclie){
        if($codclie)
            return $query->where('codclie', $codclie);
    }

    public function scopeByStatus($query, $status){
        if($status)
            return $query->where('status', $status);
    }

    public function scopeByDateRange($query, $from, $until)
    {
        if ($from && $until) {
            $query->whereBetween('created_at', [$from . ' 00:00:00', $until . ' 23:59:59']);
        } elseif ($from) {
            $query->where('created_at', '>=', $from . ' 00:00:00');
        } elseif ($until) {
            $query->where('created_at', '<=', $until . ' 23:59:59');
        }
    
        return $query;
    }
}
