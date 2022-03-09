<?php

namespace App\Models;

use Laravel\Sanctum\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Notifications\ResetPasswordNotification;


class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'surname',
        'email',
        'password',
        'financeiro',
        'investimentos',
        'monitoramento',
        'tickets',
        'is_admin',
        'company_id'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];


    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id');
    }



    /**
     * Check if user is admin
     * @return bool
     */
    public function isAdmin()
    {
        return (bool) $this->is_admin == 1;
    }

    public function sendPasswordResetNotification($token)
    {
        $url = 'https://develop.d2kq60fkavdlik.amplifyapp.com/redefinir-senha?token=' . $token;

        $this->notify(new ResetPasswordNotification($url));
    }
}
