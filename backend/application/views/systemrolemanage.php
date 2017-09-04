<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            角色列表
        </h1>
    </section>
    <section class="content" style="min-height: 800px;">
        <div class="container">
            <div class="row">
                <div class="col-xs-12">
                    <div class="col-xs-12 box-header">
                        <a class="btn btn-primary" href="#" onclick="$('#custom-generate-auth-count-view').show();">
                            添加
                        </a>
                    </div>
                </div>
            </div>
            <div class="container">
                <div class="row">
                    <table class="table area-result-view table-bordered table-hover">
                        <thead>
                        <tr style="background-color: lightslategrey;">
                            <th width="100">序号</th>
                            <th>用户角色</th>
                            <th width="150">功能设置</th>
                            <th width="250">操作</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        if (!empty($userRecords)) {
                            $i=0;
                            foreach ($userRecords as $record) {
                                $i++;
                                ?>
                                <tr>
                                    <td><?php echo $i ?></td>
                                    <td><?php echo $record->role ?></td>
                                    <div id="permission<?php echo $record->roleId ?>"
                                         style="display: none"><?php echo isset($record->permission)?$record->permission:''; ?></div>
                                    <td class="text-center">
                                        <a href="#"
                                           onclick="showRoleEdit('<?php echo $record->roleId; ?>');">
                                            操作设置 &nbsp;
                                        </a>
                                    </td>
                                    <td class="text-center">
                                        <a href="#" data-userid="<?php echo $record->roleId; ?>"
                                           onclick="confirmDelete('<?php echo $record->roleId; ?>')">
                                            删除 &nbsp;
                                        </a>
                                    </td>
                                </tr>
                                <?php
                            }
                        }
                        ?>
                        </tbody>
                    </table>
                    <div class="form-group">
                        <div id="custom-confirm-delete-view" style="display:none;">
                            <div class="form-group">
                                <label>确定删除？</label>
                            </div>
                            <div class="form-group">
                                <button class="btn btn-default" onclick="$('#custom-confirm-delete-view').hide();">取消</button>
                                <button class="btn btn-primary" onclick="deleteRole('<?php echo base_url(); ?>');">确定</button>
                                <div id="roleId" style="display: none;"></div>
                            </div>
                        </div>
                        <div id="custom-generate-auth-count-view" style="display:none;">
                            <div class="form-group form-inline">
                                <label>&nbsp;&nbsp;*角色名称 &nbsp;: </label>
                                <input id="rolename" type="text" maxlength="20"/>
                            </div>
                            <div class="form-group">
                                <button class="btn btn-default" onclick="$('#custom-generate-auth-count-view').hide();">取消</button>
                                <button class="btn btn-primary" onclick="addRole('<?php echo base_url(); ?>' );">确认</button>
                            </div>
                            <div class="form-group alert-danger" id="alertmsg" style="display: none;"></div>
                        </div>
                        <div id="custom-generate-auth-view" style="display:none;text-align: left">
                            <div class="form-group" style="text-align: center">
                                <label>功能设置</label>
                            </div>
                            <div class="form-group">
                                <div style="padding-left: 20px;"><input type="checkbox" id="manage10" value="0">&nbsp;景区管理
                                </div>
                                <div style="padding-left: 20px;"><input type="checkbox" id="manage20" value="0">&nbsp;旅游线路管理
                                </div>
                                <div style="padding-left: 20px;"><input type="checkbox" id="manage30" value="0">&nbsp;商家管理
                                </div>
                                <div style="padding-left: 20px;"><input type="checkbox" id="manage40" value="0">&nbsp;授权码管理
                                </div>
                                <div style="padding-left: 20px;"><input type="checkbox" id="manage50" value="0">&nbsp;订单管理
                                </div>
                                <div style="padding-left: 20px;"><input type="checkbox" id="manage60" value="0">&nbsp;财务管理
                                </div>
                                <div style="padding-left: 40px;"><input type="checkbox" id="manage61" value="0">&nbsp;结算管理
                                </div>
                                <div style="padding-left: 40px;"><input type="checkbox" id="manage62" value="0">&nbsp;收益统计
                                </div>
                                <div style="padding-left: 20px;"><input type="checkbox" id="manage70" value="0">&nbsp;用户管理
                                </div>
                                <div style="padding-left: 20px;"><input type="checkbox" id="manage80" value="0">&nbsp;系统管理
                                </div>
                                <div style="padding-left: 20px;"><input type="checkbox" id="manage90" value="0">&nbsp;修改密码
                                </div>
                            </div>
                            <div class="form-group" style="text-align: center">
                                <button class="btn btn-default" onclick="$('#custom-generate-auth-view').hide();">取消</button>
                                <button class="btn btn-primary" onclick="updateRole('<?php echo base_url(); ?>', '<?php echo $role; ?>' );">确认</button>
                            </div>
                            <div class="form-group alert-danger" id="alertmsg" style="display: none;"></div>
                        </div>
                        <div id="savingId" style="display: none;"></div>

                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/common.js" charset="utf-8"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/sysrole.js" charset="utf-8"></script>
<script type="text/javascript">
    jQuery(document).ready(function () {
        $('.treeview-menu').show();
        jQuery('ul.pagination li a').click(function (e) {
            e.preventDefault();
            var link = jQuery(this).get(0).href;
            var value = link.substring(link.lastIndexOf('/') + 1);
            jQuery("#searchList").attr("action", baseURL + "roleListing/" + value);
            jQuery("#searchList").submit();
        });
    });

</script>
