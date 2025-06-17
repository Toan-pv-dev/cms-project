<?php

namespace App\Classes;

class System
{
    public function configData()
    {
        $data['homepage'] = [
            'label' => 'Thông tin chung',
            'description' => 'Cài đặt đủ thông tin chung của website. Tên thương hiệu website, logo, Favicon',
            'value' => [
                'company' => ['type' => 'text', 'label' => 'Tên công ty'],
                'brand' => ['type' => 'text', 'label' => 'Tên thương hiệu'],
                'slogan' => ['type' => 'text', 'label' => 'Slogan'],
                'logo' => ['type' => 'images', 'label' => 'Logo website', 'title' => 'Click vào ô phía dưới để tải Logo'],
                'copyright' => ['type' => 'text', 'label' => 'Copyright'],
                'website' => ['type' => 'select', 'label' => 'Tình trạng website', 'option' => [
                    'open' => 'Mở cửa website',
                    'close' => 'Đóng cửa website',
                ]],
                'short_intro' => ['type' => 'ckeditor', 'label' => 'Giới thiệu ngắn'],




            ]
        ];

        $data['contact'] = [
            'label' => 'Thông tin liên hệ',
            'description' => 'Cài đặt đủ thông tin chung của website.Ví dụ địa chỉ công ty, văn phòng giao dịch, hotline, bản đồ,...',
            'value' => [
                'company_address' => ['type' => 'text', 'label' => 'Địa chỉ công ty'],
                'company_address' => ['type' => 'text', 'label' => 'Địa chỉ công ty'],
                'hotline' => ['type' => 'text', 'label' => 'Hotline'],
                'technical_phone' => ['type' => 'text', 'label' => 'Hotline kỹ thuật'],
                'fax' => ['type' => 'text', 'label' => 'Fax'],
                'phone' => ['type' => 'text', 'label' => 'Số cố định'],
                'email' => ['type' => 'text', 'label' => 'Eamil'],
                'tax' => ['type' => 'text', 'label' => 'Mã số thuế'],
                'website' => ['type' => 'text', 'label' => 'Website'],
                'map' => ['type' => 'textarea', 'label' => 'map', 'link' => ['text' => 'Hướng dẫn thiết lập bản đồ', 'href' => 'https://sikido.vn/cach-nhung-google-map-vao-website-don-gian']],
            ]
        ];
        return $data;
    }
}
