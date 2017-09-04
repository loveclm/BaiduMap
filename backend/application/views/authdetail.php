<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            查看详情
        </h1>
    </section>

    <section class="content" style="min-height: 800px;">
        <div class="container">
            <div class="row">
                <div class="col-xs-9 col-sm-8 form-inline">
                    <div class="form-group">
                        <select class="form-control" id="searchStatus">
                            <option value="0" <?php if ($searchStatus == 0) echo ' selected' ?>>状态</option>
                            <option value="1" <?php if ($searchStatus == 1) echo ' selected' ?>>已使用</option>
                            <option value="2" <?php if ($searchStatus == 2) echo ' selected' ?>>未使用</option>
                        </select>
                    </div>
                </div>

                <div class="col-xs-3 col-sm-4 form-inline">
                    <div class="form-group area-search-control-view">
                        <button class="btn btn-primary"
                                onclick="searchAuthOrder('<?php echo base_url(); ?>',<?php echo $authid; ?> );">查询
                        </button>
                        <button class="btn btn-primary" onclick="cancel('<?php echo base_url(); ?>');">返回
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <div class="container">
            <div class="row">
                <table class="table table-bordered area-result-view">
                    <thead>
                    <tr style="background-color: lightslategrey;">
                        <th sidth="">序号</th>
                        <th>授权码</th>
                        <th>状态</th>
                        <th>订单时间</th>
                        <th width="">操作</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php

                    $authCount = count($authList);
                    for ($i = 0; $i < $authCount; $i++) {
                        $item = $authList[$i];

                        ?>
                        <tr>
                            <td><?php echo $i + 1; ?></td>
                            <td><?php echo $item->code; ?></td>
                            <td><?php echo $item->status == 0 ? '未使用' : '已使用'; ?></td>
                            <td><?php echo $item->ordered_time; ?></td>
                            <td>
                                <?php
                                if ($item->status != 0) {
                                    ?>
                                    <a href="#" onclick="authOrderItem('<?php echo base_url(); ?>',<?php echo $item->id; ?>);"> 订单详情 &nbsp;</a>
                                    <?php
                                }
                                ?>
                            </td>
                        </tr>
                    <?php } ?>
                    </tbody>
                </table>
                <div class="clearfix"></div>
            </div>

        </div>
    </section>
</div>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/auth.js" charset="utf-8"></script>