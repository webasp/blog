var testEditor;
var left = 0;
$(function() {
    testEditor = editormd("content", {
        width   : "100%",
        height  : "640",
        syncScrolling : "single",
        path    : "/static/admin/editor/lib/",
        imageUpload    : true,
        imageFormats   : ["jpg", "jpeg", "gif", "png", "bmp", "webp"],
        imageUploadURL : "./php/upload.php",
        toolbarIcons : function() {
            return [
                "save","bold", "del", "italic", "quote", "ucwords", "uppercase", "lowercase", "|",
                "h1", "h2", "h3", "h4", "h5", "h6", "|",
                "list-ul", "list-ol", "hr", "|",
                "link", "image", "code", "code-block", "table", "|",
                "watch", "preview"
            ]
        },
        toolbarIconTexts : {
            save : "<span><i class='fa fa-save'></i> 保存 </span>"
        },
        // 自定义工具栏按钮的事件处理
        toolbarHandlers : {
            save : function() {
                try
                {
                    if(typeof(eval(save))=="function"){
                        save();
                    }
                }catch(e){
                    toastr.error("Save 函数不存在");
                }
            }
        },
        disabledKeyMaps : [
            "Ctrl-B", "F11", "F10"
        ],
        onload : function() {
            var keyMap = {
                "Ctrl-S": function(cm) {
                    try
                    {
                        if(typeof(eval(save))=="function"){
                            save();
                        }
                    }catch(e){
                        toastr.error("Save 函数不存在");
                    }
                },
                "Ctrl-A": function(cm) {
                    cm.execCommand("selectAll");
                }
            };

            this.addKeyMap(keyMap);
        }
    });
});