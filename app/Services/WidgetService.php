<?php

namespace App\Services;

// namespace Carbon;
// use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use App\Services\Interfaces\WidgetServiceInterface;
use App\Repositories\Interfaces\WidgetRepositoryInterface as WidgetRepository;
use Carbon\Carbon;

/**
 * Class WidgetService
 * @package App\Services
 */
class WidgetService extends BaseService implements WidgetServiceInterface
{
    protected $widgetRepository;
    public function __construct(WidgetRepository $widgetRepository)
    {
        $this->widgetRepository = $widgetRepository;
        // $this->select();
    }
    public function select()
    {
        return ['id', 'name', 'image', 'widget_catalogue_id', 'email', 'phone', 'address', 'publish'];
    }
    public function paginate($request)
    {
        $select = $this->select();
        $condition['keyword'] = addslashes($request->input('keyword'));
        $condition['publish'] = $request->input('publish');
        // dd($condition);
        // dd($condition);
        $perpage = $request->integer('perpage');
        $widgets = $this->widgetRepository->pagination($select, $condition, $perpage, ['path' => 'widget/index'], [], [], []);
        // $column = ['*'], $condition = [], int $perPage = 1, array $extend = [], array $relations = [], array $orderBy = [], $join = []
        return $widgets;
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
            $widget = $this->widgetRepository->create($payload);
            // dd($widget);

            DB::commit();
            return $widget;
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
            $widget = $this->widgetRepository->findById($id);
            // dd($widget);
            $payload = $request->except(['_token', 'send', 'password']);
            $carbonDate = Carbon::createFromFormat('Y-m-d', $payload['birthday'], 'Asia/Ho_Chi_Minh');
            $payload['birthday'] = $carbonDate->format('Y-m-d');
            // dd($payload);
            $widget = $this->widgetRepository->update($id, $payload);

            DB::commit();
            return $widget;
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
            $widget = $this->widgetRepository->delete($id);

            DB::commit();
            return $widget;
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
            $this->widgetRepository->update($post['modelId'], $payload);
            // dd($post['modelId']);
            // dd($widget);
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

            $widget =  $this->widgetRepository->updateByWhereIn('id',  $post['id'], $payload);
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
