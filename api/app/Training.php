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
}
