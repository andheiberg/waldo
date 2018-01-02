<?php

namespace Waldo\Http\Controllers;

use Waldo\Branch;
use Waldo\Screenshot;

class ScreenshotsController extends Controller
{
    /**
     * @var Branch
     */
    private $branches;

    /**
     * @var Screenshot
     */
    private $screenshots;

    /**
     * ScreenshotsController constructor.
     *
     * @param Branch     $branches
     * @param Screenshot $screenshots
     */
    public function __construct(
        Branch $branches,
        Screenshot $screenshots
    )
    {
        $this->branches = $branches;
        $this->screenshots = $screenshots;
    }

    public function show($branchId, $screenshotId)
    {
        $branch = $this->branches->find($branchId);
        $screenshot = $this->screenshots->find($screenshotId);

        if (! $branch or ! $screenshot) {
            abort(404);
        }

        return view('screenshots.show', compact('branch', 'screenshot'));
    }
}
