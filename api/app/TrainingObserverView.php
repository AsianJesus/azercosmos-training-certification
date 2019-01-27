<?php
/**
 * Created by PhpStorm.
 * User: fruit
 * Date: 1/26/2019
 * Time: 1:48 PM
 */

namespace App;


use Illuminate\Database\Eloquent\Model;

class TrainingObserverView extends Model
{
    protected $table = 'observer_training_view';

    public function user() {
        return $this->belongsTo(User::class, 'observer_id');
    }

    public function training() {
        return $this->belongsTo(Training::class, 'training_id');
    }
}