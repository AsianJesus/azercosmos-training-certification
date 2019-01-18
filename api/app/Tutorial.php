<?php

namespace App;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class Tutorial extends Model
{
    use SoftDeletes;

    protected $table = 'tutorials';

    protected $fillable = [
        'title', 'system', 'author',
    ];

    public function questions()
    {
        return $this->hasMany('App\Question', 'tutorial_id');
    }
}
