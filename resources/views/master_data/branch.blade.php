@extends('layouts.app')


@section('css')
@endsection

@section('content')
<div class="container">
    <div class="card">
        <div class="card-header">
            <button id="create-button" class="btn btn-primary" data-url="{{$create_url}}"><i class="fa fa-plus"></i> Create</button>
        </div>
        <div class="card-body">
            <table id="branch" class="table table-hover" style="width: 100%"></table>
        </div>
    </div>
</div>

<div class="modal fade" id="modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" style="display: none;">
    <div class="modal-dialog modal-dialog-centered modal-info" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title"><i class="fa fa-book"></i> <span>Create</span></h4>
            </div>
            <form id="create-form">
                <div class="modal-body">
                    <div class="form-group row">
                        <label for="create-title" class="col-sm-3 col-form-label">Kode Wilayah</label>
                        <div class="col-sm-9">
                            <input name="kode_wilayah" type="text" class="form-control" maxlength="25" required>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="create-title" class="col-sm-3 col-form-label">Kode Cabang</label>
                        <div class="col-sm-9">
                            <input name="kode_cabang" type="text" class="form-control" maxlength="25" required>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="create-title" class="col-sm-3 col-form-label">Kode Outlet</label>
                        <div class="col-sm-9">
                            <input name="kode_outlet" type="text" class="form-control" maxlength="25" required>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="create-title" class="col-sm-3 col-form-label">Singkatan Cabang</label>
                        <div class="col-sm-9">
                            <input name="abr_cabang" type="text" class="form-control" maxlength="25" required>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="create-title" class="col-sm-3 col-form-label">Nama Outlet</label>
                        <div class="col-sm-9">
                            <input name="nama_outlet" type="text" class="form-control" maxlength="25" required>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="create-title" class="col-sm-3 col-form-label">Status</label>
                        <div class="col-sm-9">
                            <select class="form-control" name="status">
                                <option value="kc">Kantor Cabang</option>
                                <option value="kcp">Kantor Cabang Pembantu</option>
                                <option value="kk">KK</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Close</button>
                    <button class="btn btn-info modal-create-save" type="submit">Save changes</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection


@section('javascript')
<script>
$(document).ready(function(){
    var ajax = {
        url: "{{ route('master_data.branch.view',['json' => true]) }}",
    };
    var column = [
        {
            title: "Kode Wilayah",
            orderable: false,
            data: null,
            name: 'code_wilayah',
            render: function (data, type, row, meta) {
                return data.code_wilayah;
            }
        },
        {
            title: "Kode Cabang",
            orderable: false,
            data: null,
            name: 'code_cabang',
            render: function (data, type, row, meta) {
                return data.code_cabang;
            }
        },
        {
            title: "Kode Outlet",
            orderable: false,
            data: null,
            name: 'code_outlet',
            render: function (data, type, row, meta) {
                return data.code_outlet;
            }
        },
        {
            title: "Singkatan Cabang",
            orderable: false,
            data: null,
            name: 'abr_cabang',
            render: function (data, type, row, meta) {
                return data.abr_cabang;
            }
        },
        {
            title: "Nama Outlet",
            orderable: false,
            data: null,
            name: 'name_outlet',
            render: function (data, type, row, meta) {
                return data.name_outlet;
            }
        },
        {
            title: "Status",
            orderable: false,
            data: null,
            name: 'status',
            render: function (data, type, row, meta) {
                return data.status;
            }
        },
        {
            title: "Action",
            orderable: false,
            data: null,
            name: 'action',
            render: function (data, type, row, meta) {
                return '<button class="btn btn-success edit" title="Edit"><i class="cil cil-pencil" aria-hidden="true"></i></button> <button data-id="" class="btn btn-danger delete"><i class="cil cil-trash" aria-hidden="true"></i></button>';
            }
        },
    ]
    dtbuilder($("table#branch"), ajax, 'id', column);
    $("#create-button").on('click', function () {
        $("#modal form").attr('action', $(this).data('url'));
        $("#modal input").val('');
        $('#modal').modal('show');
    })

    $("#create-form").submit(function (e) {
        e.preventDefault();
        let url = $(this).attr('action')
        let form = $(this).serialize()
        post(url, form, $("table#branch"))
    });

    $("table#branch").on('click', 'tbody tr .delete', function () {
        del($("table#branch"),$(this))
    })

    $("table#branch").on('click', 'tbody tr .edit', function () {
        let data = $("table#branch").DataTable().row($(this).closest('tr')).data();

        $("#modal form").attr('action', data.update_url);
        $("#modal input").val('');
        $(".modal-title span").text('Update Branch');

        $('input[name=kode_wilayah]').val(data['code_wilayah']);
        $('input[name=kode_cabang]').val(data['code_cabang']);
        $('input[name=kode_outlet]').val(data['code_outlet']);
        $('input[name=abr_cabang]').val(data['abr_cabang']);
        $('input[name=nama_outlet]').val(data['name_outlet']);
        $('select[name=status]').val(data['status']);

        $('#modal').modal('show');
    })
})
</script>
@endsection
