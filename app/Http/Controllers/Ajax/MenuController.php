<?php

namespace App\Http\Controllers\Ajax;

use Illuminate\Routing\Controller;

use App\Services\Interfaces\MenuCatalogueServiceInterface as menuCatalogueService;
use App\Services\Interfaces\MenuServiceInterface as menuService;
use App\Repositories\Interfaces\MenuCatalogueRepositoryInterface as menuCatalogueRepository;

use App\Repositories\MenuRepository;
use App\Models\Language;
use App\Http\Requests\StoreMenuCatalogueRequest;

use Illuminate\Http\Request;

class MenuController extends Controller
{
    protected $menuRepository;
    protected $language;
    protected $menuCatalogueService;
    protected $menuCatalogueRepository;
    protected $menuService;

    public function __construct(MenuRepository $menuRepository, menuCatalogueService $menuCatalogueService, menuCatalogueRepository $menuCatalogueRepository, menuService $menuService)
    {
        $this->menuRepository = $menuRepository;
        $this->menuCatalogueService = $menuCatalogueService;
        $this->menuCatalogueRepository = $menuCatalogueRepository;
        $this->menuService = $menuService;

        $this->middleware(function (Request $request, $next) {
            $locale = app()->getLocale();
            $language = Language::where('canonical', $locale)->first();
            $this->language = $language->id;
            return $next($request);
        });
    }


    public function createCatalogue(StoreMenuCatalogueRequest $request)
    {
        $menuCatalogue = $this->menuCatalogueService->create($request);
        if ($menuCatalogue) {
            return response()->json([
                'code' => 0,
                'message' => 'Tạo nhóm menu thành công!',
                'data' => $menuCatalogue
            ]);
        }

        return response()->json([
            'code' => 0,
            'message' => 'Có sự cố xảy ra hãy thử lại',
            'data' => null
        ]);
    }
    public function drag(Request $request)
    {
        $payload = $request->only('json', 'catalogueId');
        $json = $payload['json'] ?? '';
        $json = json_decode($json, true);
        $catalogueId = $payload['catalogueId'] ?? null;
        $this->menuService->dragUpdate($json, $catalogueId);
    }

    // Uncomment and use if needed
}
