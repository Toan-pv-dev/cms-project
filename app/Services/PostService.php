<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use App\Services\Interfaces\PostServiceInterface;
use App\Repositories\PostRepository as PostRepository;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Services\BaseService;
use App\Repositories\RouterRepository as RouterRepository;

use Illuminate\Support\Str;


/**
 * Class UserService
 * @package App\Services
 */
class PostService  extends BaseService implements PostServiceInterface
{
    protected $postRepository;
    protected $nestedSet;
    protected $controllerName = 'PostController';

    public function __construct(PostRepository $postRepository, RouterRepository $routerRepository)
    {
        $this->postRepository = $postRepository;
        $this->routerRepository = $routerRepository;
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
        $condition['where'] = [
            ['tb2.language_id', '=', $this->currentLanguage()],
        ];

        $perPage = $request->integer('perpage');
        $joins = [
            ['post_language as tb2', 'tb2.post_id', '=', 'id'],
            ['post_catalogue_post as tb3', 'posts.id', '=', 'tb3.post_id']
        ];





        return $this->postRepository->pagination(
            $select,         // Columns to select
            $condition,      // Conditions
            $perPage,        // Items per page
            ['path' => 'post/index', 'groupBy' => $this->select()], // Extended options
            ['posts.id' => 'DESC'], // Order by
            $joins,
            ['post_catalogues'],
            $this->whereRaw($request),
        );
    }


    public function create($request)
    {
        DB::beginTransaction();
        try {
            $post = $this->createPost($request);

            if ($post->id > 0) {
                $this->updateLanguageForPost($post, $request);
                $this->updateCatalogueForPost($post, $request);
                $this->createRouter($post, $request, $this->controllerName, $this->currentLanguage());
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
                $this->updateRouter($post, $request, $this->controllerName, $this->currentLanguage());
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
            $this->routerRepository->forceDeleteByWhere([
                ['module_id', '=', $id],
                ['controllers', '=', 'App\Http\Controller\Frontend\\' . $this->controllerName],
            ]);

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
            $this->changeUserStatus($post, $payload[$post['field']]);
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

    private function whereRaw($request)
    {
        $rawCondition = [];
        // dd($request->integer('post_catalogue_id'));
        if ($request->integer('post_catalogue_id') > 0) {
            $rawCondition['whereRaw'] =
                [
                    [
                        'tb3.post_catalogue_id IN (SELECT id FROM post_catalogues
                    WHERE lft >= (SELECT lft FROM post_catalogues AS pc WHERE pc.id = ?)
                    AND rgt <= (SELECT rgt FROM post_catalogues AS pc WHERE pc.id = ?)
                )',
                        [$request->integer('post_catalogue_id'), $request->integer('post_catalogue_id')]
                    ]
                ];
        }
        // dd($rawCondition);
        return $rawCondition;
        // dd($rawCondition);
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
