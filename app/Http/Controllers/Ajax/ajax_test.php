<?php

namespace App\Http\Controllers\Ajax;

use App\Repositories\Interfaces\DistrictRepositoryInterface  as DistrictRepository;
use App\Repositories\Interfaces\ProvinceRepositoryInterface  as ProvinceRepository;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Repositories\BaseRepository;

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
        // $province_id = $request->input('province_code');
        $get = $request->input();
        dd($get);
        $html = '';
        if ($get['target'] == 'districts') {
            $provinces = $this->provinceRepository->findById($get['data']['location_id'], ['code', 'name'], ['districts']);
            $html = $this->renderHtml($provinces->districts);
        } else if ($get['target'] == 'wards') {
            // dd($get['target']);
            $districts = $this->districtRepository->findById($get['data']['location_id'], ['code', 'name'], ['wards']);
            $html = $this->renderHtml($districts->wards, '[Chọn phường/xã]');
        }
        dd($province_id);

        // foreach ($provinces as $province) {
        //     $districts = array_merge($districts, $province->districts->toArray());
        // }
        // dd($districts);
        $response = [
            'html' => $html
        ];
        return response()->json($response);
        // foreach ($district as $district) {
        //     echo $district->code;
        // }
        // // dd($response);

        // die();
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