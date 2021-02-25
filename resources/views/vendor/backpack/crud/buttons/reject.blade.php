@if($entry->status == \App\Models\Article::PENDING)
<a href="{{ url($crud->route.'/'.$entry->getKey().'/reject') }} " class="btn btn btn-sm btn-link"><i class="la la-ban"></i> {{ __('article.reject') }}</a>
@endif
