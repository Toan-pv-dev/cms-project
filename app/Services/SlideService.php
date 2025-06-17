<?php

namespace App\Services;

// namespace Carbon;
// use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use App\Services\Interfaces\SlideServiceInterface;
use App\Repositories\Interfaces\SlideRepositoryInterface as SlideRepository;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

/**
 * Class SlideService
 * @package App\Services
 */
class SlideService  extends BaseService  implements SlideServiceInterface
{
    protected $slideRepository;
    public function __construct(SlideRepository $slideRepository)
    {
        $this->slideRepository = $slideRepository;
        // $this->select();
    }
    public function select()
    {
        return ['id', 'name', 'publish', 'keyword', 'item'];
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
        $slides = $this->slideRepository->pagination($select, $condition, $perpage, ['path' => 'slide/index'], [], [], []);
        // $column = ['*'], $condition = [], int $perPage = 1, array $extend = [], array $relations = [], array $orderBy = [], $join = []
        return $slides;
    }
    public function create($request, $languageId)
    {
        DB::beginTransaction();
        try {
            $payload = $request->only(['_token', 'name', 'keyword', 'setting', 'short_code']);
            $payload['item'] = $this->handleSlideItem($request, $languageId);
            $result = $this->slideRepository->create($payload);
            DB::commit();
            return $result;
        } catch (\Exception $e) {
            DB::rollBack();
            dd($e->getMessage());
            return false;
        }
    }

    private function handleSlideItem($request, $languageId)
    {
        $items = $request->input('slide', []);
        // dd($items);
        $itemData = [];
        foreach ($items['image'] as $key => $item) {
            $itemData[$languageId][] = [
                'name' => $item,
                'description' => $items['description'][$key] ?? '',
                'canonical' => $items['url'][$key] ?? '',
                'open_new_tab' => isset($items['open_new_tab'][$key]) ? $items['open_new_tab'][$key] : '',
                'alt' => $items['alt'][$key] ?? '',
                'title' => $items['title'][$key] ?? '',
            ];
        }
        return $itemData;
    }

    public function update($id, $request)
    {
        DB::beginTransaction();
        try {
            $payload = $request->only(['_token', 'name', 'keyword', 'setting', 'short_code', 'slide']);
            $payload['item'] = $this->handleSlideItem($request, $this->currentLanguage());
            $slide = $this->slideRepository->update($id, $payload);

            DB::commit();
            return $slide;
        } catch (\Exception $e) {
            DB::rollBack();
            dd($e->getMessage());
            return false;
        }
    }
    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            $slide = $this->slideRepository->delete($id);

            DB::commit();
            return $slide;
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
            $this->slideRepository->update($post['modelId'], $payload);
            // dd($post['modelId']);
            // dd($slide);
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

            $slide =  $this->slideRepository->updateByWhereIn('id',  $post['id'], $payload);
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
