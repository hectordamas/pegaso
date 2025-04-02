<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use App\Models\{Consultor, MenuPermiso};
use App\Notifications\MyResetPassword;


class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    public function sendPasswordResetNotification($token){
        $this->notify(new MyResetPassword($token));
    }

    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function consultor(){
        return $this->belongsTo(Consultor::class, 'codusuario', 'codusuario');
    }

    public function menupermisos(){
        return $this->hasMany(MenuPermiso::class, 'codusuario', 'codusuario');
    }


}
