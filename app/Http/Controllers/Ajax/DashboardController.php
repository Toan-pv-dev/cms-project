<?php

namespace App\Http\Controllers\Ajax;

use Illuminate\Routing\Controller;
use App\Models\Language;

use Illuminate\Support\Facades\DB;

use Illuminate\Http\Request;
use App\Services\UserService as UserService;

use App\Services\UserCatalogueService as UserCatalogueService;
use Illuminate\Support\Str;

class DashboardController extends Controller
{
    protected $userService;
    protected $userCatalogueService;
    protected $language;
    public function __construct(
        UserService $userService,
        UserCatalogueService $userCatalogueService
    ) {
        $this->userService = $userService;
        $this->userCatalogueService = $userCatalogueService;
        $this->middleware(function (Request $request, $next) {
            $locale = app()->getLocale();
            $language = Language::where('canonical', $locale)->first();
            $this->language = $language->id;
            return $next($request);
        });
    }
    public function changeStatus(Request $request)
    {

        $post = $request->input();
        $serviceName = ucfirst($post['model']) . 'Service';
        $serviceInterfaceNamespace = '\App\Services\\' . $serviceName;
        if (class_exists($serviceInterfaceNamespace)) {
            $serviceInstance = app($serviceInterfaceNamespace);
        }

        $flag = $serviceInstance->updateStatus($post);
        // dd($flag);
        return response()->json(['flag' => $flag]);
    }
    // cái trên nầy nè




    public function changeStatusAll(Request $request)
    {
        $post = $request->input();
        // dd($post['model']);
        $serviceName = $post['model'] === 'user_catalogues' ? 'UserCatalogueService' : ucfirst($post['model']) . 'Service';
        $serviceInterfaceNamespace = '\App\Services\\' . $serviceName;
        if (class_exists($serviceInterfaceNamespace)) {
            $serviceInstance = app($serviceInterfaceNamespace);
        }
        $flag = $serviceInstance->updateStatusAll($post);
        return response()->json(['flag' => $flag]);
    }

    public function getMenu(Request $request)
    {
        $model = $request->input('model');
        $keyword = $request->string('keyword');
        $serviceName  = ucfirst($model) . 'Repository';
        $serviceInterfaceNamespace = '\App\Repositories\\' . $serviceName;
        if (class_exists($serviceInterfaceNamespace)) {
            $serviceInstance = app($serviceInterfaceNamespace);
        }
        $args = $this->paginationArgument($model, $keyword);
        $object = $serviceInstance->pagination(
            $args['column'],
            $args['condition'],
            $args['perPage'],
            $args['extend'],
            $args['orderBy'],
            $args['join'],
            $args['relations'],
            $args['rawQuery']
        );
        return response()->json($object);
    }

    private function paginationArgument(string $model = '', string $keyword = '')
    {
        $model = Str::snake($model); // 'PostCatalogue' => 'post_catalogue'
        $table = $model . 's';       // => post_catalogues

        $join = [
            [$model . '_language as tb2', 'tb2.' . $model . '_id', '=', $table . '.id']
        ];

        if (strpos($model, '_catalogue') === false) {
            $join[] = [$model . '_catalogue_' . $model . ' as tb3', $table . '.id', '=', 'tb3.' . $model . '_id'];
        }
        $condition = [
            'where' => [
                ['tb2.language_id', '=', $this->language],
            ],
            'keyword' => $keyword,
        ];
        return [
            'column' => ["$table.id", "tb2.name", "tb2.canonical"],
            'condition' => $condition,
            'perPage' => 10,
            'extend' => [
                'path' => $table . '/index',
                'groupBy' => ["$table.id", "tb2.name", "tb2.canonical"]
            ],
            'orderBy' => ["$table.id" => 'DESC'],
            'join' => $join,
            'relations' => [], // Sửa từ 'relation' thành 'relations'
            'rawQuery' => [],
        ];
    }

    public function getWidgetSearchResults(Request $request)
    {

        DB::enableQueryLog();
        $payload = $request->input();
        $keyword = $payload['keyword'];
        $model = $payload['model'];
        $relationTable = Str::snake($model) . '_language';
        $languageId = $this->language;
        $repo = $this->loadRepositoryInstance($model);
        $objects = $repo->findByLanguageAndKeyword($languageId, $keyword, $relationTable);
        $data = $objects->map(function ($item) {
            $language = $item->languages->first(); // chỉ lấy 1 bản dịch theo ngôn ngữ hiện tại

            return [
                'id' => $item->id,
                'image' => $item->image ?? null,
                'post_catalogue_id' => $item->post_catalogue_id ?? null,
                'pivot_name' => $language->pivot->name ?? null,
                'pivot_canonical' => $language->pivot->canonical ?? null,
                'pivot_meta_title' => $language->pivot->meta_title ?? null,
                'pivot_meta_description' => $language->pivot->meta_description ?? null,
                'pivot_description' => $language->pivot->description ?? null,
            ];
        });

        return response()->json($data);
    }

    private function loadRepositoryInstance($model)
    {
        $serviceName  = ucfirst($model) . 'Repository';
        $serviceInterfaceNamespace = '\App\Repositories\\' . $serviceName;
        if (class_exists($serviceInterfaceNamespace)) {
            $serviceInstance = app($serviceInterfaceNamespace);
        }
        return $serviceInstance;
    }
}
