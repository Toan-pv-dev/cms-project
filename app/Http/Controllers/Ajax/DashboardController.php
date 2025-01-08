<?php

namespace App\Http\Controllers\Ajax;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Services\UserService as UserService;
use App\Services\UserCatalogueService as UserCatalogueService;

class DashboardController extends Controller
{
    protected $userService;
    protected $userCatalogueService;
    public function __construct(
        UserService $userService,
        UserCatalogueService $userCatalogueService
    ) {
        $this->userService = $userService;
        $this->userCatalogueService = $userCatalogueService;
    }
    public function changeStatus(Request $request)
    {
        // dd($request);

        $post = $request->input();
        // dd($post['model']);
        $serviceName = ucfirst($post['model']) . 'Service';
        // dd($serviceName);
        $serviceInterfaceNamespace = '\App\Services\\' . $serviceName;
        // dd($serviceInterfaceNamespace);
        // dd($serviceInterfaceNamespace);
        // dd($serviceInterfaceNamespace);
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
}