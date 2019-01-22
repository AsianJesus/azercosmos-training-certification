<?php
/**
 * Created by PhpStorm.
 * User: fruit
 * Date: 1/22/2019
 * Time: 11:23 PM
 */

namespace App;


use Illuminate\Database\Eloquent\Model;

class TutorialObserver extends Model
{
    protected $fillable = [
      'tutorial_id', 'observer_id'
    ];

    protected $table = 'observer_tutorial';

    public function user() {
        return $this->belongsTo(User::class, 'observer_id', 'ID');
    }
    public function tutorial() {
        return $this->belongsTo(Tutorial::class);
    }
}