<?php

namespace App\Services;

// namespace Carbon;
// use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use App\Services\Interfaces\UserServiceInterface;
use App\Repositories\Interfaces\UserRepositoryInterface as UserRepository;
use Carbon\Carbon;

/**
 * Class UserService
 * @package App\Services
 */
class UserService  implements UserServiceInterface
{
    protected $userRepository;
    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
        // $this->select();
    }
    public function select()
    {
        return ['id', 'name', 'image', 'user_catalogue_id', 'email', 'phone', 'address', 'publish'];
    }
    public function paginate($request)
    {
        $select = $this->select();
        $condition['keyword'] = addslashes($request->input('keyword'));
        $condition['publish'] = $request->input('publish');
        // dd($condition);
        // dd($condition);
        $perpage = $request->integer('perpage');
        $users = $this->userRepository->pagination($select, $condition, $perpage, ['path' => 'user/index'], [], [], []);
        // $column = ['*'], $condition = [], int $perPage = 1, array $extend = [], array $relations = [], array $orderBy = [], $join = []
        return $users;
    }
    public function create($request)
    {
        DB::beginTransaction();
        try {
            $payload = $request->except(['_token', 'send']);
            // dd($payload);
            $carbonDate = Carbon::createFromFormat('Y-m-d', $payload['birthday'], 'Asia/Ho_Chi_Minh');
            $payload['birthday'] = $carbonDate->format('Y-m-d');
            $payload['password'] = Hash::make($payload['password']);
            $user = $this->userRepository->create($payload);
            // dd($user);

            DB::commit();
            return $user;
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
            $user = $this->userRepository->findById($id);
            // dd($user);
            $payload = $request->except(['_token', 'send', 'password']);
            $carbonDate = Carbon::createFromFormat('Y-m-d', $payload['birthday'], 'Asia/Ho_Chi_Minh');
            $payload['birthday'] = $carbonDate->format('Y-m-d');
            // dd($payload);
            $user = $this->userRepository->update($id, $payload);

            DB::commit();
            return $user;
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
            $user = $this->userRepository->delete($id);

            DB::commit();
            return $user;
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
            $this->userRepository->update($post['modelId'], $payload);
            // dd($post['modelId']);
            // dd($user);
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

            $user =  $this->userRepository->updateByWhereIn('id',  $post['id'], $payload);
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
