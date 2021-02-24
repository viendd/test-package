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

    CONST IS_TRUST = 1;
    CONST IS_FAKE = 0;

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

    public function userPublish()
    {
        return $this->belongsTo(User::class, 'user_public_id');
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

    public function scopeSearchLikeTitle($query, $title)
    {
        return $query->where('title', 'like', '%'.$title.'%');
    }

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

    public static function topCategoriesInMonth($month = null, $year = null)
    {
        return self::select('category_id', DB::raw('COUNT(category_id) as sum'))
            ->whereMonth('created_at', $month)
            ->whereYear('created_at', $year)
            ->groupBy('category_id')
            ->with('category')
            ->orderBy(DB::raw('COUNT(category_id)'), 'DESC')
            ->take(5)
            ->get();
    }

    public static function topUserInMonth($month = null, $year = null)
    {
        return self::select('user_id', DB::raw('COUNT(user_id) as sum'))
            ->whereMonth('created_at', $month)
            ->whereYear('created_at', $year)
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
