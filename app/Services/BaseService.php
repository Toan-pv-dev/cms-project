<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use App\Services\Interfaces\BaseServiceInterface;
use App\Repositories\Interfaces\BaseRepositoryInterface as BaseRepository;
use App\Repositories\Interfaces\UserRepositoryInterface as UserRepository;
use Illuminate\Support\Facades\Auth;
use App\Repositories\RouterRepository as RouterRepository;

// use App\Classes\Nestedsetbie;

use Illuminate\Support\Facades\Log;

/**
 * Class UserService
 * @package App\Services
 */
class BaseService  implements BaseServiceInterface
{
    protected $nestedSet;
    protected $routerRepository;
    public function __construct(RouterRepository $routerRepository)
    {
        $this->routerRepository = $routerRepository;
    }
    public function currentLanguage()
    {
        return 1;
    }

    public function formatAlbum($album = null)
    {
        // dd($payload);
        return !empty($album) ? json_encode($album) : '';
    }

    public function nestedSet()
    {
        $this->nestedSet->Get();
        $this->nestedSet->Recursive(0, $this->nestedSet->Set());
        $this->nestedSet->Action();
    }

    public function formatRouterPayload($model, $request, $controllerName)
    {
        $routerPayload = [
            'canonical' => is_array($request) ? ($request['canonical'] ?? null) : $request->input('canonical'),
            'module_id' => $model->id,
            'controllers' => 'App\Http\Controller\Frontend\\' . $controllerName,
        ];
        // dd($routerPayload);
        return $routerPayload;
    }
    public function createRouter($model, $request, $controllerName)
    {
        $router = $this->formatRouterPayload($model, $request, $controllerName);
        $this->routerRepository->create($router);
    }

    public function updateRouter($model, $request, $controllerName)
    {
        $payload = $this->formatRouterPayload($model, $request, $controllerName);
        // dd($payload);
        $condition  = [
            ['module_id', '=', $model->id],
            ['controllers', '=', 'App\Http\Controller\Frontend\\' . $controllerName],
        ];
        $router = $this->routerRepository->findByCondition($condition);
        // die();

        $res = $this->routerRepository->update($router->id, $payload);
        return $res;
    }
}
