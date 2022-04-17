<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;

    protected $table = 'tasks';
    
    /**
     * Una tarea pertenece a un Usuaio  ///una tarea se puede compartir entre usuarios??
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

}
