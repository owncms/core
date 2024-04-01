<?php

namespace Modules\Core\App\Http\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;

abstract class BaseController extends Controller
{
    use ValidatesRequests;
    use AuthorizesRequests;
    use DispatchesJobs;
}
