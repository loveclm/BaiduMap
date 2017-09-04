<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            订单列表
        </h1>
    </section>
    <section class="container content">
        <div class="content" style="min-height: 800px;">
            <div class="container">
                <div class="row">
                    <div class="col-xs-4 col-sm-4 form-inline">
                        <div class="form-group area-search-name-view">
                            <div class="form-group">
                                <select class="form-control" id="searchTypeAuth">
                                    <option value="0" <?php if ($searchTypeAuth == 0) echo ' selected'; ?> >订单编号
                                    </option>
                                    <option value="1" <?php if ($searchTypeAuth == 1) echo ' selected'; ?> >手机号
                                    </option>
                                    <option value="2" <?php if ($searchTypeAuth == 2) echo ' selected'; ?> >景区
                                    </option>
                                </select>
                            </div>
                            <input type="text" id="searchNameAuth"
                                   value="<?php echo $searchNameAuth == 'ALL' ? '' : $searchNameAuth; ?>"
                                   class="form-control">
                        </div>
                    </div>

                    <div class="col-xs-4 col-sm-4 form-inline">
                        <div class="form-group">
                            <label>订单时间 &nbsp;:&nbsp;</label>
                            <input class="form-control date-picker" id="startDateAuth" type="text"
                                   data-date-format="yyyy-mm-dd" placeholder="请选择" value="">
                            <label>&nbsp; 至 &nbsp;</label>
                            <input class="form-control date-picker" id="endDateAuth" type="text"
                                   data-date-format="yyyy-mm-dd" placeholder="请选择" value="">
                        </div>
                    </div>

                    <div class="col-xs-4 col-sm-4 form-inline">
                        <div class="form-group area-search-control-view">
                            <button class="btn btn-primary"
                                    onclick="settleAuthDetail('<?php echo base_url(); ?>');">查询
                            </button>
                            <input type="button" class="form-group btn btn-primary"
                                   onclick="cancel('<?php echo base_url(); ?>',2);" value="返回"/>

                        </div>
                    </div>
                </div>
            </div>
            <div class="container">
                <div class="row">
                    <table class="table table-bordered area-result-view">
                        <thead>
                        <tr style="background-color: lightslategrey;">
                            <th width="150">订单编号</th>
                            <th width="150">手机号</th>
                            <th width="150">授权码</th>
                            <th width="">景区</th>
                            <th width="150">订单时间</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        $authCount = count($authList);
                        for ($i = 0; $i < $authCount; $i++) {
                            $item = $authList[$i];
                            ?>
                            <tr>
                                <td><?php echo $item->number; ?></td>
                                <td><?php echo $item->mobile; ?></td>
                                <td><?php echo $item->price; ?></td>
                                <td><?php
                                    $point_listitem = json_decode($item->point_list);
                                    $cs_name = '';
                                    if (count($point_listitem) > 0) {
                                        foreach ($point_listitem as $pointitem) {
                                            if ($cs_name == '') $cs_name = $pointitem->name;
                                            else $cs_name = $cs_name . ' - ' . $pointitem->name;
                                        }
                                    }
                                    echo ($item->type == 1) ? $cs_name : $item->tour_area;
                                    ?>
                                </td>
                                <td><?php echo $item->ordered_time; ?></td>
                            </tr>
                            <?php
                        } ?>
                        </tbody>
                    </table>
                    <div class="clearfix"></div>
                </div>

            </div>
        </div>
    </section>
</div>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/settle.js" charset="utf-8"></script>