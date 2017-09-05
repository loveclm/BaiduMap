<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            用户列表
        </h1>
    </section>
    <section class="container content">
        <div class="content" style="min-height: 800px;">
            <div class="container">
                <div class="row">
                    <div class="col-xs-8 col-sm-8 form-inline">
                        <div class="form-group area-search-name-view">
                            <div class="form-group">
                                <select class="form-control" id="searchType">
                                    <option value="0" <?php if ($searchType == 0) echo 'selected' ?>>手机号
                                    </option>
                                </select>
                            </div>
                            <input type="text" id="searchName"
                                   value="<?php echo $searchName == 'ALL' ? '' : $searchName; ?>"
                                   class="form-control">
                        </div>
                    </div>

                    <div class="col-xs-4 col-sm-4 form-inline">
                        <div class="form-group area-search-control-view">
                            <button class="btn btn-primary"
                                    onclick="searchUserList('<?php echo base_url(); ?>');">
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
                            <th width="">序号</th>
                            <th width="">手机号</th>
                            <th width="">购买订单</th>
                            <th width="">验证订单</th>
                            <th width="">总消费金额(元)</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php

                        $Count = count($userList);
                        $sumPaid = $sumBuy = $sumAuth = $Auth = $Buy = 0;
                        if ($userList != '') {
                            if ($shop_manager_number != '') {
                                $shop = $this->shop_model->getShopIdByNumber($shop_manager_number);
                                $shopid = $shop->id;
                            } else {
                                $shopid = '';
                            }
                            $j=0;
                            for ($i = 0; $i < $Count; $i++) {
                                $item = $userList[$i];
                                $mobile = $item->mobile;
                                $Buy = $this->collection_model->getBuyOrderPaid($mobile, $shopid);
                                $sumBuy += $Buy;
                                $Auth = $this->collection_model->getAuthOrderPaid($mobile, $shopid);
                                $sumAuth += $Auth;
                                if ($Buy == 0 && $Auth == 0) continue;
                                $j++;
                                $Paid = $this->order_model->getMyOrderInfos($mobile);
                                $Paid = $Paid == '-1' ? '0' : $Paid['total_price'];
                                $sumPaid += $Paid;
                                ?>
                                <tr>
                                    <td><?php echo $j; ?></td>
                                    <td><?php echo $item->mobile; ?></td>
                                    <td><?php echo $Buy; ?></td>
                                    <td><?php echo $Auth; ?></td>
                                    <td><?php echo $Paid; ?></td>
                                </tr>
                                <?php
                            }
                        }
                        ?>
                        <tr style="background: #fd8f23;">
                            <td>合计</td>
                            <td>---</td>
                            <td><?php echo $sumBuy; ?></td>
                            <td><?php echo $sumAuth; ?></td>
                            <td><?php echo $sumPaid; ?></td>
                        </tr>
                        </tbody>
                    </table>
                    <div class="clearfix"></div>
                </div>

            </div>
        </div>
    </section>
</div>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/usercollection.js" charset="utf-8"></script>