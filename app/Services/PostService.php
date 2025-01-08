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
        return ['posts.id', 'posts.publish', 'posts.image', 'posts.order', 'tb2.name'];
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
            ['post_language as tb2', 'tb2.post_id', '=', 'id'],
            ['post_catalogues']

        );
        return $posts;
    }

    public function create($request)
    {
        DB::beginTransaction();
        try {
            $post = $this->createPost($request);

            if ($post->id > 0) {
                $this->updateLanguageForPost($post, $request);
                $this->updateCatalogueForPost($post, $request);
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

            // $flag = $this->postRepository->update($id, $payload);
            if ($this->updatePost($post->id, $request)) {


                $this->updateLanguageForPost($post, $request);
                $this->updateCatalogueForPost($post, $request);
            }
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
            echo 123;

            $this->changeUserStatus($post, $payload[$post['field']]);
            die();
            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            echo $e->getMessage();

            return false;
        }
    }
    public function updateStatusAll($post = [])
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
            $this->postRepository->updateByWhereIn('posts.id', $array, $payload);
            DB::commit();
            return true; // Phải trả về true nếu cập nhật thành công
        } catch (\Exception $e) {
            DB::rollBack();
            echo $e->getMessage();
            // die();
            return false; // Trả về false nếu có lỗi
        }
    }
    private function createPost($request)
    {

        $payload = $request->only($this->payload());
        $payload['user_id'] = Auth::id();
        $payload['album'] = $this->formatAlbum($payload['album'] ?? null);
        // dd($payload);
        $post = $this->postRepository->create($payload);
        // dd($post);
        return $post;
    }

    private function updatePost($id, $request)
    {
        $payload = $request->only($this->payload());
        $payload['album'] = $this->formatAlbum($payload['album'] ?? null);
        // dd($payload['album']);
        // dd($id);
        return $this->postRepository->update($id, $payload);
    }
    private function formatAlbum($album = null)
    {
        // dd($payload);
        return !empty($album) ? json_encode($album) : '';
    }

    private function updateLanguageForPost($post, $request)
    {
        $payload = $request->only($this->payloadLanguage());
        $payload = $this->formatLanguagePayload($payload, $post->id);
        $post->languages()->detach([$this->currentLanguage(), $post->id]);
        return  $this->postRepository->createPivot($post, $payload, 'languages');
    }

    private function formatLanguagePayload($payload, $postId)
    {
        $payload['canonical'] =  Str::slug($payload['canonical']);
        $payload['language_id'] = $this->currentLanguage();
        $payload['post_id'] = $postId;
        return $payload;
    }
    private function updateCatalogueForPost($post, $request)
    {
        $post->post_catalogues()->sync($this->catalogue($request));
    }

    private function catalogue($request)
    {
        // Lấy dữ liệu từ request, đảm bảo nó là một mảng
        $postCatalogueIds = $request->input('post_catalogue', []);

        // Ép kiểu thành mảng (nếu là null hoặc chuỗi)
        $postCatalogueIds = is_array($postCatalogueIds) ? $postCatalogueIds : [$postCatalogueIds];

        // Loại bỏ giá trị trống và trùng lặp
        return array_filter(array_unique($postCatalogueIds));
    }


    private function payload()
    {
        return ['follow', 'publish', 'image', 'album', 'post_catalogue_id'];
    }
    private function payloadLanguage()
    {
        return ['name', 'post_id', 'description', 'content', 'meta_title', 'meta_keyword', 'meta_description', 'canonical'];
    }
}