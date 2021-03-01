<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\AuthorRequest;
use App\Models\Author;
use App\Models\HistoryTransactionToken;
use App\Models\User;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;
use Illuminate\Http\Request;

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
        CRUD::column('avatar')->type('image')->label(__('author.avatar'));
        CRUD::column('name')->type('text')->label(__('author.name'));
        CRUD::column('email')->type('email');
        CRUD::column('token')->type('number');
        CRUD::column('is_admin')->type('select_from_array')->options([
            Author::MEMBER => 'Member',
            Author::AUTHOR => 'Author'
        ])->label('Type')->label(__('author.type'));
        CRUD::column('created_at')->type('datetime')->label(__('author.created'));

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

    public function show($id, Request $request)
    {
        $this->crud->hasAccessOrFail('show');
        $this->data['crud'] = $this->crud;
        $this->data['title'] = $this->crud->getTitle() ?? mb_ucfirst($this->crud->entity_name_plural);
        $this->data['user'] = Author::findOrFail($id);
        $this->data['articles'] = $this->data['user']->articles();
        $this->data['markArticles'] = $this->data['user']->markArticles();
        if($request->status_article && $request->status_article != 'null'){
            $this->data['articles'] = $this->data['articles']->where('status', $request->status_article);
        }
        if($request->search_list_article && $request->search_list_article != ''){
            $this->data['articles'] = $this->data['articles']->searchLikeTitle($request->search_list_article);
        }
        if(isset($request->status_mark) && $request->status_mark != 'null'){
            $this->data['markArticles'] = $this->data['markArticles']->wherePivot('is_trust', $request->status_mark);
        }
        if($request->search_list_article_mark && $request->search_list_article_mark != ''){
            $this->data['markArticles'] = $this->data['markArticles']->searchLikeTitle($request->search_list_article_mark);
        }
        $this->data['markArticles'] = $this->data['markArticles']->get();
        $this->data['articles'] = $this->data['articles']->get();
        $this->data['transactions'] = HistoryTransactionToken::where('user_send_id', $id)->orWhere('user_receive_id', $id);
        if(isset($request->filter_month) && $request->filter_month != 'null'){
            $this->data['transactions'] = $this->data['transactions']->whereMonth('created_at', '=', $request->filter_month);
        }
        if(isset($request->status_transaction) && $request->status_transaction != 'null'){
            if($request->status_transaction == HistoryTransactionToken::TYPE_SEND){
                $this->data['transactions'] = $this->data['transactions']->where('user_send_id', $id);
            }
            if($request->status_transaction == HistoryTransactionToken::TYPE_RECEIVE){
                $this->data['transactions'] = $this->data['transactions']->where('user_receive_id', $id);
            }
        }
        $this->data['transactions'] = $this->data['transactions']->get();

        http_build_query(array_merge($_GET, array("tab"=> $request->tab)));
        return view($this->crud->getShowView(), $this->data);
    }
}
