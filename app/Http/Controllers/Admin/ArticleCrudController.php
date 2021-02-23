<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\ArticleRequest;
use App\Models\Article;
use App\Models\Category;
use App\Models\User;
use App\Services\UploadService;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

/**
 * Class ArticleCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class ArticleCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation {store as protected parent_store;}
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation {update as protected parent_update;}
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;

    protected $fileService;

    /**
     * Configure the CrudPanel object. Apply settings to all operations.
     *
     * @return void
     */
    public function setup()
    {
        CRUD::setModel(\App\Models\Article::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/article');
        CRUD::setEntityNameStrings('article', 'articles');
        $this->fileService = app()->make(UploadService::class);
    }

    /**
     * Define what happens when the List operation is loaded.
     *
     * @see  https://backpackforlaravel.com/docs/crud-operation-list-entries
     * @return void
     */
    protected function setupListOperation()
    {


        CRUD::addColumn(['name' => 'language_id', 'type' => 'closure', 'label' => __('category.language'), 'function' => function($entry){
            return $entry->language->name;
        }]);

        CRUD::addColumn(['name' => 'parent_id', 'type' => 'closure', 'label' => __('category.category_parent'), 'function' => function($entry){
            return $entry->category ? $entry->category->name : __('category.category_parent');
        }]);

        CRUD::addColumn(['name' => 'user_id', 'type' => 'closure', 'label' => __('article.user_created'), 'function' => function($entry){
            return $entry->user ? $entry->user->name : null;
        }]);

        CRUD::column('title')->type('text');

        CRUD::addColumn(['name' => 'image', 'type' => 'closure', 'label' => __('article.image'), 'function' => function($entry){
            return '<img src="'.url($entry->image).'" alt="" width="120" height="120"/>';
        }]);


        $this->crud->addFilter(
            [
                'name'  => 'categories',
                'type'  => 'select2',
                'label' => __('category.category_name'),
            ],
            Category::all()->pluck('name', 'id')->toArray(),
            function ($value) { // if the filter is active
                $this->crud->addClause('whereHas', 'category', function ($query) use ($value) {
                    $query->where('category_id', '=', $value);
                });
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
        CRUD::setValidation(ArticleRequest::class);

        $this->crud->addField([
            'label' => __('category.language'),
            'type' => 'select',
            'name' => "language_id",
            'entity' => 'language',
            'model' => "App\Models\Language", // related model
            'attribute' => 'name',
        ]);

        $this->crud->addField([
            'label' => 'Category',
            'type' => 'select',
            'name' => 'category_id',
            'entity' => 'category',
            'attribute' => 'name',
            'inline_create' => true,
            'ajax' => true,
        ]);

        $this->crud->addField([
            'label' => 'Title',
            'type' => 'text',
            'name' => 'title',
        ]);

        $this->crud->addField([
            'label' => 'Intro short',
            'type' => 'text',
            'name' => 'intro_short',
        ]);

        $this->crud->addField([
            'name' => 'content',
            'label' => 'Content',
            'type' => 'ckeditor',
            'placeholder' => 'Your textarea text here',
            // optional:
            'options'       => [
                'autoGrow_minHeight'   => 400,
                'autoGrow_bottomSpace' => 50,
                'height' => 400,
                'removePlugins'        => 'resize,maximize',
            ]
        ]);

        $this->crud->addField([
            'label' => "Image",
            'name' => "avatar",
            'type'      => 'upload',
            'upload'    => true,
        ]);

        $this->crud->addField([
            'label' => "Status",
            'name' => "status",
            'type' => 'select_from_array',
            'options' => [Article::DRAFT => 'Draft', Article::PUBLISHED => 'Published'],
        ]);

        $this->crud->addField([
            'label' => 'Reason',
            'type' => 'text',
            'name' => 'reason',
        ]);

        $this->crud->addField([
            'label' => 'Meta title',
            'type' => 'text',
            'name' => 'meta_title',
        ]);
        $this->crud->addField([
            'label' => 'Meta description',
            'type' => 'text',
            'name' => 'meta_description',
        ]);
        $this->crud->addField([
            'label' => 'Meta_keyword',
            'type' => 'text',
            'name' => 'meta_keyword',
        ]);
        $this->crud->field('user_id')->type('hidden');
        $this->crud->field('slug')->type('hidden');
        $this->crud->field('is_post_admin')->type('hidden');
        $this->crud->field('user_public_id')->type('hidden');
        $this->crud->field('created_date')->type('hidden');


        /**
         * Fields can be defined using the fluent syntax or array syntax:
         * - CRUD::field('price')->type('number');
         * - CRUD::addField(['name' => 'price', 'type' => 'number']));
         */
    }

    public function setExtraData(Request $request, $action, $pathUrl = null)
    {
        $extra['user_id'] = auth()->user()->id;
        $extra['slug'] = Str::slug($request->title);
        $extra['is_post_admin'] = auth()->user()->is_admin == User::IS_ADMIN ? Article::IS_POST_ADMIN : Article::IS_POST_AUTHOR;
        if ($action == 'create') {
            $extra['created_date'] = Carbon::now();
        }
        if ($request->status == Article::PUBLISHED) {
            $extra['user_public_id'] = auth()->user()->id;
            $extra['publish_date'] = Carbon::now();
        }

        return $extra;
    }

    /**
     * @param Request $request
     * @param $action
     * @param $pathUrl
     * @return string
     */
    public function handleImage($request, $action, $pathUrl = null){
        if($request->file('avatar')){
            $path = $this->fileService->uploadFile($request, 'articles', 'avatar');
            if($path){
                $request->request->set('image',$path);
            }
            if($action == 'update'){
                $this->fileService->removeFile($pathUrl);
            }
        }
        return $request;
    }

    public function store(Request $request)
    {
        $request->merge($this->setExtraData($request, 'create'));
        $this->crud->setRequest($this->handleImage($this->crud->getRequest(), 'create'));
        $item = $this->crud->create($this->crud->getRequest()->all());
        $this->data['entry'] = $this->crud->entry = $item;

        // show a success message
        \Alert::success(trans('backpack::crud.insert_success'))->flash();

        // save the redirect choice for next time
        $this->crud->setSaveAction();

        return $this->crud->performSaveAction($item->getKey());
    }

    public function update(Request $request)
    {
        $article = $this->crud->model->find($request->id);
        $request->merge($this->setExtraData($request, 'update', $article->image));
        $this->parent_update();
    }

    /**
     * Define what happens when the Update operation is loaded.
     *
     * @see https://backpackforlaravel.com/docs/crud-operation-update
     * @return void
     */
    protected function setupUpdateOperation()
    {
        $this->crud->field('id')->type('hidden');
        $this->setupCreateOperation();
    }
}
