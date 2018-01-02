<?php

namespace Waldo;

use Illuminate\Database\Eloquent\Model;

class Commit extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'hash',
        'branch_id'
    ];

    /**
     * Get the branch for the commit.
     */
    public function branch()
    {
        return $this->belongsTo('Waldo\Branch');
    }

    /**
     * Get the screenshots for the commit.
     */
    public function screenshots()
    {
        return $this->hasMany('Waldo\Screenshot');
    }
}
