<?php

namespace Waldo\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Waldo\Branch;
use Waldo\Screenshot;

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
     * HomeController constructor.
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

    public function index()
    {
        $branches = $this->branches->all();

        return view('settings.index', compact('branches'));
    }
    
    public function update(Request $request)
    {
        $request->get('branch');
        $request->get('env');
        
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
