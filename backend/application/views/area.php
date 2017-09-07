<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            <a href="#" onclick="test_api();">景区管理</a>
        </h1>
    </section>

    <section class="content" style="min-height: 800px;">
        <div class="container">
            <div class="row">
                <div class="col-xs-12 col-sm-4 form-inline">
                    <div class="form-group area-search-name-view">
                        <label>景区名称 : </label>
                        <input type="text" id="searchName"
                               value="<?php echo $searchName == 'all' ? '' : $searchName; ?>" class="form-control">
                    </div>

                </div>

                <div class="col-xs-12 col-sm-6 form-inline">
                    <div id="tip" class="form-group area-search-area-view">
                        <label>所属地区 : </label>
                        <?php

                        $address = '';
                        $addrs = explode(',', $address);
                        ?>
                        <select id='province' onchange='search(this)'></select>
                        <select id='city' onchange='search(this)'></select>
                        <select id='district' onchange='search(this)'></select>
                        <select id='street' onchange='setCenter(this)' style="display: none;"></select>

                        <div id="provinceName"
                             style="display: none;"><?php echo $address != '' ? ($addrs[0]) : ''; ?></div>
                        <div id="cityName" style="display: none;"><?php echo $address != '' ? ($addrs[1]) : ''; ?></div>
                        <div id="districtName"
                             style="display: none;"><?php echo $address != '' ? ($addrs[2]) : ''; ?></div>
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
                    <div class="form-group">
                        <select class="form-control" id="searchStatus">
                            <option value="0" <?php if ($searchStatus == 0) echo ' selected' ?>>状态</option>
                            <option value="2" <?php if ($searchStatus == 2) echo ' selected' ?>>未上架</option>
                            <option value="1" <?php if ($searchStatus == 1) echo ' selected' ?>>已上架</option>
                        </select>
                    </div>
                </div>

                <div class="col-xs-12 col-sm-2 form-inline">
                    <div class="form-group area-search-control-view">
                        <a href="#" class="btn btn-primary" onclick="searchArea_jingqu('<?php echo base_url(); ?>');">查询</a>

                        <a class="btn btn-primary" href="<?php echo base_url(); ?>addarea">
                            <span>新增</span>
                        </a>

                    </div>
                </div>
            </div>
        </div>
        <div class="container">
            <div class="row">
                <table class="table table-bordered area-result-view">
                    <thead>
                    <tr style="background-color: lightslategrey;">
                        <th>景区名称</th>
                        <th width="">景点个数</th>
                        <th width="">景区总价格(元)</th>
                        <th>所属地区</th>
                        <th width="">状态</th>
                        <th width="">操作</th>
                    </tr>
                    </thead>
                    <tbody id="content_tbl">
                    <?php
                    $areaCount = count($areaList);

                    for ($i = 0; $i < $areaCount; $i++) {
                        $area = $areaList[$i];
                        $points = json_decode($areaList[$i]->point_list);
                        $pointCount = sizeof($points);
                        ?>
                        <tr>
                            <td><?php echo $area->name; ?></td>
                            <td><?php echo $pointCount; ?></td>
                            <td><?php echo floatval($area->price)*floatval($area->discount_rate); ?></td>
                            <td><?php echo $area->address; ?></td>
                            <td><?php echo $area->status == 1 ? '已上架' : '未上架'; ?></td>
                            <td>
                                <a href="<?php echo base_url(); ?>editarea/<?php echo $area->id; ?>">查看&nbsp;</a>
                                <?php
                                if ($area->status == 0) {
                                    ?>
                                    <a href="#" onclick="deleteAreaConfirm_jingqu(<?php echo $area->id; ?>);">删除&nbsp;</a>
                                    <?php
                                }
                                if ($area->status == 0) {
                                    ?>
                                    <a href="#" onclick="deployAreaConfirm_jingqu(<?php echo $area->id; ?>);">上架&nbsp;</a>
                                    <?php
                                } else {
                                    ?>
                                    <a href="#" onclick="undeployAreaConfirm_jingqu(<?php echo $area->id; ?>);">下架</a>
                                    <?php
                                }
                                ?>
                            </td>
                        </tr>
                    <?php } ?>
                    </tbody>
                </table>
                <div class="form-group">
                    <div id="custom-confirm-delete-view" style="display:none;">
                        <p>
                            是否要删除此景区？
                        </p>

                        <div class="form-group">
                            <button class="btn btn-default" onclick="deleteArea_jingqu('<?php echo base_url(); ?>', 0);">取消</button>
                            <button class="btn btn-primary" onclick="deleteArea_jingqu('<?php echo base_url(); ?>', 1);">确定</button>
                        </div>

                    </div>
                    <div id="custom-confirm-deploy-view" style="display:none;">
                        <p id="deployMessage">
                            是否要上架此景区？
                        </p>

                        <div class="form-group">
                            <button class="btn btn-default" onclick="deployArea_jingqu('<?php echo base_url(); ?>', 0);">取消</button>
                            <button class="btn btn-primary" onclick="deployArea_jingqu('<?php echo base_url(); ?>', 1);">确定</button>
                            <input id="current-areaid" style="display: none;"/>
                            <input id="current-areastatus" style="display: none;"/>
                        </div>
                    </div>
                </div>
                <div class="clearfix"></div>
            </div>

        </div>
    </section>
</div>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/area.js" charset="utf-8"></script>
<script
    src="http://webapi.amap.com/maps?v=1.3&key=0250860ccb5953fa5d655e8acf40ebb7&plugin=AMap.PolyEditor,AMap.MouseTool,AMap.DistrictSearch"></script>
<script src="http://webapi.amap.com/ui/1.0/main.js?v=1.0.10"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/map.js" charset="utf-8"></script>