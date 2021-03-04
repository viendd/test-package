<?php

namespace App\Modules\Dashboard\Controllers;

use App\Modules\Article\Models\Article;
use App\Modules\Author\Models\Author;
use App\Modules\Category\Models\Category;
use App\Models\User;
use App\Models\UserMarkArticle;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;
use Carbon\Carbon;
use Illuminate\Http\Request;

/**
 * Class DashboardCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class DashboardCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation {search as traitSearch;}
    /**
     * Configure the CrudPanel object. Apply settings to all operations.
     *
     * @return void
     */
    public function setup()
    {
        CRUD::setRoute(config('backpack.base.route_prefix') . '/dashboard');
        CRUD::setEntityNameStrings('dashboard', 'dashboards');
        CRUD::setListView('Dashboard::index');
    }

    public function index(Request $request)
    {
        $this->crud->hasAccessOrFail('list');
        $this->data['crud'] = $this->crud;
        $this->data['title'] = $this->crud->getTitle() ?? mb_ucfirst($this->crud->entity_name_plural);
        $this->data['count_categories'] = Category::all()->count();
        $this->data['count_article'] = Article::all()->count();
        $member = Author::where('is_admin', '<>', User::IS_ADMIN);
        $this->data['count_member'] = $member->count();
        $this->data['count_token'] = $member->sum('token');
        $this->data['top_categories'] = Article::topCategoriesInMonth($request->month ?? Carbon::now()->month, $request->year ?? Carbon::now()->year);
        $this->data['top_user_write'] = Article::topUserInMonth($request->month ?? Carbon::now()->month, $request->year ?? Carbon::now()->year);
        $this->data['top_user_mark'] = UserMarkArticle::topUserMarkFilter($request->month ?? Carbon::now()->month, $request->year ?? Carbon::now()->year);

        // load the view from /resources/views/vendor/backpack/crud/ if it exists, otherwise load the one in the package
        return view($this->crud->getListView(), $this->data);
    }
}
