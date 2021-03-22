<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'user_id',
        'started_at'
    ];

    public function user()
    {
        return $this->hasOne(User::class);
    }

}
