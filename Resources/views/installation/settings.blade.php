@extends('core::layouts.installation')
@section('main')
    <div class="installation-box">
        <nav class="nav nav-pills nav-justified" id="settingsTab" role="tablist">
            <a class="nav-item nav-link active" data-toggle="tab" href="#general" role="tab" aria-controls="general">General</a>
            <a class="nav-item nav-link" data-toggle="tab" href="#database" role="tab"
               aria-controls="database">Database</a>
{{--            <a class="nav-item nav-link" data-toggle="tab" href="#post" role="tab" aria-controls="post">Post</a>--}}
        </nav>
        <form action="{{ route('installation.post-settings') }}" method="POST">
            @csrf
            <div class="tab-content" id="settingsTabContent">
                <div class="tab-pane fade show active" id="general" role="tabpanel" aria-labelledby="general-tab">
                    <span class="title">Website</span>
                    <div class="form-group">
                        <label for="siteTitle">Site title</label>
                        <input type="text" class="form-control" placeholder="Enter site title" id="siteTitle"
                               name="site_title">
                    </div>
                    <div class="form-check">
                        <input type="checkbox" class="form-check-input" id="defaultDomain" name="default_domain">
                        <label class="form-check-label" for="defaultDomain">Create default domain</label>
                    </div>
                    <hr>
                    <span class="title">Admin account</span>
                    <div class="form-group">
                        <label for="emailAddress">Email address</label>
                        <input type="email" class="form-control" id="emailAddress" placeholder="Enter e-mail"
                               name="admin[email]">
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="password">Password</label>
                                <input type="password" class="form-control" id="password" placeholder="Enter password"
                                       name="admin[password]">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="repeatPassword">Repeat password</label>
                                <input type="password" class="form-control" id="repeatPassword"
                                       placeholder="Repeat password" name="admin[repeat_password]">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="tab-pane fade" id="database" role="tabpanel" aria-labelledby="database-tab">
                    <span class="title">Database</span>
                    <div class="form-group">
                        <label for="databaseName">Name</label>
                        <input type="text" class="form-control" placeholder="Enter database name" id="databaseName"
                               name="database[name]">
                    </div>
                    <div class="form-group">
                        <label for="databasePort">Port</label>
                        <input type="text" class="form-control" placeholder="Enter database port" id="databasePort"
                               name="database[port]">
                    </div>
                    <div class="form-group">
                        <label for="databaseUsername">Username</label>
                        <input type="text" class="form-control" placeholder="Enter database username" id="databaseUsername"
                               name="database[username]">
                    </div>
                    <div class="form-group">
                        <label for="databasePassword">Password</label>
                        <input type="text" class="form-control" placeholder="Enter username password" id="databasePassword"
                               name="database[password]">
                    </div>
                </div>
{{--                <div class="tab-pane fade" id="post" role="tabpanel" aria-labelledby="post-tab">--}}

{{--                </div>--}}
            </div>
            <button type="submit" class="btn">@lang('core::installation.next')</button>
        </form>
    </div>

    <script src="{{ asset('js/jquery.min.js') }}"></script>
    <script src="{{ asset('js/bootstrap.min.js') }}"></script>
@endsection
