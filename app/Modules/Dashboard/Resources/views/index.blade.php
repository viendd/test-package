@extends(backpack_view('blank'))
@php
    $defaultBreadcrumbs = [
      trans('backpack::crud.admin') => url(config('backpack.base.route_prefix'), 'dashboard'),
      $crud->entity_name_plural => url($crud->route),
      trans('backpack::crud.list') => false,
    ];

    // if breadcrumbs aren't defined in the CrudController, use the default breadcrumbs
    $breadcrumbs = $breadcrumbs ?? $defaultBreadcrumbs;
@endphp
<script>
    function filterData(event){
        $('#filter_date').submit();
    }
</script>
@section('header')
    <div class="container-fluid">
        <h2>
            <span class="text-capitalize">{!! $crud->getHeading() ?? $crud->entity_name_plural !!}</span>
            <small id="datatable_info_stack">{!! $crud->getSubheading() ?? '' !!}</small>
        </h2>
    </div>
@endsection

@section('content')
    <div class="content">
        <div class="row">
            <div class="col-sm-3">
                <div class="statistical category">
                    <p class="number">{{$count_categories}}</p>
                    {{__('Dashboard::general.all_category')}}
                </div>
            </div>
            <div class="col-sm-3">
                <div class="statistical article" role="alert">
                    <p class="number">{{$count_article}}</p>
                    {{__('Dashboard::general.all_article')}}
                </div>
            </div>
            <div class="col-sm-3">
                <div class="statistical member" role="alert">
                    <p class="number">{{$count_member}}</p>
                    {{__('Dashboard::general.all_member')}}
                </div>
            </div>
            <div class="col-sm-3">
                <div class="statistical token" role="alert">
                    <p class="number">{{$count_token}}</p>
                    {{__('Dashboard::general.all_token')}}
                </div>
            </div>
        </div>
    </div>
    <hr>

    <div class="row">
        @php
            $month = request('month') ?? \Carbon\Carbon::now()->month;
            $year = request('year') ?? \Carbon\Carbon::now()->year;
        @endphp
        <div class="col-md-12" style="margin-left: 15px">
            <div class="row">
                <form action="{{route('dashboard.index')}}" id="filter_date">
                    <div class="row">
                        <div class="col-md-6">
                            <select class="form-control" name="month" id="month" onchange="filterData(event)">
                                @for($i=1;$i<=12;$i++)
                                    <option value="{{$i}}" {{ $month== $i ? 'selected' : ''}}> {{__('Dashboard::general.month')}} {{$i}}</option>
                                @endfor
                            </select>
                        </div>
                        <div class="col-md-6">
                            <select class="form-control" name="year" id="year" onchange="filterData(event)">
                                @for($i=\Carbon\Carbon::now()->year; $i >= 2010;$i--)
                                    <option value="{{$i}}" {{$year == $i ? 'selected' : ''}}> {{__('Dashboard::general.year')}} {{$i}}</option>
                                @endfor
                            </select>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <div class="col-md-4">
            {{__('Dashboard::general.title_category')}}
            <table class="table table-bordered table-hover">
                <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col"> {{__('Dashboard::general.category')}}</th>
                    <th scope="col">{{__('Dashboard::general.amount_article')}}</th>
                </tr>
                </thead>
                <tbody>
                @foreach($top_categories as $key =>  $category)
                <tr>
                    <th scope="row">{{$key + 1}}</th>
                    <td>{{$category->category->name}}</td>
                    <td>{{$category->sum}}</td>
                </tr>
                @endforeach
                </tbody>
            </table>
        </div>
        <div class="col-md-4">
            {{__('Dashboard::general.title_user')}}
            <table class="table table-bordered table-hover">
                <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Member</th>
                    <th scope="col"> {{__('Dashboard::general.amount_article')}}</th>
                </tr>
                </thead>
                <tbody>
                @foreach($top_user_write as $key =>  $user)
                    <tr>
                        <th scope="row">{{$key + 1}}</th>
                        <td>{{$user->user->name}}</td>
                        <td>{{$user->sum}}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
        <div class="col-md-4">
            {{__('Dashboard::general.title_user_mark')}}
            <table class="table table-bordered table-hover">
                <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Member</th>
                    <th scope="col"> {{__('Dashboard::general.amount_mark')}}</th>
                </tr>
                </thead>
                <tbody>
                @foreach($top_user_mark as $key =>  $user)
                    <tr>
                        <th scope="row">{{$key + 1}}</th>
                        <td>{{$user->user->name}}</td>
                        <td>{{$user->sum}}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
@section('after_styles')
    <!-- DATA TABLES -->
    <link rel="stylesheet" type="text/css" href="{{ asset('packages/datatables.net-bs4/css/dataTables.bootstrap4.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('packages/datatables.net-fixedheader-bs4/css/fixedHeader.bootstrap4.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('packages/datatables.net-responsive-bs4/css/responsive.bootstrap4.min.css') }}">

    <link rel="stylesheet" href="{{ asset('packages/backpack/crud/css/crud.css').'?v='.config('backpack.base.cachebusting_string') }}">
    <link rel="stylesheet" href="{{ asset('packages/backpack/crud/css/form.css').'?v='.config('backpack.base.cachebusting_string') }}">
    <link rel="stylesheet" href="{{ asset('packages/backpack/crud/css/list.css').'?v='.config('backpack.base.cachebusting_string') }}">

    <!-- CRUD LIST CONTENT - crud_list_styles stack -->
    @stack('crud_list_styles')
@endsection

@section('after_scripts')
    @include('crud::inc.datatables_logic')
    <script src="{{ asset('packages/backpack/crud/js/crud.js').'?v='.config('backpack.base.cachebusting_string') }}"></script>
    <script src="{{ asset('packages/backpack/crud/js/form.js').'?v='.config('backpack.base.cachebusting_string') }}"></script>
    <script src="{{ asset('packages/backpack/crud/js/list.js').'?v='.config('backpack.base.cachebusting_string') }}"></script>

    <!-- CRUD LIST CONTENT - crud_list_scripts stack -->
    @stack('crud_list_scripts')
@endsection


    <style>
        .statistical{
            width: 100%;
            height: 200px;
            border-radius: 10px;
            text-align: center;
            color: white;
            font-size: 20px;
            padding-top: 40px;
        }
        .category{
            background-image: linear-gradient(to right, red , yellow);
        }
        .article{
            background-image: linear-gradient(to right, pink , palevioletred);
        }
        .member{
            background-image: linear-gradient(to right, darkseagreen , darkgreen);
        }
        .token{
            background-image: linear-gradient(to right, steelblue , deepskyblue);
        }
        .number{
            font-size: 35px;
            font-weight: bold;
        }
    </style>

