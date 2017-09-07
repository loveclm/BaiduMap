<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            二维码列表
        </h1>
    </section>

    <section class="content" style="min-height: 800px;">
        <div class="container">
            <div class="row">
                <div class="col-xs-6 col-sm-4 form-inline">
                    <div class="form-group area-search-name-view">
                        <div class="form-group">
                            <select class="form-control" id="searchType">
                                <?php
                                if ($shop_manager_number == '') {
                                    ?>
                                    <option value="0" <?php if ($searchType == 0) echo ' selected' ?>>商家名称</option>
                                    <?php
                                } ?>
                                <option value="1" <?php if ($searchType == 1) echo ' selected' ?>>名称</option>
                            </select>
                        </div>
                        <input type="text" id="searchName"
                               value="<?php echo $searchName == 'all' ? '' : $searchName; ?>" class="form-control">
                    </div>

                </div>

                <div class="col-xs-6 col-sm-4 form-inline">
                    <div class="form-group">
                        <select class="form-control" id="searchStatus">
                            <option value="0" <?php if ($searchStatus == 0) echo ' selected' ?>>类型</option>
                            <option value="2" <?php if ($searchStatus == 2) echo ' selected' ?>>景区</option>
                            <option value="1" <?php if ($searchStatus == 1) echo ' selected' ?>>旅游线路</option>
                        </select>
                    </div>
                </div>

                <div class="col-xs-6 col-sm-4 form-inline">
                    <div class="form-group area-search-control-view">
                        <button class="btn btn-primary" onclick="searchQR('<?php echo base_url(); ?>');">查询</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="container">
            <div class="row">
                <table class="table table-bordered area-result-view">
                    <thead>
                    <tr style="background-color: lightslategrey;">
                        <?php
                        if ($shop_manager_number == '') {
                            ?>
                            <th>商家名称</th>
                            <?php
                        } ?>
                        <th width="">类型</th>
                        <th width="">名称</th>
                        <th width="">新增时间</th>
                        <th width="">操作</th>
                    </tr>
                    </thead>
                    <tbody id="content_tbl">
                    <?php

                    $qrCount = count($qrList);
                    for ($i = 0; $i < $qrCount; $i++) {
                        $qr = $qrList[$i];
                        ?>
                        <tr>
                            <?php
                            if ($shop_manager_number == '') {
                                ?>
                                <td><?php echo $qr['shop']; ?></td>
                                <?php
                            } ?>
                            <td><?php echo $qr['type']; ?></td>
                            <td><?php echo $qr['target']; ?></td>
                            <td><?php echo $qr['time']; ?></td>
                            <td>
                                <a href="#"
                                   onclick="showQR('<?php echo '?shopid=' . $qr['shopid'] .'&type=' . $qr['areatype'] . '&targetid=' . $qr['targetid']; ?>');">查看</a>
                            </td>
                        </tr>
                    <?php } ?>
                    </tbody>
                </table>
                <div class="form-group">
                    <div id="custom-generate-qr-view" style="min-width:350px; display:none;">
                        <div class="form-group">
                            <div id="qr-view"></div>
                            <button style="position: absolute; top: 3px; right: 3px;border: none;background: none;"
                                    onclick="cancelQR('<?php echo base_url(); ?>');">
                                <img src="<?php echo base_url(); ?>assets/images/close.png" style="width: 30px;">
                            </button>
                        </div>
                    </div>
                </div>
                <div class="clearfix"></div>
            </div>

        </div>
    </section>
</div>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/shop.js" charset="utf-8"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/jquery.qrcode.js" charset="utf-8"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/qrcode.js" charset="utf-8"></script>