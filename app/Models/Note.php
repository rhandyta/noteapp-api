<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Note extends Model
{
    use SoftDeletes;
    use HasFactory;
    protected $table = 'notes';
    protected $fillable = ['user_id','title','body','visible','archive'];
}
