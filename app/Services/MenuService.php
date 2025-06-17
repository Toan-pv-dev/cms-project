<?php

namespace App\Services;

// namespace Carbon;
// use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use App\Services\Interfaces\MenuServiceInterface;
use App\Repositories\Interfaces\MenuRepositoryInterface as MenuRepository;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use App\Classes\Nestedsetbie;
use App\Services\BaseService;
use App\Repositories\Interfaces\MenuCatalogueRepositoryInterface as MenuCatalogueRepository;


/**
 * Class MenuService
 * @package App\Services
 */
class MenuService  extends BaseService implements MenuServiceInterface
{
    protected $menuRepository;
    protected $nestedSet;
    protected $menuCatalogueRepository;
    public function __construct(MenuRepository $menuRepository, MenuCatalogueRepository $menuCatalogueRepository)
    {
        $this->menuRepository = $menuRepository;
        $this->menuCatalogueRepository = $menuCatalogueRepository;


        // $this->select();
    }
    private function initialize()
    {
        $this->nestedSet = new Nestedsetbie(
            [
                'table' => 'menus',
                'foreignkey' => 'menu_id',
                'language_id' => $this->currentLanguage(),
                'isMenu' => true
            ]
        );
    }
    public function select()
    {
        return ['id', 'name', 'image', 'menu_catalogue_id', 'email', 'phone', 'address', 'publish'];
    }
    public function paginate($request)
    {
        return;
    }
    public function create($request, $languageId)
    {
        DB::beginTransaction();
        try {
            $firstPayload = $request->only('menu', 'type', 'menu_catalogue_id');
            $menuData = $firstPayload['menu'];

            foreach ($menuData['name'] as $index => $name) {
                $menuId = $menuData['id'][$index];
                $menuArray = [
                    'menu_catalogue_id' => $firstPayload['menu_catalogue_id'],
                    'order' => $menuData['order'][$index],
                    'user_id' => Auth::id(),
                ];
                if ($menuId == 0) {
                    $menuSave = $this->menuRepository->create($menuArray);
                } else {
                    $menuSave = $this->menuRepository->update($menuId, $menuArray);
                    if ($menuSave->rgt - $menuSave->lft > 1) {
                        $this->menuRepository->updateByWhere(
                            [
                                ['lft', '>', $menuSave->lft],
                                ['rgt', '<', $menuSave->lft],

                            ],
                            ['menu_catalogue_id' => $firstPayload['menu_catalogue_id']],
                        );
                    }
                }
                if ($menuSave->id > 0) {
                    $menuSave->languages()->detach([$languageId, $menuSave->id]);
                    $payloadLanguage = [
                        'language_id' => $languageId,
                        'name' => $name,
                        'canonical' => $menuData['canonical'][$index],
                    ];
                    $this->menuRepository->createPivot($menuSave, $payloadLanguage, 'languages');
                }
            }

            $this->initialize();
            $this->nestedSet();

            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            dd([
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString(),
            ]);
            return false;
        }
    }
    public function convertMenuToFormArray($menus, $languageId = null)
    {

        foreach ($menus as $menu) {
            // Ưu tiên lấy theo $languageId nếu truyền vào
            $language = $languageId
                ? $menu->languages->firstWhere('pivot.language_id', $languageId)
                : $menu->languages->first();

            $result['name'][] = $language?->pivot?->name ?? '';
            $result['canonical'][] = $language?->pivot?->canonical ?? '';
            $result['order'][] = $menu->order;
            $result['id'][] = $menu->id;
        }

        return $result ?? [
            'name' => [],
            'canonical' => [],
            'order' => [],
            'id' => []
        ];
    }


    public function saveChildren($request, $languageId, $menu)
    {
        DB::beginTransaction();
        try {
            // dd($request->all());
            $firstPayload = $request->only('menu');
            $menuData = $firstPayload['menu'];

            foreach ($menuData['name'] as $index => $name) {
                $menuId = $menuData['id'][$index];
                $menuArray = [
                    'menu_catalogue_id' => $menu->menu_catalogue_id,
                    'parent_id' => $menu->id,
                    'order' => $menuData['order'][$index],
                    'user_id' => Auth::id(),
                ];

                if ($menuId == 0) {

                    $menuSave = $this->menuRepository->create($menuArray);
                } else
                    $menuSave = $this->menuRepository->update($menu->id, $menuArray);


                if ($menuSave) {
                    $menuSave->languages()->detach([$languageId, $menu->id]);
                    $payloadLanguage = [
                        'language_id' => $languageId,
                        'name' => $name,
                        'canonical' => $menuData['canonical'][$index],
                    ];
                    $this->menuRepository->createPivot($menuSave, $payloadLanguage, 'languages');
                }
            }

            $this->initialize();
            $this->nestedSet();
            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            dd([
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString(),
            ]);
            return false;
        }
    }

    public function update($id, $request)
    {
        DB::beginTransaction();
        try {
            $menu = $this->menuRepository->findById($id);
            // dd($menu);
            $payload = $request->except(['_token', 'send', 'password']);
            $carbonDate = Carbon::createFromFormat('Y-m-d', $payload['birthday'], 'Asia/Ho_Chi_Minh');
            $payload['birthday'] = $carbonDate->format('Y-m-d');
            // dd($payload);
            $menu = $this->menuRepository->update($id, $payload);

            DB::commit();
            return $menu;
        } catch (\Exception $e) {
            DB::rollBack();
            echo $e->getMessage();
            die();
            return false;
        }
    }

    public function dragUpdate($json = [], $menuCatalogueId = 0, $parentId = 0)
    {
        if (count($json)) {
            foreach ($json as $key => $val) {
                $update = [
                    'order' => count($json) - $key,
                    'parent_id' => $parentId,
                ];
                $menu = $this->menuRepository->update($val['id'], $update);
                if (isset($val['children']) && count($val['children'])) {
                    $this->dragUpdate($val['children'], $menuCatalogueId, $val['id']);
                }
            }
        }
        $this->initialize();
        $this->nestedSet();
        return true;
    }

    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            $this->menuRepository->forceDeleteByWhere([
                ['menu_catalogue_id', '=', $id]
            ]);
            $this->menuCatalogueRepository->forceDelete($id);

            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            echo $e->getMessage();
            die();
            // return false;
        }
    }
    public function updateStatus($post = [])
    {
        DB::beginTransaction();
        try {
            // dd($post);
            $payload[$post['field']] =  (($post['value'] == 1) ? 0 : 1);
            // dd($payload);
            // dd($payload[$post['field']]);
            // dd($payload);
            $this->menuRepository->update($post['modelId'], $payload);
            // dd($post['modelId']);
            // dd($menu);
            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            echo $e->getMessage();
            die();
            return false;
        }
    }
    public function updateStatusAll($post)
    {
        DB::beginTransaction();
        try {
            // dd($post);
            $payload[$post['field']] = $post['value'];

            $menu =  $this->menuRepository->updateByWhereIn('id',  $post['id'], $payload);
            DB::commit();
            // dd($flag);
            return true; // Phải trả về true nếu cập nhật thành công
        } catch (\Exception $e) {
            DB::rollBack();
            echo $e->getMessage();
            die();
            return false; // Trả về false nếu có lỗi
        }
    }
}
