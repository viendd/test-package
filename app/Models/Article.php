<?php

namespace App\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Article extends Model
{
    use CrudTrait;

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    CONST DRAFT = 0;
    CONST PUBLISHED = 1;
    CONST APPROVE = 2;
    CONST PENDING = 3;
    CONST REJECT = 4;
    CONST IS_POST_ADMIN = 1;
    CONST IS_POST_AUTHOR = 0;

    protected $table = 'articles';
    // protected $primaryKey = 'id';
    // public $timestamps = false;
    protected $guarded = ['id'];
    // protected $fillable = [];
    // protected $hidden = [];
    // protected $dates = [];

    /*
    |--------------------------------------------------------------------------
    | FUNCTIONS
    |--------------------------------------------------------------------------
    */

    public static function listStatus()
    {
        return [
            self::DRAFT => __('article.draft'),
            self::PUBLISHED => __('article.published'),
            self::APPROVE => __('article.approve'),
            self::PENDING => __('article.pending'),
            self::REJECT => __('article.reject')
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */
    public function language()
    {
        return $this->belongsTo(Language::class, 'language_id');
    }

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function tags()
    {
        return $this->belongsToMany(Tag::class, 'new_tag', 'new_id', 'tag_id');
    }
    /*
    |--------------------------------------------------------------------------
    | SCOPES
    |--------------------------------------------------------------------------
    */

    public function scopeTopWriteArticle($query)
    {
        return $query->select('user_id', DB::raw('COUNT(user_id) as sum'))
            ->where('status', Article::APPROVE)
            ->groupBy('user_id')
            ->with('user')
            ->orderBy(DB::raw('COUNT(user_id)'), 'DESC')
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
