<?php
/**
 * Created by PhpStorm.
 * User: junior
 * Date: 10/02/19
 * Time: 01:31
 */

namespace App\Jobs;


use App\TrainingParticipant;
use Illuminate\Support\Facades\Mail;

class NotifyParticipantsJob extends Job
{
    protected $training;
    protected $participants;
    public function __construct($training, $participants)
    {
        $this->training = $training;
        $this->participants = $participants;
    }
    public function handle() {
        $participants = TrainingParticipant::whereIn('participant_id', $this->participants)
            ->where('training_id', $this->training['id'])->with('user')->get();
        foreach ($participants as $participant) {
            Mail::send('new_training', ['training' => $this->training,
                'from' => $participant->start_date, 'to' => $participant->end_date], function ($mailer)  use ($participant) {
                $mailer->to(convertLoginToEmail($participant['participant']['LOGIN']), $participant['participant']['NAME']);
            });
        }
    }
}