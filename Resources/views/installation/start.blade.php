@extends('core::layouts.Installation')
@section('main')
    <div class="installation-box">
        @lang('core::installation.start')
        <a href="{{ route('installation.requirements') }}" class="btn">@lang('core::installation.next')</a>
    </div>
@endsection
