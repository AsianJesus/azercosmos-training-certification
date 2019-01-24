<?php
/**
 * Created by PhpStorm.
 * User: fruit
 * Date: 1/24/2019
 * Time: 12:45 PM
 */

namespace App;


use Illuminate\Database\Eloquent\Model;

class File extends Model
{
    protected $fillable = [
      'fileable_id', 'fileable_type', 'path'
    ];

    public function fileable() {
        return $this->morphTo('fileable');
    }
}