<?php

namespace Waldo\Http\Controllers;

use Waldo\Branch;

class BranchesController extends Controller
{
    /**
     * @var Branch
     */
    private $branches;

    /**
     * BranchesController constructor.
     *
     * @param Branch $branches
     */
    public function __construct(Branch $branches)
    {
        $this->branches = $branches;
    }

    public function show($branchId)
    {
        $branches = $this->branches->all();
        $branch = $this->branches->find($branchId);
        $commit = $branch->commits()->orderBy('updated_at', 'desc')->first();
        $screenshots = $commit->screenshots;

        return view('home.index', compact('branches', 'branch', 'screenshots'));
    }
}
