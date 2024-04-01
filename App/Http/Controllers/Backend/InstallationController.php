<?php

namespace Modules\Core\App\Http\Controllers\Backend;

use Illuminate\Http\Request;
use Modules\Core\App\src\Installation\Installation;
use Modules\Core\App\src\Software\Software;
use Modules\Core\App\Http\Controllers\BaseController;

class InstallationController extends BaseController
{
    /**
     * @var Software
     */
    private Software $software;
    /**
     * @var Installation
     */
    private Installation $installation;
    /**
     * @var string
     */
    public string $title;

    /**
     * @param Software $software
     * @param Installation $installation
     */
    public function __construct(Software $software, Installation $installation)
    {
        $this->software = $software;
        $this->installation = $installation;
    }

    /**
     * @return object
     */
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

    /**
     * @return Factory|View
     */
    public function start()
    {
        $this->title = trans('core::installation.steps.title.start');
        return view('core::installation.start');
    }

    /**
     * @param Request $request
     * @return Factory|View
     */
    public function getRequirements(Request $request)
    {
        $this->title = trans('core::installation.steps.title.requirements');
        $requirements['general'] = $this->software->all();
        $requirements['general']['system_ver'] = \Application::getVersion();
        $requirements['extensions'] = $this->installation->checkRequirements();
        $requirements['permissions'] = $this->installation->checkPermissions();
        return view('core::installation.requirements', compact('requirements'));
    }

    /**
     * @return Factory|View
     */
    public function getSettings()
    {
        $this->title = trans('core::installation.steps.title.settings');
        return view('core::installation.settings');
    }

    public function postSettings(Request $request)
    {
        dd($request->all());
    }
}
