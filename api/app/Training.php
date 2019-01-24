<?php

namespace App;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class Training extends Model
{
    use SoftDeletes;

    protected $table = 'trainings';

    protected $fillable = [
        'title', 'description', 'reference', 'status', 'is_test_exam', 'question_number', 'pass_score', 'exam_time', 'tutorial_id', 'originator_id',
    ];
    protected $with = ['tutorial'];

    public function tutorial() {
        return $this->belongsTo(Tutorial::class);
    }

    public function questions() {
        return $this->tutorial->questions();
    }

    public function observers () {
        return $this->hasMany(TrainingObserver::class);
    }

    public function participants () {
        return $this->hasMany(TrainingParticipant::class);
    }
}
