<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            <?php echo isset($course) ? '编辑' : '新增';?>旅游线路
        </h1>
    </section>

    <section class="content">
        <div class="container">
            <div class="row custom-info-row">
                <label class="col-sm-2">旅游线路名称:</label>
                <input type="text" class="col-sm-4" id="coursename" maxlength="20"
                       value="<?php echo isset($course) ? $course->name : '';?>" />
                <input type="text" class="col-sm-4" id="courseprice" maxlength="10"
                       value="<?php echo isset($course) ? $course->price : '';?>" style="display: none;"/>
                <div id="custom-error-coursename" class="custom-error col-sm-4" style="display: none;">线路名称要不超过10个字符</div>
            </div>
            <div class="row custom-info-row">
                <label class="col-sm-2">旅游线路折扣比率:</label>
                <input style="text-align: right;" type="text"
                       class="col-sm-1" id="courserate"
                       value="<?php echo isset($course) ? (floatval($course->discount_rate)*100) : '';?>">
                <label>%</label>
            </div>
        </div>
        <div class="container">
            <div class="row">
                <div class="col-sm-2">
                    <label>选择景区：</label>
                </div>
                <div class="col-sm-10 custom-course-itemlist-view">
                    <div class="col-sm-5" style="height: 100%; padding: 10px;">
                        <div class="area-list-view">
                            <input class="btn btn-default" id="course-search" placeholder="搜索景区"/>
                            <a href="#" class="btn btn-default" onclick="findAreaInList('<?php echo base_url(); ?>');">
                                <i class="fa fa-search"></i>
                            </a>
<!--                            <input class"fa fa-search" type="button" value="" onclick=""/>-->
                            <div class="form-group">
                                <ul id="courseList">
                                    <?php
                                    $areaCount = count($areaList);
                                    for($i = 0; $i < $areaCount; $i++) {
                                        $area = $areaList[$i];
                                        ?>
                                        <li class="custom-areaitem" id="areaitem-<?php echo $area->id;?>" onclick="selectCourse(<?php echo $area->id;?>);">
                                            <div id="areatitle-<?php echo $area->id;?>"><?php echo $area->name;?></div>
                                            <div id="areaprice-<?php echo $area->id;?>" style="display: none;"><?php echo $area->price;?></div>
                                        </li>
                                    <?php
                                    }
                                    ?>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-2">
                        <div class="custom-course-itemlist-control">
                            <div class="form-group">
                                <input type="button" value="添加>>" onclick="addAreaToCourse();"/>
                            </div>
                            <div class="form-group">
                                <input type="button" value="<<删除" onclick="removeAreaFromCourse();"/>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-5" style="height: 100%; padding: 10px;">
                        <div class="course-item-view">

                            <ul id="courseItems">
                                <?php
                                if(isset($course)){
                                    $itemList = json_decode($course->point_list);
                                    $itemCount = count($itemList);
                                    for($i = 0; $i < $itemCount; $i++) {
                                        $item = $itemList[$i];
                                        $areaTmp=$this->area_model->getAreaById($item->id);
                                        if(count($areaTmp)==0) $item->name=$item->name.'被删除. 请删除这个线路，并重新创建它.';
                                        ?>
                                        <li class="custom-courseitem" data-id="<?php echo $item->id;?>"
                                            style="color:<?php echo count($areaTmp)!=0?'black':'red';?>"
                                            onclick="selectedCourseItem(this);">
                                            <div><?php echo $item->name;?></div>
                                        </li>
                                        <?php
                                    }
                                }
                                ?>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="container">
            <div class="row">
                <div class="col-sm-offset-2 custom-course-control-view">
                    <input type="button" class="btn btn-default" onclick="cancel('<?php echo base_url(); ?>');" value="取消" />
                    <input type="button" class="btn btn-primary" onclick="processCourse('<?php echo base_url(); ?>' ,
                        '<?php echo isset($course)? $course->id: 0;?>');" value="确认" />
                </div>
            </div>
        </div>
    </section>
</div>

<!-- Course Management JS-->
<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/course.js" charset="utf-8"></script>