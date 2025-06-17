<?php

if (!function_exists('renderSystemInput')) {
    function renderSystemInput(string $name = '', array $systems = [])
    {
        return '<input type="text" name="config[' . $name . ']" value="' . old($name, $systems[$name] ?? '') . '" class="form-control" placeholder="" autocomplete="off">';
    }
}


if (!function_exists('renderSystemImages')) {
    function renderSystemImages(string $name = '', array $systems = [])
    {
        return '<input type="text" name="config[' . $name . ']" value="' . old($name, $systems[$name] ?? '') . '" class="form-control upload-image" placeholder="" autocomplete="off">';
    }
}

if (!function_exists('renderSystemTextarea')) {
    function renderSystemTextarea(string $name = '', array $systems = [])
    {
        return '<textarea type="text" name="config[' . $name . ']" class="form-control" placeholder="" autocomplete="off">' . old($name, $systems[$name] ?? '') . '</textarea>';
    }
}

if (!function_exists('renderSystemLink')) {
    function renderSystemLink(array $item = [], array $systems = [])
    {
        if (isset($item['link']))
            return '<a href="' . $item['link']['href'] . '" class="system_link">'  . $item["link"]["text"] .  '</a>';
        return;
    }
}

if (!function_exists('renderSystemEditor')) {
    function renderSystemEditor(string $name = '', array $systems = [])
    {
        return '<textarea name="config[' . $name . ']" id="' . $name . '" class="form-control ck-editor" placeholder="" autocomplete="off">' . old($name, $systems[$name] ?? '') . '</textarea>';
    }
}

if (!function_exists('renderSystemSelect')) {
    function renderSystemSelect(array $item = [], $name = '', array $systems = [])
    {
        $options = '';
        if (isset($item['option'])) {
            foreach ($item['option'] as $key => $val) {
                $selected = old('config[' . $name . ']', $systems[$name] ?? '') == $key ? ' selected' : '';
                $options .= '<option value="' . $key . '"' . $selected . '>' . $val . '</option>';
            }
        }

        return '<select class="form-control " name="config[' . $name . ']">' . $options . '</select>';
    }
}


if (!function_exists('recursive')) {
    function recursive($data, $parentId = 0)
    {
        $temp = [];
        if (!is_null($data) && count($data)) {
            foreach ($data as $key => $val) {
                if ($val->parent_id == $parentId) {

                    $temp[] = [
                        'item' => $val,
                        'children' => recursive($data, $val->id)
                    ];
                }
            }
        }
        return $temp;
    }
}


if (!function_exists('recursive_menu')) {
    function recursive_menu($data)
    {
        $html = '<ol class="dd-list">';
        if (count($data)) {
            foreach ($data as $val) {
                if (!isset($val['item']) || !$val['item']) {
                    continue;
                }

                $menu = $val['item'];
                $itemId = $menu->id;

                // Trích xuất tên ngôn ngữ (nếu có)
                $language = $menu->languages->first();
                $itemName = $language?->pivot?->name ?? 'Không có tên';

                $itemRoute = route('menu.children', $itemId);

                $html .= "<li class='dd-item' data-id='{$itemId}'>";
                $html .= "<div class='dd-handle'>
                            <span class='label label-info'><i class='fa fa-users'></i></span>
                            {$itemName}
                          </div>";
                $html .= "<a class='create-children-menu' href='{$itemRoute}'>Quản lý menu con</a>";

                if (!empty($val['children'])) {
                    $html .= recursive_menu($val['children']);
                }

                $html .= "</li>";
            }
        }
        $html .= '</ol>';

        return $html;
    }
}
