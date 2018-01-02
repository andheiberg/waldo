<?php

namespace Waldo\Http\Controllers\API;

use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Waldo\Commit;

class CommitsController extends Controller
{
    /**
     * @var Commit
     */
    private $commit;

    /**
     * Create a new controller instance.
     *
     * @param Commit $commit
     */
    public function __construct(Commit $commit)
    {
        $this->commit = $commit;
    }

    public function index()
    {
        return $this->commit->all();
    }

    public function show($commitId)
    {
        $commit = $this->commit->find($commitId);
        
        if (! $commit) {
            throw new NotFoundHttpException;
        }
        
        return $commit;
    }
}
