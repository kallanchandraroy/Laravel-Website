@extends('Layout.app')
@section('title', 'Services')
@section('content')

    <div id="mainDiv" class="container d-none">
        <div class="row">
            <div class="col-md-12 p-3">
                <button id="addNewBtnId" class="btn my-3 btn-sm btn-danger">Add New </button>
                <table id="" class="table table-striped table-bordered" cellspacing="0" width="100%">
                    <thead>
                        <tr>
                            <th class="th-sm">Image</th>
                            <th class="th-sm">Name</th>
                            <th class="th-sm">Description</th>
                            <th class="th-sm">Edit</th>
                            <th class="th-sm">Delete</th>
                        </tr>
                    </thead>
                    <tbody id="service_table">


                    </tbody>
                </table>

            </div>
        </div>
    </div>

    <div id="loaderDiv" class="container">
        <div class="row">
            <div class="col-md-12 text-center p-5">
                <img class="loading-icon m-5" src="{{ asset('images/loader.svg') }}">
            </div>
        </div>
    </div>

    <div id="WrongDiv" class="container d-none">
        <div class="row">
            <div class="col-md-12 text-center p-5">
                <h3>Something Went Wrong !</h3>
            </div>
        </div>
    </div>


    <div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-body p-3 text-center">
                    <h5 class="mt-4">Do You Want To Delete?</h5>
                    <h5 id="serviceDeleteId" class="mt-4 d-none"> </h5>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-sm btn-primary" data-dismiss="modal">No</button>
                    <button data-id=" " id="serviceDeleteConfirmBtn" type="button"
                        class="btn  btn-sm  btn-danger">Yes</button>
                </div>
            </div>
        </div>
    </div>

    <!--Edit Modal-->
    <div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-body p-5 text-center">
                    <h5 id="serviceEditId" class="mt-4 d-none"> </h5>
                    <!-- Edit Section -->
                    <h4>Service Edit</h4>
                    <div id="serviceEditForm" class="d-none w-100">
                        <input id="serviceNameID" type="text" id="" class="form-control mb-4" placeholder="Service Name">
                        <input id="serviceDesID" type="text" id="" class="form-control mb-4" placeholder="Service Des">
                        <input id="serviceImgID" type="text" id="" class="form-control mb-4"
                            placeholder="Service Image Link">
                    </div>
                    <img id="serviceEditLoader" class="loading-icon m-5" src="{{ asset('images/loader.svg') }}">

                    <h5 id="serviceEditWrong" class="d-none">Something Went Wrong !</h5>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-sm btn-primary" data-dismiss="modal">Cancel</button>
                    <button data-id=" " id="serviceEditConfirmBtn" type="button"
                        class="btn  btn-sm  btn-danger">Save</button>
                </div>
            </div>
        </div>
    </div>


    <!--Add Modal-->
    <div class="modal fade" id="addModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-body p-5 text-center">

                    <h4>Service Add</h4>
                    <div id="serviceAddForm" class="w-100">
                        <input id="serviceNameaddID" type="text" id="" class="form-control mb-4" placeholder="Service Name">
                        <input id="serviceDesaddID" type="text" id="" class="form-control mb-4" placeholder="Service Des">
                        <input id="serviceImgaddID" type="text" id="" class="form-control mb-4"
                            placeholder="Service Image Link">
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-sm btn-primary" data-dismiss="modal">Cancel</button>
                    <button id="serviceAddConfirmBtn" type="button" class="btn  btn-sm  btn-danger">Add New</button>
                </div>
            </div>
        </div>
    </div>

@endsection


@section('script')

    <script type="text/javascript">
        getServicesData();



        function getServicesData() {
            axios
                .get("/getServicesData")
                .then(function(response) {
                    if (response.status == 200) {
                        $("#mainDiv").removeClass("d-none");
                        $("#loaderDiv").addClass("d-none");

                        $("#service_table").empty();

                        var jsonData = response.data;
                        $.each(jsonData, function(i, item) {
                            $("<tr>")
                                .html(
                                    "<td><img class='table-img' src=" +
                                    jsonData[i].service_img +
                                    "></td>" +
                                    "<td>" +
                                    jsonData[i].service_name +
                                    "</td>" +
                                    "<td>" +
                                    jsonData[i].service_des +
                                    "</td>" +
                                    "<td><a class='serviceEditBtn' data-id=" +
                                    jsonData[i].id +
                                    " ><i class='fas fa-edit'></i></a></td>" +
                                    "<td><a  class='serviceDeleteBtn' data-id=" +
                                    jsonData[i].id +
                                    "  ><i class='fas fa-trash-alt'></i></a></td>"
                                )
                                .appendTo("#service_table");
                        });

                        //Service Delete Icon Click
                        $(".serviceDeleteBtn").click(function() {
                            var id = $(this).data("id");
                            $("#serviceDeleteId").html(id);
                            $('#serviceDeleteConfirmBtn').attr('data-id', id);
                            $("#deleteModal").modal("show");
                        });



                        //Service Table Edit Icon Click
                        $(".serviceEditBtn").click(function() {
                            var id = $(this).data("id");
                            $("#serviceEditId").html(id);
                            ServiceUpdateDetails(id);
                            //$('#serviceEditConfirmBtn').attr('data-id', id);
                            $("#editModal").modal("show");
                        });


                    } else {
                        $("#loaderDiv").addClass("d-none");
                        $("#WrongDiv").removeClass("d-none");
                    }
                })
                .catch(function(error) {
                    $("#loaderDiv").addClass("d-none");
                    $("#WrongDiv").removeClass("d-none");
                });
        }



        //Service Delete Modal Yes Btn
        $("#serviceDeleteConfirmBtn").click(function() {
            //var id = $(this).data("id");
            var id = $('#serviceDeleteId').html();
            getServiceDelete(id);
        });


        //Each Services Delete
        function getServiceDelete(deleteID) {
            $("#serviceDeleteConfirmBtn").html(
            "<div class='spinner-border spinner-border-sm' role='status'></div>"); //loading animation
            axios
                .post("/ServiceDelete", {
                    id: deleteID,
                })
                .then(function(response) {
                    $("#serviceDeleteConfirmBtn").html("Yes");
                    if (response.status == 200) {
                        if (response.data == 1) {
                            $("#deleteModal").modal("hide");
                            getServicesData();
                            toastr.success("Delete Successfully");
                        } else {
                            $("#deleteModal").modal("hide");
                            getServicesData();
                            toastr.error("Delete Fail");
                        }
                    } else {
                        $('#deleteModal').modal('hide');
                        toastr.error('Something Went Wrong !');
                    }
                })
                .catch(function(error) {
                    $('#deleteModal').modal('hide');
                    toastr.error('Something Went Wrong !');
                });
        }

        //Each Services Update Deatils Edit
        function ServiceUpdateDetails(detailsID) {
            axios
                .post("/ServiceDetails", {
                    id: detailsID,
                })
                .then(function(response) {
                    if (response.status == 200) {
                        $("#serviceEditForm").removeClass("d-none");
                        $("#serviceEditLoader").addClass("d-none");

                        var jsonData = response.data;
                        $("#serviceNameID").val(jsonData[0].service_name);
                        $("#serviceDesID").val(jsonData[0].service_des);
                        $("#serviceImgID").val(jsonData[0].service_img);
                    } else {
                        $("#serviceEditLoader").addClass("d-none");
                        $("#serviceEditWrong").removeClass("d-none");
                    }
                })
                .catch(function(error) {
                    $("#serviceEditLoader").addClass("d-none");
                    $("#serviceEditWrong").removeClass("d-none");
                });
        }

        //Service Edit Update Modal Yes Btn
        $("#serviceEditConfirmBtn").click(function() {
            var id = $("#serviceEditId").html();
            var name = $("#serviceNameID").val();
            var des = $("#serviceDesID").val();
            var img = $("#serviceImgID").val();
            ServiceUpdate(id, name, des, img);
        });

        //Each Services Update
        function ServiceUpdate(serviceID, serviceName, serviceDes, serviceImg) {
            $("#serviceEditConfirmBtn").html(
            "<div class='spinner-border spinner-border-sm' role='status'></div>"); //loading animation
            if (serviceName.length == 0) {
                toastr.error("Service Name is Empty!");
            } else if (serviceDes.length == 0) {
                toastr.error("Service Description is Empty!");
            } else if (serviceImg.length == 0) {
                toastr.error("Service Image is Empty!");
            } else {
                axios
                    .post("/ServiceUpdate", {
                        id: serviceID,
                        name: serviceName,
                        des: serviceDes,
                        img: serviceImg,
                    })
                    .then(function(response) {
                        $("#serviceEditConfirmBtn").html("Save");
                        if (response.status == 200) {
                            if (response.data == 1) {
                                $("#editModal").modal("hide");
                                getServicesData();
                                toastr.success("Update Successfully");
                            } else {
                                $("#editModal").modal("hide");
                                getServicesData();
                                toastr.error("Update Fail");
                            }
                        } else {
                            $('#editModal').modal('hide');
                            toastr.error('Something Went Wrong !');
                        }
                    })
                    .catch(function(error) {
                        $('#editModal').modal('hide');
                        toastr.error('Something Went Wrong !');
                    });
            }
        }


        //Service Add new

        $('#addNewBtnId').click(function() {
            $('#addModal').modal('show');
        })


        //Service Add Modal Yes Btn
        $("#serviceAddConfirmBtn").click(function() {
            var name = $("#serviceNameaddID").val();
            var des = $("#serviceDesaddID").val();
            var img = $("#serviceImgaddID").val();
            ServiceAdd(name, des, img);
        });

        //Service Add Method
        function ServiceAdd(serviceName, serviceDes, serviceImg) {
            $("#serviceAddConfirmBtn").html(
            "<div class='spinner-border spinner-border-sm' role='status'></div>"); //loading animation
            if (serviceName.length == 0) {
                toastr.error("Service Name is Empty!");
            } else if (serviceDes.length == 0) {
                toastr.error("Service Description is Empty!");
            } else if (serviceImg.length == 0) {
                toastr.error("Service Image is Empty!");
            } else {
                axios
                    .post("/ServiceAdd", {
                        name: serviceName,
                        des: serviceDes,
                        img: serviceImg,
                    })
                    .then(function(response) {
                        $("#serviceAddConfirmBtn").html("Add New");
                        if (response.status == 200) {
                            if (response.data == 1) {
                                $("#addModal").modal("hide");
                                getServicesData();
                                toastr.success("Add Successfully");
                            } else {
                                $("#addModal").modal("hide");
                                getServicesData();
                                toastr.error("Add Fail");
                            }
                        } else {
                            $('#addModal').modal('hide');
                            toastr.error('Something Went Wrong !');
                        }
                    })
                    .catch(function(error) {
                        $('#addModal').modal('hide');
                        toastr.error('Something Went Wrong !');
                    });
            }
        }
    </script>

@endsection
