<?php

namespace Waldo\Http\Controllers;

use Waldo\Branch;

class HomeController extends Controller
{
    /**
     * @var Branch
     */
    private $branches;

    /**
     * HomeController constructor.
     *
     * @param Branch $branches
     */
    public function __construct(Branch $branches)
    {
        $this->branches = $branches;
    }

    public function index()
    {
        $branches = $this->branches->all();

        return view('home.index', compact('branches'));
    }
}
