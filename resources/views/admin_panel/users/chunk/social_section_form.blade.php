<div class="box box-primary">
    <div class="box-header with-border">
        <h3 class="box-title">Страницы в соцсетях</h3>
        <div class="box-tools pull-right">
            <button type="button" class="btn btn-box-tool" data-widget="collapse">
                <i class="fa fa-minus"></i>
            </button>
        </div>
    </div>
    <div class="box-body">

        <div class="form-group row">
            <label for="face_book" class="col-md-4 col-form-label text-md-right">FaceBook</label>
            <div class="col-md-6">
                <input id="face_book" name="contacts[face_book]" type="text" class="form-control" value="{{$social_profile->face_book ?? ''}}">
            </div>
        </div>
        <div class="form-group row">
            <label for="linked_in" class="col-md-4 col-form-label text-md-right">LinkedIn</label>
            <div class="col-md-6">
                <input id="linked_in" name="contacts[linked_in]" type="text" class="form-control" value="{{$social_profile->linked_in ?? ''}}">
            </div>
        </div>

        <div class="form-group row">
            <label for="v_kontakte" class="col-md-4 col-form-label text-md-right">Вконтакте</label>
            <div class="col-md-6">
                <input id="v_kontakte" name="contacts[v_kontakte]" type="text" class="form-control" value="{{$social_profile->v_kontakte ?? ''}}">
            </div>
        </div>

        <div class="form-group row">
            <label for="odnoklasniki" class="col-md-4 col-form-label text-md-right">Одноклассники</label>
            <div class="col-md-6">
                <input id="odnoklasniki" name="contacts[odnoklasniki]" type="text" class="form-control" value="{{$social_profile->odnoklasniki ?? '' }}">
            </div>
        </div>
    </div>
</div>