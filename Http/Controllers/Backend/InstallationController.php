<?php

namespace Modules\Core\Http\Controllers\Backend;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Core\src\Installation\Installation;
use Modules\Core\src\Software\Software;

class InstallationController extends Controller
{
    private object $software;
    private object $installation;
    public string $title;

    public function __construct(Software $software, Installation $installation)
    {
        $this->software = $software;
        $this->installation = $installation;
    }

    public function getLanguages(): object
    {
        $this->title = trans('core::installation.steps.title.languages');
        return view('core::installation.languages');
    }

    public function postLanguages(Request $request)
    {
        if (!$request->has('lang') || $request->get('lang') == '') {
            return redirect()->back();
        }
        if ($request->session()->has('installation_lang')) {
            $request->session()->forget('installation_lang');
        }
        $request->session()->put('installation_lang', $request->get('lang'));
        return redirect(route('installation.start'));
    }

    public function start(): object
    {
        $this->title = trans('core::installation.steps.title.start');
        return view('core::installation.start');
    }

    public function getRequirements(Request $request): object
    {
        $this->title = trans('core::installation.steps.title.requirements');
        $requirements['general'] = $this->software->all();
        $requirements['general']['system_ver'] = \Application::getVersion();
        $requirements['extensions'] = $this->installation->checkRequirements();
        $requirements['permissions'] = $this->installation->checkPermissions();
        return view('core::installation.requirements', compact('requirements'));
    }

    public function getSettings(): object
    {
        $this->title = trans('core::installation.steps.title.settings');
        return view('core::installation.settings');
    }

    public function postSettings(Request $request)
    {
        dd($request->all());
    }
}
