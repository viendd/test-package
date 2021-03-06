<?php

namespace App\Modules\Menu\Controllers;

use App\Modules\Menu\Requests\MenuRequest;
use App\Models\Language;
use App\Modules\Menu\Models\Menu;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;
use Illuminate\Support\Facades\DB;

/**
 * Class MenuCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class MenuCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ReorderOperation;

    /**
     * Configure the CrudPanel object. Apply settings to all operations.
     *
     * @return void
     */
    public function setup()
    {
        CRUD::setModel(\App\Modules\Menu\Models\Menu::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/menu');
        CRUD::setEntityNameStrings('menu', 'menus');
        CRUD::setReorderView('Menu::reorder');
    }

    protected function setupReorderOperation()
    {
        $this->crud->set('reorder.label', 'name');
        // define how deep the admin is allowed to nest the items
        // for infinite levels, set it to 0
        $this->crud->set('reorder.max_level', 2);
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

        CRUD::column('name')->type('text');

        CRUD::addColumn(['name' => 'parent_id', 'type' => 'closure', 'label' => __('Menu::general.parent'), 'function' => function($entry){
            return $entry->parent ? $entry->parent->name : __('Menu::general.parent');
        }, 'wrapper'   => [
            'href' => function ($crud, $column, $entry, $related_key) {
                if($entry->parent) return backpack_url('menu/' .$entry->parent_id. '/show');
            },
        ]]);

        CRUD::column('uri')->type('text');
        CRUD::column('order_no')->type('number')->label(__('Category::general.order'));

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
        CRUD::setValidation(MenuRequest::class);

       $this->addFieldMenu();

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

    public function addFieldMenu()
    {
        $this->crud->addFields([
           [
                   'label' => __('Category::general.language'),
                   'type' => 'select',
                   'name' => "language_id",
                   'entity' => 'language',
                   'model' => "App\Models\Language", // related model
                   'attribute' => 'name',
                    'default' => 1
           ],
            [
                'label' => __('author.name'),
                'type' => 'text',
                'name' => "name",
            ],
            [
                'label' => __('Menu::general.parent'),
                'type' => 'select_on_change',
                'name' => "parent_id",
                'entity' => 'parent',
                'model' => "App\Modules\Menu\Models\Menu", // related model
                'attribute' => 'name',
                'field_change' => 'language_id',
                'url' => 'api/menu'
            ],
            [
                'label' => 'Uri',
                'type' => 'text',
                'name' => "uri",
            ],
            [
                'label' => __('Menu::general.order_no'),
                'type' => 'number',
                'name' => "order_no",
            ],
        ]);
    }

    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            $menu = Menu::findOrFail($id);
            Menu::where('parent_id', $id)->delete();
            $menu->delete();
            DB::commit();
            return true;
        }catch (\Exception $exception){
            DB::rollBack();
            return $exception->getMessage();
        }
    }

    public function reorder()
    {

        $this->crud->hasAccessOrFail('reorder');

        if (! $this->crud->isReorderEnabled()) {
            abort(403, 'Reorder is disabled.');
        }
        if(request()->language_id) $this->data['entries'] = Menu::where('language_id', request()->language_id)->get();
        else $this->data['entries'] = Menu::where('language_id', Language::VI)->get();
        $this->data['crud'] = $this->crud;
        $this->data['title'] = $this->crud->getTitle() ?? trans('backpack::crud.reorder').' '.$this->crud->entity_name;

        // load the view from /resources/views/vendor/backpack/crud/ if it exists, otherwise load the one in the package
        return view($this->crud->getReorderView(), $this->data);
    }
}
