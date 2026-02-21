<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Notification extends Model
{
    protected $table = 'notifications';
    protected $fillable = ['notifiable_type', 'notifiable_id', 'type', 'donnees', 'lu_le'];

    public function notifiable(): MorphTo
    {
        return $this->morphTo();
    }
}
