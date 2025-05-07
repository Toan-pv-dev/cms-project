<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use App\Services\Interfaces\GenerateServiceInterface;
use App\Repositories\Interfaces\GenerateRepositoryInterface as GenerateRepository;
use App\Repositories\Interfaces\UserRepositoryInterface as UserRepository;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Artisan;

/**
 * Class UserService
 * @package App\Services
 */
class GenerateService  implements GenerateServiceInterface
{
    protected $generateRepository;
    protected $userRepository;
    public function __construct(GenerateRepository $generateRepository, UserRepository $userRepository)
    {
        $this->generateRepository = $generateRepository;
        $this->userRepository = $userRepository;
    }
    public function select()
    {
        return ['id', 'name', 'canonical', 'publish', 'image', 'current'];
    }
    public function paginate($request)
    {


        $select = $this->select();
        $condition['publish'] = $request->input('publish');
        $perpage = $request->integer('perpage');
        $languages = $this->generateRepository->pagination($select, $condition, $perpage, ['path' => 'generate/index'], [], [], []);
        return $languages;
    }

    public function create($request)
    {

        $this->makeDatabase($request);
        $this->makeController($request); //done
        $this->makeModel($request); //done
        $this->makeRepository($request);
        $this->makeService($request);
        $this->registerBinding('Services', $request->input('name'));
        $this->registerBinding('Repositories', $request->input('name'));
        $this->makeRequest($request);
        $this->makeView($request);
        if ($request->input('module_type') == 'catalogue') {
            $this->makeRule($request);
        }
        $this->makeRouter($request);
    }

    private function makeModel($request)
    {
        $moduleType = $request->input('module_type');
        switch ($moduleType) {
            case 'catalogue':
                $this->createCatalogueModel($request);
                break;
            case 'detail':
                $this->createModel($request);
                break;
            default:
                break;
        }
    }

    private function createCatalogueModel($request)
    {

        $templateModelPath = app_path('Template/models/PostCatalogue.php');
        $modelContent = file_get_contents($templateModelPath);
        $moduleName = $request->input('name');
        $module = $this->convertModuleNameToTable($moduleName);
        $extractModule = explode('_', $module);
        $name = $extractModule[0];

        $modelContent = file_get_contents($templateModelPath);
        $replace = [
            'First_Model' => ucfirst($name),
            'first_model' => lcfirst($name),
        ];
        $modelContent = str_replace(array_keys($replace), array_values($replace), $modelContent);
        $modelPathPut = app_path('Models/' . $moduleName . '.php');
        File::put($modelPathPut, $modelContent);
    }

    private function createModel($request)
    {
        $templateModelPath = app_path('Template/models/Post.php');
        $modelContent = file_get_contents($templateModelPath);
        $moduleName = $request->input('name');


        $modelContent = file_get_contents($templateModelPath);
        $replace = [
            'ModelName' => ucfirst($moduleName),
            'modelName' => lcfirst($moduleName),
        ];
        $modelContent = str_replace(array_keys($replace), array_values($replace), $modelContent);
        $modelPathPut = app_path('Models/' . $moduleName . '.php');
        File::put($modelPathPut, $modelContent);
    }

    private function makeRouter($request)
    {
        $name = $request->input('name');
        $module = $this->convertModuleNameToTable($name);
        $extractModule = explode('_', $module);
        $baseFolder = base_path('routes/web.php');
        $ruleContent = file_get_contents($baseFolder);
        $routePrefix = (count($extractModule) === 2) ? $extractModule[0] . '/' . $extractModule[1] : $extractModule[0];
        $routeName = (count($extractModule) === 2) ? $extractModule[0] . '.' . $extractModule[1] : $extractModule[0];
        $routeGroup = <<<ROUTE

    // {$name} routes
    Route::group(['prefix' => '{$routePrefix}'], function () {
        Route::post('store', [{$name}Controller::class, 'store'])->name('{$routeName}.store');
        Route::get('index', [{$name}Controller::class, 'index'])->name('{$routeName}.index');
        Route::get('create', [{$name}Controller::class, 'create'])->name('{$routeName}.create');
        Route::get('edit/{id}', [{$name}Controller::class, 'edit'])->name('{$routeName}.edit');
        Route::post('update/{id}', [{$name}Controller::class, 'update'])->name('{$routeName}.update');
        Route::get('delete/{id}', [{$name}Controller::class, 'delete'])->name('{$routeName}.delete');
        Route::post('destroy/{id}', [{$name}Controller::class, 'destroy'])->name('{$routeName}.destroy');
    });
    //@@new-module@@

ROUTE;
        $routeContent = str_replace('//@@new-module@@', $routeGroup, $ruleContent);
        $pathUse = "use App\\Http\\Controllers\\Backend\\{$name}Controller;";
        $routeContent = str_replace('//@@use-module@@', $pathUse . "\n" . '//@@use-module@@', $routeContent);


        File::put($baseFolder, $routeContent);
    }



    private function makeRule($request)
    {
        $name = $request->input('name');
        $ruleName = $name . 'Rule';
        $rulePath = app_path('Rules/Children' . $ruleName . '.php');
        if (!File::exists($rulePath)) {
            $ruleTemplate = app_path('Template/RuleTemplate.php');
            $ruleContent = file_get_contents($ruleTemplate);
            $replace = [
                'ModuleName' => $name,
            ];
            $ruleContent = str_replace(array_keys($replace), array_values($replace), $ruleContent);
            File::put($rulePath, $ruleContent);
        }
    }

    private function makeView($request)
    {
        $name = $request->input('name');
        $module = $this->convertModuleNameToTable($name);
        $extractModule = explode('_', $module);

        $baseFolder = resource_path('views/backend/' . $extractModule[0]);
        $viewFolder = count($extractModule) === 2
            ? $baseFolder . '/' . $extractModule[1]
            : $baseFolder . '/' . $extractModule[0];


        $componentFolder = $viewFolder . '/components';
        $this->createDirectoryIfNotExists($viewFolder);
        $this->createDirectoryIfNotExists($componentFolder);

        $sourceType = count($extractModule) === 2 ? 'catalogue' : 'post';
        $sourceBase = app_path("Template/views/{$sourceType}");

        $viewPath = implode('.', $extractModule);
        $replacements = [
            'ModuleName' => $name,
            'moduleName' => lcfirst($name),
            'viewPath'   => $viewPath,
        ];

        $this->copyAndReplaceFiles(
            $sourceBase,
            $viewFolder,
            ['store.blade.php', 'index.blade.php', 'delete.blade.php'],
            $replacements
        );

        $this->copyAndReplaceFiles(
            $sourceBase . '/components',
            $componentFolder,
            ['aside.blade.php', 'filter.blade.php', 'table.blade.php', 'seo.blade.php', 'general.blade.php'],
            $replacements
        );
    }

    private function createDirectoryIfNotExists($path)
    {
        if (!File::exists($path)) {
            File::makeDirectory($path, 0755, true);
        }
    }

    private function copyAndReplaceFiles($sourceDir, $destinationDir, $files, $replacements)
    {
        foreach ($files as $file) {
            $sourceFile = $sourceDir . '/' . $file;
            $destinationFile = $destinationDir . '/' . $file;

            if (!File::exists($destinationFile) && File::exists($sourceFile)) {
                $content = file_get_contents($sourceFile);
                $newContent = str_replace(array_keys($replacements), array_values($replacements), $content);
                File::put($destinationFile, $newContent);
            }
        }
    }


    private function makeRequest($request)
    {
        $name = $request->input('name');
        $requestArray = [
            'Store' . $name . 'Request',
            'Update' . $name . 'Request',
            'Delete' . $name . 'Request',
        ];
        $requestTemplate = [
            'RequestTemplateStore',
            'RequestTemplateUpdate',
            'RequestTemplateDelete',
        ];

        if ($request->input('module_type') != 'catalogue') {
            unset($requestArray[2]);
            unset($requestTemplate[2]);
        }

        foreach ($requestTemplate as $key => $val) {
            $requestPath = app_path('Template/' . $val . '.php');
            $requestContent = file_get_contents($requestPath);
            $replace = [
                '{ModuleName}' => $name,
            ];
            $requestContent = str_replace(array_keys($replace), array_values($replace), $requestContent);

            $requestPathPut = app_path('Http/Requests/' . $requestArray[$key] . '.php');
            File::put($requestPathPut, $requestContent);
        }
    }
    private function makeDatabase($request)
    {
        $payload = $request->only('schema', 'name', 'module_type');
        $module = $this->convertModuleNameToTable($payload['name']);
        $moduleExtract = explode('_', $module);
        $this->makeMainTable($payload, $module);
        if ($payload['module_type'] != 'difference') {
            $this->makeLanguageTable($request, $module);

            if (count($moduleExtract) == 1) {
                $this->makeRelationTable($module);
            }
        }
        Artisan::call('migrate');
        // die();
    }

    private function relationSchema($tableName = '', $module = '')
    {
        $schema = <<<SCHEMA
Schema::create('{$tableName}', function (Blueprint \$table) {
    \$table->unsignedBigInteger('{$module}_catalogue_id');
    \$table->unsignedBigInteger('{$module}_id');

    \$table->foreign('{$module}_catalogue_id')->references('id')->on('{$module}_catalogues')->onDelete('cascade');
    \$table->foreign('{$module}_id')->references('id')->on('{$module}s')->onDelete('cascade');
});
SCHEMA;

        return $schema;
    }

    private function explodeModuleName($moduleName)
    {
        $name = preg_split('/(?=[A-Z])/', $moduleName, -1, PREG_SPLIT_NO_EMPTY);
        return $name[0];
    }

    private function makeLanguageTable($request, $module)
    {
        // $foreignKey = $module . '_id';
        $pivotTableName = $module . '_language';
        // dd($this->pivotSchema($module));
        $pivotSchema = $this->pivotSchema($module);
        $dropPivotTable = $module . '_language';

        $migrationPivot = $this->createMigrationFile($pivotSchema, $dropPivotTable);

        $migrationPivotFileName = date('Y_m_d_His', time() + 10) . '_create_' . $pivotTableName . '_table.php';
        $migrationPivotPath = database_path('migrations/' . $migrationPivotFileName);

        FILE::put($migrationPivotPath, $migrationPivot);
    }

    private function makeMainTable($payload, $module)
    {
        // $moduleExtract = explode('_', $module); // Ví dụ: 'product'
        $tableName = $module . 's';
        // dd($tableName);
        // dd($payload);

        $migrationFileName = date('Y_m_d_His') . '_create_' . $tableName . '_table.php';
        $migrationPath = database_path('migrations/' . $migrationFileName);

        $migrationTemplate = $this->createMigrationFile($payload['schema'], $tableName);

        // Ghi file migration (nếu cần thì bỏ comment dòng sau)
        File::put($migrationPath, $migrationTemplate);
    }


    private function pivotSchema($module)
    {
        $pivotSchema = <<< SCHEMA
    Schema::create('{$module}_language', function (Blueprint \$table) {
        \$table->unsignedBigInteger('{$module}_id');
        \$table->unsignedBigInteger('language_id');

        \$table->foreign('{$module}_id')->references('id')->on('{$module}s')->onDelete('cascade');
        \$table->foreign('language_id')->references('id')->on('languages')->onDelete('cascade');

        \$table->string('name');
        \$table->text('description')->nullable();
        \$table->longText('content')->nullable();
        \$table->string('meta_title')->nullable();
        \$table->string('meta_keyword')->nullable();
        \$table->text('meta_description')->nullable();
        \$table->string('canonical')->nullable();
        \$table->timestamps();
        });
    SCHEMA;

        return $pivotSchema;
    }

    private function makeRelationTable($module)
    {
        $moduleExtract = explode('_', $module);
        $tableName = $module . '_catalogue_' . $moduleExtract[0];

        $schema = $this->relationSchema($tableName, $module);
        $migrationRelationFile = $this->createMigrationFile($schema, $tableName);

        // dd($migrationRelationFile); // Hiển thị nội dung migration file

        $migrationRelationFileName = date('Y_m_d_His', time() * 10) . '_create_' . $tableName . '_table.php';
        $migrationRelationPath = database_path('migrations/' . $migrationRelationFileName);

        // Ghi file migration vào thư mục
        File::put($migrationRelationPath, $migrationRelationFile);
    }






    private function createMigrationFile($schema, $tableName)
    {


        $migrationTemplate = <<< Migration
<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
       $schema
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('{$tableName}');
    }
};

Migration;
        return $migrationTemplate;
    }

    private function makeController($request)
    {
        $payload = $request->only('name', 'module_type');
        $moduleName = $payload['name'];
        $name =  preg_split('/(?=[A-Z])/', $moduleName, -1, PREG_SPLIT_NO_EMPTY);
        $name =  $name[0];

        switch ($payload['module_type']) {
            case 'catalogue':
                $this->createCatalogueController($name);
                break;
            case 'detail':
                $this->createController($name);
            default:
                break;
        }
    }

    private function createCatalogueController($name)
    {


        $controllerName = $name . 'CatalogueController';

        $templateControllerPath = app_path('Template/controllers/PostCatalogueController.php');
        $controllerContent = file_get_contents($templateControllerPath);
        $replace = [
            'FirstModel' => $name,
            'firstModel' => lcfirst($name)
        ];
        $controllerContent = str_replace(array_keys($replace), array_values($replace), $controllerContent);
        $controllerPath = app_path('Http/Controllers/Backend/' . $controllerName . '.php');
        File::put($controllerPath, $controllerContent);
    }

    private function createController($name)
    {

        $controllerName = $name . 'Controller';
        $templateControllerPath = app_path('Template/controllers/PostController.php');
        $controllerContent = file_get_contents($templateControllerPath);
        $replace = [
            'ControllerName' => $name,
            'controllerName' => lcfirst($name),
        ];
        $controllerContent = str_replace(array_keys($replace), array_values($replace), $controllerContent);
        $controllerPath = app_path('Http/Controllers/Backend/' . $controllerName . '.php');
        File::put($controllerPath, $controllerContent);
    }

    private function registerBinding($type, $name)
    {

        if ($type == 'Repositories') {
            $head = 'Repository';
        } else {
            $head = 'Service';
        }

        $useInterface = "use App\\{$type}\\Interfaces\\{$name}{$head}Interface;";
        $useClass = "use App\\{$type}\\{$name}{$head};";

        $serviceBindingLine = "\$this->app->bind({$name}ServiceInterface::class, {$name}Service::class);";
        $repositoryBindingLine = "\$this->app->bind({$name}RepositoryInterface::class, {$name}Repository::class);";
        if ($type == 'Repositories') {
            $providerPath = app_path('Providers/RepositoryServiceProvider.php');
            $bindingLine = $repositoryBindingLine;
        } else {
            $providerPath = app_path('Providers/AppServiceProvider.php');
            $bindingLine = $serviceBindingLine;
        };
        $providerContent = File::get($providerPath);

        if (!str_contains($providerContent, $useInterface)) {
            $providerContent = preg_replace(
                '/(namespace App\\\Providers;)/',
                "$1\n$useInterface\n$useClass",
                $providerContent
            );
        }
        if (!str_contains($providerContent, $bindingLine)) {
            $providerContent = preg_replace_callback(
                '/protected function registerBindings\(\): void\s*\{([\s\S]*?)\n\s*\}/',
                function ($matches) use ($bindingLine) {
                    $existing = rtrim($matches[1]);
                    return "protected function registerBindings(): void\n    {\n" . $existing . "\n        {$bindingLine}\n    }";
                },
                $providerContent
            );
        }

        File::put($providerPath, $providerContent);
    }







    private function makeRepository($request)
    {
        switch ($request->input('module_type')) {
            case 'catalogue':
                $this->createCatalogueRepository($request);
                break;
            case 'detail':
                $this->createRepository($request);
                break;
        };
    }

    private function createCatalogueRepository($request)
    {
        $firstRepoName = $this->explodeModuleName($request->input('name'));
        $moduleType = $request->input('module_type');
        // $module = $this->convertModuleNameToTable($name);
        $repository = $this->initializeServiceLayer('Repository', 'Repositories', $request->input('name'), $moduleType);
        $replacement = [
            'FirstModel' => $firstRepoName,
            'firstModel' => lcfirst($firstRepoName),
        ];

        // dd($firstRepoName);
        $repositoryInterfaceContent = $repository['interface']['layerInterfaceContent'];
        // dd($repositoryInterfaceContent);
        $repositoryInterfaceContent = str_replace(array_keys($replacement), array_values($replacement), $repositoryInterfaceContent);
        $repositoryContent = $repository['interface']['layerContent'];
        $repositoryContent = str_replace(array_keys($replacement), array_values($replacement), $repositoryContent);
        $repositoryPath = $repository['interface']['layerPath'];
        $repositoryInterfacePath = $repository['interface']['layerInterfacePath'];
        File::put($repositoryPath, $repositoryContent);
        File::put($repositoryInterfacePath, $repositoryInterfaceContent);
    }
    private function createRepository($request)
    {
        $firstRepoName = $this->explodeModuleName($request->input('name'));
        $moduleType = $request->input('module_type');
        // $module = $this->convertModuleNameToTable($name);
        $repository = $this->initializeServiceLayer('Repository', 'Repositories', $request->input('name'), $moduleType);
        $replacement = [
            'ModelName' => $firstRepoName,
            'modelName' => lcfirst($firstRepoName),
        ];

        $repositoryInterfaceContent = $repository['interface']['layerInterfaceContent'];
        $repositoryInterfaceContent = str_replace(array_keys($replacement), array_values($replacement), $repositoryInterfaceContent);
        $repositoryContent = $repository['interface']['layerContent'];
        $repositoryContent = str_replace(array_keys($replacement), array_values($replacement), $repositoryContent);
        $repositoryPath = $repository['interface']['layerPath'];
        $repositoryInterfacePath = $repository['interface']['layerInterfacePath'];
        File::put($repositoryPath, $repositoryContent);
        File::put($repositoryInterfacePath, $repositoryInterfaceContent);
    }

    private function makeService($request)
    {
        switch ($request->input('module_type')) {
            case 'catalogue':
                $this->createCatalogueService($request);
                break;
            case 'detail':
                $this->createService($request);
                break;
        };
    }

    private function createCatalogueService($request)
    {
        $firstServiceName = $this->explodeModuleName($request->input('name'));
        $service = $this->initializeServiceLayer('Service', 'Services', $request->input('name'), $request->input('module_type'));
        $replacement = [
            'FirstModel' => $firstServiceName,
            'firstModel' => lcfirst($firstServiceName),
        ];

        $serviceInterfaceContent = $service['interface']['layerInterfaceContent'];
        // dd($serviceInterfaceContent);
        $serviceInterfaceContent = str_replace(array_keys($replacement), array_values($replacement), $serviceInterfaceContent);

        $serviceContent = $service['interface']['layerContent'];
        $serviceContent = str_replace(array_keys($replacement), array_values($replacement), $serviceContent);
        $servicePath = $service['interface']['layerPath'];
        // dd($servicePath);
        $serviceInterfacePath = $service['interface']['layerInterfacePath'];
        File::put($servicePath, $serviceContent);
        File::put($serviceInterfacePath, $serviceInterfaceContent);
        // $this->registerBinding('Repositories', $name);

    }

    private function createService($request)
    {
        $modelName = $request->input('name');
        $service = $this->initializeServiceLayer('Service', 'Services', $request->input('name'), $request->input('module_type'));
        $replacement = [
            'ModelName' => $modelName,
            'modelName' => lcfirst($modelName),
        ];

        $serviceInterfaceContent = $service['interface']['layerInterfaceContent'];
        // dd($serviceInterfaceContent);
        $serviceInterfaceContent = str_replace(array_keys($replacement), array_values($replacement), $serviceInterfaceContent);

        $serviceContent = $service['interface']['layerContent'];
        $serviceContent = str_replace(array_keys($replacement), array_values($replacement), $serviceContent);
        $servicePath = $service['interface']['layerPath'];
        // dd($servicePath);
        $serviceInterfacePath = $service['interface']['layerInterfacePath'];
        File::put($servicePath, $serviceContent);
        File::put($serviceInterfacePath, $serviceInterfaceContent);
        // $this->registerBinding('Repositories', $name);

    }
    private function initializeServiceLayer($layer, $folder, $name, $moduleType)
    {
        // dd($name);
        $option = [
            $layer . 'Name' => $name . $layer,
            $layer . 'InterfaceName' => $name . $layer . 'Interface',
        ];
        if ($moduleType == 'catalogue') {
            $layerInterfaceRead = app_path('Template/' . lcfirst($folder) . '/interfaces/PostCatalogue' . $layer . 'Interface.php');
            $layerPathRead = app_path('Template/' . lcfirst($folder) . '/PostCatalogue' . $layer . '.php');
        } elseif ($moduleType == 'detail') {
            $layerInterfaceRead = app_path('Template/' . lcfirst($folder) . '/interfaces/Post' . $layer . 'Interface.php');
            $layerPathRead = app_path('Template/' . lcfirst($folder) . '/Post' . $layer . '.php');
        };
        // dd($layerInterfaceRead);

        $layerInterfaceContent = file_get_contents($layerInterfaceRead);
        // dd($option[$layer . 'InterfaceName']);
        $layerInterfacePath = app_path($folder . '/Interfaces/' . $option[$layer . 'InterfaceName'] . '.php');
        $layerContent = file_get_contents($layerPathRead);

        $layerPathPut = app_path($folder . '/' . $option[$layer . 'Name'] . '.php');
        return [
            'interface' => [
                'layerInterfaceContent' => $layerInterfaceContent,
                'layerInterfacePath' => $layerInterfacePath,
                'layerContent' => $layerContent,
                'layerPath' => $layerPathPut,
            ],
        ];
    }

    private function convertModuleNameToTable($name)
    {
        $temp = strtolower(preg_replace('/(?<!^)[A-Z]/', '_$0', $name));
        return  $temp;
    }
    public function update($id, $request)
    {
        DB::beginTransaction();
        try {

            $user = $this->generateRepository->findById($id);
            $payload = $request->except(['_token', 'send']);
            $language = $this->generateRepository->update($id, $payload);
            DB::commit();
            return $language;
        } catch (\Exception $e) {
            DB::rollBack();
            echo $e->getMessage();
            return false;
        }
    }
    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            $user = $this->generateRepository->delete($id);

            DB::commit();
            return $user;
        } catch (\Exception $e) {
            DB::rollBack();
            echo $e->getMessage();

            return false;
        }
    }

    public function saveTranslate($option, $request)
    {

        DB::beginTransaction();
        try {
            $payload = [
                'name' => $request->input('translate_name'),
                'description' => $request->input('translate_description'),
                'content' => $request->input('translate_content'),
                'meta_title' => $request->input('translate_meta_title'),
                'meta_keyword' => $request->input('translate_meta_keyword'),
                'meta_description' => $request->input('translate_meta_description'),
                'canonical' => $request->input('translate_canonical'),
                // 'post_catalogue_id' => $option['id'],
                'language_id' => $option['GenerateId']
            ];

            $repositoryNameSpace = 'App\Repositories\\' . ucfirst($option['model']) . 'Repository';
            if (class_exists($repositoryNameSpace)) {
                $repositoryInstance = app($repositoryNameSpace);
            }

            $model = $repositoryInstance->findById($option['id']);
            $model->languages()->detach([$option['GenerateId'], $model->id]);

            $repositoryInstance->createTranslatePivot($model, $payload, 'languages', $option['GenerateId']);


            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            echo $e->getMessage();
            return false;
        }
    }
    public function modelIdConvert($model)
    {
        $temp = strtolower(preg_replace('/(?<!^)[A-Z]/', '_$0', $model));
        return $temp . '_id';
    }
}
