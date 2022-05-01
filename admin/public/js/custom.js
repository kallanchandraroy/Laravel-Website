$(document).ready(function () {
    $('#VisitorDt').DataTable();
    $('.dataTables_length').addClass('bs-select');
});

function getServicesData() {

    axios.get('/getServicesData')
        .then(function (response) {

            if (response.status == 200) {
                $('#mainDiv').removeClass('d-none');
                $('#loaderDiv').addClass('d-none');

                $('#service_table').empty();

                var jsonData = response.data;
                $.each(jsonData, function (i, item) {

                    $('<tr>').html(

                        "<td><img class='table-img' src=" + jsonData[i].service_img + "></td>" +
                        "<td>" + jsonData[i].service_name + "</td>" +
                        "<td>" + jsonData[i].service_des + "</td>" +
                        "<td><a href=''><i class='fas fa-edit'></i></a></td>" +
                        "<td><a  class='serviceDeleteBtn' data-id=" + jsonData[i].id + "  ><i class='fas fa-trash-alt'></i></a></td>"

                    ).appendTo('#service_table');
                });

                $('.serviceDeleteBtn').click(function () {
                    var id = $(this).data('id');

                    $('#serviceDeleteConfirmBtn').attr('data-id', id);
                    $('#deleteModal').modal('show');
                })

                $('#serviceDeleteConfirmBtn').click(function () {
                    var id = $(this).data('id');
                    getServiceDelete(id);
                })
            } else {

                $('#loaderDiv').addClass('d-none');
                $('#WrongDiv').removeClass('d-none');
            }

        }).catch(function (error) {

            $('#loaderDiv').addClass('d-none');
            $('#WrongDiv').removeClass('d-none');
        });

}



function getServiceDelete(deleteID) {
    axios.post('/ServiceDelete', {
            id: deleteID
        })
        .then(function (response) {

            if (response.data == 1) {
                //alert('Success');
                $('#deleteModal').modal('hide');
                getServicesData();
                toastr.success('Delete Successfully');

            } else {
                //alert('fail');
                $('#deleteModal').modal('hide');
                getServicesData();
                toastr.error('Delete Fail');
            }
        })
        .catch(function (error) {


        });
}
