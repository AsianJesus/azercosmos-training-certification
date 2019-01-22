<?php
/**
 * Created by PhpStorm.
 * User: fruit
 * Date: 1/22/2019
 * Time: 11:17 PM
 */

namespace App;


use Illuminate\Database\Eloquent\Model;

class Moderator extends Model
{
    protected $fillable = [
        'tutorial_id', 'moderator_id'
    ];

    protected $table = 'moderator_tutorial';

    public function user() {
        return $this->belongsTo(User::class, 'moderator_id', 'ID');
    }

    public function tutorial() {
        return $this->belongsTo(Tutorial::class);
    }
}