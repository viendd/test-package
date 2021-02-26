@if($entry->status == \App\Models\Article::STATUS_PENDING)
    <a href="{{ url($crud->route.'/'.$entry->getKey().'/approve') }} " class="btn btn-sm btn-link"><i class="la la-check-circle"></i> {{ __('article.approve') }}</a>
@endif

