<!-- Content Wrapper -->
<div id="content-wrapper" class="d-flex flex-column">

    <!-- Main Content -->
    <div id="content">
        <!-- Begin Page Content -->
        <div class="container-fluid">
            <!-- DataTales Example -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Category Product</h6>
                </div>
                <div><button type="button" data-toggle="modal" class="btn btn-primary ml-2 mt-2" data-target="#createcategory">Create</button></div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Category Name</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <?php
                                    $no = 1;
                                    foreach ($category as $c) {
                                    ?>
                                        <td> <?= $no++; ?> </td>
                                        <td> <?php echo $c->name_category; ?> </td>
                                        <td>
                                            <button class="btn showedit" data-toggle="modal" data-target="#editcategory" data-category='<?= json_encode($c) ?>'><i class="fas fa-pen"></i></button>
                                            <button class="btn delete " data-category='<?= json_encode($c) ?>'><i class="fas fa-trash"></i></button>
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

    <!-- Modal Create Category -->
    <div class="modal fade" id="createcategory" tabindex="-1" role="dialog" aria-labelledby="createcategoryLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="createcategoryLabel">Create category Product</h5>
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
                                        <label for="exampleInputEmail1">Category Name </label><label style="font-size: 12px; color: red;"> *required</label>
                                        <input type="text" name="name_category" class="form-control required" placeholder="Category Name">
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
    <!-- End Create Category -->

    <!-- Modal edit Category -->
    <div class="modal fade" id="editcategory" tabindex="-1" role="dialog" aria-labelledby="editcategoryLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editcategoryLabel">Edit Product</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-10">
                            <div class="modal-body">
                                <form id="editcategory">
                                    <input type="hidden" readonly name="category_id" id="category_id" class="form-control">
                                    <div class="form-group">
                                        <label for="exampleInputEmail1">Category Name </label><label style="font-size: 12px; color: red;"> *required</label>
                                        <input type="text" name="name_category" id="category_name" class="form-control required" placeholder="Category Name">
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
    <!-- End edit Category -->



    <script src="assets/vendor/jquery/jquery.min.js"></script>
    <script src="assets/dist/notiflix-3.2.5.min.js"></script>
    <script>
        const BASE_URL_API = `<?= getenv('BASE_URL_API') ?>`
        $(function() {
            $('#create').on('submit', function(e) {
                var createD = new FormData(this);
                e.preventDefault()
                $.ajax({
                    url: `${BASE_URL_API}api/category`,
                    type: "POST",
                    dataType: "JSON",
                    data: createD,
                    processData: false,
                    contentType: false,
                    beforeSend: () => {

                    }
                }).done((res) => {
                    $('#createcategory').modal('hide')
                    window.location.href = ""
                }).fail((error) => {});
            })
            $('.showedit').on('click', function() {
                const category = $(this).data('category');
                $('#category_id').val(category.id);
                $("#category_name").val(category.name_category);
            })
            $('#editcategory').on('submit', function(e) {
                e.preventDefault()
                const id = $('#category_id').val();
                var dataC = {
                    name_category: $("#category_name").val()
                }
                console.log(dataC);

                $.ajax({
                    url: `${BASE_URL_API}api/category/update/${id}`,
                    type: "PUT",
                    dataType: "JSON",
                    contentType: 'application/json',
                    processData: false,
                    data: JSON.stringify(dataC),
                    beforeSend: () => {

                    }
                }).done((res) => {
                    $('#editcategory').modal('hide')
                    window.location.href = ""
                }).fail((error) => {});
            })
            $('.delete').on('click', function(e) {
                const category = $(this).data('category');
                const id = category.id
                console.log(id);
                e.preventDefault()
                Notiflix.Confirm.show(
                    'Delete category',
                    'Do you agree with me?',
                    'Yes',
                    'No',
                    () => {
                        $.ajax({
                            url: `${BASE_URL_API}api/category/delete/${id}`,
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