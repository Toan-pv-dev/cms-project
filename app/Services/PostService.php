<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use App\Services\Interfaces\PostServiceInterface;
use App\Repositories\Interfaces\PostRepositoryInterface as PostRepository;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Services\BaseService;
use Illuminate\Support\Str;


/**
 * Class UserService
 * @package App\Services
 */
class PostService  extends BaseService implements PostServiceInterface
{
    protected $postRepository;
    protected $nestedSet;
    public function __construct(PostRepository $postRepository)
    {
        $this->postRepository = $postRepository;
    }
    public function select()
    {
        return ['posts.id', 'posts.publish', 'posts.image', 'posts.level', 'posts.order', 'tb2.name', 'tb2.canonical'];
    }
    public function paginate($request)
    {


        $select = $this->select();
        $condition['keyword'] = addslashes($request->input('keyword'));
        $condition['publish'] = $request->input('publish');
        $perpage = $request->integer('perpage');
        $posts = $this->postRepository->pagination(
            $select,
            $condition,
            $perpage,
            ['path' => 'post/index'],
            [],
            [
                'posts.id' => 'DESC',
            ],
            ['post_catalogue_post as tb2', 'tb2.post_id', '=', 'post_id'],

        );
        return $posts;
    }

    public function create($request)
    {
        DB::beginTransaction();
        try {
            // dd($request->all());
            $payload = $request->only($this->payload());
            // dd($payload);
            $payload['user_id'] = Auth::id();
            $payload['album'] = json_encode($payload['album']);
            // dd($payload);
            $post = $this->postRepository->create($payload);

            if ($post->id > 0) {
                $payloadLanguage = $request->only($this->payloadLanguage());
                // dd($payload);
                $payloadLanguage['language_id'] = $this->currentLanguage();
                $payloadLanguage['post_id'] = $post->id;
                $payloadLanguage['canonical'] =  Str::slug($payloadLanguage['canonical']);
                // dd($payloadLanguage);
                // dd($payloadLanguage);
                $language = $this->postRepository->createPivot($post, $payloadLanguage, 'languages');
                // dd($language);

                $catalogue = $this->catalogue($request);
                // dd($catalogue);
                // dd($post);
                dd($catalogue);
                $post->post_catalogues()->sync($catalogue);
                // echo 1;
                // die();
            }
            DB::commit();
            return $post;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error during post creation: ' . $e->getMessage());
            throw new \Exception('Error creating the post: ' . $e->getMessage()); // Rethrow with a custom message
        }
    }


    public function update($id, $request)
    {
        DB::beginTransaction();
        try {
            $post = $this->postRepository->findById($id);
            // dd($post);
            $payload = $request->only($this->payload());

            $payload['user_id'] = Auth::id();
            $payload['album'] = json_encode($payload['album']);
            // dd($payload['album']);

            // dd($payload);
            $flag = $this->postRepository->update($id, $payload);
            if ($flag) {

                $payloadLanguage = $request->only($this->payloadLanguage());
                $payloadLanguage['language_id'] = $this->currentLanguage();
                $payloadLanguage['post_catalogue_id'] = $id;
                $post->languages()->detach([$payloadLanguage['language_id'], $id]);
                $response = $this->postRepository->createTranslatePivot($post, $payloadLanguage);
            }
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
            $user = $this->postRepository->delete($id);

            DB::commit();
            return $user;
        } catch (\Exception $e) {
            DB::rollBack();
            echo $e->getMessage();
            die();
            return false;
        }
    }
    public function updateStatus($post = [])
    {
        DB::beginTransaction();
        try {
            // dd($post);
            $payload[$post['field']] = (($post['value'] == '1') ? '0' : '1');
            $this->postRepository->update($post['modelId'], $payload);
            // dd($post);
            $this->changeUserStatus($post, $payload[$post['field']]);
            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            echo $e->getMessage();

            return false;
        }
    }
    public function updateStatusAll($post)
    {
        DB::beginTransaction();
        try {
            $payload[$post['field']] = $post['value'];
            $flag = $this->postRepository->updateByWhereIn('id', $post['id'], $payload);
            $this->changeUserStatus($post, $payload[$post['field']]);
            DB::commit();
            return true; // Phải trả về true nếu cập nhật thành công
        } catch (\Exception $e) {
            DB::rollBack();
            echo $e->getMessage();
            return false; // Trả về false nếu có lỗi
        }
    }
    private function changeUserStatus($post,  $value)
    {
        // dd($post);
        DB::beginTransaction();
        try {
            $array = [];
            if (isset($post['modelId'])) {
                $array[] = $post['modelId'];
            } else {
                $array = $post['id'];
            }
            $payload[$post['field']] = $value;
            $this->postRepository->updateByWhereIn('post_catalogues.id', $array, $payload);
            DB::commit();
            return true; // Phải trả về true nếu cập nhật thành công
        } catch (\Exception $e) {
            DB::rollBack();
            echo $e->getMessage();
            // die();
            return false; // Trả về false nếu có lỗi
        }
    }
    private function catalogue($request)
    {
        // Lấy dữ liệu từ request, đảm bảo nó là một mảng
        $postCatalogueIds = $request->input('post_catalogue_id', []);

        // Ép kiểu thành mảng (nếu là null hoặc chuỗi)
        $postCatalogueIds = is_array($postCatalogueIds) ? $postCatalogueIds : [$postCatalogueIds];

        // Loại bỏ giá trị trống và trùng lặp
        return array_filter(array_unique($postCatalogueIds));
    }


    private function payload()
    {
        return ['follow', 'publish', 'image', 'album', 'parent_id'];
    }
    private function payloadLanguage()
    {
        return ['name', 'post_id', 'description', 'content', 'meta_title', 'meta_keyword', 'meta_description', 'canonical'];
    }
}
