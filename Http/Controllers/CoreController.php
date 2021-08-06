<?php

namespace Modules\Core\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Redirect;
use Modules\Core\src\FormBuilder\Traits\FormBuilderTrait;
use Illuminate\Http\Request;
use Bouncer;
use Modules\Admin\src\Presenter\Presenter;
use Modules\Admin\Traits\FormController;

abstract class CoreController extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests, FormBuilderTrait, FormController;

    protected string $model;
    protected string $defaultRedirect = 'index';
    public string $modulePrefix;
    public string $routeWithModulePrefix;
    public array $searchableColumns = [];

    /**
     * Rewrite view method to a module scope
     * @param $view
     * @param array $data
     * @param array $mergeData
     * @return \Illuminate\Contracts\View\View
     */
    public function view($view, $data = [], $mergeData = []): object
    {
        return view($this->modulePrefix . '::' . $view, $data, $mergeData);
    }

    /**
     * Redirect to a specific route
     * @param $route
     * @param array $parameters
     * @param int $status
     * @param array $headers
     * @return RedirectResponse
     */
    public function redirect($route, array $parameters = [], int $status = 302, array $headers = []): RedirectResponse
    {
        return Redirect::route($route, $parameters, $status, $headers);
    }

    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index(): object
    {
        if (!Bouncer::can('index', $this->model)) {
            return abort(403);
        }
        $entity = new $this->model;
        $data = (new Presenter($entity, $this->searchableColumns))->withFilters()->getData();
        return $this->view($this->baseView . '.index', compact(['entity', 'data']));
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create(): object
    {
        if (!Bouncer::can('create', $this->model)) {
            return abort(403);
        }
        $form = $this->form($this->form, [
            'method' => 'POST',
            'route' => [$this->routeWithModulePrefix . '.' . 'store']
        ]);

        $item = new $this->model;
        return $this->view($this->baseView . '.create', ['form' => $form, 'item' => $item]);
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        if (!Bouncer::can('create', $this->model)) {
            return abort(403);
        }
        if (isset($this->request)) {
            $this->validateForm($request, new $this->request);
        }

        $this->model::create($request->all());
        return $this->redirect($this->routeWithModulePrefix . '.' . $this->defaultRedirect);
    }

    /**
     * Show the specified resource.
     * @return Response
     */
    public function show($id): object
    {
        if (!Bouncer::can('show', $this->model)) {
            return abort(403);
        }
        $item = (new $this->model)->withTrashed()->findOrFail($id);

        if (method_exists($item, 'attributesToUnset')) {
            $item->attributesToUnset();
        }
        $form = $this->form($this->form, [
            'method' => 'POST',
            'model' => $item
        ]);
        $form->disableFields();

        $entity = new $this->model;
        return $this->view($this->baseView . '.show', compact(['form', 'item', 'entity']));
    }

    /**
     * Show the form for editing the specified resource.
     * @return Response
     */
    public function edit($id): object
    {
        if (!Bouncer::can('edit', $this->model)) {
            return abort(403);
        }
        $item = $this->model::withTrashed()->findOrFail($id);

        if (method_exists($item, 'attributesToUnset')) {
            $item->attributesToUnset();
        }
        $form = $this->form($this->form, [
            'method' => 'PUT',
            'route' => [$this->routeWithModulePrefix . '.update', $item->id],
            'model' => $item
        ]);

        $entity = new $this->model;
        return $this->view($this->baseView . '.edit', compact(['item', 'form', 'entity']));
    }

    public function update(Request $request, $id)
    {
        if (!Bouncer::can('edit', $this->model)) {
            return abort(403);
        }
        if (isset($this->request)) {
            $this->validateForm($request, new $this->request, $id);
        }
        $item = $this->model::findOrFail($id);
        $item->fill($request->all())->save();

        return $this->redirect($this->routeWithModulePrefix . '.' . $this->defaultRedirect);
    }
}
