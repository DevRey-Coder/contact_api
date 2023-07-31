<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Favorite extends Model
{
    use HasFactory;
    public function users()
    {
        return $this->belongsToMany(User::class);
    }

    public function contact()
    {
        return $this->belongsTo(Contact::class, 'contact_id');
    }
}
