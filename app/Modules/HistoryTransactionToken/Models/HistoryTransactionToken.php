<?php

namespace App\Modules\HistoryTransactionToken\Models;

use App\Modules\Author\Models\Author;
use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class HistoryTransactionToken extends Model
{
    use CrudTrait;

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $table = 'history_transaction_token';
    // protected $primaryKey = 'id';
    // public $timestamps = false;
    protected $guarded = ['id'];
    // protected $fillable = [];
    // protected $hidden = [];
    // protected $dates = [];
    const TYPE_SEND = 1;
    const TYPE_RECEIVE = 2;
    /*
    |--------------------------------------------------------------------------
    | FUNCTIONS
    |--------------------------------------------------------------------------
    */

    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */
    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function userSend()
    {
        return $this->belongsTo(Author::class, 'user_send_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function userReceive()
    {
        return $this->belongsTo(Author::class, 'user_receive_id');
    }

    /*
    |--------------------------------------------------------------------------
    | SCOPES
    |--------------------------------------------------------------------------
    */
    public function scopeTopTransaction($query)
    {
        return $query->select('user_send_id', DB::raw('SUM(token) as sum'))
            ->groupBy('user_send_id')
            ->with('userSend')
            ->orderBy(DB::raw('SUM(token)'), 'DESC')
            ->where('type', HistoryTransactionToken::TYPE_SEND)
            ->take(5)
            ->get();
    }
    /*
    |--------------------------------------------------------------------------
    | ACCESSORS
    |--------------------------------------------------------------------------
    */

    /*
    |--------------------------------------------------------------------------
    | MUTATORS
    |--------------------------------------------------------------------------
    */
}
