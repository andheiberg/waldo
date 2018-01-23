<?php

namespace Waldo\Services;

use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Filesystem\FilesystemManager;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Intervention\Image\ImageManager;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Waldo\Branch;
use Waldo\Commit;
use Waldo\Screenshot;
use Waldo\Setting;
use Waldo\Exceptions\ComparisonImageNotFoundException;

class ScreenshotComparer
{
    /**
     * @var Branch
     */
    private $branches;

    /**
     * @var Commit
     */
    private $commits;

    /**
     * @var Screenshot
     */
    private $screenshots;

    /**
     * @var Setting
     */
    private $settings;

    /**
     * @var Filesystem
     */
    private $filesystem;

    /**
     * @var Str
     */
    private $str;

    /**
     * Create a new controller instance.
     *
     * @param Branch            $branches
     * @param Commit            $commits
     * @param Screenshot        $screenshots
     * @param Setting.          $settings
     * @param FilesystemManager $filesystemManager
     * @param Str               $str
     */
    public function __construct(
        Branch $branches,
        Commit $commits,
        Screenshot $screenshots,
        Setting $settings,
        FilesystemManager $filesystemManager,
        Str $str
    ) {
        $this->branches = $branches;
        $this->commits = $commits;
        $this->screenshots = $screenshots;
        $this->settings = $settings;
        $this->filesystem = $filesystemManager->drive('public');
        $this->str = $str;
    }

    public function compare(Screenshot $screenshot)
    {
        $prodBranch = $this->branches->where(
            'name', $this->settings->find('branch')->value
        )->first();

        if (! $prodBranch) {
            throw new ComparisonImageNotFoundException;
        }

        $production = $this->screenshots->where('branch_id', $prodBranch->id)
            ->where('suite', $screenshot->suite)
            ->where('feature', $screenshot->feature)
            ->where('scenario', $screenshot->scenario)
            ->where('step', $screenshot->step)
            ->where('screen', $screenshot->screen)
            ->where('touch', $screenshot->touch)
            ->orderBy('created_at', 'desc')
            ->first();

        if (! $production) {
            throw new ComparisonImageNotFoundException;
        }
        
        $diffPath = str_replace('.png', '_diff.png', $screenshot->getPath());
        $baseLine = $this->filesystem->get($production->getPath());
        $screenshot = $this->filesystem->get($screenshot->getPath());

        $manager = new ImageManager(['driver' => 'imagick']);
        $screenshot = $manager->make($screenshot);
        $baseLine = $manager->make($baseLine);

        $compare = $baseLine->compare($screenshot);

        $this->filesystem->put($diffPath, $compare->getDiffImage()->encode('png'));

        return [
            'score' => $compare->getScore(),
            'base_line_id' => $production->id,
            'diff_path' => $diffPath
        ];
    }
}
