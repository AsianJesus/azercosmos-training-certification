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

    protected $withCount = [
        'questions', 'verified_questions'
    ];

    public function questions()
    {
        return $this->hasMany('App\Question', 'tutorial_id');
    }

    public function verified_questions () {
        return $this->questions()->where('verified', true);
    }

    public function unverified_questions () {
        return $this->questions()->where('verified', false);
    }

    public function observers () {
        return $this->hasMany(TutorialObserver::class);
    }

    public function moderators () {
        return $this->hasMany(Moderator::class);
    }
}
