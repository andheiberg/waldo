<?php

namespace Waldo\Http\Controllers\API;

use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Waldo\Branch;

class BranchesController extends Controller
{
    /**
     * @var Branch
     */
    private $branches;

    /**
     * Create a new controller instance.
     *
     * @param Branch $branches
     */
    public function __construct(Branch $branches)
    {
        $this->branches = $branches;
    }

    public function index()
    {
        return $this->branches->all();
    }

    public function show($branchId)
    {
        $branch = $this->branches->find($branchId);
        
        if (! $branch) {
            throw new NotFoundHttpException;
        }
        
        return $branch;
    }
}
