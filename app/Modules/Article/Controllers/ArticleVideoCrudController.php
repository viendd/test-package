<?php

namespace App\Modules\Article\Controllers;

use App\Modules\Article\Requests\ArticleVideoRequest;
use App\Modules\Article\Models\Article;
use App\Modules\Author\Models\Author;
use App\Models\Language;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;
use Carbon\Carbon;

/**
 * Class ArticleVideoCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class ArticleVideoCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation {search as traitSearch;}
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;


    /**
     * Configure the CrudPanel object. Apply settings to all operations.
     *
     * @return void
     */
    public function setup()
    {
        CRUD::setModel(Article::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/articlevideo');
        CRUD::setEntityNameStrings('articlevideo', 'article_videos');
        CRUD::setShowView('Article::show');
        $this->crud->addButtonFromView('line', 'approve', 'approve', 'beginning');
        $this->crud->addButtonFromView('line', 'reject', 'reject', 'beginning');
    }

    public function search()
    {
        $this->crud->addClause('where','status', '<>', Article::STATUS_DRAFT);
        $this->crud->addClause('where','type', '=', Article::TYPE_VIDEO);
        return $this->traitSearch();
    }

    /**
     * Define what happens when the List operation is loaded.
     *
     * @see  https://backpackforlaravel.com/docs/crud-operation-list-entries
     * @return void
     */
    protected function setupListOperation()
    {
//        CRUD::setFromDb(); // columns
        CRUD::addColumn(['name' => 'language_id', 'type' => 'closure', 'label' => __('Category::general.language'), 'function' => function($entry){
            return $entry->language->name;
        }]);

        CRUD::column('title')->type('text')->label(__('Article::general.title'));
        CRUD::addColumn(['name' => 'user_id', 'type' => 'closure', 'label' => __('Article::general.user_created'), 'function' => function($entry){
            return $entry->user ? $entry->user->name : null;
        }, 'wrapper'   => [
            'href' => function ($crud, $column, $entry, $related_key) {
                return backpack_url('author/' .$entry->user_id. '/show');
            },
        ]]);
        CRUD::addColumn(['name' => 'created_date', 'type' => 'closure', 'label' => __('Article::general.created_date'), 'function' => function($entry){
            return Carbon::parse($entry->created_at)->format('d/m/Y');
        }]);

        CRUD::addColumn(['name' => 'status', 'type' => 'closure', 'label' => __('Article::general.status'), 'function' => function($entry){
            return Article::listStatus()[$entry->status];
        }]);

        CRUD::addColumn(['name' => 'user_public_id', 'type' => 'closure', 'label' => __('Article::general.user_public'), 'function' => function($entry){
            return $entry->userPublish ? $entry->userPublish->name : __('Article::general.not_public');
        }]);

        $this->crud->addFilter(
            [
                'name'  => 'language_id',
                'type'  => 'dropdown',
                'label' => 'Language'
            ],
            Language::all()->pluck('short_name', 'id')->toArray(),
            function ($value) { // if the filter is active
                if ($value != 0) {
                    $this->crud->addClause('where', 'language_id', $value);
                }
            }
        );

        $this->crud->addFilter(
            [
                'name'  => 'status',
                'type'  => 'select2',
                'label' => __('Article::general.status'),
            ],
            Article::listStatus(),
            function ($value) { // if the filter is active
                $this->crud->addClause('where', 'status', $value);
            }
        );

        $this->crud->addFilter(
            [
                'name'  => 'user',
                'type'  => 'select2',
                'label' => __('Article::general.author'),
            ],
            Author::all()->pluck('name', 'id')->toArray(),
            function ($value) { // if the filter is active
                $this->crud->addClause('where', 'user_id', $value);
            }
        );
        /**
         * Columns can be defined using the fluent syntax or array syntax:
         * - CRUD::column('price')->type('number');
         * - CRUD::addColumn(['name' => 'price', 'type' => 'number']);
         */
    }



    /**
     * Define what happens when the Create operation is loaded.
     *
     * @see https://backpackforlaravel.com/docs/crud-operation-create
     * @return void
     */
    protected function setupCreateOperation()
    {
        CRUD::setValidation(ArticleVideoRequest::class);

        CRUD::setFromDb(); // fields

        /**
         * Fields can be defined using the fluent syntax or array syntax:
         * - CRUD::field('price')->type('number');
         * - CRUD::addField(['name' => 'price', 'type' => 'number']));
         */
    }

    /**
     * Define what happens when the Update operation is loaded.
     *
     * @see https://backpackforlaravel.com/docs/crud-operation-update
     * @return void
     */
    protected function setupUpdateOperation()
    {
        $this->setupCreateOperation();
    }

    protected function setupShowOperation()
    {
        $this->setupListOperation();
    }

    public function show($id)
    {
        $this->crud->addColumn([
            'label' => 'Information emoji',
            'name' => 'info_emoji',
            'type' => 'closure',
            'function' => function($entry){
                return view('Article::info_emoji', ['entry' => $entry, 'reaction' => $entry->userReactionArticle, 'mark' => $entry->userMarkArticle()]);
            }
        ]);
        $this->crud->hasAccessOrFail('show');
        $this->data['crud'] = $this->crud;
        $this->crud->addColumn([
            'label' => 'Video',
            'name' => 'video',
            'type' => 'closure',
            'function' => function($entry){
                return '<video width="320" height="240" controls><source src="'.url($entry->video).'" type="video/mp4"></source></video>';
            }
        ]);
        $this->crud->addColumn('publish_date');
        $this->crud->addColumn([
            'label' => 'Content',
            'name' => 'content',
            'type' => 'closure',
            'function' => function($entry){
                return strip_tags($entry->content);
            }
        ]);
        $this->data['title'] = $this->crud->getTitle() ?? mb_ucfirst($this->crud->entity_name_plural);
        $this->data['entry'] =$this->crud->model->findOrFail($id);
        return view($this->crud->getShowView(), $this->data);
    }
}
