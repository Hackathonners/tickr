<?php

namespace App\Karina;

use App\Support\Str;
use Illuminate\Database\Eloquent\Model;

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
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = ['name_search'];

    /**
     * Set the guest's name.
     *
     * @param  string  $value
     * @return void
     */
    public function setNameAttribute($value)
    {
        $this->attributes['name_search'] = Str::lower(Str::ascii($value));
        $this->attributes['name'] = $value;
    }

    /**
     * Get guest list that owns this guest.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function guestlist()
    {
        return $this->belongsTo(GuestList::class);
    }

    /**
     * Scope a query to only include guests that match given name.
     *
     * @param $name
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeFilterByName($query, $name)
    {
        $name = Str::searchable($name, true);
        return $query->where('name_search', 'LIKE', '%' . $name . '%');
    }
}
