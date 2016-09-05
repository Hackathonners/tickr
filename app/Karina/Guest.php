<?php

namespace App\Karina;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Guest extends Model
{
    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'notes',
    ];

    /**
     * Get guest list that owns this guest.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function guestlist()
    {
        return $this->belongsTo(GuestList::class);
    }
}
