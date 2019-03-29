<?php
/**
 * Created by PhpStorm.
 * User: junior
 * Date: 10/02/19
 * Time: 01:39
 */

namespace App\Jobs;

use App\Training;
use App\TrainingParticipant;
use Illuminate\Support\Facades\Mail;

class SendParticipantPassJob extends Job
{
    protected $training;
    protected $participant;
    public function __construct($training, $participant)
    {
        $this->training = $training;
        $this->participant = $participant;
    }

    public function handle() {
        $training = Training::findOrFail($this->training)->load('observers_view.user');
        $participant = TrainingParticipant::where('participant_id', $this->participant)
            ->where('training_id', $this->training)->first();
        Mail::send('participant_passed', ['participant' => $participant, 'trainingName' => $training->title,
            'pass_score' => $training->pass_score, 'is_test' => $training->is_test], function ($mailer)  use ($participant) {
            foreach(array_map(function ($o) {
                return $o->user;
            }, $training->observers_view ?? []) as $observer) {
                $mailer->to(convertLoginToEmail($observer['LOGIN']), $observer['NAME']);
            }
        });
    }
}