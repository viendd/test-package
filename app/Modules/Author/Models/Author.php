<?php

namespace App\Modules\Author\Models;

use App\Modules\Article\Models\Article;
use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Model;

class Author extends Model
{
    use CrudTrait;

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $table = 'users';
    // protected $primaryKey = 'id';
    // public $timestamps = false;
    protected $guarded = ['id'];
    // protected $fillable = [];
    // protected $hidden = [];
    // protected $dates = [];

    CONST MEMBER = 0;
    CONST AUTHOR = 2;
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

    public function articles()
    {
        return $this->hasMany(Article::class, 'user_id');
    }

    public function markArticles()
    {
        return $this->belongsToMany(Article::class, 'user_mark_article', 'user_id', 'article_id')->withPivot('is_trust', 'evidence','created_at');
    }

    public function followUsers()
    {
        return $this->belongsToMany(self::class, 'follows', 'user_id', 'user_follow_id');
    }
    public function followed()
    {
        return $this->belongsToMany(self::class, 'follows', 'user_follow_id', 'user_id');
    }

    /*
    |--------------------------------------------------------------------------
    | SCOPES
    |--------------------------------------------------------------------------
    */

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
