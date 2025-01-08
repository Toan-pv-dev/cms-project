<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use App\Services\Interfaces\PostServiceInterface as PostService;
use App\Http\Requests\StorePostRequest;
use App\Http\Requests\UpdatePostRequest;
use App\Http\Requests\DeletePostRequest;
use Illuminate\Http\Request;
use App\Repositories\PostRepository as PostRepository;
use App\Classes\Nestedsetbie;


class PostController extends Controller
{
    protected $postService;
    protected $postRepository;
    protected $nestedset;
    protected $language;


    public function __construct(PostService $postService, PostRepository $postRepository)
    {
        $this->postService = $postService;
        $this->postRepository = $postRepository;
        $this->nestedset = new Nestedsetbie(
            [
                'table' => 'post_catalogues',
                'foreignkey' => 'post_catalogue_id',
                'language_id' => 1,
            ]
        );
        $this->language = $this->currentLanguage();
    }
    private function getDropdown()
    {
        return $this->nestedset->Dropdown();
    }
    public function index(Request $request)
    {
        $config = [
            'js' => [
                'https://cdnjs.cloudflare.com/ajax/libs/switchery/0.8.2/switchery.min.js',
                'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js',
            ],
            'css' => [
                'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css',
                'https://cdnjs.cloudflare.com/ajax/libs/switchery/0.8.2/switchery.css',

            ],
            'model' => 'post'
        ];
        $config['seo'] = config('apps.post');

        $posts = $this->postService->paginate($request);

        $template = 'backend.post.post.index';
        return view('backend.dashboard.layout', compact(
            'template',
            'config',
            'posts',

        ));
    }

    public function create()
    {
        // dd($location);
        $config = $this->configData();
        $config['seo'] = config('apps.post');
        $config['method'] = 'create';
        $dropdown = $this->getDropdown();
        $template = 'backend.post.post.store';
        return view('backend.dashboard.layout', compact(
            'template',
            'config',
            'dropdown'
        ));
    }
    public function store(StorePostRequest $storePostRequest)
    {

        if ($this->postService->create($storePostRequest)) {
            flash()->success('Thêm bản ghi thành công');
            return redirect()->route('post.index');
        }
        flash()->error('Thêm bản ghi không thành công');
        return redirect()->route('post.index');
    }
    public function edit($id)
    {

        $post = $this->postRepository->getPostById($id, $this->language);
        $album = json_decode($post->album);

        $post_catalogue = $this->catalogue($post);

        $config = $this->configData();
        // dd($post_catalogue);
        $config['seo'] = config('apps.post');
        $config['method'] = 'update';
        $dropdown = $this->getDropdown();
        // $album = json_decode($post->album);
        $template = 'backend.post.post.store';
        return view('backend.dashboard.layout', compact(
            'template',
            'config',
            'post',
            'dropdown',
            'album',
            'post_catalogue'
        ));
    }
    public function update($id, UpdatePostRequest $updaterequest,)
    {
        if ($this->postService->update($id, $updaterequest)) {
            flash()->success('Cap nhat ban ghi thanh cong');
            return redirect()->route('post.index');
        }
        return redirect()->route('post.index')->with('error', 'Thêm mới bản ghi không thành công');
    }
    public function delete($id)
    {
        $post = $this->postRepository->getPostById($id, $this->language);
        $config['seo'] = config('apps.post');
        $config['method'] = 'delete';
        // dd($post);
        $template = 'backend.post.post.delete';
        return view('backend.dashboard.layout', compact(
            'template',
            'post',
            'config'
        ));
    }
    public function destroy($id, DeletePostRequest $request)
    {
        if ($this->postRepository->delete($id)) {
            flash()->success('Xoa ban ghi thanh cong');
            return redirect()->route('post.index');
        } else {
            flash()->error('Xoa ban ghi khong thanh cong');
            return redirect()->route('post.index');
        }
    }
    private function configData()
    {
        return  [
            'js' => [
                'https://cdnjs.cloudflare.com/ajax/libs/switchery/0.8.2/switchery.min.js',
                'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js',
                '/backend/plugins/ckfinder_2/ckfinder.js',
                '/backend/library/finder.js',
                '/backend/library/seo.js',
                '/backend/plugins/ckeditor/ckeditor.js',
            ],
            'css' => [
                'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css',
                'https://cdnjs.cloudflare.com/ajax/libs/switchery/0.8.2/switchery.css',

            ]
        ];
    }
    private function catalogue($post)
    {
        $ids = $post->post_catalogues->pluck('id')->toArray();
        return $ids;
    }
}
