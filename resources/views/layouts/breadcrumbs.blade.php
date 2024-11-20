<div class="content-header-left col">
    <h3 class="content-header-title text-white  mb-0 d-flex align-items-center">@yield('module_title')</h3>
</div>
<div class="col-auto">
    <div class="breadcrumbs-top float-md-right">
        <div class="breadcrumb-wrapper">
            <ol class="breadcrumb">
                @foreach($breadcrumbs as $breadcrumb)
                    <li class="breadcrumb-item {{$breadcrumb['url'] ? '' : 'active'}}">
                        <a class="text-white" href="{{$breadcrumb['url'] ? $breadcrumb['url'] : '#'}}">{{$breadcrumb['title']}}</a>
                    </li>
                @endforeach
            </ol>
        </div>
    </div>
</div>