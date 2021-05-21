function dtbuilder(element, ajax, row_id, column) {
    if ($.fn.DataTable.isDataTable(element)) {
        element.DataTable().clear();
        element.DataTable().destroy();
        element.empty();
    }

    element.DataTable({
        processing:true,
        language: {
            processing: '<div class="loading-spinner"></div>'
        },
        bLengthChange: false,
        bFilter: false,
        order: [],
        scrollX: true,
        serverSide: true,
        ajax: ajax,
        rowId:row_id,
        columns:column
    });
}

function post(url, form, table) {
    $.ajax({
        method: "POST",
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        url: url,
        data: form
    }).done(function (res) {
        Swal.fire({
            text: "Success",
            icon: "success",
        })
        table.DataTable().ajax.reload();
        $('#modal').modal('hide');
    }).fail(function (res) {
        Swal.fire({
            icon: 'error',
            text: res.responseJSON,
        })
    });
}

function del(element,self){
    let data = element.DataTable().row(self.closest('tr')).data();
    console.log(data)
    Swal.fire({
        title: "Are you sure?",
        text: "Once deleted, you will not be able to recover this data!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonText: 'Yes',
        cancelButtonText: 'No',
    })
    .then((result) => {
        if (result.isConfirmed === true) {
            $.ajax({
                method: "GET",
                url: data.delete_url,
            }).done(function (res) {
                element.DataTable().ajax.reload();
                Swal.fire({
                    text: "Success",
                    icon: "success",
                })
            }).fail(function (res) {
                Swal.fire({
                    icon: 'error',
                    text: res.responseJSON,
                })
            });
        }
    })
}
