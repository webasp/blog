jQuery.fn.Update = function(config){

		var default_config = {
				max_width: 1024,
				max_height: 900,
                img_domain: 'http://ouz7z86vp.bkt.clouddn.com/'
			};


    var eleFile = document.querySelector('#thumb-file');
    // 压缩图片需要的一些元素和对象
    var reader = new FileReader(), img = new Image();
    // 选择的文件对象
    var file = null;
    // 缩放图片需要的canvas
    var canvas = document.createElement('canvas');
    var context = canvas.getContext('2d');
    eleFile.addEventListener('change', function (event) {
        file = event.target.files[0];
        //console.log(file);

        if (file.type.indexOf("image") == 0) {
            reader.readAsDataURL(file);
        }
    });

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
            Form.append("file",blob,"image.jpg");
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
                success: function (data) {
                    $('.updateThumb>img').attr('src',default_config['img_domain']+data.key);
                    $('input[name=thumb]').val(data.key);
                }
            });

            return false;

        }, file.type || 'image/png');
    };
	};
	function default_call_back(){};