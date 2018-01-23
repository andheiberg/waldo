<?php

namespace Waldo\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Waldo\Branch;
use Waldo\Screenshot;
use Waldo\Setting;

class SettingsController extends Controller
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
     * @var Setting
     */
    private $settings;

    /**
     * HomeController constructor.
     *
     * @param Branch     $branches
     * @param Screenshot $screenshots
     * @param Setting    $settings
     */
    public function __construct(
        Branch $branches,
        Screenshot $screenshots,
        Setting $settings
    ) {
        $this->branches = $branches;
        $this->screenshots = $screenshots;
        $this->settings = $settings;
    }

    public function index()
    {
        $branches = $this->branches->all();

        return view('settings.index', compact('branches'));
    }
    
    public function update(Request $request)
    {
        $branch = $this->settings->find('branch');
        $branch->update([
            'value' => $request->get('branch')
        ]);

        $env = $this->settings->find('env');
        $env->update([
            'value' => $request->get('env')
        ]);
        
        return redirect('settings');
    }
    
    public function deleteAll()
    {
        $screenshots = $this->screenshots->all();
        
        foreach ($screenshots as $screenshot) {
            $screenshot->delete();
        }
        
        return redirect('settings');
    }

    public function deleteOld()
    {
        $screenshots = $this->screenshots->where('created_at', '<', Carbon::now()->subDays(7))->all();

        foreach ($screenshots as $screenshot) {
            $screenshot->delete();
        }

        return redirect('settings');
    }
}
