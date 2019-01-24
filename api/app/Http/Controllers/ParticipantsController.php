<?php
/**
 * Created by PhpStorm.
 * User: fruit
 * Date: 1/24/2019
 * Time: 5:30 PM
 */

namespace App\Http\Controllers;


use App\TrainingParticipant;

class ParticipantsController extends Controller
{
    protected $participant;
    protected $rules = [];
    public function __construct(TrainingParticipant $participant)
    {
        parent::__construct($participant, $this->rules);
        $this->participant = $participant;
    }
}