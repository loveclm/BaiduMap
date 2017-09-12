<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>添加人员</h1>
    </section>

    <section class="content">
        <div class="container">
            <div class="row">
                <!-- left column -->
                <div class="col-md-4">
                        <!-- form start -->
                        <form role="form" id="addUser" action="<?php echo base_url() ?>addNewUser" method="post"
                              role="form">
                            <div class="box-body">
                                <div class="row">
                                    <div class="col-md-12 form-inline">
                                        <div class="form-group" style="margin-bottom: 10px;">
                                            <label for="email">&nbsp;&nbsp;&nbsp;*账 号 &nbsp;:&nbsp;</label>
                                            <input type="text" class="form-control required email" id="email"
                                                   name="email" maxlength="12" value="<?php echo isset($email)?$email:''; ?>">
                                        </div>
                                    </div>
                                    <div class="col-md-12 form-inline">
                                        <div class="form-group" style="margin-bottom: 10px;">
                                            <label for="fname" >&nbsp;&nbsp;&nbsp;*姓 名 &nbsp;:&nbsp;</label>
                                            <input type="text" class="form-control required" id="fname" name="fname"
                                                   maxlength="128" value="<?php echo isset($fname)?$fname:''; ?>">
                                        </div>

                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12 form-inline">
                                        <div class="form-group" style="margin-bottom: 10px;">
                                            <label for="password">&nbsp;&nbsp;&nbsp;*密 码 &nbsp;:&nbsp;</label>
                                            <input type="password" class="form-control required" id="password"
                                                   name="password" maxlength="20" value="<?php echo isset($password)?$password:''; ?>">
                                        </div>
                                    </div>
                                    <div class="col-md-12 form-inline">
                                        <div class="form-group" style="margin-bottom: 10px;">
                                            <label for="cpassword">*确认密码 &nbsp;:&nbsp;</label>
                                            <input type="password" class="form-control required equalTo" id="cpassword"
                                                   name="cpassword" maxlength="20" value="<?php echo isset($cpassword)?$cpassword:''; ?>">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12 form-inline" style="display:none;">
                                        <div class="form-group" style="margin-bottom: 10px;">
                                            <label for="mobile">Mobile Number</label>
                                            <input type="text" class="form-control required digits" id="mobile"
                                                   name="mobile" maxlength="10" value="1234567890">
                                        </div>
                                    </div>
                                    <div class="col-md-12 form-inline">
                                        <div class="form-group" style="margin-bottom: 10px;">
                                            <label for="role">*用户角色 &nbsp;:&nbsp;</label>
                                            <select class="form-control required" id="role" name="role">
                                                <option value="0">请选择</option>
                                                <?php
                                                if (!empty($roles)) {
                                                    $i=0;
                                                    foreach ($roles as $rl) {
                                                        $i++;
                                                        ?>
                                                        <option <?php echo isset($roleId)?($i==$roleId?'selected':''):''; ?>
                                                            value="<?php echo $i; ?>"><?php echo $rl->role ?></option>
                                                        <?php
                                                    }
                                                }
                                                ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- /.box-body -->

                            <div class="">
                                <input type="submit" class="btn btn-primary" value="确认" />
                                <input type="reset" class="btn btn-default" value="取消" onclick="cancel('<?php echo base_url();?>');" />
                            </div>
                        </form>
                </div>
                <div class="col-md-4">
                    <?php
                    $this->load->helper('form');
                    $error = $this->session->flashdata('error');
                    if ($error) {
                        ?>
                        <div class="alert alert-danger alert-dismissable">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                            <?php echo $this->session->flashdata('error'); ?>
                        </div>
                    <?php } ?>
                    <?php
                    $success = $this->session->flashdata('success');
                    if ($success) {
                        ?>
                        <div class="alert alert-success alert-dismissable" style="display:none;">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                            <input type="text" value="<?php echo $this->session->flashdata('success'); ?>"
                                id="success_message" style="display:none;">
                        </div>
                    <?php } ?>

                    <div class="row">
                        <div class="col-md-12">
                            <?php echo validation_errors('<div class="alert alert-danger alert-dismissable">', ' <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button></div>'); ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

</div>
<script src="<?php echo base_url(); ?>assets/js/addUser.js" type="text/javascript"></script>