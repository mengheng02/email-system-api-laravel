<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmailTemplate extends Model
{
    // Allow mass assignment
    protected $fillable = [
        'name',
        'subject',
        'body'
    ];

    // Optional: Relationship to emails
    public function emails()
    {
        return $this->hasMany(Email::class, 'template_id');
    }
}

