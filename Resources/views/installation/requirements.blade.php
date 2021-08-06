@extends('core::layouts.Installation')
@section('main')
    <div class="installation-box">
        <span class="title">@lang('core::installation.general_info')</span>
        <div class="col-info">
            <span>@lang('core::installation.system_ver')</span>
            <span>{{ $requirements['general']['system_ver'] }}</span>
        </div>
        <div class="col-info">
            <span>@lang('core::installation.php_ver')</span>
            <span
                class="@if($requirements['general']['passed_version'])status-success @else status-error @endif">{{ $requirements['general']['php_version'] }}</span>
        </div>
        <hr>
        <div>
            <span class="title">@lang('core::installation.require_extensions')</span>
            @foreach($requirements['extensions'] as $type => $typeRequirements)
                <div class="extension-type">
                    <h6>{{ $type }}</h6>
                    <div class="extension-type-list">
                        @foreach($typeRequirements as $extension => $status)
                            <div class="col-info">
                                <span>{{ $extension }}</span>
                                <span class="status @if($status) status-success @else status-error @endif">@if($status)
                                        &#10003; @else &#120; @endif</span>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endforeach
        </div>
        <hr>
        <div>
            <span class="title">@lang('core::installation.require_permissions')</span>
            @foreach($requirements['permissions'] as $permission => $status)
                <div class="col-info">
                    <span>{{ $permission }}</span>
                    <span class="status @if($status) status-success @else status-error @endif">@if($status)
                            &#10003; @else &#120; @endif</span>
                </div>
            @endforeach
        </div>
        <a href="{{ route('installation.settings') }}" class="btn">@lang('core::installation.next')</a>
    </div>
@endsection
