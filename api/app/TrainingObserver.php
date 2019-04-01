<?php
/**
 * Created by PhpStorm.
 * User: fruit
 * Date: 1/24/2019
 * Time: 5:00 PM
 */

namespace App;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TrainingObserver extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'observer_id', 'training_id'
    ];
    protected $table = 'observer_training';
    public function user() {
        return $this->belongsTo(User::class, 'observer_id', 'ID');
    }

    public function training() {
        return $this->belongsTo(Training::class, 'training_id');
    }
}