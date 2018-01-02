<?php

namespace Waldo;

use Illuminate\Database\Eloquent\Model;

class Branch extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name'
    ];

    /**
     * Get the commits for the branch.
     */
    public function commits()
    {
        return $this->hasMany('Waldo\Commit');
    }
}
