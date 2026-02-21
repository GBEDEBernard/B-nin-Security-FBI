<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Incident extends Model
{
    protected $table = 'incidents';
    protected $fillable = ['entreprise_id', 'employe_id', 'site_client_id', 'titre', 'description', 'niveau'];

    public function entreprise(): BelongsTo
    {
        return $this->belongsTo(Entreprise::class);
    }

    public function employe(): BelongsTo
    {
        return $this->belongsTo(Employe::class);
    }

    public function site(): BelongsTo
    {
        return $this->belongsTo(SiteClient::class, 'site_client_id');
    }
}
