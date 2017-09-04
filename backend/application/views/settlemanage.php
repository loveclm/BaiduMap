<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            结算列表
        </h1>
    </section>
    <section class="container content">
        <ul class="nav nav-tabs" id="myTab1">
            <li class="<?php echo $showList == '1' ? 'active' : ''; ?>">
                <a data-toggle="tab" href="#tab_content1"
                   aria-expanded="<?php echo $showList == '1' ? true : false; ?>"
                   onclick="searchBuyOrder('<?php echo base_url(); ?>');">线上购买订单结算</a>
            </li>

            <li class="<?php echo $showList == '1' ? '' : 'active'; ?>">
                <a data-toggle="tab" href="#tab_content2"
                   aria-expanded="<?php echo $showList == '1' ? false : true; ?>"
                   onclick="searchAuthOrder('<?php echo base_url(); ?>');">后付款授权码结算</a>
            </li>
        </ul>

        <div class="tab-content">
            <div id="tab_content1" class="tab-pane <?php echo $showList == '1' ? 'active' : ''; ?>">
                <div class="content" style="min-height: 800px;">
                    <div class="container">
                        <div class="row">
                            <div class="col-xs-4 col-sm-4 form-inline">
                                    <div class="form-group area-search-name-view"
                                        style="display: <?php echo $shop_manager_number == ''?'block':'none';?>">
                                        <div class="form-group">
                                            <select class="form-control" id="searchType">
                                                <option value="0" <?php if ($searchType == 0) echo 'selected' ?>>商家账号
                                                </option>
                                                <option value="1" <?php if ($searchType == 1) echo 'selected' ?>>商家名称
                                                </option>
                                            </select>
                                        </div>
                                        <input type="text" id="searchName"
                                               value="<?php echo $searchName == 'ALL' ? '' : $searchName; ?>"
                                               class="form-control">
                                    </div>
                            </div>

                            <div class="col-xs-6 col-sm-6 form-inline">
                                <div class="form-group">
                                    <label>年月份 &nbsp;:&nbsp;</label>
                                    <input class="form-control date-picker" id="startDate" type="text"
                                           data-date-format="yyyy-mm-dd" placeholder="请选择"
                                           value="<?php echo $startDate; ?>">
                                    <label>&nbsp; 至 &nbsp;</label>
                                    <input class="form-control date-picker" id="endDate" type="text"
                                           data-date-format="yyyy-mm-dd" placeholder="请选择"
                                           value="<?php echo $endDate; ?>">
                                </div>
                            </div>

                            <div class="col-xs-2 col-sm-2 form-inline">
                                <div class="form-group area-search-control-view">
                                    <button class="btn btn-primary"
                                            onclick="searchBuyOrder('<?php echo base_url(); ?>');">
                                        查询
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
                                    <th width="100">年月份</th>
                                    <?php
                                    if ($shop_manager_number == '') {
                                        ?>
                                        <th width="150">商家账号</th>
                                        <th width="">商家名称</th>
                                        <?php
                                    } ?>
                                    <th width="">结算金额(元)</th>
                                    <th width="">平台提成(元)</th>
                                    <th width="">实际结算金额(元)</th>
                                    <th width="100">状态</th>
                                    <th width="150">操作</th>
                                </tr>
                                </thead>
                                <tbody id="content_tbl_1">
                                <?php
                                $Count = count($buyList);
                                $sumTotal = 0;
                                $sumSettled = 0;
                                $sumFee = 0;
                                for ($i = 0; $i <= $Count; $i++) {
                                    if (!isset($buyList[$i])) continue;
                                    $item = $buyList[$i];
                                    $shops = $item['shops'];
                                    $j = 0;
                                    ?>
                                    <tr>
                                        <td rowspan="<?php echo count($shops) > 0 ? count($shops) : '1'; ?>"
                                            style="vertical-align: middle;">
                                            <?php echo $item['month_name']; ?>
                                        </td>
                                        <?php foreach ($shops as $sh) {
                                            $j++;
                                            $price = floatval($sh->address_2->price);
                                            $status = $sh->status != "" ? $sh->status->status : "";
                                            $settle = $price * floatval($sh->discount_rate);
                                            $fee = $price - $settle;
                                            $sumTotal += $price;
                                            $sumFee += $fee;
                                            $sumSettled += $settle;
                                            ?>
                                            <?php echo ($j != 1) ? '<tr>' : ''; ?>

                                            <?php
                                            if ($shop_manager_number == '') {
                                                ?>
                                                <td><?php echo $sh->phonenumber; ?></td>
                                                <td><?php echo $sh->name; ?></td>
                                                <?php
                                            } ?>
                                            <td><?php echo $price; ?></td>
                                            <td><?php echo $fee; ?></td>
                                            <td><?php echo $settle; ?></td>
                                            <td><?php
                                                echo $status == 0 ? '未结算' : '已结算'; ?>
                                            </td>
                                            <td>
                                                <a href="#" onclick="settleBuyDetail('<?php echo base_url(); ?>','<?php echo $sh->id; ?>');">查看订单
                                                    &nbsp;</a>
                                                <?php
                                                if ($status == 0) {
                                                    ?>
                                                    <a href="#"
                                                       onclick="showConfirmBuy('<?php echo $item['month_name']; ?>','<?php echo $sh->id; ?>');">
                                                        结算
                                                        &nbsp;</a>
                                                    <?php
                                                }
                                                ?>
                                            </td>
                                            <?php echo ($j != 1) ? '</tr>' : ''; ?>
                                            <?php
                                        } ?>
                                    </tr>
                                    <?php
                                }
                                ?>
                                <tr style="background: #fd8f23;">
                                    <td>合计</td>
                                    <?php
                                    if ($shop_manager_number == '') {
                                        ?>
                                        <td>---</td>
                                        <td>---</td>
                                        <?php
                                    } ?>
                                    <td><?php echo $sumTotal; ?></td>
                                    <td><?php echo $sumFee; ?></td>
                                    <td><?php echo $sumSettled; ?></td>
                                    <td>---</td>
                                    <td>---</td>
                                </tr>
                                </tbody>
                            </table>
                            <div class="form-group">
                                <div id="custom-confirm-deploy-view" style="display:none;">
                                    <p>
                                        是否结算？
                                    </p>

                                    <div class="form-group">
                                        <button class="btn btn-default"
                                                onclick="$('#custom-confirm-deploy-view').hide();">取消
                                        </button>
                                        <button class="btn btn-primary"
                                                onclick="performBuySettle('<?php echo base_url(); ?>', 1);">确定
                                        </button>
                                        <input id="current-month-name" style="display: none;"/>
                                        <input id="current-shop-id" style="display: none;"/>
                                    </div>
                                </div>
                            </div>
                            <div class="clearfix"></div>
                        </div>

                    </div>
                </div>
            </div>

            <div id="tab_content2" class="tab-pane <?php echo $showList == '1' ? '' : 'active'; ?>">
                <div class="content" style="min-height: 800px;">
                    <div class="container">
                        <div class="row">
                            <div class="col-xs-4 col-sm-4 form-inline">
                                    <div class="form-group area-search-name-view"
                                         style="display: <?php echo $shop_manager_number == ''?'block':'none'; ?>">
                                        <div class="form-group">
                                            <select class="form-control" id="searchTypeAuth">
                                                <option value="0" <?php if ($searchTypeAuth == 0) echo ' selected'; ?> >
                                                    商家账号
                                                </option>
                                                <option value="1" <?php if ($searchTypeAuth == 1) echo ' selected'; ?> >
                                                    商家名称
                                                </option>
                                            </select>
                                        </div>
                                        <input type="text" id="searchNameAuth"
                                               value="<?php echo $searchNameAuth == 'ALL' ? '' : $searchNameAuth; ?>"
                                               class="form-control">
                                    </div>
                            </div>

                            <div class="col-xs-6 col-sm-6 form-inline">
                                <div class="form-group">
                                    <label>年月份 &nbsp;:&nbsp;</label>
                                    <input class="form-control date-picker" id="startDateAuth" type="text"
                                           data-date-format="yyyy-mm-dd" placeholder="请选择"
                                           value="<?php echo $startDate; ?>">
                                    <label>&nbsp; 至 &nbsp;</label>
                                    <input class="form-control date-picker" id="endDateAuth" type="text"
                                           data-date-format="yyyy-mm-dd" placeholder="请选择"
                                           value="<?php echo $endDate; ?>">
                                </div>
                            </div>

                            <div class="col-xs-2 col-sm-2 form-inline">
                                <div class="form-group area-search-control-view">
                                    <button class="btn btn-primary"
                                            onclick="searchAuthOrder('<?php echo base_url(); ?>');">查询
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
                                    <th width="">年月份</th>
                                    <?php
                                    if ($shop_manager_number == '') {
                                        ?>
                                        <th width="">商家账号</th>
                                        <th width="">商家名称</th>
                                        <?php
                                    } ?>
                                    <th width="">使用授权码</th>
                                    <th width="">结算金额</th>
                                    <th width="">状态</th>
                                    <th width="">操作</th>
                                </tr>
                                </thead>
                                <tbody id="content_tbl_2">
                                <?php
                                $Count = count($authList);
                                $sumTotal = 0;
                                $sumCount = 0;
                                for ($i = 0; $i <= $Count; $i++) {
                                    if (!isset($authList[$i])) continue;
                                    $item = $authList[$i];
                                    $shops = $item['shops'];
                                    $j = 0;
                                    ?>
                                    <tr>
                                        <td rowspan="<?php echo count($shops) > 0 ? count($shops) : '1'; ?>"
                                            style="vertical-align: middle;">
                                            <?php echo $item['month_name']; ?>
                                        </td>
                                        <?php foreach ($shops as $sh) {
                                            $j++;
                                            $price = floatval($sh->address_2->price);
                                            $status = $sh->status != "" ? $sh->status->status : "";
                                            if (isset($sh->address_2->codeCount))
                                                $codeCount = floatval($sh->address_2->codeCount);
                                            else
                                                $codeCount = 0;
                                            $sumTotal += $price;
                                            $sumCount += $codeCount;
                                            ?>
                                            <?php echo ($j != 1) ? '<tr>' : ''; ?>
                                            <?php
                                            if ($shop_manager_number == '') {
                                                ?>
                                                <td><?php echo $sh->phonenumber; ?></td>
                                                <td><?php echo $sh->name; ?></td>
                                                <?php
                                            } ?>
                                            <td><?php echo $codeCount; ?></td>
                                            <td><?php echo $price; ?></td>
                                            <td><?php
                                                echo $status == 0 ? '未结算' : '已结算'; ?>
                                            </td>
                                            <td>
                                                <a href="#" onclick="settleAuthDetail('<?php echo base_url(); ?>','<?php echo $sh->id; ?>');">查看订单
                                                    &nbsp;</a>
                                                <?php
                                                if ($status == 0) {
                                                    ?>
                                                    <a href="#"
                                                       onclick="showConfirmAuth('<?php echo $item['month_name']; ?>','<?php echo $sh->id; ?>');">
                                                        结算
                                                        &nbsp;</a>
                                                    <?php
                                                }
                                                ?>
                                            </td>
                                            <?php echo ($j != 1) ? '</tr>' : ''; ?>
                                            <?php
                                        } ?>
                                    </tr>
                                <?php } ?>
                                <tr style="background: #fd8f23;">
                                    <td>合计</td>
                                    <?php
                                    if ($shop_manager_number == '') {
                                        ?>
                                        <td>---</td>
                                        <td>---</td>
                                        <?php
                                    } ?>
                                    <td><?php echo $sumCount; ?></td>
                                    <td><?php echo $sumTotal; ?></td>
                                    <td>---</td>
                                    <td>---</td>
                                </tr>
                                </tbody>
                            </table>
                            <div class="form-group">
                                <div id="custom-generate-auth-view" style="display:none;">
                                    <div class="form-group">
                                        <label>是否结算？</label>
                                    </div>
                                    <div class="form-group">
                                        <button class="btn btn-default"
                                                onclick="$('#custom-generate-auth-view').hide();">取消
                                        </button>
                                        <button class="btn btn-primary" onclick="showMoney();">确定</button>
                                    </div>
                                </div>

                                <div id="custom-generate-auth-count-view" style="display:none;">
                                    <div class="form-group">
                                        <label>结算金额 &nbsp; </label>
                                        <input id="auth-count"/> &nbsp; 元
                                    </div>

                                    <div class="form-group">
                                        <button class="btn btn-default"
                                                onclick="$('#custom-generate-auth-count-view').hide();">取消
                                        </button>
                                        <button class="btn btn-primary"
                                                onclick="performAuthSettle('<?php echo base_url(); ?>');">确定
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div class="clearfix"></div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/settle.js" charset="utf-8"></script>