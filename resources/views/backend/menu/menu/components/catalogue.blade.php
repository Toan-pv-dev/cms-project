 <div class="row">
     <div class="col-lg-5">
         <div class="panel-head">
             <div class="panel-title">
                 Vị trí Menu
             </div>
             <div class="panel-description">
                 <p>- Website có các vị trí hiển thị cho từng menu</span>
                 <p>- Lựa chọn vị trí mà bạn muốn hiển thị <span class="text-danger">(*)</span> la bat buoc</p>
             </div>
         </div>
     </div>
     <div class="col-lg-7">
         <div class="ibox">
             <div class="ibox-content">
                 <div class="row ">
                     <div class="col-lg-12 mb10">
                         <div class="uk-flex uk-flex-middle uk-flex-between">
                             <div class="text-bold">Chọn vị trí hiển thị <span class="text-danger">(*)</span>
                             </div>

                             <button data-toggle="modal" data-target="#modal-form" type="button"
                                 class="btn btn-primary">Thêm</button>
                         </div>

                     </div>
                     <div class="col-lg-6 " style="height: 100%">
                         <select class="setupSelect2" name="menu_catalogue_id" id="">
                             <option value="0">[Chọn vị trí hiển thị]</option>

                             @foreach ($menuCatalogue as $item)
                                 <option value="{{ $item->id }}"
                                     {{ isset($menu) && $menu->menu_catalogue_id == $item->id ? 'selected' : '' }}>
                                     {{ $item->name }}</option>
                             @endforeach
                         </select>
                     </div>
                     @if ($config['method'] == 'create')
                         <div class="col-lg-6 " style="height: 100%">
                             <select class="setupSelect2" name="type" id="">
                                 <option value="0">[Chọn kiểu menu]</option>
                                 @foreach (__('module.type') as $key => $val)
                                     <option value="{{ $key }}">{{ $val }}</option>
                                 @endforeach

                             </select>
                         </div>
                     @endif
                 </div>
             </div>
         </div>
     </div>
 </div>
