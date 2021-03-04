<?php

namespace App\Modules\Menu\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Modules\Menu\Models\Menu;
use Illuminate\Http\Request;

class MenuController extends Controller
{
    public function index(Request $request)
    {
        $search_term = $request->all();

        $results = Menu::where($search_term)->get();

        return $results;
    }
}
