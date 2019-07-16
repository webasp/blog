! function (e) {
    "use strict";
    var t = function () {
            this.$body = e("body"),
            this.$modal = e("#event-modal"),
            this.$event = "#external-events div.external-event",
            this.$calendar = e("#calendar"),
            this.$saveCategoryBtn = e(".save-category"),
            this.$categoryForm = e("#add-category form"),
            this.$extEvents = e("#external-events"),
            this.$calendarObj = null
    };

    var type = [
        {"class": 'important', "title": '重要'},
        {"class": 'notice', "title": '注意'},
        {"class": 'work', "title": '工作'},
        {"class": 'study', "title": '学习'},
        {"class": 'hobby', "title": '爱好'},
        {"class": 'culture', "title": '修养'},
        {"class": 'fitness', "title": '健身'},
        {"class": 'daily', "title": '日常'},
        {"class": 'sleep', "title": '睡眠'},
        {"class": 'waste', "title": '无意义'}
        ];

    var date = new Date();
    var complete = date.getFullYear() + '-' + (date.getMonth() + 1) + '-' + date.getDate() +' '+ date.getHours() +':'+date.getMinutes();
    var scrollTime = date.getHours()+':'+date.getMinutes()+':00';

    function getHour(s1, s2) {
        s1 = new Date(s1);
        s2 = new Date(s2);
        var ms = s2.getTime() - s1.getTime();
        return ms;
    }

    var count = function (data) {



        let newfood=[];
        var temp = {};
        for(var i in data) {
            var key= data[i].type;
            if(temp[key]) {
                temp[key].type = temp[key].type ;
                temp[key].time = temp[key].time + getHour(data[i].start,data[i].end);
            } else {
                temp[key] = {};
                temp[key].type = data[i].type;
                temp[key].time = getHour(data[i].start,data[i].end);
            }

            temp[key].title= type[(data[i].type)*1-1].title;
            temp[key].class= type[(data[i].type)*1-1].class;
        }



        var html = $("#CountHtml");
        html.html('');
        for(var k in temp){
            var leave1=temp[k].time%(24*3600*1000);
            var hours=Math.floor(leave1/(3600*1000));
            var leave2=leave1%(3600*1000);
            var minutes=Math.floor(leave2/(60*1000));
            var str = '';
            if(hours != '0'){
                str = hours+"小时";
            }

            if(minutes != '0'){
                str += minutes+"分";
            }


            html.append("<p><span class="+temp[k].class+"> "+temp[k].title+" <b>"+str+"</b></span></p>");
        }


    }

    var getTaskCat = function(l){
        $.ajax({
            url:'/admin/schedule/cat/get',
            type: "POST",
            success: function(result) {
                var catHtml;
                $.each(result, function (n, value) {

                    l.$extEvents.append('<div class="external-event ' + value.class + '" data-class="' + value.class + '" style="position: relative;" data-id="'+value.id+'">' + value.title + "</div>"),l.enableDrag();
                });
            },
            error: function(request) {
                toastr.error('获取Cat出错');
            }
        });
    }



    t.prototype.onDrop = function (t,result) {
         var l = result.className,
             i = {};
             i.title = result.title,
             i.id = result.id,
             i.start = result.start,
             i.end = result.end,
             i.complete = 0,
        l && (i.className = [l]),
            this.$calendar.fullCalendar("renderEvent", i, !0),
        e("#drop-remove").is(":checked") && t.remove();

    }, t.prototype.onEventClick = function (t, n, a) {


        var l = this,

            i = e("<form></form>");
            i.append("<div class='input-group m-b-15'><input class='form-control' type=text value='" + t.title + "' /><span class='input-group-append'><button type='submit' class='btn btn-success btn-md  '><i class='fa fa-check'></i> 保存 </button></span></div>"),
            l.$modal.modal({
             backdrop: "static"
             });

            if (t.complete == '0' || t.complete == 'undefined'){
                l.$modal.find(".complete-event").show().click(function () {
                    $.ajax({
                        url:'/admin/schedule/edit',
                        type: "POST",
                        data:{
                            id:t.id,
                            complete: complete
                        },
                        success: function(result) {
                            if(result.status === 1){
                                t.complete = 1;
                                t.className = [t.className[0],'complete'];
                                l.$calendarObj.fullCalendar("updateEvent", t);
                                l.$modal.modal("hide");
                            }else{
                                return false;
                                toastr.error(result.info);
                            }
                        },
                        error: function(request) {
                            toastr.error('添加日程出错');
                        }
                    });
                })
            }else{
                l.$modal.find(".complete-event").hide();
            }
           l.$modal.find(".complete-event");
           l.$modal.find(".delete-event").show().end().find(".save-event").hide().end().find(".modal-body").empty().prepend(i).end().find(".delete-event").unbind("click").click(function () {

               $.ajax({
                   url:'/admin/schedule/del',
                   type: "POST",
                   data:{
                       id:t.id,
                   },
                   success: function(result) {
                       //console.log(t.id);
                       if(result.status === 1){
                           l.$calendarObj.fullCalendar("removeEvents", function (e) {
                               return e._id == t._id
                           }), l.$modal.modal("hide");
                           //toastr.success(result.info);
                       }else{
                           //toastr.error(result.info);
                       }

                   },
                   error: function(request) {
                       toastr.error('添加日程出错');
                   }
               });


        }), l.$modal.find("form").on("submit", function () {


            var title = i.find("input[type=text]").val();

                $.ajax({
                    url:'/admin/schedule/edit',
                    type: "POST",
                    data:{
                        id:t.id,
                        title:title
                    },
                    success: function(result) {
                        if(result.status === 1){
                            t.title = i.find("input[type=text]").val();
                            l.$calendarObj.fullCalendar("updateEvent", t);
                            l.$modal.modal("hide");
                            //toastr.success(result.info);
                        }else{
                            return false;
                            toastr.error(result.info);
                        }
                    },
                    error: function(request) {
                        toastr.error('添加日程出错');
                    }
                });


                return false;

        })
    },

        t.prototype.onSelect = function (t, n, a) {


        var l = this;
        l.$modal.modal({
            backdrop: "static"
        });

        var i = e("<form></form>");
        i.append("<div class='row'></div>"),
            i.find(".row").append("<div class='col-12'><div class='form-group'><label class='control-label'>Event Name</label><input class='form-control' placeholder='Insert Event Name' type='text' name='title'/></div></div>").append("<div class='col-12'><div class='form-group'><label class='control-label'>Category</label><select class='form-control' name='category'></select></div></div>").find("select[name='category']")
                .append("<option value='important'> 重要 </option>")
                .append("<option value='notice'> 注意 </option>")
                .append("<option value='work'> 工作 </option>")
                .append("<option value='study'> 学习 </option>")
                .append("<option value='hobby'> 爱好 </option>")
                .append("<option value='culture'> 修养 </option>")
                .append("<option value='fitness'> 健身 </option>")
                .append("<option value='daily'> 日常 </option>")
                .append("<option value='sleep'> 睡眠 </option>")
                .append("<option value='waste'> 无意义 </option></div></div>"),
            l.$modal.find(".delete-event").hide().end().find(".save-event").show().end().find(".complete-event").hide().end().find(".modal-body").empty().prepend(i).end().find(".save-event").unbind("click").click(function () {
            i.submit()
        }), l.$modal.find("form").on("submit", function () {

            var e = i.find("input[name='title']").val(),
                a = (i.find("input[name='beginning']").val(), i.find("input[name='ending']").val(), i.find("select[name='category'] option:checked").val());

            if(e.length == 0){
                alert("You have to give a title to your event");
                return false;
            }


            $.ajax({
                url:'/admin/schedule/add',
                type: "POST",
                data:{
                    title:e,
                    start:t.format(),
                    end:n.format(),
                    className:a
                },
                success: function(result) {
                    if(result.status === 1){
                        l.$calendarObj.fullCalendar("renderEvent", {
                            id:result.id,
                            title: e,
                            start: t,
                            end: n,
                            allDay: !1,
                            className: a,
                            complete:0
                        }, !0), l.$modal.modal("hide");
                    }else{
                    }
                },
                error: function(request) {
                    toastr.error('添加日程出错');
                }
            });

            return false;



        }), l.$calendarObj.fullCalendar("unselect")
    }, t.prototype.enableDrag = function () {
        e(this.$event).each(function () {
            var t = {
                title: e.trim(e(this).text())
            };
            e(this).data("eventObject", t), e(this).draggable({
                zIndex: 999,
                revert: !0,
                revertDuration: 0
            })
        })
    }, t.prototype.init = function () {
        this.enableDrag();
        var t = new Date,
            n = (t.getDate(), t.getMonth(), t.getFullYear(), new Date(e.now())),
            l = this;


        l.$calendarObj = l.$calendar.fullCalendar({
            allDaySlot:false,
            slotDuration: "00:15:00",
            slotEventOverlap:false,
            //minTime: "00:00:00",
            //maxTime: "22:00:00",
            slotLabelFormat:'H:mm',
            scrollTime:scrollTime,
            defaultView: "agendaDay",
            header: {
                left: "prev,next today",
                center: "title",
                right: "agendaDay,agendaWeek,month"
            },

            events:function(start, end,timezone, callback){
                $.ajax({
                    url: '/admin/schedule/getTask',
                    type: 'POST',
                    dataType: 'json',
                    data: {
                        start: start.unix(),
                        end: end.unix()
                    },
                    success: function(json) {
                        count(json);
                        callback(json);
                    }
                });

            },

            editable: !0,
            droppable: !0,
            eventLimit: !0,
            selectable: !0,
            allDayText: '全天',
            monthNames: ['一月','二月','三月','四月','五月','六月','七月','八月','九月','十月','十一月','十二月'],
            monthNamesShort: ['1','2','3','4','5','6','7','8','9','10','11','12'],
            dayNames: ['星期日','星期一','星期二','星期三','星期四','星期五','星期六'],
            dayNamesShort: ['周日','周一','周二','周三','周四','周五','周六'],
            buttonText: {
                today: '今天',
                month: '本月',
                week: '本周',
                day: '今天'
            },


            // 选入日程
            drop: function (t) {
                var et = e(this);
                var date = new Date(t);
                var date = new Date(date.valueOf() - 8*60*60*1000);
                var hours,minutes;

                if(date.getHours() < 4){
                    hours = '06';
                }else{
                    hours = date.getHours();
                }

                if(date.getMinutes() === 0){
                    minutes = '00';
                }else{
                    minutes = date.getMinutes();
                }

                var start = date.getFullYear() + '-' + (date.getMonth() + 1) + '-' + date.getDate() + ' ' + hours + ':' + minutes;

                $.ajax({
                    url:'/admin/schedule/add',
                    type: "POST",
                    data:{
                        title:et.text(),
                        start:start,
                        className:et.data('class')
                    },
                    success: function(result) {
                        l.onDrop(et,result);
                        //toastr.success(et.text());
                    },
                    error: function(request) {
                        toastr.error('添加日程出错');
                    }
                });




            },

            // 添加
            select: function (e, t, n) {

                l.onSelect(e, t, n)
            },

            // 修改
            eventClick: function (e, t, n) {
                l.onEventClick(e, t, n)
            },

            eventDrop: function(event){
                $.ajax({
                    url:'/admin/schedule/edit',
                    type: "POST",
                    data:{
                        id:event.id,
                        end:event.end.format(),
                        start:event.start.format()
                    },
                    success: function(result) {
                        if(result.status === 1){
                        }else{
                        }
                    },
                    error: function(request) {
                        toastr.error('更新日程出错');
                    }
                });




            },

            // 修改时间
            eventResize: function(event) {

                $.ajax({
                    url:'/admin/schedule/edit',
                    type: "POST",
                    data:{
                        id:event.id,
                        end:event.end.format(),
                        start:event.start.format()
                    },
                    success: function(result) {
                        if(result.status === 1){
                            //toastr.success(result.info);
                        }else{
                            //toastr.error(result.info);
                        }
                    },
                    error: function(request) {
                        toastr.error('添加日程出错');
                    }
                });


            }

        }), this.$saveCategoryBtn.on("click", function () {

            var e = l.$categoryForm.find("input[name='category-name']").val(),
                t = l.$categoryForm.find("select[name='category-color']").val();

            if(e.length == 0){
                alert("You have to give a title to your event");
                return false;
            }

            $.ajax({
                url:'/admin/schedule/cat/add',
                type: "POST",
                data:{
                    title:e,
                    class:t
                },
                success: function(result) {
                    if(result.status === 1){
                        l.$categoryForm.find("input[name='category-name']").val('');
                        (l.$extEvents.append('<div class="external-event ' + t + '" data-class="' + t + '" style="position: relative;" data-id="'+result.id+'">' + e + "</div>"), l.enableDrag());
                    }
                },
                error: function(request) {
                    toastr.error('添加日程出错');
                }
            });
        })
        getTaskCat(l);


        var folder = $("#drop-trash");

        folder.droppable({
            drop : function(event, ui) {

                var sourceElement = $(ui.helper);
                var id = sourceElement.data('id');
                $.ajax({
                    url:'/admin/schedule/cat/del',
                    type: "POST",
                    data:{
                        id:id
                    },
                    success: function(result) {
                        if(result.status === 1){
                            ui.draggable.remove();
                            toastr.success(result.info);
                        }else{
                            toastr.error(result.info);
                        }
                    },
                    error: function(request) {
                        toastr.error('删除出错');
                    }
                });

            },

        });


    }, e.CalendarApp = new t, e.CalendarApp.Constructor = t
}(window.jQuery),
    function (e) {
        "use strict";
        e.CalendarApp.init()
    }(window.jQuery);