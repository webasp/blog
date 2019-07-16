/*!
 * Image (upload) dialog plugin for Editor.md
 *
 * @file        image-dialog.js
 * @author      pandao
 * @version     1.3.4
 * @updateTime  2015-06-09
 * {@link       https://github.com/pandao/editor.md}
 * @license     MIT
 */

(function() {

    var factory = function (exports) {

		var pluginName   = "image-dialog";

        exports.fn.imageDialog = function () {
            var cm = this.cm;
            var lang = this.lang;
            var editor = this.editor;
            var settings = this.settings;
            var cursor = cm.getCursor();
            var selection = cm.getSelection();
            var imageLang = lang.dialog.image;
            var classPrefix = this.classPrefix;
            var iframeName = classPrefix + 'image-iframe';
            var dialogName = classPrefix + pluginName,
                dialog;
            cm.focus();
            var loading = function (show) {
                var _loading = dialog.find('.' + classPrefix + 'dialog-mask');
                _loading[(show) ? 'show' : 'hide']()
            }
            if (editor.find('.' + dialogName).length < 1) {
                var guid = new Date().getTime();
                var action = settings.imageUploadURL + (settings.imageUploadURL.indexOf('?') >= 0 ? '&' : '?') + 'guid=' + guid;
                if (settings.crossDomainUpload) {
                    action += '&callback=' + settings.uploadCallbackURL + '&dialog_id=editormd-image-dialog-' + guid
                }
                var dialogContent = ((settings.imageUpload) ? '<form action="' + action + '" id="images-upload" method="post" enctype="multipart/form-data" class="' + classPrefix + 'form">' : '<div class="' + classPrefix + 'form">') +
                    '<label>' + imageLang.url + '</label>' +
                    '<input type="text" data-url />' + (function () {
                        return (settings.imageUpload) ? '<div class="' + classPrefix + 'file-input">' +
                            '<input type="file" id="file" name="file" accept="image/*" multiple />' +
                            '<input type="submit" value="' + imageLang.uploadButton + '" />' +
                            '</div>' : ''
                    })() +
                    '<br/>' +
                    '<label>' + imageLang.alt + '</label>' +
                    '<input type="text" value="' + selection + '" data-alt />' +
                    '<br/>' +
                    '<label>' + imageLang.link + '</label>' +
                    '<input type="text" value="http://" data-link />' +
                    '<br/>' +
                    ((settings.imageUpload) ? '</form>' : '</div>')
                dialog = this.createDialog({
                    title: imageLang.title,
                    width: (settings.imageUpload) ? 465 : 380,
                    height: 254,
                    name: dialogName,
                    content: dialogContent,
                    mask: settings.dialogShowMask,
                    drag: settings.dialogDraggable,
                    lockScreen: settings.dialogLockScreen,
                    maskStyle: {
                        opacity: settings.dialogMaskOpacity,
                        backgroundColor: settings.dialogMaskBgColor
                    },
                    buttons: {
                        enter: [lang.buttons.enter, function () {
                            var url = this.find('[data-url]').val()
                            var alt = this.find('[data-alt]').val()
                            var link = this.find('[data-link]').val()
                            if (url === '') {
                                alert(imageLang.imageURLEmpty)
                                return false
                            }
                            var altAttr = (alt !== '') ? ' "' + alt + '"' : ''
                            var arr_url = url.split('$$')
                            var text = ''
                            for (var i = 0, length = arr_url.length; i < length; i++) {
                                if (link === '' || link === 'http://') {
                                    text = text + '![' + alt + '](' + arr_url[i] + altAttr + ')\r\n\r\n'
                                } else {
                                    text = text + '[![' + alt + '](' + arr_url[i] + altAttr + ')](' + link + altAttr + ')\r\n\r\n'
                                }
                            }
                            cm.replaceSelection(text)
                            if (alt === '') {
                                // cm.setCursor(cursor.line, cursor.ch + 2)
                            }
                            this.hide().lockScreen(false).hideMask()
                            return false
                        }],
                        cancel: [lang.buttons.cancel, function () {
                            this.hide().lockScreen(false).hideMask()
                            return false
                        }]
                    }
                })
                dialog.attr('id', classPrefix + 'image-dialog-' + guid)
                if (!settings.imageUpload) {
                    return
                }
                var update = function(file){
                    var default_config = {
                        max_width: 1024,
                        max_height: 900,
                        img_domain: 'http://ouz7z86vp.bkt.clouddn.com/'
                    };

                    // 压缩图片需要的一些元素和对象
                    var reader = new FileReader();
                    var img = new Image();

                    // 缩放图片需要的canvas
                    var canvas = document.createElement('canvas');
                    var context = canvas.getContext('2d');

                    if (file.type.indexOf("image") == 0) {
                        reader.readAsDataURL(file);
                    }

                    // 文件base64化，以便获知图片原始尺寸
                    reader.onload = function(e) {
                        img.src = e.target.result;
                    };

                    // base64地址图片加载完毕后
                    img.onload = function () {
                        // 图片原始尺寸
                        var originWidth = this.width;
                        var originHeight = this.height;
                        // 最大尺寸限制
                        var maxWidth = default_config['max_width'], maxHeight = default_config['max_height'];
                        // 目标尺寸
                        var targetWidth = originWidth, targetHeight = originHeight;
                        // 图片尺寸超过限制
                        if (originWidth > maxWidth || originHeight > maxHeight) {
                            if (originWidth / originHeight > maxWidth / maxHeight) {
                                // 更宽，按照宽度限定尺寸
                                targetWidth = maxWidth;
                                targetHeight = Math.round(maxWidth * (originHeight / originWidth));
                            } else {
                                targetHeight = maxHeight;
                                targetWidth = Math.round(maxHeight * (originWidth / originHeight));
                            }
                        }

                        // canvas对图片进行缩放
                        canvas.width = targetWidth;
                        canvas.height = targetHeight;
                        // 清除画布
                        context.clearRect(0, 0, targetWidth, targetHeight);
                        // 图片压缩
                        context.drawImage(img, 0, 0, targetWidth, targetHeight);
                        // canvas转为blob并上传
                        canvas.toBlob(function (blob) {
                            // console.log(blob);
                            var Form = new FormData();
                            Form.append("file",blob,"images.jpg");
                            Form.append('key', new Date().getTime() + '.jpg')
                            var token = $("input[name=token]").val();
                            Form.append('token',token);
                            $.ajax({
                                url: 'http://up-z2.qiniu.com',
                                method: "POST",
                                data: Form,
                                processData: false,
                                contentType: false,
                                //dataType: 'json',
                                success: function (json) {
                                    var oldurl = $('[data-url]').val();
                                    if (oldurl === '') {
                                        $('[data-url]').val(default_config['img_domain'] + json.key)
                                    } else {
                                        oldurl = oldurl + default_config['img_domain'] + json.key
                                        $('[data-url]').val(oldurl)
                                    }
                                    loading(false);
                                }
                            });

                            return false;

                        }, file.type || 'image/png');
                    };
                };

                var submitHandler = function() {

                    var file = $('[name="file"]').get(0).files[0];
                    // console.log(file);

                    update(file);
                    return false
                }
                var fileInput = dialog.find('[name="file"]')
                fileInput.off('change').on('change', function() {
                    var fileName = fileInput.val()
                    var isImage = new RegExp('(\\.(' + settings.imageFormats.join('|') + '))$') // /(\.(webp|jpg|jpeg|gif|bmp|png))$/
                    if (fileName === '') {
                        alert(imageLang.uploadFileEmpty)
                        return false
                    }
                    if (!isImage.test(fileName)) {
                        alert(imageLang.formatNotAllowed + settings.imageFormats.join(', '))
                        return false
                    }
                    loading(true)
                    dialog.find('[type="submit"]').off('click').on('click', submitHandler).trigger('click')
                })
            }
            dialog = editor.find('.' + dialogName)
            dialog.find('[type="text"]').val('')
            dialog.find('[type="file"]').val('')
            dialog.find('[data-link]').val('http://')
            this.dialogShowMask(dialog)
            this.dialogLockScreen()
            dialog.show()
        }

	};

	// CommonJS/Node.js
	if (typeof require === "function" && typeof exports === "object" && typeof module === "object")
    {
        module.exports = factory;
    }
	else if (typeof define === "function")  // AMD/CMD/Sea.js
    {
		if (define.amd) { // for Require.js

			define(["editormd"], function(editormd) {
                factory(editormd);
            });

		} else { // for Sea.js
			define(function(require) {
                var editormd = require("./../../editormd");
                factory(editormd);
            });
		}
	}
	else
	{
        factory(window.editormd);
	}

})();
