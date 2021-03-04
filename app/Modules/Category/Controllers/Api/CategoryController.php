<?php

namespace App\Modules\Category\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Modules\Category\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index(Request $request)
    {
        $search_term = $request->all();

        $results = Category::where($search_term)->get();

        return $results;
    }
}
