<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\AuthorRequest;
use App\Models\Author;
use App\Models\User;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class AuthorCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class AuthorCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation{ search as traitSearch;}
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;

    /**
     * Configure the CrudPanel object. Apply settings to all operations.
     *
     * @return void
     */
    public function setup()
    {
        CRUD::setModel(\App\Models\Author::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/author');
        CRUD::setEntityNameStrings('author', 'authors');
        CRUD::setShowView('admin.author.show');
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
        CRUD::column('avatar')->type('image');
        CRUD::column('name')->type('text');
        CRUD::column('email')->type('email');
        CRUD::column('token')->type('number');
        CRUD::column('is_admin')->type('select_from_array')->options([
            Author::MEMBER => 'Member',
            Author::AUTHOR => 'Author'
        ])->label('Type');
        CRUD::column('created_at')->type('datetime');

        /**
         * Columns can be defined using the fluent syntax or array syntax:
         * - CRUD::column('price')->type('number');
         * - CRUD::addColumn(['name' => 'price', 'type' => 'number']);
         */
    }

    public function search()
    {
        $this->crud->addClause('where', 'is_admin', '<>', User::IS_ADMIN);
        return $this->traitSearch();
    }
}
