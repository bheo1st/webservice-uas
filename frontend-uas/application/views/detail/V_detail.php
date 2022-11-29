<!-- Content Wrapper -->
<div id="content-wrapper" class="d-flex flex-column">

    <!-- Main Content -->
    <div id="content">
        <!-- Begin Page Content -->
        <div class="container-fluid">
            <!-- DataTales Example -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Description Product</h6>
                </div>
                <div><button type="button" data-toggle="modal" class="btn btn-primary ml-2 mt-2" data-target="#createdetail">Create</button></div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Short Description</th>
                                    <th>Long Description</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <?php
                                    $no = 1;
                                    foreach ($detail as $d) {
                                    ?>
                                        <td> <?= $no++; ?> </td>
                                        <td> <?php echo $d->short_description ?> </td>
                                        <td> <?php echo $d->long_description; ?> </td>
                                        <td>
                                            <button class="btn showedit" data-toggle="modal" data-target="#editdetail" data-detail='<?= json_encode($d) ?>'><i class="fas fa-pen"></i></button>
                                            <button class="btn delete " data-detail='<?= json_encode($d) ?>'><i class="fas fa-trash"></i></button>
                                        </td>

                                </tr>
                            <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>
        <!-- /.container-fluid -->

    </div>
    <!-- End of Main Content -->



    <!-- Modal Create Detail -->
    <div class="modal fade" id="createdetail" tabindex="-1" role="dialog" aria-labelledby="createdetailLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="createdetailLabel">Create Detail Product</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-10">
                            <div class="modal-body">
                                <form id="create">
                                    <div class="form-group">
                                        <label for="exampleInputEmail1">Short Description </label><label style="font-size: 12px; color: red;"> *required</label>
                                        <input type="text" name="short_description" class="form-control required" placeholder="Short Description">
                                    </div>
                                    <div class="form-group">
                                        <label for="exampleInputPassword1">Long Description </label> <label style="font-size: 12px; color: red;"> *required</label>
                                        <input type="text" name="long_description" class="form-control" placeholder="Long Description">
                                    </div>

                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button " class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Save changes</button>
                        </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- End Create Detail -->

    <!-- Modal edit detail -->
    <div class="modal fade" id="editdetail" tabindex="-1" role="dialog" aria-labelledby="editdetailLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editdetailLabel">Edit Product</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-10">
                            <div class="modal-body">
                                <form id="editdetail">
                                    <input type="hidden" readonly name="detail_id" id="detail_id" class="form-control" placeholder="Product Name">
                                    <div class="form-group">
                                        <label for="exampleInputEmail1">Short Description </label><label style="font-size: 12px; color: red;"> *required</label>
                                        <input type="text" name="sort_description" id="detail_sort" class="form-control required" placeholder="Short Description">
                                    </div>
                                    <div class="form-group">
                                        <label for="exampleInputPassword1">Long Description </label> <label style="font-size: 12px; color: red;"> *required</label>
                                        <input type="text" name="long_description" id="detail_long" class="form-control" placeholder="Long Description">
                                    </div>

                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button " class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Save changes</button>
                        </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- End edit Detail -->

    <script src="assets/vendor/jquery/jquery.min.js"></script>
    <script src="assets/dist/notiflix-3.2.5.min.js"></script>

    <script>
        const BASE_URL_API = `<?= getenv('BASE_URL_API') ?>`
        $(function() {
            $('#create').on('submit', function(e) {
                var createD = new FormData(this);
                e.preventDefault()
                $.ajax({
                    url: `${BASE_URL_API}api/detail`,
                    type: "POST",
                    dataType: "JSON",
                    data: createD,
                    processData: false,
                    contentType: false,
                    beforeSend: () => {

                    }
                }).done((res) => {
                    $('#createdetail').modal('hide')
                    window.location.href = ""
                }).fail((error) => {});
            })
            $('.showedit').on('click', function() {
                const detail = $(this).data('detail');
                $('#detail_id').val(detail.id);
                $("#detail_sort").val(detail.short_description);
                $("#detail_long").val(detail.long_description);
            })
            $('#editdetail').on('submit', function(e) {
                e.preventDefault()
                const id = $('#detail_id').val();
                var dataE = {
                    short_description: $("#detail_sort").val(),
                    long_description: $("#detail_long").val()
                }
                console.log(dataE);

                $.ajax({
                    url: `${BASE_URL_API}api/detail/update/${id}`,
                    type: "PUT",
                    dataType: "JSON",
                    contentType: 'application/json',
                    processData: false,
                    data: JSON.stringify(dataE),
                    beforeSend: () => {

                    }
                }).done((res) => {
                    $('#editproduct').modal('hide')
                    window.location.href = ""
                }).fail((error) => {});
            })
            $('.delete').on('click', function(e) {
                const detail = $(this).data('detail');
                const id = detail.id
                e.preventDefault()
                Notiflix.Confirm.show(
                    'Delete detail',
                    'Do you agree with me?',
                    'Yes',
                    'No',
                    () => {
                        $.ajax({
                            url: `${BASE_URL_API}/api/detail/delete/${id}`,
                            type: "DELETE",
                            dataType: "JSON",
                            beforeSend: () => {

                            }
                        }).done((res) => {
                            window.location.href = ""
                        }).fail((error) => {});
                    }
                );

            })
        })
    </script>