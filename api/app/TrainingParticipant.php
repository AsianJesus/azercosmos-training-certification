<?php
/**
 * Created by PhpStorm.
 * User: fruit
 * Date: 1/24/2019
 * Time: 5:11 PM
 */

namespace App;


use Illuminate\Database\Eloquent\Model;

class TrainingParticipant extends Model
{
    protected $fillable = [
        'training_id', 'start_date', 'end_date', 'participant_id',
        'status', 'score', 'attempt', 'examiner_id'
    ];

    protected $with = ['participant', 'examiner'];
    protected $table = 'participant_training';
    public function training() {
        return $this->belongsTo(Training::class);
    }

    public function examiner() {
        return $this->belongsTo(User::class, 'examiner');
    }

    public function participant() {
        return $this->belongsTo(User::class, 'participant');
    }
}