<?php

namespace App\Services;

use App\Models\{ModuleName};
use Illuminate\Support\Facades\DB;
use App\Services\Interfaces\{ModuleName}ServiceInterface;
use App\Repositories\Interfaces\{ModuleName}RepositoryInterface as {ModuleName}Repository;
use App\Repositories\Interfaces\LanguageRepositoryInterface as languageRepository;
use App\Repositories\RouterRepository as RouterRepository;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\App;
use App\Services\BaseService;
use Illuminate\Support\Str;
use App\Classes\Nestedsetbie;


// ModuleName
// moduleName
// {table_name}
// module_name
// modulePath
// headModule



/**
 * Class UserService
 * @package App\Services
 */
class {ModuleName}Service  extends BaseService implements {ModuleName}ServiceInterface
{
    protected ${moduleName}Repository;
    protected $languageRepository;
    // protected $nestedSet;
    protected $routerRepository;
    protected $controllerName = '{ModuleName}Controller';
    public function __construct({ModuleName}Repository ${moduleName}Repository, RouterRepository $routerRepository, languageRepository $languageRepository)
    {
        $this->{moduleName}Repository = ${moduleName}Repository;
        $this->languageRepository = $languageRepository;
        $this->routerRepository = $routerRepository;
        $this->nestedSet = new Nestedsetbie(
            [
                'table' => '{table_name}',
                'foreignkey' => '{module_name}_id',
                'language_id' => $this->currentLanguage(),
            ]
        );
    }
    public function select()
    {
        return ['{table_name}.id', '{table_name}.publish', '{table_name}.image', '{table_name}.level', '{table_name}.order', 'tb2.name', 'tb2.canonical'];
    }
    public function paginate($request)
    {

        $locale = App::getLocale();
        $language = $this->languageRepository->findByCondition([['canonical', '=', $locale]]);
        $languageId = $language->id;
        $select = $this->select();
        $condition['keyword'] = addslashes($request->input('keyword'));
        $condition['publish'] = $request->input('publish');
        $perpage = $request->integer('perpage');
        ${moduleName}s = $this->{moduleName}Repository->pagination(
            $select,
            $condition,
            $perpage,
            ['path' => '{modulePath}/index'],
            ['{table_name}.lft' => 'asc'],
            [
                ['{module_name}_language as tb2', 'tb2.{module_name}_id', '=', '{table_name}.id']
            ],
            [],
            [
                ['tb2.language_id', '=', $languageId]
            ]
        );
        return ${moduleName}s;
    }

    public function create($request)
    {
        // die();
        DB::beginTransaction();
        try {

            ${moduleName} = $this->create{ModuleName}($request);

            if (${moduleName}->id > 0) {

                $payloadLanguage = $this->updateLanguageForCatalogue(${moduleName}, $request);
                // die();

                // dd($payloadLanguage);
                $this->createRouter(${moduleName}, $request, $this->controllerName);
                // die();
                // $this->routerRepository->create($routerPayload);
                $this->nestedSet();
            }
            DB::commit();

            return ${moduleName};
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error during {moduleName} creation: ' . $e->getMessage());
            throw new \Exception('Error creating the {moduleName}: ' . $e->getMessage()); // Rethrow with a custom message
        }
    }


    public function update($id, $request)
    {
        // echo 1;
        // die();
        DB::beginTransaction();
        try {


            ${moduleName} = $this->{moduleName}Repository->findById($id);
            $flag = $this->update{ModuleName}($id, $request);
            if ($flag) {
                $this->updateLanguageForCatalogue(${moduleName}, $request);
            }

            $this->updateRouter(${moduleName}, $request, $this->controllerName);



            $this->nestedSet->Get();
            $this->nestedSet->Recursive(0, $this->nestedSet->Set());
            $this->nestedSet->Action();
            DB::commit();

            return true;
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
            $user = $this->{moduleName}Repository->delete($id);

            DB::commit();
            return $user;
        } catch (\Exception $e) {
            DB::rollBack();
            echo $e->getMessage();
            die();
            return false;
        }
    }
    public function updateStatus(${headModule} = [])
    {
        DB::beginTransaction();
        try {
            // dd(${headModule});
            $payload[${headModule}['field']] = ((${headModule}['value'] == '1') ? '0' : '1');
            $this->{moduleName}Repository->update(${headModule}['modelId'], $payload);
            // dd(${headModule});
            $this->changeUserStatus(${headModule}, $payload[${headModule}['field']]);
            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            echo $e->getMessage();

            return false;
        }
    }
    public function updateStatusAll(${headModule})
    {
        DB::beginTransaction();
        try {
            $payload[${headModule}['field']] = ${headModule}['value'];
            $flag = $this->{moduleName}Repository->updateByWhereIn('id', ${headModule}['id'], $payload);
            $this->changeUserStatus(${headModule}, $payload[${headModule}['field']]);
            DB::commit();
            return true; // Phải trả về true nếu cập nhật thành công
        } catch (\Exception $e) {
            DB::rollBack();
            echo $e->getMessage();
            return false; // Trả về false nếu có lỗi
        }
    }
    private function changeUserStatus(${headModule},  $value)
    {
        // dd(${headModule});
        DB::beginTransaction();
        try {
            $array = [];
            if (isset(${headModule}['modelId'])) {
                $array[] = ${headModule}['modelId'];
            } else {
                $array = ${headModule}['id'];
            }
            $payload[${headModule}['field']] = $value;
            $this->{moduleName}Repository->updateByWhereIn('{table_name}.id', $array, $payload);
            DB::commit();
            return true; // Phải trả về true nếu cập nhật thành công
        } catch (\Exception $e) {
            DB::rollBack();
            echo $e->getMessage();
            // die();
            return false; // Trả về false nếu có lỗi
        }
    }

    private function update{ModuleName}($id, $request)
    {
        // ${moduleName} = $this->{moduleName}Repository->findById($id);
        $payload = $request->only($this->payload());
        $payload['user_id'] = Auth::id();
        $payload['album'] = json_encode($payload['album']);
        $flag = $this->{moduleName}Repository->update($id, $payload);
        return $flag;
    }

    private function create{ModuleName}($request)
    {
        $payload = $request->only($this->payload());
        // dd($payload);
        $payload['user_id'] = Auth::id();
        $payload['album'] = $this->formatAlbum($payload['album'] ?? null);
        // dd($payload['album']);
        // dd($payload);
        ${moduleName} = $this->{moduleName}Repository->create($payload);
        return ${moduleName};
    }

    private function updateLanguageForCatalogue(${moduleName}, $request)
    {

        $payloadLanguage = $this->formatLanguagePayload(${moduleName}, $request);
        // dd($payloadLanguage);
        // dd($payloadLanguage['language_id']);
        ${moduleName}->languages()->detach([$payloadLanguage['language_id'], ${moduleName}->id]);
        $translate = $this->{moduleName}Repository->createPivot(${moduleName}, $payloadLanguage, 'languages');
        // dd($translate);
        return $translate;
    }

    private function formatLanguagePayload(${moduleName}, $request)
    {
        $payloadLanguage = $request->only($this->payloadLanguage());
        $payloadLanguage['language_id'] = $this->currentLanguage();
        $payloadLanguage['{module_name}_id'] = ${moduleName}->id;
        $payloadLanguage['canonical'] =  Str::slug($payloadLanguage['canonical']);
        // dd($payloadLanguage);
        return $payloadLanguage;
    }





    private function payload()
    {
        return ['parent_id', 'follow', 'publish', 'image', 'album'];
    }
    private function payloadLanguage()
    {
        return ['name', 'description', 'content', 'meta_title', 'meta_keyword', 'meta_description', 'canonical'];
    }
}