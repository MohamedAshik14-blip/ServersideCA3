<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Destination extends Model
{
    protected $table = 'destination';
    protected $fillable = ['name', 'description'];

    public function hotels()
    {
        return $this->hasMany(Hotels::class);
    }

    public function images()
    {
        return $this->hasMany(Image::class);
    }
}
