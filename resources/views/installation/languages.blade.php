@extends('core::layouts.installation')
@section('main')
    <div class="installation-box">
        <form action="{{ route('installation.post-languages') }}" method="POST">
            @csrf
            <select multiple="multiple" name="lang" class="select-lang" required>
                <option value="en" selected>English</option>
                <option value="pl">Polski</option>
            </select>
            <button type="submit" class="btn">@lang('core::installation.next')</button>
        </form>
    </div>
@endsection
