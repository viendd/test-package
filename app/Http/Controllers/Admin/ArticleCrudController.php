<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\ArticleRequest;
use App\Models\Article;
use App\Models\Author;
use App\Models\Category;
use App\Models\Language;
use App\Models\Tag;
use App\Models\User;
use App\Services\UploadService;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

/**
 * Class ArticleCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class ArticleCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation {search as traitSearch;}
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation {store as protected parent_store;}
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation {update as protected parent_update;}
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\FetchOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;


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
        CRUD::setListView('admin.article.index');
        CRUD::setShowView('admin.article.show');
        $this->crud->addButtonFromView('line', 'approve', 'approve', 'beginning');
        $this->crud->addButtonFromView('line', 'reject', 'reject', 'beginning');
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

        CRUD::column('title')->type('text');

        CRUD::addColumn(['name' => 'parent_id', 'type' => 'closure', 'label' => __('category.category_parent'), 'function' => function($entry){
            return $entry->category ? $entry->category->name : __('category.category_parent');
        }, 'wrapper'   => [
            'href' => function ($crud, $column, $entry, $related_key) {
                return backpack_url('category/' .$entry->category_id. '/show');
            },
        ]]);

        CRUD::addColumn(['name' => 'user_id', 'type' => 'closure', 'label' => __('article.user_created'), 'function' => function($entry){
            return $entry->user ? $entry->user->name : null;
        }, 'wrapper'   => [
            'href' => function ($crud, $column, $entry, $related_key) {
                return backpack_url('author/' .$entry->user_id. '/show');
            },
        ]]);
        CRUD::addColumn(['name' => 'created_date', 'type' => 'closure', 'label' => __('article.created_date'), 'function' => function($entry){
            return Carbon::parse($entry->created_at)->format('d/m/Y');
        }]);

        CRUD::addColumn(['name' => 'status', 'type' => 'closure', 'label' => __('article.status'), 'function' => function($entry){
            return Article::listStatus()[$entry->status];
        }]);

        CRUD::addColumn(['name' => 'user_public_id', 'type' => 'closure', 'label' => __('article.user_public'), 'function' => function($entry){
            return $entry->userPublish ? $entry->userPublish->name : __('article.not_public');
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

        $this->crud->addFilter(
            [
                'name'  => 'status',
                'type'  => 'select2',
                'label' => __('article.status'),
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
                'label' => __('article.author'),
            ],
            Author::all()->pluck('name', 'id')->toArray(),
            function ($value) { // if the filter is active
                $this->crud->addClause('where', 'user_id', $value);
            }
        );

        $this->crud->addFilter([ // select2_multiple filter
            'name' => 'tags',
            'type' => 'select2_multiple',
            'label'=> 'Tags',
        ], function () {
            return Tag::all()->keyBy('id')->pluck('name', 'id')->toArray();
        }, function ($values) { // if the filter is active
            $this->crud->query = $this->crud->query->whereHas('tags', function ($q) use ($values) {
                foreach (json_decode($values) as $key => $value) {
                    if ($key == 0) {
                        $q->where('tags.id', $value);
                    } else {
                        $q->orWhere('tags.id', $value);
                    }
                }
            });
        });


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
            'type' => 'relationship',
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
            'options' => [Article::STATUS_PUBLISHED => 'Published', Article::STATUS_DRAFT => 'Draft'],
        ]);

        $this->crud->addField([
            'label' => 'Tags',
            'type' => 'relationship',
            'name' => 'tags', // the method that defines the relationship in your Model
            'entity' => 'tags', // the method that defines the relationship in your Model
            'attribute' => 'name', // foreign key attribute that is shown to user
            'pivot' => true, // on create&update, do you need to add/delete pivot table entries?
            'inline_create' => ['entity' => 'tag'],
            'ajax' => true,
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

    public function setExtraData(Request $request, $action)
    {
        $extra['user_id'] = auth()->user()->id;
        $extra['slug'] = Str::slug($request->title);
        $extra['is_post_admin'] = auth()->user()->is_admin == User::IS_ADMIN ? Article::IS_POST_ADMIN : Article::IS_POST_AUTHOR;
        if ($action == 'create') {
            $extra['created_date'] = Carbon::now();
        }
        if ($request->status == Article::STATUS_PUBLISHED) {
            $extra['user_public_id'] = auth()->user()->id;
            $extra['publish_date'] = Carbon::now();
            $extra['status'] = Article::STATUS_APPROVE;
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
        $request->merge($this->setExtraData($request, 'update'));
        $this->crud->setRequest($this->handleImage($this->crud->getRequest(), 'update', $article->image));
        $item = $this->crud->update($this->crud->getRequest()->id,$this->crud->getRequest()->all());
        $this->data['entry'] = $this->crud->entry = $item;

        // show a success message
        \Alert::success(trans('backpack::crud.update_success'))->flash();

        // save the redirect choice for next time
        $this->crud->setSaveAction();

        return $this->crud->performSaveAction($item->getKey());
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

    /**
     * Respond to AJAX calls from the select2 with entries from the Category model.
     * @return JSON
     */
    public function fetchCategory()
    {
        return $this->fetch(Category::class);
    }

    /**
     * Respond to AJAX calls from the select2 with entries from the Tag model.
     * @return JSON
     */
    public function fetchTags()
    {
        return $this->fetch(Tag::class);
    }

    protected function setupShowOperation()
    {
        $this->setupListOperation();
    }

    public function index()
    {
        $this->crud->hasAccessOrFail('list');
        $this->data['crud'] = $this->crud;
        $this->data['title'] = $this->crud->getTitle() ?? mb_ucfirst($this->crud->entity_name_plural);
        $this->data['top'] = $this->crud->model->topWriteArticle();

        // load the view from /resources/views/vendor/backpack/crud/ if it exists, otherwise load the one in the package
        return view($this->crud->getListView(), $this->data);
    }

    public function show($id)
    {
        $this->crud->addColumn([
            'label' => 'Information emoji',
            'name' => 'info_emoji',
            'type' => 'closure',
            'function' => function($entry){
                return view('admin.article.info_emoji', ['entry' => $entry, 'reaction' => $entry->userReactionArticle, 'mark' => $entry->userMarkArticle()]);
            }
        ]);
        $this->crud->hasAccessOrFail('show');
        $this->data['crud'] = $this->crud;
        $this->crud->addColumn([
            'label' => 'Image',
            'name' => 'image',
            'type' => 'closure',
            'function' => function($entry){
                return '<img src="'.url($entry->image).'" with="200" height="200">';
            }
        ]);
        $this->crud->addColumn('publish_date');
        $this->crud->addColumn('intro_short');
        $this->crud->addColumn([
            'label' => 'Content',
            'name' => 'content',
            'type' => 'closure',
            'function' => function($entry){
                return strip_tags($entry->content);
            }
        ]);
        $this->crud->addColumn('view');
        $this->crud->addColumn('meta_title');
        $this->crud->addColumn('meta_description');
        $this->crud->addColumn('meta_keyword');
        $this->data['title'] = $this->crud->getTitle() ?? mb_ucfirst($this->crud->entity_name_plural);
        $this->data['entry'] =$this->crud->model->findOrFail($id);
        return view($this->crud->getShowView(), $this->data);
    }

    public function search()
    {
        $this->crud->addClause('where','status', '<>', Article::STATUS_DRAFT);
        return $this->traitSearch();
    }

    public function approve($id)
    {
        $article = Article::findOrFail($id);
        $article->update(['status' => Article::STATUS_APPROVE, 'user_public_id' => auth()->user()->id, 'publish_date' => Carbon::now()]);
        $article->refresh();
        \Alert::success(trans('backpack::crud.update_success'))->flash();
        return redirect()->route('article.index');
    }

    public function reject($id)
    {
        $article = Article::findOrFail($id);
        $article->update(['status' => Article::STATUS_REJECT]);
        $article->refresh();
        \Alert::success(trans('backpack::crud.update_success'))->flash();
        return redirect()->route('article.index');
    }
}
