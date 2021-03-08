@extends('admin_panel.layouts.app')

@section('section_title')
    {{$content['title']}}
@endsection

@section('section_breadcrumbs')
    @include('admin_panel.partials.breadcrumbs', ['crumbs' => $content['crumbs']])
@endsection

@section('content')

    <div class="col-12">
        <div class="box  box-primary">
            <div class="box-body table-responsive no-padding">
                @include('admin_panel.news.partials.category_table', ['table' => $content['fields'], 'categories' => $categories, 'sort_by' => $sort_by, 'sort_order' => $sort_order])
            </div>
        </div>
        <div class="pull-right">
            {{ $categories->appends(['sort_by' => $sort_by, 'sort_order' => $sort_order])->onEachSide(2)->links() }}
        </div>
        <div class="pull-left">
            <a class="btn btn-primary btn-flat" href="{{route('admin.category.create')}}">Добавить</a>
        </div>

        <!-- Modal -->
        <div class="modal fade" id="modalCategory" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Выберите новую категорию для новостей</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group row">
                            <label for="parent_id" class="col-sm-5 col-form-label text-right">Перенести в категорию</label>
                            <div class="col-sm-7">
                                <select autocomplete="off" name="parent_id" id="parent_id" class="form-control">
                                    <option></option>
                                    @foreach($categories as $category)
                                        <option value="{{$category->id}}">{{$category->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Отмена</button>
                        <a href="javascript:;" id="delete_confirm" data-id="" class="btn btn-primary">Удалить</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('footer_js')
    <script>
        $(document).ready(function () {
            $('.delete').on('click', function () {
                $('#modalCategory').modal('show')
                    .on('hide.bs.modal', function (e) {
                        let sel = $('#parent_id');
                        sel.parent().find('option')
                            .prop('disabled', false);
                        sel.val('');
                });
                let select_row = $('#parent_id');
                let the_same = select_row.parent().find("option[value=" + $(this).data('id') + "]");
                the_same.prop('disabled', true);
                $('#delete_confirm').data('id', $(this).data('id'))
                    .on('click', function () {
                        let new_category = select_row.val();
                        $('<input>').attr({type: 'hidden', name: 'new_category', value: new_category}).appendTo('form');
                        $('#formDelete' + $(this).data('id')).submit();
                })
            });
        })
    </script>
@endsection