<?php

namespace Waldo;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Screenshot extends Model
{
    /**
     * Indicates if the IDs are auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'commit_id',
        'branch_id',
        'suite',
        'feature',
        'scenario',
        'step',
        'env',
        'user_agent',
        'browser',
        'browser_version',
        'platform',
        'screen',
        'touch',
        'score',
        'base_line_id',
        'diff_path'
    ];

    /**
     * Get branch for the screenshot.
     */
    public function branch()
    {
        return $this->belongsTo('Waldo\Branch');
    }

    /**
     * Get commit for the screenshot.
     */
    public function commit()
    {
        return $this->belongsTo('Waldo\Commit');
    }

    /**
     * Get base line screenshot for the screenshot.
     */
    public function baseLine()
    {
        return $this->belongsTo('Waldo\Screenshot');
    }

    public function getPath()
    {
        $branch = Str::slug($this->branch->name);
        $commit = Str::slug($this->commit->hash);
        
        return "/{$branch}/{$commit}/{$this->id}.png";
    }

    public function getPublicUrl()
    {
        return '/storage/'.$this->getPath();
    }

    public function getBaseLinePath()
    {
        if (! $this->baseLine) {
            return null;
        }

        return $this->baseLine->getPath();
    }

    public function getBaseLinePublicUrl()
    {
        return '/storage/'.$this->getBaseLinePath();
    }

    public function getDiffPath()
    {
        return $this->diff_path;
    }

    public function getDiffPublicUrl()
    {
        return '/storage'.$this->getDiffPath();
    }

    public function getStatus()
    {
        switch ($this->score) {
            case null:
                return 'Not Run';
            case 0:
                return 'Pass';
            default:
                return 'Fail';
        }
    }
}
