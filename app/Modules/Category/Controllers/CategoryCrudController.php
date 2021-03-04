<?php

namespace App\Modules\Category\Controllers;

use App\Modules\Category\Requests\CategoryRequest;
use App\Models\Language;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

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
        CRUD::setModel(\App\Modules\Category\Models\Category::class);
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
        CRUD::addColumn(['name' => 'language_id', 'type' => 'closure', 'label' => __('Category::general.language'), 'function' => function($entry){
            return $entry->language->name;
        }]);
        CRUD::column('order')->type('number')->label(__('Category::general.order'));
        CRUD::addColumn('parent');
        CRUD::column('name')->type('text')->label(__('Category::general.category_name'));
        CRUD::column('slug')->type('text');
        CRUD::addColumn([   // select_multiple: n-n relationship (with pivot table)
            'label'     => __('Category::general.article'), // Table column heading
            'type'      => 'relationship_count',
            'name'      => 'articles', // the method that defines the relationship in your Model
            'wrapper'   => [
                'href' => function ($crud, $column, $entry, $related_key) {
                    return backpack_url('article?categories='.$entry->getKey());
                },
            ],
        ]);

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
            'label' => __('Category::general.language'),
            'type' => 'select',
            'name' => "language_id",
            'entity' => 'language',
            'model' => "App\Models\Language", // related model
            'attribute' => 'name',
        ]);

        $this->crud->addField([
            'label' => __('Category::general.category_name'),
            'type' => 'text',
            'name' => "name",
        ]);

        $this->crud->addField([
            'label' => __('Category::general.category_parent'),
            'type' => 'select_on_change',
            'name' => "parent_id",
            'entity' => 'parent',
            'model' => "App\Modules\Category\Models\Category", // related model
            'attribute' => 'name',
            'field_change' => 'language_id',
            'url' => 'api/category',
        ]);

        $this->crud->addField([
            'label' => __('Category::general.order'),
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

    protected function setupShowOperation()
    {
        $this->setupListOperation();
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
