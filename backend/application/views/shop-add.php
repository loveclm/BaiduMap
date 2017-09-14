<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            <?php echo isset($shop) ? '编辑' : '新增'; ?>商家
        </h1>
    </section>

    <section class="content">
        <div class="container">
            <div class="row custom-info-row">
                <label class="col-sm-2">*商家名称:</label>
                <input type="text" class="col-sm-2" id="shopname" maxlength="20"
                       value="<?php echo isset($shop) ? $shop->name : ''; ?>"/>

                <div id="custom-error-shopname" class="custom-error col-sm-4" style="display: none;">商家名称要不超过10个字符</div>
            </div>

            <div class="row custom-info-row">
                <label class="col-sm-2">*商家账号:</label>
                <input type="text" class="col-sm-2" id="shopid" maxlength="11"
                       value="<?php echo isset($shop)? $shop->phonenumber:''; ?>"/>

                <div id="custom-error-shopid" class="custom-error col-sm-4" style="display: none;">账号使用手机号，只能是11位</div>
            </div>

            <div class="row custom-info-row">
                <label class="col-sm-2">*初始密码:</label>
                <input type="text" class="col-sm-2" id="shoppassword" disabled
                       value="<?php echo rand(100000,999999); ?>"/>
            </div>

            <div id="tip" class="row custom-info-row">
                <label class="col-sm-2"> *所属地区:</label>
                <?php
                $address = isset($shop) ? ($shop->address_1) : '';
                $addrs = explode(',', $address);
                ?>
                <select id='province' onchange='search(this)' style="height: 26px;"></select>
                <select id='city' onchange='search(this)' style="height: 26px;"></select>
                <select id='district' onchange='search(this)' style="height: 26px;"></select>
                <select id='street' onchange='setCenter(this)' style="display: none;"></select>

                <div id="provinceName" style="display: none;"><?php echo $address != '' ? ($addrs[0]) : ''; ?></div>
                <div id="cityName" style="display: none;"><?php echo $address != '' ? ($addrs[1]) : ''; ?></div>
                <div id="districtName" style="display: none;"><?php echo $address != '' ? ($addrs[2]) : ''; ?></div>
            </div>
            <div class="col-sm-8" style="display: none;">
                <div class="form-group col-md-12" style="position: absolute; z-index: 1000;">
                    <input id="city_Name" type="text" placeholder="输入您要定位的地址"
                           value="<?php echo isset($area) ? ($area->address) : ''; ?>"/>
                    <input id="area-position" style="display: none;"
                           value="<?php echo isset($area) ? json_encode($areaInfo->position) : ''; ?>"/>
                </div>
                <!-- ////////////////////GaoDe Map Part  -->
                <div id="custom-map-container" style="height: 600px;"></div>
                <!-- ////////////////////                -->
            </div>

            <div class="row custom-info-row">
                <label class="col-sm-2"> *商家类型:</label>
                <select class="col-sm-2" id="shoptype">
                    <option value="0">请选择</option>
                    <option value="1" <?php echo isset($shop) && $shop->type == '1' ? 'selected' : ''; ?>>旅行社</option>
                    <option value="2" <?php echo isset($shop) && $shop->type == '2' ? 'selected' : ''; ?>>渠道商</option>
                </select>
            </div>

            <div class="row custom-info-row">
                <label class="col-sm-2">*订单分成比率:</label>
                <input style="text-align: right;" type="number" min="0" class="col-sm-2" id="shoprate"
                       value="<?php echo isset($shop) ? (floatval($shop->discount_rate) * 100) : ''; ?>">
                <label> % </label>
            </div>
        </div>
        <div class="container">
            <div class="row">
                <div class="col-sm-offset-2 custom-course-control-view">
                    <input type="button" class="btn btn-default" onclick="cancel('<?php echo base_url(); ?>');"
                           value="取消"/>
                    <input type="button" class="btn btn-primary"
                           onclick="processShop('<?php echo base_url(); ?>' , '<?php echo isset($shop) ? $shop->id : 0; ?>');"
                           value="确认"/>
                </div>
            </div>
        </div>
    </section>
</div>

<!-- Course Management JS-->
<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/shop.js" charset="utf-8"></script>
<script
    src="https://webapi.amap.com/maps?v=1.3&key=0250860ccb5953fa5d655e8acf40ebb7&plugin=AMap.PolyEditor,AMap.MouseTool,AMap.DistrictSearch">
</script>
<script src="http://webapi.amap.com/ui/1.0/main.js?v=1.0.10"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/map.js" charset="utf-8"></script>
