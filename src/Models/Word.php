<?php

namespace Bishopm\Spellmaster\Models;

use Illuminate\Database\Eloquent\Model;
use Cartalyst\Tags\TaggableTrait;
use Cartalyst\Tags\TaggableInterface;

class Word extends Model implements TaggableInterface
{
    use TaggableTrait;

    protected $guarded = array('id');
}
