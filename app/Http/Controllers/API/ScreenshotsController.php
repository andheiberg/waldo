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
use Waldo\Screenshot;
use Waldo\Exceptions\ComparisonImageNotFoundException;
use Waldo\Services\ScreenshotComparer;

class ScreenshotsController extends Controller
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
     * @var Filesystem
     */
    private $filesystem;

    /**
     * @var Str
     */
    private $str;

    /**
     * @var ScreenshotComparer
     */
    private $screenshotComparer;

    /**
     * Create a new controller instance.
     *
     * @param Branch             $branches
     * @param Commit             $commits
     * @param Screenshot         $screenshots
     * @param FilesystemManager  $filesystemManager
     * @param Str                $str
     * @param ScreenshotComparer $screenshotComparer
     */
    public function __construct(
        Branch $branches,
        Commit $commits,
        Screenshot $screenshots,
        FilesystemManager $filesystemManager,
        Str $str,
        ScreenshotComparer $screenshotComparer
    ) {
        $this->branches = $branches;
        $this->commits = $commits;
        $this->screenshots = $screenshots;
        $this->filesystem = $filesystemManager->drive('public');
        $this->str = $str;
        $this->screenshotComparer = $screenshotComparer;
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

        if ($screenshot->score == NULL) {
            $diff = $this->screenshotComparer->compare($screenshot);

            $screenshot->update([
                'score' => $diff['score'],
                'base_line_id' => $diff['base_line_id'],
                'diff_path' => $diff['diff_path']
            ]);
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

            return [
                'id' => $screenshot->id,
                'url' => 'http://localhost:8080/screenshots/'.$screenshot->id
            ];
        } catch (ValidationException $e) {
            $errors = json_encode($e->errors());
            return response("Invalid data ($errors)", 400);
        }
    }
}
