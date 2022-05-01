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
                        "<td><a class='serviceEditBtn' data-id=" + jsonData[i].id + " ><i class='fas fa-edit'></i></a></td>" +
                        "<td><a  class='serviceDeleteBtn' data-id=" + jsonData[i].id + "  ><i class='fas fa-trash-alt'></i></a></td>"

                    ).appendTo('#service_table');
                });

                //Service Delete Icon Click
                $('.serviceDeleteBtn').click(function () {
                    var id = $(this).data('id');
                    $('#serviceDeleteId').html(id);
                    //$('#serviceDeleteConfirmBtn').attr('data-id', id);
                    $('#deleteModal').modal('show');
                })

                //Service Delete Modal Yes Btn
                $('#serviceDeleteConfirmBtn').click(function () {
                    var id = $(this).data('id');
                    getServiceDelete(id);
                })


                //Service Table Edit Icon Click
                $('.serviceEditBtn').click(function () {
                    var id = $(this).data('id');
                    $('#serviceEditId').html(id);
                    ServiceUpdateDetails(id);
                    //$('#serviceEditConfirmBtn').attr('data-id', id);
                    $('#editModal').modal('show');
                })


                  //Service Edit Update Modal Yes Btn
                  $('#serviceEditConfirmBtn').click(function () {
                    var id = $('#serviceEditId').html()
                    var name = $('#serviceNameID').val();
                    var des = $('#serviceDesID').val();
                    var img = $('#serviceImgID').val();

                    ServiceUpdate(id,name,des,img);
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


//Each Services Delete
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

//Each Services Update Deatils Edit
function ServiceUpdateDetails(detailsID) {
    axios.post('/ServiceDetails', {
            id: detailsID
        })
        .then(function (response) {
            if (response.status == 200) {
                $('#serviceEditForm').removeClass('d-none');
                $('#serviceEditLoader').addClass('d-none');

                var jsonData = response.data;
                $('#serviceNameID').val(jsonData[0].service_name);
                $('#serviceDesID').val(jsonData[0].service_des);
                $('#serviceImgID').val(jsonData[0].service_img);


            } else {
                $('#serviceEditLoader').addClass('d-none');
                $('#serviceEditWrong').removeClass('d-none');
            }

        })
        .catch(function (error) {
            $('#serviceEditLoader').addClass('d-none');
            $('#serviceEditWrong').removeClass('d-none');

        });
}


//Each Services Update
function ServiceUpdate(serviceID,serviceName,serviceDes,serviceImg) {

    if(serviceName.length==0){
        toastr.error('Service Name is Empty!');

    }else if(serviceDes.length==0){
        toastr.error('Service Description is Empty!');
    }else if(serviceImg.length==0){
        toastr.error('Service Image is Empty!');
    }else{
        axios.post('/ServiceUpdate', {
            id: serviceID,
            name: serviceName,
            des: serviceDes,
            img: serviceImg,
        })
        .then(function (response) {
            if (response.data == 1) {

                $('#editeModal').modal('hide');
                getServicesData();
                toastr.success('Update Successfully');

            } else {

                $('#editeModal').modal('hide');
                getServicesData();
                toastr.error('Update Fail');
            }

        })
        .catch(function (error) {


        });
    }

}
