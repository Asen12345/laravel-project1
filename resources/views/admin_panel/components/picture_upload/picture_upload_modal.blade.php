<div style="display:none">
        <input type="file" id="image_hidden_file" name="image_hidden_file" onclick="this.value=null;" onchange="upload_temp_avatar();" value="">
        <input type="submit" id="my_hidden_load" style="display: none" value='Загрузить'>
        <input type='hidden' name='x1' id='x1'>
        <input type='hidden' name='x2' id='x2'>
        <input type='hidden' name='y1' id='y1'>
        <input type='hidden' name='y2' id='y2'>
        <input type='hidden' name='img_width' id='img_width'>
        <input type='hidden' name='img_height' id='img_height'>
</div>
<iframe id="requestFrame" name="requestFrame" style="display: none"></iframe>

<div class="modal" id="avatar_select_form" tabindex="-1" role="dialog" aria-labelledby="avatar_select_form" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalCenterTitle">Загрузка файла</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div id='avatar_loading_prompt'>
                    <i class="fa fa-refresh fa-spin"></i>
                    Подождите, файл загружается...
                </div>
                <div style='display:none' id='avatar_img_div'>
                    Пожалуйста, выберите область
                    <img id='avatar_img' src='' class="">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Закрыть</button>
                <button type="button" class="btn btn-primary" onClick='save_avatar()'>Сохранить</button>
            </div>
        </div>
    </div>
</div>
