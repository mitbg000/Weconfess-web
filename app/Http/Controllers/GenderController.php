<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File; 
use Image;
use Carbon\Carbon;
use App\Models\Settings;
use App\Models\Pages;
use App\Models\Items;
use App\Models\Images;
use App\Models\Likes;
use App\Models\User;
use App\Models\Categories;
use App\Models\ItemsViews;
use App\Models\Notifications;
use App\Models\Reports;
use \Conner\Tagging\Taggable;
use Overtrue\LaravelLike\Traits\Likeable;
use App\Models\Advertising;
use App\Models\Genders;
use App\Models\Points;
use App\Models\Photos;
use Snipe\BanBuilder\CensorWords;

class GenderController extends Controller
{

    use Likeable;
    
    public function __construct()
    {
        $this->categories = Categories::where('status', 1)->get();
        $this->pages = Pages::where('status', 1)->get();
        $this->most_used_tags = \Conner\Tagging\Model\Tag::where('count', '>=', 2)->limit('3')->get();
        $this->results_per_page = Settings::find('results_per_page')->value;
        
        if(Auth::check()){
            $this->userPoints = Auth::user()->total_point_count(); // if you are logged in, assign points to the current user
        } else {
            $this->userPoints = 0; // if he is a guest he awards 0 points
        }
        
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($id, $slug)
    {

        $gender = Genders::find($id);

        // get items list
        $items = Items::query()->whereHas('category', function ($query) {
            
            if (Auth::check()) {
                $userPoints = Auth::user()->total_point_count(); // if you are logged in, assign points to the current user
            } else {
                $userPoints = 0; // if he is a guest he awards 0 points
            }
            
            $query->where('score', '<=', $userPoints);
            $query->where('status', 1); // only where the category it belongs to is active
        })
        ->where('genders_id', $gender->id)
        ->where('status', 1)
        ->orderByDesc('featured')
        ->orderByDesc('id')
        ->paginate($this->results_per_page);

        return view('index')->with([
            'site_name' => Settings::find('site_name')->value,
            'site_description' => Settings::find('site_description')->value,
            'page_name' => $gender->name,
            'items' => $items,
            'categories' => $this->categories,
            'pages' => $this->pages,
            'status_write' => Settings::find('active_upload')->value,
            'setting_adv_top' => Settings::find('adv_top')->value,
            'setting_adv_bottom' => Settings::find('adv_bottom')->value,
            'adv_top' => Advertising::where('id', Settings::find('adv_top')->value)->first(),
            'adv_bottom' => Advertising::where('id', Settings::find('adv_bottom')->value)->first(),
            'story_preview_chars' => Settings::find('story_preview_chars')->value
        ]);

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
