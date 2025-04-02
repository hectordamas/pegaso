<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\{Menu, User};

class MenuPermiso extends Model
{
    use HasFactory;

    protected $table = "menupermiso";

    protected $primaryKey = "codmenupermiso";

    public function user(){
        return $this->belongsTo(User::class, 'codusuario', 'codusuario');
    }

    public function menu(){
        return $this->belongsTo(Menu::class, 'codmenu', 'codmenu');
    }
}
