<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use Laravel\Sanctum\HasApiTokens;


class Contact extends Model
{
     use HasApiTokens, HasFactory, Notifiable, HasRoles, SoftDeletes;

    protected $table = 'contacts';
    protected $fillable = [
        'name',
        'email',
        'mobile',
        'message',
        'status',
    ];
}
