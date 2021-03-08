<link rel="stylesheet" type="text/css" href="{{ asset('/assets/imgareaselect/css/imgareaselect-default.css') }}"/>
<script src="{{ asset('/assets/imgareaselect/scripts/jquery.imgareaselect.js') }}"></script>
<script type="text/javascript">
    function preUpload() {
        const file_data = $('#image_hidden_file').prop('files')[0];
        const form_data = new FormData();
        form_data.append('image', file_data);
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': "{{ csrf_token() }}"
            },
            method: "POST",
            url: "{{route($type . '.resource.pre_upload.image')}}",
            data: form_data,
            contentType: false,
            processData: false,
            done: function (data) {

            },
            success: function (data) {
                if (data['is'] === 'failed') {
                    onFileUploadFailed(data['error'])
                }
                if (data['is'] === 'success') {
                    onFileUploadComplete(data['path'])
                }
            }
        })
    }
    function save_avatar() {
        $.ajax({
            method: "POST",
            url: "{{route($type . '.resource.upload.image')}}",
            headers: {'X-CSRF-Token': '{{ csrf_token() }}'},
            data: {
                img_path: $('#avatar_img').attr('src'),
                x1: $('#x1').val(),
                y1: $('#y1').val(),
                x2: $('#x2').val(),
                y2: $('#y2').val(),
                img_width: $('#img_width').val(),
                img_height: $('#img_height').val()
            },
            done: function (data) {

            },
            success: function (data) {
                $('#user_avatar').attr('src', data.path);
                $('#picture').val(data.path);
                $('#avatar_select_form').modal('hide');
            }
        })
    }

    function select_avatar() {
        $('#image_hidden_file').click();
    }

    function onCloseAvatarSelectionModal() {
        $('img#avatar_img').imgAreaSelect({hide: true});
    }


    function upload_temp_avatar() {
        $('#avatar_img').attr('src', '');
        $('#avatar_loading_prompt').show();
        $('#avatar_img_div').hide();
        preUpload();
        $('#avatar_select_form').modal();
    }

    function preview(img, selection) {
        $('#x1').val(selection.x1);
        $('#x2').val(selection.x2);
        $('#y1').val(selection.y1);
        $('#y2').val(selection.y2);
        $('#img_width').val($(img).width());
        $('#img_height').val($(img).height());
    }

    function onFileUploadComplete(filename) {
        $('#avatar_loading_prompt').hide();
        $('#avatar_img').attr('src', filename);
        $('#avatar_img_div').show();
        innitFile()
    }

    function onFileUploadFailed(error) {
        $('#avatar_select_form').modal('hide');
        alert(error);
    }
    function innitFile() {
        let edit_box =  $('img#avatar_img').imgAreaSelect({
            instance: true,
            handles: true,
            show: true,
            @if($type !== 'admin.author')
            @if($type !== 'admin.researches')
            aspectRatio: '1:1',
            @endif
            @endif
            onSelectEnd: function () {
            },
            onSelectChange: preview,
            onInit: defaultSizeSelect,
        });
        function defaultSizeSelect (img, selection) {
            edit_box.setSelection(0, 0, img.width - 0.6, img.height - 0.6, true);
            edit_box.update();
            let dto = { x1: 0, y1: 0, x2: img.width - 0.6, y2: img.height - 0.6};
            preview(img, dto)
        }

        $("#avatar_select_form").on("hidden.bs.modal", onCloseAvatarSelectionModal);
    }

</script>