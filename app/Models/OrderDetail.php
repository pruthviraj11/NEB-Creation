<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use Laravel\Sanctum\HasApiTokens;

class OrderDetail extends Model
{
     use HasApiTokens, HasFactory, Notifiable, HasRoles, SoftDeletes;

    protected $table = 'order_details';
    protected $fillable = [
        'guest_id',
        'user_id',
        'order_status',
        'order_type',
        'card_type',
        'transaction_id',
        'auth_code',
        'response_code',
        'response_desc',
        'payment_response',
        'total_amount',
        'fname',
        'lname',
        'username',
        'email',
        'mobile',
        'address1',
        'address2',
        'country',
        'state',
        'zip',
        's_fname',
        's_lname',
        's_username',
        's_email',
        's_mobile',
        's_address1',
        's_address2',
        's_country',
        's_state',
        's_zip',
        
    ];
}
