<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\MenuPermiso;

class Menu extends Model
{
    use HasFactory;

    protected $table = 'menus';

    public function menupermisos()
    {
        return $this->hasMany(MenuPermiso::class, 'codmenu', 'codmenu');
    }
}
