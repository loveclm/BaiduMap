<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>订单详情</h1>
    </section>

    <section class="content">
        <div class="container">
            <div class="row custom-info-row">
                <label class="col-sm-2">&nbsp;&nbsp;&nbsp;订单编号：</label>
                <label class="col-sm-4"><?php echo isset($orderitem) ? $orderitem->number : '';?></label>
            </div>
            <div class="row custom-info-row">
                <label class="col-sm-2">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;授权码：</label>
                <label class="col-sm-4"><?php echo isset($orderitem) ? $orderitem->code : '';?></label>
            </div>
            <div class="row custom-info-row">
                <label class="col-sm-2">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;手机号：</label>
                <label class="col-sm-4"><?php echo isset($orderitem) ? $orderitem->mobile : '';?></label>
            </div>
            <div class="row custom-info-row">
                <label class="col-sm-2">&nbsp;&nbsp;&nbsp;订单时间：</label>
                <label class="col-sm-4"><?php echo isset($orderitem) ? $orderitem->ordered_time : '';?></label>
            </div>
            <div class="row custom-info-row">
                <label class="col-sm-2">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;状态：</label>
                <label class="col-sm-4"><?php echo isset($orderitem) ? ($orderitem->status=='0'?'未使用':'使用中') : '';?></label>
            </div>
        </div>
        <div class="container">
            <div class="row">
                <div class="col-sm-offset-2 custom-course-control-view">
                    <input type="button" class="btn btn-primary"
                           onclick="cancel('');" value="返回" />
                </div>
            </div>
        </div>
    </section>
</div>

<!-- Course Management JS-->
<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/auth.js" charset="utf-8"></script>