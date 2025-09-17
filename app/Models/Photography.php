<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use Laravel\Sanctum\HasApiTokens;

class Photography extends Model
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles, SoftDeletes;

    protected $table = 'photographies';
    protected $fillable = [
        'category_id',
        'parent_id',
        'title',
        'slug',
        'front_image',
        'back_image',
        'price',
        'discount_price',
        'description',
        'short_description',
        'is_home',
        'status',
    ];
}
