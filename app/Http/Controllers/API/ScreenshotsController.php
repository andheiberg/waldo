<?php

namespace Waldo\Http\Controllers\API;

use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Filesystem\FilesystemManager;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Intervention\Image\ImageManager;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Waldo\Branch;
use Waldo\Commit;
use Waldo\Exceptions\ComparisonImageNotFoundException;
use Waldo\Screenshot;

class ScreenshotsController extends Controller
{
    /**
     * @var Screenshot
     */
    private $screenshots;

    /**
     * @var Branch
     */
    private $branches;

    /**
     * @var Commit
     */
    private $commits;

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
     * @param FilesystemManager $filesystemManager
     * @param Str               $str
     */
    public function __construct(
        Branch $branches,
        Commit $commits,
        Screenshot $screenshots,
        FilesystemManager $filesystemManager,
        Str $str
    ) {
        $this->branches = $branches;
        $this->commits = $commits;
        $this->screenshots = $screenshots;
        $this->filesystem = $filesystemManager->drive('public');
        $this->str = $str;
    }

    public function index()
    {
        return $this->screenshots->all();
    }

    public function show($screenshotId)
    {
        $screenshot = $this->screenshots->find($screenshotId);

        if (! $screenshot) {
            throw new NotFoundHttpException;
        }

        return $screenshot;
    }

    public function store(Request $request)
    {
        try {
            $this->validate($request, [
                'screenshot' => 'required|file',
                'branch'     => 'required|string',
                'commit'     => 'required|string',
                'suite'      => 'required|string',
                'feature'    => 'required|string',
                'scenario'   => 'required|string',
                'step'       => 'required|string',
                'env'        => 'required|string',
                'user_agent' => 'string|nullable',
                'screen'     => 'string|nullable',
                'touch'      => 'boolean'
            ]);

            $file = $request->files->get('screenshot');
            $branch = $request->get('branch');
            $commit = $request->get('commit');
            $suite = $request->get('suite', 'default');
            $feature = $request->get('feature');
            $scenario = $request->get('scenario');
            $step = $request->get('step');
            $env = $request->get('env');
            $userAgent = $request->get('user_agent');
            $screen = $request->get('screen');
            $touch = $request->get('touch');

            $id = sha1($branch.$commit.$env.$suite.$feature.$scenario.$step.$userAgent.$screen.$touch);
            $filename = "/{$this->str->slug($branch)}/{$this->str->slug($commit)}/".$id;
            $filename .= '.'.$file->getClientOriginalExtension();

            $this->filesystem->put($filename, file_get_contents($file));

            $branch = $this->branches->firstOrCreate(['name' => $branch]);
            $commit = $this->commits->firstOrCreate(['hash' => $commit, 'branch_id' => $branch->id]);
            $screenshot = $this->screenshots->updateOrCreate([
                'id' => $id,
                'branch_id' => $branch->id
            ], [
                'commit_id' => $commit->id,
                'suite' => $suite,
                'feature' => $feature,
                'scenario' => $scenario,
                'step' => $step,
                'user_agent' => $userAgent,
                'screen' => $screen,
                'touch' => $touch,
                'env' => $env
            ]);

            $diff = $this->compare($screenshot);

            $screenshot->update([
                'score' => $diff['score'],
                'base_line_id' => $diff['base_line_id'],
                'diff_path' => $diff['diff_path']
            ]);

            return [
                'id' => $screenshot->id,
                'score' => $diff['score'],
                'url' => 'http://localhost:8080/screenshots/'.$screenshot->id
            ];
        } catch (ValidationException $e) {
            $errors = json_encode($e->errors());
            return response("Invalid data ($errors)", 400);
        }
    }

    public function compare(Screenshot $screenshot)
    {
        $prodBranch = $this->branches->where(
            'name', $settings->find('branch')->branch
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

        $this->filesystem->put($diffPath, $compare[0]->encode('png'));

        return [
            'score' => $compare[1],
            'base_line_id' => $production->id,
            'diff_path' => $diffPath
        ];
    }
}
