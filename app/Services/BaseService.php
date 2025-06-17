<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use App\Services\Interfaces\BaseServiceInterface;
use App\Services\Interfaces\LanguageServiceInterface as LanguageService;
use App\Repositories\Interfaces\BaseRepositoryInterface as BaseRepository;
use App\Repositories\LanguageRepository as LanguageRepository;
use App\Models\Language;
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
    protected $languageRepository;
    public function __construct(RouterRepository $routerRepository, LanguageRepository $languageRepository)
    {
        $this->languageRepository = $languageRepository;
        $this->routerRepository = $routerRepository;
    }
    public function currentLanguage()
    {
        $locale = app()->getLocale();
        $language = Language::where('canonical', $locale)->first();
        return $language->id ?? 1;
    }

    public function formatAlbum($album = null)
    {
        // dd($payload);
        // dd($album);
        return !empty($album) ? json_encode($album) : '';
    }

    public function nestedSet()
    {
        $this->nestedSet->Get();
        $this->nestedSet->Recursive(0, $this->nestedSet->Set());
        $this->nestedSet->Action();
    }

    public function formatRouterPayload($model, $request, $controllerName, $languageId)
    {
        $routerPayload = [
            'canonical' => is_array($request) ? ($request['canonical'] ?? null) : $request->input('canonical'),
            'module_id' => $model->id,
            'language_id' => $languageId,
            'controllers' => 'App\Http\Controller\Frontend\\' . $controllerName,
        ];
        return $routerPayload;
    }
    public function createRouter($model, $request, $controllerName, $languageId)
    {
        // dd($model, $request, $controllerName, $languageId);
        $router = $this->formatRouterPayload($model, $request, $controllerName, $languageId);
        $this->routerRepository->create($router);
    }

    public function updateRouter($model, $request, $controllerName, $languageId)
    {
        $payload = $this->formatRouterPayload($model, $request, $controllerName, $languageId);

        // dd($payload);
        $condition  = [
            ['module_id', '=', $model->id],
            ['controllers', '=', 'App\Http\Controller\Frontend\\' . $controllerName],

        ];
        $router = $this->routerRepository->findByCondition($condition);
        $res = $this->routerRepository->update($router->id, $payload);
        return $res;
    }
}
