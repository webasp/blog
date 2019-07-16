$('#add-habits').on('show.bs.modal', function (event) {
    var button = $(event.relatedTarget);
    var week = button.data('week');
    var modal = $(this);
    modal.find('.modal-body input[name=week]').val(week);
});

$('#good_habits_edit').on('show.bs.modal', function (event) {
    var button = $(event.relatedTarget);
    var id = button.data('id');
    var title = button.data('title');
    var status = button.data('status');
    var description = button.data('description');
    var modal = $(this);
    modal.find('.modal-body input[name=id]').val(id);
    modal.find('.modal-body input[name=title]').val(title);
    modal.find('.modal-body input[name=status]').val(status);
    modal.find('.modal-body textarea[name=description]').html(description);
});

// 修改
$("form[name=good_habits_edit]").submit(function(){

    $.ajax({
        url:'/admin/goodhabits/edit',
        type: "POST",
        data:$(this).serialize(),
        success: function(result) {

            if(result.status === 1){
                toastr.success(result.info,function () {
                    $("#add-habits").modal('hide');
                    setTimeout(function (e) {
                        location.replace(location.href);
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

// 添加坏习惯
$("form[name=add-habits]").submit(function(){

    $.ajax({
        url:'/admin/habits/add',
        type: "POST",
        data:$(this).serialize(),
        success: function(result) {

            if(result.status === 1){
                toastr.success(result.info,function () {
                    $("#add-habits").modal('hide');
                    setTimeout(function (e) {
                        location.replace(location.href);
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
// 添加
$("form[name=add-GoodHabits]").submit(function(){

    $.ajax({
        url:'/admin/goodhabits/add',
        type: "POST",
        data:$(this).serialize(),
        success: function(result) {

            if(result.status === 1){
                toastr.success(result.info,function () {
                    $("#add-habits").modal('hide');
                    setTimeout(function (e) {
                        location.replace(location.href);
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

// 删除
$(document).on('click','span[data-target=good_habits_del]',function(){

    if(!confirm("确定删除习惯吗？")){
        return false;
    }

    var id = $(this).data('id');
    $.ajax({
        url:'/admin/goodhabits/'+id+'/del',
        type: "GET",
        success: function(result) {
            if(result.status === 1){

                toastr.success(result.info,function () {
                    setTimeout(function (e) {
                        location.replace(location.href);
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
$(document).on('click','a[data-toggle=good-habits-del]',function(){

    if(!confirm("确定删除坏习惯吗？")){
        return false;
    }

    var id = $(this).data('id');
    $.ajax({
        url:'/admin/habits/'+id+'/del',
        type: "GET",
        success: function(result) {
            if(result.status === 1){

                toastr.success(result.info,function () {
                    setTimeout(function (e) {
                        location.replace(location.href);
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

// 完成
$(document).on('click','a[data-toggle=habits-complete]',function(){

    if(!confirm("确定？")){
        return false;
    }

    var id = $(this).data('id');
    $.ajax({
        url:'/admin/habits/'+id+'/complete',
        type: "GET",
        success: function(result) {
            if(result.status === 1){

                toastr.success(result.info,function () {
                    setTimeout(function (e) {
                        location.replace(location.href);
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

// 标记
$('#habits-annal').on('show.bs.modal', function (event) {
    var button = $(event.relatedTarget);
    var id = button.data('id');
    var day = button.data('day');
    var modal = $(this);
    modal.find('.modal-body input[name=good_habits_links_id]').val(id);
    modal.find('.modal-body input[name=day]').val(day);
});

$("form[name=habits-annal]").submit(function(){

    $.ajax({
        url:'/admin/habits/annal',
        type: "POST",
        data:$(this).serialize(),
        success: function(result) {

            if(result.status === 1){
                toastr.success(result.info,function () {
                    $("#add-habits").modal('hide');
                    setTimeout(function (e) {
                        location.replace(location.href);
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