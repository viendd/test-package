<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\CategoryRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

/**
 * Class CategoryCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class CategoryCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation { store as protected parent_store;}
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation { update as protected parent_update;}
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;

    use \Backpack\CRUD\app\Http\Controllers\Operations\InlineCreateOperation;

    /**
     * Configure the CrudPanel object. Apply settings to all operations.
     *
     * @return void
     */
    public function setup()
    {
        CRUD::setModel(\App\Models\Category::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/category');
        CRUD::setEntityNameStrings('category', 'categories');
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

        CRUD::column('order')->type('number')->label(__('category.order'));
        CRUD::column('name')->type('text')->label(__('category.category_name'));
        CRUD::column('slug')->type('text');

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
        CRUD::setValidation(CategoryRequest::class);
        $this->crud->addField([
            'label' => __('category.language'),
            'type' => 'select',
            'name' => "language_id",
            'entity' => 'language',
            'model' => "App\Models\Language", // related model
            'attribute' => 'name',
        ]);

        $this->crud->addField([
            'label' => __('category.category_name'),
            'type' => 'text',
            'name' => "name",
        ]);

        $this->crud->addField([
            'label' => __('category.category_parent'),
            'type' => 'select',
            'name' => "parent_id",
            'entity' => 'category',
            'model' => "App\Models\Category", // related model
            'attribute' => 'name',
        ]);

        $this->crud->addField([
            'label' => __('category.order'),
            'type' => 'number',
            'name' => "order"
        ]);

        $this->crud->field('slug')->type('hidden');

        /**
         * Fields can be defined using the fluent syntax or array syntax:
         * - CRUD::field('price')->type('number');
         * - CRUD::addField(['name' => 'price', 'type' => 'number']));
         */
    }

    public function store(Request $request)
    {
        $request->merge(['slug' => Str::slug($request->input('name'))]);
        $this->parent_store();
    }

    public function update(Request $request)
    {
        $request->merge(['slug' => Str::slug($request->input('name'))]);
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
