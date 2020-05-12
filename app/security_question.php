<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Security_question extends Model
{
    protected $table = "security_questions";
    protected $fillable = ['question'];
}
