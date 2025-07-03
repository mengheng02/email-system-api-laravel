<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Email extends Model
{
    protected $fillable = [
        'to',
        'subject',
        'body',
        'status',
        'template_id',
        'error_message'];
}
