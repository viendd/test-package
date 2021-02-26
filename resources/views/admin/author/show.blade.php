@extends(backpack_view('blank'))
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-BmbxuPwQa2lc/FVzBcNJ7UAyJxM6wuqIj61tLrc4wSX0szH/Ev+nYRRuWlolflfl" crossorigin="anonymous">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/js/bootstrap.bundle.min.js" integrity="sha384-b5kHyXgcpbZJO/tY9Ul7kGkf1S0CWuKcCD38l8YkeH8z8QjE0GmW1gYU5S9FOnJ0" crossorigin="anonymous"></script>
<script>
    function setParamUrl(name){
        const urlParams = new URLSearchParams(window.location.search);

        urlParams.set('tab', name);

        window.location.search = urlParams;
    }

    function filterStatusArticle(event, form){
        $('#' + form).submit();
    }
</script>
@php
    $defaultBreadcrumbs = [
      trans('backpack::crud.admin') => url(config('backpack.base.route_prefix'), 'dashboard'),
      $crud->entity_name_plural => url($crud->route),
      trans('backpack::crud.preview') => false,
    ];

    // if breadcrumbs aren't defined in the CrudController, use the default breadcrumbs
    $breadcrumbs = $breadcrumbs ?? $defaultBreadcrumbs;
@endphp

@section('header')
    <section class="container-fluid d-print-none">
        <a href="javascript: window.print();" class="btn float-right"><i class="la la-print"></i></a>
        <h2>
            <span class="text-capitalize">{!! $crud->getHeading() ?? $crud->entity_name_plural !!}</span>
            <small>{!! $crud->getSubheading() ?? mb_ucfirst(trans('backpack::crud.preview')).' '.$crud->entity_name !!}.</small>
            @if ($crud->hasAccess('list'))
                <small class=""><a href="{{ url($crud->route) }}" class="font-sm"><i class="la la-angle-double-left"></i> {{ trans('backpack::crud.back_to_all') }} <span>{{ $crud->entity_name_plural }}</span></a></small>
            @endif
        </h2>
    </section>
@endsection
@section('content')
    <div class="row">
        <div class="{{ $crud->getShowContentClass() }}">

            <!-- Default box -->
                <div class="nav-tabs-custom">
                    <ul class="nav nav-tabs" id="myTab" role="tablist">
                        <li class="nav-item" role="presentation">
                            <a onclick="setParamUrl('profile')" class="nav-link <?= (!isset($_GET['tab']) || isset($_GET['tab']) && $_GET['tab'] == "profile")? "active" : "" ?>" id="home-tab" data-bs-toggle="tab" data-bs-target="#home" type="button" role="tab" aria-controls="home" aria-selected="true">{{__('author.profile')}}</a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a onclick="setParamUrl('list-articles')" class="nav-link <?= (isset($_GET['tab']) && $_GET['tab'] == "list-articles")? "active" : "" ?>" id="profile-tab" data-bs-toggle="tab" data-bs-target="#profile" type="button" role="tab" aria-controls="profile" aria-selected="false">{{__('author.list_article')}}</a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a onclick="setParamUrl('list-articles-mark')" class="nav-link <?= (isset($_GET['tab']) && $_GET['tab'] == "list-articles-mark")? "active" : "" ?>" id="contact-tab" data-bs-toggle="tab" data-bs-target="#contact" type="button" role="tab" aria-controls="contact" aria-selected="false">{{__('author.list_article_mark')}}</a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a onclick="setParamUrl('transaction-token')" class="nav-link <?= (isset($_GET['tab']) && $_GET['tab'] == "transaction-token")? "active" : "" ?>" id="transaction-token-tab" data-bs-toggle="tab" data-bs-target="#transaction-token" type="button" role="tab" aria-controls="transaction_token" aria-selected="false">{{__('author.history_transaction_token')}}</a>
                        </li>
                    </ul>
                    <div class="tab-content" id="myTabContent">
                        <div class="tab-pane fade show <?= !isset($_GET['tab']) || (isset($_GET['tab']) && $_GET['tab'] == "profile")? "active" : "" ?>" id="home" role="tabpanel" aria-labelledby="home-tab">
                            <div class="mb-3">
                                <label for="exampleFormControlInput1" class="form-label">Avatar</label>
                                <br>
                                @if($user->avatar)
                                    <img src="{{url($user->avatar)}}" alt="" width="140" height="140">
                                @else
                                    <img src="{{url('storage/users/default.png')}}" alt="" width="140" height="140">
                                @endif

                            </div>
                            <div class="mb-3">
                                <label for="exampleFormControlInput1" class="form-label">Email</label>
                                <input type="email" class="form-control" id="exampleFormControlInput1" placeholder="name@example.com" value="{{$user->email}}" disabled>
                            </div>
                            <div class="mb-3">
                                <label for="exampleFormControlInput1" class="form-label">{{__('author.name')}}</label>
                                <input type="text" class="form-control" id="exampleFormControlInput1" value="{{$user->name}}" disabled>
                            </div>
                            <div class="mb-3">
                                <label for="exampleFormControlInput1" class="form-label">{{__('author.phone')}}</label>
                                <input type="text" class="form-control" id="exampleFormControlInput1" value="{{$user->phone}}" disabled>
                            </div>
                            <div class="mb-3">
                                <label for="exampleFormControlInput1" class="form-label">{{__('author.introduction')}}</label>
                                <input type="email" class="form-control" id="exampleFormControlInput1" placeholder="name@example.com" value="{{$user->introduction}}" disabled>
                            </div>
                            <div class="mb-3">
                                <label for="exampleFormControlInput1" class="form-label">{{__('author.amount_token')}}</label>
                                <input type="number" class="form-control" id="exampleFormControlInput1" value="{{$user->token}}" disabled>
                            </div>
                            <div class="mb-3">
                                <label for="exampleFormControlInput1" class="form-label">{{__('author.amount_token')}}</label>
                                <input type="number" class="form-control" id="exampleFormControlInput1" value="{{$user->token}}" disabled>
                            </div>
                            <div class="mb-3">
                                <label for="exampleFormControlInput1" class="form-label">{{__('author.amount_user_follow')}}</label>
                                <input type="number" class="form-control" id="exampleFormControlInput1" value="{{$user->followUsers->count()}}" disabled>
                            </div>
                            <div class="mb-3">
                                <label for="exampleFormControlInput1" class="form-label">{{__('author.amount_followed')}}</label>
                                <input type="number" class="form-control" id="exampleFormControlInput1" value="{{$user->followed->count()}}" disabled>
                            </div>
                            <div class="mb-3">
                                <label for="exampleFormControlInput1" class="form-label">{{__('author.address')}}</label>
                                <input type="number" class="form-control" id="exampleFormControlInput1" value="{{$user->address}}" disabled>
                            </div>
                            <div class="mb-3">
                                <label for="exampleFormControlInput1" class="form-label">{{__('author.company')}}</label>
                                <input type="number" class="form-control" id="exampleFormControlInput1" value="{{$user->company}}" disabled>
                            </div>
                            <div class="mb-3">
                                <label for="exampleFormControlInput1" class="form-label">Facebook</label>
                                <input type="text" class="form-control" id="exampleFormControlInput1" value="{{$user->facebook}}" disabled>
                            </div>
                            <div class="mb-3">
                                <label for="exampleFormControlInput1" class="form-label">Instagram</label>
                                <input type="text" class="form-control" id="exampleFormControlInput1" value="{{$user->instagram}}" disabled>
                            </div>
                        </div>
                        <div class="tab-pane fade <?= (isset($_GET['tab']) && $_GET['tab'] == "list-articles")? "active show" : "" ?>" id="profile" role="tabpanel" aria-labelledby="profile-tab">
                            <div class="top_head_list_article">
                                <div class="row">
                                    <div class="col-sm-5">
                                        <h4>{{__('author.total_article')}} : {{$articles->count()}}</h4>
                                    </div>
                                    <div class="col-sm-7">
                                        <div class="row">
                                            <div class="col-sm-5">
                                                <form action="{{route('author.show', ['id' => $user->id])}}" id="form_status_article">
                                                    <input name="tab" type="hidden" value="{{!isset($_GET['tab']) ? 'profile' : $_GET['tab']}}">
                                                    <input name="search_list_article" type="hidden" value="{{!isset($_GET['search_list_article']) ? '' : $_GET['search_list_article']}}">
                                                    <select name="status_article" id="status_article" onchange="filterStatusArticle(event, 'form_status_article')">
                                                        <option value="null">{{__('author.select_status')}}</option>
                                                        <option value="{{\App\Models\Article::STATUS_APPROVE}}" {{isset($_GET['status_article']) && $_GET['status_article'] == \App\Models\Article::STATUS_APPROVE ? 'selected' : '' }}>{{__('author.approve')}}</option>
                                                        <option value="{{\App\Models\Article::STATUS_PENDING}}" {{isset($_GET['status_article']) && $_GET['status_article'] == \App\Models\Article::STATUS_PENDING ? 'selected' : '' }}>{{__('author.pending')}}</option>
                                                        <option value="{{\App\Models\Article::STATUS_REJECT}}" {{isset($_GET['status_article']) && $_GET['status_article'] == \App\Models\Article::STATUS_REJECT ? 'selected' : '' }}>{{__('author.reject')}}</option>
                                                    </select>
                                                </form>
                                            </div>
                                            <div class="col-sm-5">
                                                <form action="{{route('author.show', ['id' => $user->id])}}" id="form_search_list_article">
                                                    <input name="tab" type="hidden" value="{{!isset($_GET['tab']) ? 'profile' : $_GET['tab']}}">
                                                    <input name="status_article" type="hidden" value="{{!isset($_GET['status_article']) ? 'null' : $_GET['status_article']}}">
                                                    <input type="text" name="search_list_article" value="{{!isset($_GET['search_list_article']) ? '' : $_GET['search_list_article']}}">
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <table class="table">
                                <thead>
                                <tr>
                                    <th scope="col">{{__('author.title')}}</th>
                                    <th scope="col">{{__('author.category')}}</th>
                                    <th scope="col">{{__('author.status')}}</th>
                                    <th scope="col">{{__('author.action')}}</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($articles as $article)
                                    <tr>
                                        <th scope="row">{{$article->title}}</th>
                                        <td>{{$article->category->name}}</td>
                                        <td>{{\App\Models\Article::listStatus()[$article->status]}}</td>
                                        <td><a class="btn btn-primary" href="{{route('article.show', ['id' => $article->id])}}">{{__('author.view_detail')}}</a></td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="tab-pane fade <?= (isset($_GET['tab']) && $_GET['tab'] == "list-articles-mark")? "active show" : "" ?>" id="contact" role="tabpanel" aria-labelledby="contact-tab">
                            <div class="top_head_list_article_mark">
                                <div class="row">
                                    <div class="col-sm-5"><h4>{{__('author.total_article')}} : {{$markArticles->count()}}</h4>
                                    </div>
                                    <div class="col-sm-7">
                                        <div class="row">
                                            <div class="col-sm-5">
                                                <form action="{{route('author.show', ['id' => $user->id])}}" id="status_list_article_mark">
                                                    <input name="tab" type="hidden" value="{{!isset($_GET['tab']) ? 'profile' : $_GET['tab']}}">
                                                    <input name="search_list_article_mark" type="hidden" value="{{!isset($_GET['search_list_article_mark']) ? '' : $_GET['search_list_article_mark']}}">
                                                    <select name="status_mark" id="status_mark" onchange="filterStatusArticle(event, 'status_list_article_mark')">
                                                        <option value="null">{{__('author.select_status')}}</option>
                                                        <option value="{{\App\Models\Article::IS_TRUST}}" {{isset($_GET['status_mark']) && $_GET['status_mark'] == \App\Models\Article::IS_TRUST ? 'selected' : '' }}>Trust</option>
                                                        <option value="{{\App\Models\Article::IS_FAKE}}" {{isset($_GET['status_mark']) && $_GET['status_mark'] === '0' ? 'selected' : '' }}>Fake</option>
                                                    </select>
                                                </form>
                                            </div>
                                            <div class="col-sm-5">
                                                <form action="{{route('author.show', ['id' => $user->id])}}" id="form_search_list_article_mark">
                                                    <input name="tab" type="hidden" value="{{!isset($_GET['tab']) ? 'profile' : $_GET['tab']}}">
                                                    <input name="status_mark" type="hidden" value="{{!isset($_GET['status_mark']) ? 'null' : $_GET['status_mark']}}">
                                                    <input type="text" name="search_list_article_mark" value="{{!isset($_GET['search_list_article_mark']) ? '' : $_GET['search_list_article_mark']}}">
                                                </form>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>
                            <table class="table">
                                <thead>
                                <tr>
                                    <th scope="col">{{__('author.title')}}</th>
                                    <th scope="col">{{__('author.category')}}</th>
                                    <th scope="col">{{__('author.status')}}</th>
                                    <th scope="col">{{__('author.date')}}</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($markArticles as $article)
                                    <tr>
                                        <th scope="row">{{$article->title}}</th>
                                        <td>{{$article->category->name}}</td>
                                        <td>
                                            {{$article->pivot->is_trust == \App\Models\Article::IS_TRUST ? 'Trust' : 'Fake'}}
                                        </td>
                                        <td>{{$article->pivot->created_at}}</td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="tab-pane fade <?= (isset($_GET['tab']) && $_GET['tab'] == "transaction-token")? "active show" : "" ?>" id="transaction-token" role="tabpanel" aria-labelledby="transaction-token-tab">
                            <div class="top_header_transaction">
                                <div class="row">
                                    <div class="col-sm-5">
                                        <h4>{{__('author.total_transaction')}} : {{$transactions->count()}}</h4>
                                    </div>
                                    <div class="col-sm-7">
                                        <div class="row">
                                            <div class="col-sm-5">
                                                <form action="{{route('author.show', ['id' => $user->id])}}" id="form_filter_month">
                                                    <input name="tab" type="hidden" value="{{!isset($_GET['tab']) ? 'profile' : $_GET['tab']}}">
                                                    <input name="status_transaction" type="hidden" value="{{!isset($_GET['status_transaction']) ? '' : $_GET['status_transaction']}}">
                                                    <select name="filter_month" id="filter_month" onchange="filterStatusArticle(event, 'form_filter_month')">
                                                        <option value="null">{{__('author.all_month')}}</option>
                                                        @for($i = 1;$i<=12;$i++)
                                                            <option value="{{$i}}" {{isset($_GET['filter_month']) && $_GET['filter_month'] == $i ? 'selected': ''}}>{{__('author.month')}} {{$i}}</option>
                                                        @endfor
                                                    </select>
                                                </form>
                                            </div>
                                            <div class="col-sm-5">
                                                <form action="{{route('author.show', ['id' => $user->id])}}" id="form_status_transaction">
                                                    <input name="tab" type="hidden" value="{{!isset($_GET['tab']) ? 'profile' : $_GET['tab']}}">
                                                    <input name="filter_month" type="hidden" value="{{!isset($_GET['filter_month']) ? 'null' : $_GET['filter_month']}}">
                                                    <select name="status_transaction" id="status_transaction" onchange="filterStatusArticle(event, 'form_status_transaction')">
                                                        <option value="null">{{__('author.all_status')}}</option>
                                                        <option value="{{\App\Models\HistoryTransactionToken::TYPE_SEND}}" {{isset($_GET['status_transaction']) && $_GET['status_transaction'] == \App\Models\HistoryTransactionToken::TYPE_SEND ? 'selected': ''}}>{{__('author.send_token')}}</option>
                                                        <option value="{{\App\Models\HistoryTransactionToken::TYPE_RECEIVE}}" {{isset($_GET['status_transaction']) && $_GET['status_transaction'] == \App\Models\HistoryTransactionToken::TYPE_RECEIVE ? 'selected': ''}}>{{__('author.receive_token')}}</option>
                                                    </select>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <table class="table">
                                <thead>
                                <tr>
                                    <th scope="col">{{__('author.type_transaction')}}</th>
                                    <th scope="col">{{__('author.name_transaction')}}</th>
                                    <th scope="col">{{__('author.amount_token')}}</th>
                                    <th scope="col">{{__('author.date')}}</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($transactions as $transaction)
                                    <tr>
                                        <th scope="row">{{$transaction->user_send_id == $user->id ? __('author.send_token') : __('author.receive_token')}}</th>
                                        <td>{{$transaction->user_send_id == $user->id ? @\App\Models\Author::find($transaction->user_receive_id)->name : @\App\Models\Author::find($transaction->user_send_id)->name}}</td>
                                        <td>{{$transaction->token}}</td>
                                        <td>{{$transaction->created_at}}</td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
@endsection


