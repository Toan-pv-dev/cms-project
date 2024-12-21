<?php

namespace App\Http\Controllers\Ajax;

use App\Repositories\Interfaces\DistrictRepositoryInterface  as DistrictRepository;
use App\Repositories\Interfaces\ProvinceRepositoryInterface  as ProvinceRepository;
use Illuminate\Support\Facades\Log;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Repositories\BaseRepository;
use Psy\Readline\Hoa\Console;

class LocationController extends Controller
{
    protected $districtRepository;
    protected $provinceRepository;
    //
    public function __construct(
        DistrictRepository $districtRepository,
        ProvinceRepository $provinceRepository
    ) {
        $this->districtRepository = $districtRepository;
        $this->provinceRepository = $provinceRepository;
    }
    public function getLocation(Request $request)
    {
        // Log the incoming request data
        // Log::info('Request data:', $request->all());

        $get = $request->input();
        // Log::info('Target:', [$get['target']]);
        // dd($get);

        $html = '';
        if ($get['target'] == 'districts') {
            // Fetch the provinces based on location ID
            $province = $this->provinceRepository->findById($get['data']['location_id'], ['code', 'name'], ['districts']);
            // Log::info('Fetched provinces:', [$provinces]);

            // Generate HTML for districts
            $html = $this->renderHtml($province->districts);
        } else if ($get['target'] == 'wards') {
            $district = $this->districtRepository->findById($get['data']['location_id'], ['code', 'name'], ['wards']);
            // Log::info('Fetched districts:', [$districts]);

            // Generate HTML for wards
            $html = $this->renderHtml($district->wards, '[Chọn phường/xã]');
        }

        $response = ['html' => $html];
        return response()->json($response);
    }

    public function renderHtml($districts, $root = '[Chọn Quận/huyện]')
    {
        $html = '<option value="0">' . $root . '</option>';
        foreach ($districts as $district) {
            $html .= '<option value = "' . $district->code . '">'  . $district->name .  '</option>';
        }
        return $html;
    }
}
