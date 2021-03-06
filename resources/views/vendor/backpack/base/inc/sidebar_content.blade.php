<!-- This file is used to store sidebar items, starting with Backpack\Base 0.9.0 -->
<li class="nav-item"><a class="nav-link" href="{{ backpack_url('dashboard') }}"><i class="la la-home nav-icon"></i> {{ trans('backpack::base.dashboard') }}</a></li>
<!-- Users, Roles, Permissions -->
<li class="nav-item nav-dropdown">
    <a class="nav-link nav-dropdown-toggle" href="#"><i class="nav-icon la la-users"></i> {{ __('menu.authentication') }}</a>
    <ul class="nav-dropdown-items">
        <li class="nav-item"><a class="nav-link" href="{{ backpack_url('user') }}"><i class="nav-icon la la-user"></i> <span> {{ __('menu.admin') }}</span></a></li>
        <li class="nav-item"><a class="nav-link" href="{{ backpack_url('role') }}"><i class="nav-icon la la-id-badge"></i> <span> {{ __('menu.role') }}</span></a></li>
        <li class="nav-item"><a class="nav-link" href="{{ backpack_url('permission') }}"><i class="nav-icon la la-key"></i> <span> {{ __('menu.permission') }}</span></a></li>
    </ul>
</li>
<li class='nav-item'><a class='nav-link' href='{{ backpack_url('page') }}'><i class='nav-icon la la-file-o'></i> <span>{{__('menu.static_page')}}</span></a></li>
<li class='nav-item'><a class='nav-link' href='{{ backpack_url('author') }}'><i class='nav-icon la la-address-card'></i> {{ __('menu.author') }}</a></li>
<li class='nav-item'><a class='nav-link' href='{{ backpack_url('historytransactiontoken') }}'><i class='nav-icon la la-traffic-light'></i> {{ __('menu.history') }}</a></li>
<li class='nav-item'><a class='nav-link' href='{{ backpack_url('category') }}'><i class='nav-icon la la-list'></i> {{ __('menu.category') }}</a></li>
<li class='nav-item'><a class='nav-link' href='{{ backpack_url('article') }}'><i class='nav-icon la la-newspaper-o'></i>{{__('menu.article')}}</a></li>
<li class='nav-item'><a class='nav-link' href='{{ backpack_url('tag') }}'><i class='nav-icon la la-tag'></i> Tags</a></li>

<li class='nav-item'><a class='nav-link' href='{{ backpack_url('menu') }}'><i class='nav-icon la la-question'></i> Menus</a></li>

<li class='nav-item'><a class='nav-link' href='{{ backpack_url('articlevideo') }}'><i class='nav-icon la la-question'></i> {{__('article.video')}} </a></li>
