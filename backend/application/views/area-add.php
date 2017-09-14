<link rel="stylesheet" href="http://cache.amap.com/lbs/static/main.css?v=1.0?v=1.0"/>

<style type="text/css">

    #tip {
        background-color: transparent;
        border: transparent;
        padding: 0 10px;
        position: relative;
        font-size: inherit;
        left: 0px;
        top: 0px;
        border-radius: 3px;
        line-height: 36px;
    }

    #tip select {
        width: 100px;
        height: 30px;
    }

    .amap-indoor-map .label-canvas {
        position: absolute;
        top: 0;
        left: 0
    }

    .amap-indoor-map .highlight-image-con * {
        pointer-events: none
    }

    .amap-indoormap-floorbar-control {
        position: absolute;
        margin: auto 0;
        bottom: 165px;
        right: 12px;
        width: 35px;
        text-align: center;
        line-height: 1.3em;
        overflow: hidden;
        padding: 0 2px
    }

    .amap-indoormap-floorbar-control .panel-box {
        background-color: rgba(255, 255, 255, .9);
        border-radius: 3px;
        border: 1px solid #ccc
    }

    .amap-indoormap-floorbar-control .select-dock {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        box-sizing: border-box;
        height: 30px;
        border: solid #4196ff;
        border-width: 0 2px;
        border-radius: 2px;
        pointer-events: none;
        background: linear-gradient(to bottom, #f6f8f9 0, #e5ebee 50%, #d7dee3 51%, #f5f7f9 100%)
    }

    .amap-indoor-map .transition {
        transition: opacity .2s
    }

    ,
    .amap-indoormap-floorbar-control .transition {
        transition: top .2s, margin-top .2s
    }

    .amap-indoormap-floorbar-control .select-dock:after, .amap-indoormap-floorbar-control .select-dock:before {
        content: "";
        position: absolute;
        width: 0;
        height: 0;
        left: 0;
        top: 10px;
        border: solid transparent;
        border-width: 4px;
        border-left-color: #4196ff
    }

    .amap-indoormap-floorbar-control .select-dock:after {
        right: 0;
        left: auto;
        border-left-color: transparent;
        border-right-color: #4196ff
    }

    .amap-indoormap-floorbar-control.is-mobile {
        width: 37px
    }

    .amap-indoormap-floorbar-control.is-mobile .floor-btn {
        height: 35px;
        line-height: 35px
    }

    .amap-indoormap-floorbar-control.is-mobile .select-dock {
        height: 35px;
        top: 36px
    }

    .amap-indoormap-floorbar-control.is-mobile .select-dock:after, .amap-indoormap-floorbar-control.is-mobile .select-dock:before {
        top: 13px
    }

    .amap-indoormap-floorbar-control.is-mobile .floor-list-box {
        height: 105px
    }

    .amap-indoormap-floorbar-control .floor-list-item .floor-btn {
        color: #555;
        font-family: "Times New Roman", sans-serif, "Microsoft Yahei";
        font-size: 16px
    }

    .amap-indoormap-floorbar-control .floor-list-item.selected .floor-btn {
        color: #000
    }

    .amap-indoormap-floorbar-control .floor-btn {
        height: 28px;
        line-height: 28px;
        overflow: hidden;
        cursor: pointer;
        position: relative;
        -webkit-touch-callout: none;
        -webkit-user-select: none;
        -khtml-user-select: none;
        -moz-user-select: none;
        -ms-user-select: none;
        user-select: none
    }

    .amap-indoormap-floorbar-control .floor-btn:hover {
        background-color: rgba(221, 221, 221, .4)
    }

    .amap-indoormap-floorbar-control .floor-minus, .amap-indoormap-floorbar-control .floor-plus {
        position: relative;
        text-indent: -1000em
    }

    .amap-indoormap-floorbar-control .floor-minus:after, .amap-indoormap-floorbar-control .floor-plus:after {
        content: "";
        position: absolute;
        margin: auto;
        top: 9px;
        left: 0;
        right: 0;
        width: 0;
        height: 0;
        border: solid transparent;
        border-width: 10px 8px;
        border-top-color: #777
    }

    .amap-indoormap-floorbar-control .floor-minus.disabled, .amap-indoormap-floorbar-control .floor-plus.disabled {
        opacity: .3
    }

    .amap-indoormap-floorbar-control .floor-plus:after {
        border-bottom-color: #777;
        border-top-color: transparent;
        top: -3px
    }

    .amap-indoormap-floorbar-control .floor-list-box {
        max-height: 153px;
        position: relative;
        overflow-y: hidden
    }

    .amap-indoormap-floorbar-control .floor-list {
        margin: 0;
        padding: 0;
        list-style: none
    }

    .amap-indoormap-floorbar-control .floor-list-item {
        position: relative
    }

    .amap-indoormap-floorbar-control .floor-btn.disabled, .amap-indoormap-floorbar-control .floor-btn.disabled *, .amap-indoormap-floorbar-control.with-indrm-loader * {
        -webkit-pointer-events: none !important;
        pointer-events: none !important
    }

    .amap-indoormap-floorbar-control .with-indrm-loader .floor-nonas {
        opacity: .5
    }

    .amap-indoor-map-moverf-marker {
        color: #555;
        background-color: #FFFEEF;
        border: 1px solid #7E7E7E;
        padding: 3px 6px;
        font-size: 12px;
        white-space: nowrap;
        display: inline-block;
        position: absolute;
        top: 1em;
        left: 1.2em
    }

    .amap-indoormap-floorbar-control .amap-indrm-loader {
        -moz-animation: amap-indrm-loader 1.25s infinite linear;
        -webkit-animation: amap-indrm-loader 1.25s infinite linear;
        animation: amap-indrm-loader 1.25s infinite linear;
        border: 2px solid #91A3D8;
        border-right-color: transparent;
        box-sizing: border-box;
        display: inline-block;
        overflow: hidden;
        text-indent: -9999px;
        width: 13px;
        height: 13px;
        border-radius: 7px;
        position: absolute;
        margin: auto;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0
    }

    @-moz-keyframes amap-indrm-loader {
        0% {
            -moz-transform: rotate(0deg);
            transform: rotate(0deg);
        }
        100% {
            -moz-transform: rotate(360deg);
            transform: rotate(360deg)
        }
    }

    @-webkit-keyframes amap-indrm-loader {
        0% {
            -webkit-transform: rotate(0deg);
            transform: rotate(0deg);
        }
        100% {
            -webkit-transform: rotate(360deg);
            transform: rotate(360deg)
        }
    }

    @keyframes amap-indrm-loader {
        0% {
            -moz-transform: rotate(0deg);
            -ms-transform: rotate(0deg);
            -webkit-transform: rotate(0deg);
            transform: rotate(0deg)
        }
        100% {
            -moz-transform: rotate(360deg);
            -ms-transform: rotate(360deg);
            -webkit-transform: rotate(360deg);
            transform: rotate(360deg)
        }
    }
</style>
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            <?php echo ($isEdit == '0') ? '新增景区' : '编辑景区'; ?>
        </h1>
    </section>

    <section class="content">
        <input id="custom-base-url" value="<?php echo base_url(); ?>" style="display: none;"/>
        <?php
        if (isset($area)) {
            $areaInfo = json_decode($area->info);
            //var_dump($areaInfo->zoom);
        }
        ?>
        <div class="container">
            <div class="row">
                <div class="col-xs-6 col-sm-4 form-inline">
                    <div class="form-group area-add-view">
                        <label for="exampleInputName2">景区名称:</label>
                        <input type="text" class="form-control" id="areaname" maxlength="20"
                               value="<?php echo isset($area) ? $area->name : ''; ?>"/>
                        <input type="text" id="point-list" style="display:none;"
                               value="<?php echo isset($area) ? $area->id : ''; ?>"/>
                        <div id="custom-error-areaname" class="custom-error" style="display: none;">景区名称要不超过10个字符</div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-xs-6 col-sm-4 form-inline">
                    <div class="form-group area-add-view">
                        <label>上传录音：</label>
                        <a class="btn btn-primary" href="#" onclick="uploadAreaAudio()">
                            <span>上传录音</span>
                        </a>
                        <input id="upload-area-audio" type="file" style="display: none"/>
                        <a href="#" id="area-audio-file"
                           onclick="$('#area-audio-file').html('');"><?php echo isset($areaInfo) ? $areaInfo->audio : ''; ?></a>
                    </div>
                </div>
            </div>
        </div>
        <div class="container">
            <div class="row">
                <div id="tip" class="form-group">
                    <label for="exampleInputName2">所属地区：</label>
                    <?php
                    $address = isset($area) ? ($area->address) : '';
                    $addrs = explode(',', $address);
                    ?>
                    <select id='province' onchange='search(this)'></select>
                    <select id='city' onchange='search(this)'></select>
                    <select id='district' onchange='search(this)'></select>
                    <select id='street' onchange='setCenter(this)' style="display: none;"></select>
                    <div id="provinceName" style="display: none;"><?php echo $address != '' ? ($addrs[0]) : ''; ?></div>
                    <div id="cityName" style="display: none;"><?php echo $address != '' ? ($addrs[1]) : ''; ?></div>
                    <div id="districtName" style="display: none;"><?php echo $address != '' ? ($addrs[2]) : ''; ?></div>
                </div>
                <input id="map_zoom_data" style="display: none;"
                       value="<?= isset($areaInfo->zoom) ? $areaInfo->zoom : '13'; ?>">
                </input>
                <div class="row">
                    <div class="form-inline">
                        <div class="form-group area-add-view">
                            <label for="exampleInputName2">景区折扣比率:</label>
                            <input type="text" class="form-control" id="arearate"
                                   value="<?php echo isset($area) ? (floatval($area->discount_rate) * 100) : ''; ?>">
                            <label">%</label>
                        </div>
                    </div>
                    <div class="col-sm-8">
                        <div class="form-group col-md-12" style="position: absolute; z-index: 1000;">
                            <input class="btn btn-default" id="city_Name" type="text" placeholder="输入您要定位的地址"
                                   value="<?php echo isset($addrs[3]) != '' ? ($addrs[3]) : ''; ?>"/>

                            <input id="area-position" style="display: none;"
                                   value="<?php echo isset($area) ? json_encode($areaInfo->position) : ''; ?>"/>
                            <a href="#" class="btn btn-default" onclick="searchMapArea();">
                                <i class="fa fa-search"></i>
                            </a>
                        </div>
                        <!-- ////////////////////GaoDe Map Part  -->
                        <div id="custom-map-container" style="height: 600px;"></div>
                        <!-- ////////////////////                -->
                    </div>
                    <div id="detail_editing_panel" class="col-sm-3"
                         style="display:<?php echo $isEdit == '0' ? 'none' : 'block'; ?>; border: 1px solid;height: 600px;">

                        <div class="point-list-view">
                            <div class="form-group col-sm-6">
                                <button class="btn btn-primary" type="button" onclick="showAddPoint();">标记景点</button>
                            </div>
                            <div class="form-group col-sm-6">
                                <input id="upload-overlay" type="file" style="display: none;"/>
                                <button class="btn btn-primary" type="button" onclick="uploadOverlay();">上传覆盖图</button>
                                <input id="area-overlay" value="<?php echo isset($area) ? ($areaInfo->overay) : ''; ?>"
                                       style="display: none;"/>
                            </div>
                            <div id="area-image-message" style="text-align: right; margin:20px;"></div>
                            <div class="form-group">
                                <div id="pointList">

                                </div>
                            </div>
                        </div>

                        <div class="point-add-view" style="display: none;">
                            <input id="point-view-index" style="display: none;" value="0"/>

                            <div class="form-group">
                                <label>景点名称：</label>
                                <input type="text" class="form-control" id="pointname" maxlength="20">
                            </div>
                            <div class="form-group">
                                <label>景点简述：</label>
                                <input type="text" class="form-control" id="pointdescription" maxlength="40">
                            </div>

                            <div class="form-group" style="display: none;">
                                <input id="upload-point-image" type="file" style="display: none;">
                                <input id="pointimage" value="" style="display: none;"/>
                                <label>上传图片：</label>
                                <a class="btn btn-primary" onclick="uploadPointImage();">
                                    <span>上传图片</span>
                                </a>
                                <span id="point-image-message"></span>
                            </div>

                            <input id="point-position-temp" value="" style="display: none;">
                            <div class="form-group" style="height: 150px;width: 100%; display: none;">
                                <img id="point-item-image" style="height: 150px;width: 100%; display:none;" src=""/>
                            </div>

                            <div class="form-group">
                                <label>上传录音：</label>
                                <a class="btn btn-primary" onclick="uploadPointAudio();">
                                    <span>上传录音</span>
                                </a>
                                <input id="upload-point-audio" type="file" style="display: none;">
                                <label id="pointaudio" value="" style="display: none"></label>
                                <a href="#" id="pointaudio_view"
                                   onclick="$('#pointaudio_view').html('');"></a>
                            </div>

                            <div class="form-group">
                                <label>景点价格：</label>
                                <input type="text" class="form-control" id="pointprice">
                            </div>

                            <div class="form-group">
                                <input type="checkbox" id="pointfree"/> 试听
                            </div>

                            <div class="form-group" style="text-align: center">
                                <button class="btn btn-default" type="button" onclick="addPoint(0);">取消</button>
                                <button class="btn btn-primary" type="button" onclick="addPoint(1);">完成</button>
                            </div>

                        </div>
                    </div>

                </div>
                <div class="col-md-12 form-inline" style="margin-top: 10px;">
                    <input type="button" class="btn btn-primary"
                           onclick="addTouristArea('<?php echo base_url(); ?>', <?php echo isset($area) ? $area->id : 0; ?>);"
                           value="确认"/>
                    <a class="btn btn-default" href="<?php echo base_url() . 'area' ?>">
                        <span>取消</span>
                    </a>
                </div>

            </div>
            <input id="page_loaded_status" value="0" style="display: none;"/>
    </section>
</div>

<!-- Baidu Map JS-->
<!--//////////////////////////-->

<!--////////////////////////// -->
<script
        src="http://webapi.amap.com/maps?v=1.3&key=0250860ccb5953fa5d655e8acf40ebb7&plugin=AMap.PolyEditor,AMap.MouseTool,AMap.DistrictSearch"></script>
<script src="http://webapi.amap.com/ui/1.0/main.js?v=1.0.10"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/map.js" charset="utf-8"></script>