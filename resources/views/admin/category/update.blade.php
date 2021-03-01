@extends(backpack_view('blank'))
<script src="http://code.jquery.com/jquery-1.10.2.js"></script>
<script src="http://code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
<script>
    $(document).ready(function() {
        function loadCategoryByLanguageSelect(){
            var language_id = $('#language_id').val();
            $.ajax({
                type: "GET",
                url: "{{route('category.getCategoryByLanguage')}}",
                data: {language_id: language_id, id : $('#id').val()},
                dataType : "text",
                success: function( data ) {
                    $('#parent_id').html(data);
                }
            });
        }

        $('#language_id').change(function (){
            loadCategoryByLanguageSelect();
        })
    });
</script>
@php
    $defaultBreadcrumbs = [
      trans('backpack::crud.admin') => url(config('backpack.base.route_prefix'), 'dashboard'),
      $crud->entity_name_plural => url($crud->route),
      trans('backpack::crud.add') => false,
    ];

    // if breadcrumbs aren't defined in the CrudController, use the default breadcrumbs
    $breadcrumbs = $breadcrumbs ?? $defaultBreadcrumbs;
@endphp

@section('header')
    <section class="container-fluid">
        <h2>
            <span class="text-capitalize">{!! $crud->getHeading() ?? $crud->entity_name_plural !!}</span>
            <small>{!! $crud->getSubheading() ?? trans('backpack::crud.add').' '.$crud->entity_name !!}.</small>

            @if ($crud->hasAccess('list'))
                <small><a href="{{ url($crud->route) }}" class="d-print-none font-sm"><i class="la la-angle-double-{{ config('backpack.base.html_direction') == 'rtl' ? 'right' : 'left' }}"></i> {{ trans('backpack::crud.back_to_all') }} <span>{{ $crud->entity_name_plural }}</span></a></small>
            @endif
        </h2>
    </section>
@endsection

@section('content')
    <form action="{{route('category.update', ['id' => $entry->id])}}" method="POST">
        {{ csrf_field() }}
        {{ method_field('PUT') }}
        <input type="hidden" name="id" value="{{$entry->id}}" id="id">
        <div class="content">
            <div class="row">
                <label for="exampleFormControlInput1" class="form-label">{{__('category.language')}}</label><span class="required">*</span>
                <br>
                <select name="language_id" id="language_id" class="form-control">
                    @foreach($languages as $language)
                        <option value="{{$language->id}}" {{$entry->language_id == $language->id ? 'selected' : ''}}>{{$language->name}}</option>
                    @endforeach
                </select>
            </div>
            <div class="row">
                <label for="exampleFormControlInput1" class="form-label">{{__('category.category_name')}}</label><span class="required">*</span>
                <input type="text" name="name" class="form-control" id="name" value="{{$entry->name}}">
            </div>
            <div class="row">
                <label for="exampleFormControlInput1" class="form-label">{{__('category.category_parent')}}</label>
                <select name="parent_id" id="parent_id" class="form-control">
                    <option value>-</option>
                    @foreach($categories as $category)
                            <option value="{{$category->id}}" {{$entry->parent_id == $category->id ? "selected" : ''}}>{{$category->name}}</option>
                    @endforeach
                </select>
            </div>
            <input type="hidden" name="slug" class="form-control" id="slug">
            <div class="row">
                <label for="exampleFormControlInput1" class="form-label">{{__('category.order')}}</label><span class="required">*</span>
                <input type="number" name="order" class="form-control" id="order" value="{{$entry->order}}">
            </div>
        </div>
        <div class="action">
            <button class="btn btn-save" type="submit">Lưu</button>
            <button class="btn btn-back">Trở về</button>
        </div>
    </form>
@endsection
<style>
    .content {
        padding: 20px 40px;
        background: white;
    }
    .row{
        margin-bottom: 15px;
    }
    .action {
        padding: 20px 0px;
    }
    .btn-save{
        background: #42ba96 !important;
        width: 150px;
        height: 50px;
        color: white !important;
    }
    .btn-back{
        width: 100px;
        height: 50px;
        background: #d9e2ef !important;
    }
    .required{
        color: red !important;
    }
</style>
