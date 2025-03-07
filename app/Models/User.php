<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
    ];

    /* realtion with userdetails table */
    public function details()
    {
        return $this->hasOne(UserDetail::class);
    }

    /* realtion with location table */
    public function location()
    {
        return $this->hasOne(Location::class);
    }
}
