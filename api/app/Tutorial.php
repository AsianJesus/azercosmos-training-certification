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

    public function observers () {
        return $this->hasMany(TutorialObserver::class);
    }
    public function moderators () {
        return $this->hasMany(Moderator::class);
    }

    public function moderators_users () {
        return $this->hasManyThrough(User::class, Moderator::class, 'tutorial_id',
            'id', 'id', 'moderator_id');
    }

    public function observers_users () {
        return $this->hasManyThrough(User::class, TutorialObserver::class, 'tutorial_id',
            'id', 'id', 'observer_id');
    }

}
