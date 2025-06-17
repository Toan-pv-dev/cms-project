<?php

namespace App\Services;

// namespace Carbon;
// use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use App\Services\Interfaces\SystemServiceInterface;
use App\Repositories\Interfaces\SystemRepositoryInterface as SystemRepository;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

/**
 * Class SystemService
 * @package App\Services
 */
class SystemService  implements SystemServiceInterface
{
    protected $systemRepository;
    public function __construct(SystemRepository $systemRepository)
    {
        $this->systemRepository = $systemRepository;
        // $this->select();
    }
    public function select()
    {
        return ['id', 'name', 'image', 'system_catalogue_id', 'email', 'phone', 'address', 'publish'];
    }
    public function paginate($request, $languageId)
    {
        $select = $this->select();
        $condition = [
            'keyword' => $request->input('keyword'),
            'publish' => $request->input('publish'),
            'language_id' => $languageId,
        ];

        $perpage = $request->integer('perpage');
        $systems = $this->systemRepository->pagination($select, $condition, $perpage, ['path' => 'system/index'], [], [], []);
        // $column = ['*'], $condition = [], int $perPage = 1, array $extend = [], array $relations = [], array $orderBy = [], $join = []
        return $systems;
    }
    public function create($request, $languageId)
    {
        DB::beginTransaction();
        try {
            $firstPayload = $request->input('config');

            foreach ($firstPayload as $key => $val) {

                $condition = ['keyword' => $key, 'language_id' => $languageId];
                $data = [
                    'content' => $val,
                    'user_id' => Auth::id(),
                ];
                $this->systemRepository->updateOrInsert($data, $condition);
            }
            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            echo $e->getMessage();
            return false;
        }
    }

    public function update($id, $request)
    {
        DB::beginTransaction();
        try {
            $system = $this->systemRepository->findById($id);
            // dd($system);
            $payload = $request->except(['_token', 'send', 'password']);
            $carbonDate = Carbon::createFromFormat('Y-m-d', $payload['birthday'], 'Asia/Ho_Chi_Minh');
            $payload['birthday'] = $carbonDate->format('Y-m-d');
            // dd($payload);
            $system = $this->systemRepository->update($id, $payload);

            DB::commit();
            return $system;
        } catch (\Exception $e) {
            DB::rollBack();
            echo $e->getMessage();
            die();
            return false;
        }
    }
    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            $system = $this->systemRepository->delete($id);

            DB::commit();
            return $system;
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
            $this->systemRepository->update($post['modelId'], $payload);
            // dd($post['modelId']);
            // dd($system);
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

            $system =  $this->systemRepository->updateByWhereIn('id',  $post['id'], $payload);
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
