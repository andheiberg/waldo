<?php

namespace Waldo\Http\Controllers;

use Waldo\Branch;
use Waldo\Screenshot;
use Waldo\Services\ScreenshotComparer;

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
     * @param ScreenshotComparer $screenshotComparer
     */
    public function __construct(
        Branch $branches,
        Screenshot $screenshots,
        ScreenshotComparer $screenshotComparer
    ) {
        $this->branches = $branches;
        $this->screenshots = $screenshots;
        $this->screenshotComparer = $screenshotComparer;
    }

    public function show($branchId, $screenshotId)
    {
        $branch = $this->branches->find($branchId);
        $screenshot = $this->screenshots->find($screenshotId);

        if (! $branch or ! $screenshot) {
            abort(404);
        }

        if ($screenshot->score == NULL) {
            $diff = $this->screenshotComparer->compare($screenshot);

            $screenshot->update([
                'score' => $diff['score'],
                'base_line_id' => $diff['base_line_id'],
                'diff_path' => $diff['diff_path']
            ]);
        }

        return view('screenshots.show', compact('branch', 'screenshot'));
    }
}
