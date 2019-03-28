<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Topic extends Model
{
    protected $fillable = ['name','questions_count'];

    public function question()
    {
        return $this->belongsToMany(Question::class);
    }
}
