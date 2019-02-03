<?php
/**
 * Created by PhpStorm.
 * User: fruit
 * Date: 2/3/2019
 * Time: 10:31 PM
 */

namespace App\Jobs;


use App\TrainingParticipant;
use App\Training;
use Illuminate\Support\Facades\Mail;

class NotificationJob extends Job
{
    protected $training_id;
    public function __construct($training_id)
    {
        $this->training_id = $training_id;
    }

    public function handle() {
        $training = Training::findOrFail($this->training_id);
        $participants = TrainingParticipant::where('training_id', $this->training_id)->where('status', '<', 2)->with('participant')->get();
        foreach ($participants as $participant) {
            Mail::send('notification', ['training_title' => $training->title, 'end_date' => $participant['end_date']],
                function ($mailer)  use ($participant) {
                    $mailer->to(convertLoginToEmail($participant['participant']['LOGIN']), $participant['participant']['NAME']);
                });
        }
    }
}