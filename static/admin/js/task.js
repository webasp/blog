function create_modal(id, size){
    if(!arguments[0]){
        var d = new Date();
        var id = 'modal' + d.getFullYear() + d.getMonth() + d.getDate() + d.getHours() + d.getMinutes() + d.getSeconds() + d.getMilliseconds() + Math.floor(Math.random()*(9999-1000)+1000);
    }

    if(!arguments[1]){
        var size = '';
    }

    var div = $('<div class="modal fade" id="'+id+'" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true"></div>');
    div.html('<div class="modal-dialog '+size+'"><div class="modal-content"></div></div>');
    div.appendTo('body');

    div.on('hidden.bs.modal',function(e){
        $(this).remove();
    });

    div.modal('show');

    div.set_html = function(data){
        if(!arguments[0]){
            return $(this).find('.modal-content').html();
        }
        $(this).find('.modal-content').html(data);
    }

    return div;
};

// 选择日记类型
$(".diary-type label").click(function () {
    $(this).addClass('active').siblings().removeClass('active');
});

// 查询今日任务
var getTodayTask  = function (today) {
    if(today){
        var url = 'task/task/'+today;
    }else{
        var url = 'task/task/';
    }

    $.ajax({
        url: url,
        type: "GET",
        success: function(html) {
            $("#today-task").html(html);
        },
        error: function(request) {
            alert("Connection error");
        }
    });
};

// 创建计划
$("form[name=okr-add]").submit(function(){
    $.ajax({
        url:'okr/add',
        type: "POST",
        data:$(this).serialize(),
        success: function(result) {

            if(result.status === 1){
                toastr.success(result.info,function () {
                    $("#add-okr").modal('hide');
                    setTimeout(function (e) {
                        getOkrTask(result.type);
                    },1000);
                });
            }

            if(result.status === 0){
                toastr.error(result.info);
            }

        },
        error: function(request) {
            alert("Connection error");
        }
    });
    $(this).find('input[name=title]').val('');
    return false;
});

// 编辑计划
$(document).on('click','span[data-toggle=edit-okr]',function(){
    var id = $(this).data('id');
    $.ajax({
        url:'okr/'+id+'/edit',
        type: "GET",
        success: function(result) {
            var model = create_modal('okr-edit','modal-sm');
            model.set_html(result);
        },
        error: function(request) {
            alert("Connection error");
        }
    });
    return false;
});

// 查看计划
$(document).on('click','span[data-toggle=okr]',function(){
    var id = $(this).data('id');
    $.ajax({
        url:'okr/'+id,
        type: "GET",
        success: function(result) {
            var model = create_modal('people','modal-lg');
            model.set_html(result);
        },
        error: function(request) {
            alert("Connection error");
        }
    });
    return false;
});

// 更新计划
$(document).on('submit','form[name=okr-update]',function(){
    var id = $(this).find('input[name=id]').val();
    $.ajax({
        url:'okr/'+id+'/update',
        type: "POST",
        data:$(this).serialize(),
        success: function(result) {
            if(result.status === 1){
                toastr.success(result.info,function () {
                    $("#okr-edit").modal('hide');
                    setTimeout(function (e) {
                        getOkrTask(result.type);

                    },1000);
                });
            }

            if(result.status === 0){
                toastr.success(result.info);
            }

        },
        error: function(request) {
            alert("Connection error");
        }
    });
    return false;
});

// 删除计划
$(document).on('click','button[data-toggle=okr-del]',function(){

    if(!confirm("确定删除计划吗？")){
        return false;
    }
    if(!confirm("无法恢复的哟")){
        return false;
    }

    var id = $(this).data('id');
    $.ajax({
        url:'okr/'+id+'/del',
        type: "GET",
        success: function(result) {
            if(result.status === 1){
                toastr.success(result.info,function () {
                    $("#okr-edit").modal('hide');
                    setTimeout(function (e) {
                        getOkrTask(result.type);
                    },1000);
                });
            }

            if(result.status === 0){
                toastr.success(result.info);
            }
        },
        error: function(request) {
            alert("Connection error");
        }
    });
    return false;
});

$("form[name=okr-update]").submit(function(){
    $.ajax({

        type: "POST",
        data:$(this).serialize(),
        success: function(result) {

        },
        error: function(request) {
            alert("Connection error");
        }
    });
    $(this).find('input[name=title]').val('');
    return false;
});




// 创建今日任务
$("form[name=task-add]").submit(function(){
    $.ajax({
        url:'task/add',
        type: "POST",
        data:$(this).serialize(),
        success: function(result) {
            toastr.success(result.info,function () {
                getTodayTask();
                $("#add-task").modal('hide');
            });

        },
        error: function(request) {
            alert("Connection error");
        }
    });
    $(this).find('input[name=title]').val('');
    return false;
});

// 更新今日任务
$(document).on('submit','form[name=task-update]',function(){
    var id = $(this).find('input[name=task-id]').val();
    $.ajax({
        url:'task/'+id+'/update',
        type: "POST",
        data:$(this).serialize(),
        success: function(result) {
            toastr.success(result.info,function () {
                getTodayTask();
                $("#updateTask").modal('hide');
            });

        },
        error: function(request) {
            alert("Connection error");
        }
    });
    return false;
});
// 取消
$(document).on('click','button[data-toggle=TodayTaskCancel]',function(){
    var id = $(this).data('id');
    $.ajax({
        url:'task/'+id+'/cancel',
        type: "GET",
        success: function(result) {
            toastr.success(result.info,function () {
                getTodayTask();
                $("#updateTask").modal('hide');
            });

        },
        error: function(request) {
            alert("Connection error");
        }
    });
    return false;
});

// 删除
$(document).on('click','button[data-toggle=TodayTaskDel]',function(){
    var id = $(this).data('id');
    $.ajax({
        url:'task/'+id+'/del',
        type: "GET",
        success: function(result) {
            toastr.success(result.info,function () {
                getTodayTask();
                $("#updateTask").modal('hide');
            });

        },
        error: function(request) {
            alert("Connection error");
        }
    });
    return false;
});

// 修改项目任务
$(document).on('click','span[data-toggle=edit-project-task]',function(){
    var id = $(this).data('id');
    $.ajax({
        url:'projectTask/'+id+'/edit',
        type: "GET",
        success: function(result) {
            var model = create_modal('edit-project-task','modal-sm');
            model.set_html(result);
        },
        error: function(request) {
            alert("Connection error");
        }
    });
    return false;
});

// 更新项目任务
$(document).on('submit','form[name=project-task-update]',function(){
    var id = $(this).find('input[name=id]').val();
    $.ajax({
        url:'projectTask/'+id+'/update',
        type: "POST",
        data:$(this).serialize(),
        success: function(result) {
            if(result.status === 1){
                toastr.success(result.info,function () {
                    $("#edit-project-task").modal('hide');
                });
            }

            if(result.status === 0){
                toastr.success(result.info,function () {
                    //location.replace(location.href);
                });
            }

        },
        error: function(request) {
            alert("Connection error");
        }
    });
    return false;
});
// 删除项目任务
$(document).on('click','button[data-toggle=project-task-del]',function(){
    var id = $(this).data('id');
    if(!confirm("确定删除任务吗？")){
        return false;
    }
    if(!confirm("删除数据无法恢复的！")){
        return false;
    }
    $.ajax({
        url:'projectTask/'+id+'/del',
        type: "GET",
        success: function(result) {
            if(result.status === 1){
                toastr.success(result.info,function () {
                    location.replace(location.href);
                });
            }

            if(result.status === 0){
                toastr.success(result.info,function () {
                    location.replace(location.href);
                });
            }
        },
        error: function(request) {
            alert("Connection error");
        }
    });
    return false;
});



// 创建项目任务
$("form[name=project-task]").submit(function(){
    $.ajax({
        url:'task/project/add',
        type: "POST",
        data:$(this).serialize(),
        success: function(result) {
            if(result.status === 1){
                toastr.success(result.info,function () {
                    $("#add-project-task").modal('hide');
                    location.replace(location.href);
                });
            }
            if(result.status === 0){
                toastr.error(result.info);
            }

        },
        error: function(request) {
            alert("Connection error");
        }
    });
    $(this).find('input[name=title]').val('');
    return false;
});

$(document).on('mouseover','#today-task ul li',function(){
    $(this).find('span.editTask').show();
});
$(document).on('mouseout','#today-task ul li',function(){
    $(this).find('span.editTask').hide();
});

// 完成今日任务
$(document).on('click','span[data-target=complete]',function(){
    $(this).attr("disabled", true);
    $(this).text('处理中..');
    var id = $(this).data('id');
    $.ajax({
        url:'task/complete/'+id,
        type: "GET",
        success: function(result) {
            if(result.status === 1){
                toastr.success(result.info);
                setTimeout(function (e) {
                    getTodayTask();
                },1000);
            }

            if(result.status === 0){
                toastr.error(result.info);
            }
        },
        error: function(request) {
            toastr.error('Connection error');
        }
    });
    return false;
});

$(document).on('click','span[data-target=updateTask]',function(){
    var id = $(this).data('id');
    $.ajax({
        url:'task/task/edit/'+id+'',
        type: "GET",
        success: function(result) {
            var model = create_modal('updateTask','modal-sm');
            model.set_html(result);
        },
        error: function(request) {
            alert("Connection error");
        }
    });
    return false;
});

// 选择星期
$(document).on('click','#week button',function(){
    var today = $(this).data('today');
    getTodayTask(today);
    return false;
});


// 添加项目任务
$("form[name=add-project-item]").submit(function(){
    $.ajax({
        url:'/admin/project/item/add',
        type: "POST",
        data:$(this).serialize(),
        success: function(result) {
            if(result.status === 1){
                toastr.success(result.info,function () {
                    $("#add-project-item").modal('hide');
                    setTimeout(function () {
                        getProjectTaskList(result.pid);
                    },1000);
                });
            }

            if(result.status === 0){

                toastr.error(result.info,function () {
                    $("#add-project-task").modal('hide');
                });
            }
        },
        error: function(request) {
            alert("Connection error");
        }
    });
    $(this).find('input[name=title]').val('');
    $(this).find('textarea[name=content]').val('');
    return false;
});
// 打开修改窗口
$(document).on('click','span[data-toggle=edit-project-item]',function(){
    var id = $(this).data('id');
    $.ajax({
        url:'/admin/project/task/'+id+'/edit',
        type: "GET",
        success: function(result) {
            var model = create_modal('edit-project-item');
            model.set_html(result);
        },
        error: function(request) {
            alert("Connection error");
        }
    });
    return false;
});
// 更新项目任务
$(document).on('submit','form[name=edit-project-item]',function(){
    var id = $(this).find('input[name=id]').val();
    $.ajax({
        url:'/admin/project/item/'+id+'/update',
        type: "POST",
        data:$(this).serialize(),
        success: function(result) {
            if(result.status === 1){
                toastr.success(result.info,function () {
                    $("#edit-project-item").modal('hide');
                    setTimeout(function (e) {
                        location.replace(location.href);
                    },1000);
                });
            }

            if(result.status === 0){
                toastr.success(result.info,function () {
                    //location.replace(location.href);
                });
            }

        },
        error: function(request) {
            alert("Connection error");
        }
    });
    return false;
});
// 删除项目任务
$(document).on('click','span[data-toggle=project-item-del]',function(){
    var id = $(this).data('id');
    if(!confirm("确定删除任务吗？")){
        return false;
    }
    $.ajax({
        url:'/admin/project/item/'+id+'/del',
        type: "GET",
        success: function(result) {
            if(result.status === 1){
                toastr.success(result.info,function () {
                    location.replace(location.href);
                });
            }

            if(result.status === 0){
                toastr.success(result.info,function () {
                    location.replace(location.href);
                });
            }
        },
        error: function(request) {
            alert("Connection error");
        }
    });
    return false;
});
// 项目任务备注
var getprojectItemReplay = function (fid,pid) {
    if(pid){
        var url = '/admin/project/item/replay/'+fid+'/'+pid;
    }else{
        var url = '/admin/project/item/replay/'+fid;
    }

    $.ajax({
        url: url,
        type: "GET",
        success: function(html) {
            $("#project-task-replay").html(html);
        },
        error: function(request) {
            alert("Connection error");
        }
    });
}
// 提交项目备注
$(document).on('submit','form[name=add-project-replay]',function(){
    var fid = $(this).data('fid');
    $.ajax({
        url:'/admin/project/replay',
        type: "POST",
        data:$(this).serialize(),
        success: function(result) {
            if(result.status === 1){
                toastr.success(result.info,function () {
                    setTimeout(function (e) {
                        getprojectItemReplay(result.fid,result.pid);
                    },1000);
                });
            }

            if(result.status === 0){
                toastr.success(result.info,function () {
                    //location.replace(location.href);
                });
            }

        },
        error: function(request) {
            alert("Connection error");
        }
    });
    $(this).find('textarea[name=content]').val('');
    return false;
});
// 删除
$(document).on('click','span[data-toggle=project-replay-del]',function(){
    var id = $(this).data('id');
    if(!confirm("确定删除吗？")){
        return false;
    }
    $.ajax({
        url:'/admin/project/replay/'+id+'/del',
        type: "GET",
        success: function(result) {
            if(result.status === 1){
                toastr.success(result.info,function () {
                    setTimeout(function (e) {
                        getprojectItemReplay(result.fid,result.pid);
                    },1000);
                });
            }

            if(result.status === 0){
                toastr.error(result.info);
            }
        },
        error: function(request) {
            alert("Connection error");
        }
    });
    return false;
});

// 完成项目任务
$(document).on('click','button[data-toggle=project-item-complete]',function(){
    var id = $(this).data('id');
    var pid = $(this).data('pid');
    $.ajax({
        url:'/admin/project/item/'+pid+'/'+id+'/complete',
        type: "GET",
        success: function(result) {
            if(result.status === 1){
                toastr.success(result.info,function () {
                    setTimeout(function (e) {
                        getProjectTaskList(result.pid);
                    },1000);
                });
            }

            if(result.status === 0){
                toastr.error(result.info);
            }
        },
        error: function(request) {
            alert("Connection error");
        }
    });
    return false;
});

// 取消项目任务
$(document).on('click','span[data-toggle=project-item-cancel]',function(){
    var id = $(this).data('id');
    var pid = $(this).data('pid');
    $.ajax({
        url:'/admin/project/item/'+pid+'/'+id+'/cancel',
        type: "GET",
        success: function(result) {
            if(result.status === 1){
                toastr.success(result.info,function () {
                    setTimeout(function (e) {
                        getProjectTaskList(result.pid);
                    },1000);
                });
            }

            if(result.status === 0){
                toastr.error(result.info);
            }
        },
        error: function(request) {
            alert("Connection error");
        }
    });
    return false;
});

var getProjectTaskList = function (pid) {
    $.ajax({
        url: '/admin/project/item/'+pid,
        type: "GET",
        success: function(html) {
            $("#project-task-list").html(html);
        },
        error: function(request) {
            alert("Connection error");
        }
    });
}

var getOkrTask = function (type) {
    if(type){
        var url = '/admin/okr/task/'+type;
    }else{
        var url = '/admin/okr/task';
    }
    $.ajax({
        url: url,
        type: "GET",
        success: function(html) {
            $("#okr-list").html(html);
        },
        error: function(request) {
            alert("Connection error");
        }
    });
}

// 添加计划项目
$(document).on('submit','form[name=post-okr-item]',function(){
    var fid = $(this).data('fid');
    $.ajax({
        url:'/admin/okr/item/post',
        type: "POST",
        data:$(this).serialize(),
        success: function(result) {
            if(result.status === 1){
                toastr.success(result.info,function () {
                    setTimeout(function (e) {
                        getOkrTaskList(result.okr_id);
                    },1000);
                });
            }

            if(result.status === 0){
                toastr.error(result.info);
            }

        },
        error: function(request) {
            alert("Connection error");
        }
    });
    $(this).find('input[name=id]').val('');
    $(this).find('input[name=content]').val('');
    return false;
});

// 计划任务列表
var getOkrTaskList = function ($okr_id) {
    $.ajax({
        url: '/admin/okr/item/list/'+$okr_id,
        type: "GET",
        success: function(html) {
            $("#okr-item-list").html(html);
        },
        error: function(request) {
            alert("Connection error");
        }
    });
}

// 修改计划

$(document).on('click','span[data-toggle=edit-okr-item]',function(){
    var id = $(this).data('id');
    var content = $(this).data('content');
    $("input[name=id]").val(id);
    $("input[name=content]").val(content);
    return false;
});

$(document).on('click','span[data-toggle=okr-item-del]',function(){
    var id = $(this).data('id');
    if(!confirm("确定删除吗？")){
        return false;
    }
    $.ajax({
        url:'/admin/okr/item/'+id+'/del',
        type: "GET",
        success: function(result) {
            if(result.status === 1){
                toastr.success(result.info,function () {
                    setTimeout(function (e) {
                        getOkrTaskList(result.okr_id);
                    },1000);
                });
            }

            if(result.status === 0){
                toastr.error(result.info);
            }
        },
        error: function(request) {
            alert("Connection error");
        }
    });
    return false;
});
// 取消
$(document).on('click','span[data-toggle=okr-item-cancel]',function(){
    var id = $(this).data('id');
    $.ajax({
        url:'/admin/okr/item/'+id+'/cancel',
        type: "GET",
        success: function(result) {
            if(result.status === 1){
                toastr.success(result.info,function () {
                    setTimeout(function (e) {
                        getOkrTaskList(result.okr_id);
                    },1000);
                });
            }

            if(result.status === 0){
                toastr.error(result.info);
            }
        },
        error: function(request) {
            alert("Connection error");
        }
    });
    return false;
});

// 完成
$(document).on('click','button[data-toggle=okr-item-complete]',function(){
    var id = $(this).data('id');
    $.ajax({
        url:'/admin/okr/item/'+id+'/complete',
        type: "GET",
        success: function(result) {
            if(result.status === 1){
                toastr.success(result.info,function () {
                    setTimeout(function (e) {
                        getOkrTaskList(result.okr_id);
                    },1000);
                });
            }

            if(result.status === 0){
                toastr.error(result.info);
            }
        },
        error: function(request) {
            alert("Connection error");
        }
    });
    return false;
});


// 提交计划备注
$(document).on('submit','form[name=okr-item-replay]',function(){

    $.ajax({
        url:'/admin/okr/item/replay',
        type: "POST",
        data:$(this).serialize(),
        success: function(result) {
            if(result.status === 1){
                toastr.success(result.info,function () {
                    setTimeout(function (e) {
                        getOkrItemReplay(result.okr_id,result.pid);
                    },1000);
                });
            }

            if(result.status === 0){
                toastr.success(result.info,function () {
                    //location.replace(location.href);
                });
            }

        },
        error: function(request) {
            alert("Connection error");
        }
    });
    $(this).find('textarea[name=content]').val('');
    return false;
});

$(document).on('click','span[data-toggle=okr-cancel]',function(){
    var id = $(this).data('id');
    if(!confirm("确认不在跟进任务了？")){
        return false;
    }
    $.ajax({
        url:'/admin/okr/'+id+'/cancel',
        type: "GET",
        success: function(result) {
            if(result.status === 1){
                toastr.success(result.info,function () {
                    setTimeout(function (e) {
                        getOkrTask();
                        $("#people").modal('hide');
                    },1000);
                });
            }

            if(result.status === 0){
                toastr.error(result.info);
            }
        },
        error: function(request) {
            alert("Connection error");
        }
    });
    return false;

});
// 删除
$(document).on('click','span[data-toggle=okr-replay-del]',function(){
    var id = $(this).data('id');
    if(!confirm("确定删除吗？")){
        return false;
    }
    $.ajax({
        url:'/admin/okr/replay/'+id+'/del',
        type: "GET",
        success: function(result) {
            if(result.status === 1){
                toastr.success(result.info,function () {
                    setTimeout(function (e) {
                        getOkrItemReplay(result.okr_id,result.pid);
                    },1000);
                });
            }

            if(result.status === 0){
                toastr.error(result.info);
            }
        },
        error: function(request) {
            alert("Connection error");
        }
    });
    return false;
});


$(document).on('click','#okr-item-list p',function(){
    var id = $(this).data('id');

    var normal_score = $(this).data('normal_score');
    var backward_score = $(this).data('backward_score');
    var complete_score = $(this).data('complete_score');
    var normal_details = $(this).data('normal_details');
    var backward_details = $(this).data('backward_details');
    var complete_details = $(this).data('complete_details');


    $(this).css("background","#CDDC39").css('color','#fff').siblings().css("background","#f6feec").css('color','#606060');
    $('input[name=item_id]').val(id);

    $("select[name=normal_score]").val(normal_score);
    $("select[name=backward_score]").val(backward_score);
    $("select[name=complete_score]").val(complete_score);
    $("textarea[name=normal_details]").val(normal_details);
    $("textarea[name=backward_details]").val(backward_details);
    $("textarea[name=complete_details]").val(complete_details);


    return false;
});

// 添加计划项目
$(document).on('submit','form[name=okr-item-detail]',function(){
    var okr_id = $("input[name=okr_id]").val();


    $.ajax({
        url:'/admin/okr/item/complete',
        type: "POST",
        data:$(this).serialize(),
        success: function(result) {
            if(result.status === 1){
                toastr.success(result.info,function () {
                    setTimeout(function (e) {
                        $("select[name=normal_score]").val('');
                        $("select[name=backward_score]").val('');
                        $("select[name=complete_score]").val('');
                        $("textarea[name=normal_details]").val('');
                        $("textarea[name=backward_details]").val('');
                        $("textarea[name=complete_details]").val('');
                        getOkrTaskList(okr_id);
                    },1000);
                });
            }

            if(result.status === 0){
                toastr.error(result.info);
            }

        },
        error: function(request) {
            alert("Connection error");
        }
    });
    $(this).find('input[name=id]').val('');
    $(this).find('input[name=content]').val('');
    return false;
});

$("#okr-type button").click(function () {
   var type = $(this).data('type');
    getOkrTask(type);
});






