<!-- Content Wrapper -->
<div id="content-wrapper" class="d-flex flex-column">

    <!-- Main Content -->
    <div id="content">
        <!-- Begin Page Content -->
        <div class="container-fluid">
            <!-- DataTales Example -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Product</h6>
                </div>
                <div><button type="button" data-toggle="modal" class="btn btn-primary ml-2 mt-2" data-target="#createproduct">Create</button></div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Image</th>
                                    <th>Product Name</th>
                                    <th>Product Code</th>
                                    <th>Category Name</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <?php
                                    $no = 1;
                                    foreach ($data['product'] as $p) {
                                    ?>
                                        <td> <?= $no++; ?> </td>
                                        <td><img src="<?php echo 'http://localhost/backend-uas/assets/img/upload/' . $p->image ?>" height="20px"></td>
                                        <td> <?php echo $p->name_product ?> </td>
                                        <td> <?php echo $p->code_product; ?> </td>
                                        <td> <?php echo $p->name_category; ?> </td>
                                        <td>
                                            <button class="btn info" data-toggle="modal" data-target="#detailproduct" data-product='<?= json_encode($p) ?>'><i class="fas fa-info"></i></button>
                                            <button class="btn showedit" data-toggle="modal" data-target="#editproduct" data-product='<?= json_encode($p) ?>'><i class="fas fa-pen"></i></button>
                                            <button class="btn delete " data-product='<?= json_encode($p) ?>'><i class="fas fa-trash"></i></button>
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

    <!-- Modal detail product -->
    <div class="modal fade" id="detailproduct" tabindex="-1" role="dialog" aria-labelledby="detailproductLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title product_title" id="detailproductLabel"></h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-6">
                            <img src="" id="product_img" alt="" width="225px">
                        </div>
                        <div class="col-6">
                            <p class="product_long"></p>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal edit product -->
    <div class="modal fade" id="editproduct" tabindex="-1" role="dialog" aria-labelledby="editproductLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editproductLabel">Edit Product</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-10">
                            <div class="modal-body">
                                <form id="edit">
                                    <input type="hidden" readonly name="product_id" id="product_id" class="form-control" placeholder="Product Name">
                                    <div class="form-group">
                                        <label for="exampleInputEmail1">Product Name</label>
                                        <input type="text" name="name_product" id="product_name" class="form-control" placeholder="Product Name">
                                    </div>
                                    <div class="form-group">
                                        <label for="exampleInputPassword1">Product Code</label>
                                        <input type="number" name="code_product" id="product_code" class="form-control" placeholder="Product Code">
                                    </div>
                                    <div class="mb-3">
                                        <input type="hidden" id="base64input" name="base64input">
                                        <label for="exampleFormControlInput1" class="form-label">Upload Image</label>
                                        <input class="" type="file" id="image_g" onchange="base64(this)" name="image" class="form-control" />
                                    </div>
                                    <div class="form-group">
                                        <label for="exampleFormControlSelect1">Category</label>
                                        <select name="id_category" id="product_category" class="form-control" id="exampleFormControlSelect1">
                                            <?php foreach ($data['category'] as $c) { ?>
                                                <option value="<?= $c->id; ?>" <?= (isset($data->id_detail) ? ($data->id_detail === $c->id ? 'selected' : '') : ''); ?>> <?= $c->name_category; ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="exampleFormControlSelect1">Description</label>
                                        <select name="id_detail" id="product_detail" class="form-control" id="exampleFormControlSelect1">
                                            <?php foreach ($data['detail'] as $d) { ?>
                                                <option value="<?= $d->id; ?>" <?= (isset($data->id_detail) ? ($data->id_detail === $d->id ? 'selected' : '') : ''); ?>> <?= $d->short_description; ?></option>
                                            <?php } ?>
                                        </select>
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

    <!-- Modal Create product -->
    <div class="modal fade" id="createproduct" tabindex="-1" role="dialog" aria-labelledby="createproductLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="createproductLabel">Create Product</h5>
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
                                        <label for="exampleInputEmail1">Product Name </label><label style="font-size: 12px; color: red;"> *required</label>
                                        <input type="text" name="name_product" class="form-control required" placeholder="Product Name">
                                    </div>
                                    <div class="form-group">
                                        <label for="exampleInputPassword1">Product Code</label> <label style="font-size: 12px; color: red;"> *required</label>
                                        <input type="text" name="code_product" class="form-control" placeholder="Product Code">
                                    </div>
                                    <div class="mb-3">
                                        <label for="exampleFormControlInput1" class="form-label">Upload Image</label>
                                        <input class="" type="file" name="image" class="form-control" />
                                    </div>
                                    <div class="form-group">
                                        <label for="exampleFormControlSelect1">Category</label>
                                        <select name="id_category" class="form-control" id="exampleFormControlSelect1">
                                            <?php foreach ($data['category'] as $c) { ?>
                                                <option value="<?= $c->id; ?>" <?= (isset($data->id_detail) ? ($data->id_detail === $c->id ? 'selected' : '') : ''); ?>> <?= $c->name_category; ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="exampleFormControlSelect1">Description</label>
                                        <select name="id_detail" class="form-control" id="exampleFormControlSelect1">
                                            <?php foreach ($data['detail'] as $d) { ?>
                                                <option value="<?= $d->id; ?>" <?= (isset($data->id_detail) ? ($data->id_detail === $d->id ? 'selected' : '') : ''); ?>> <?= $d->short_description; ?></option>
                                            <?php } ?>
                                        </select>
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











    <script src="assets/vendor/jquery/jquery.min.js"></script>
    <script src="assets/dist/notiflix-3.2.5.min.js"></script>
    <script>
        const BASE_URL_API = `<?= getenv('BASE_URL_API') ?>`
        $(function() {
            $('.info').on('click', function() {
                const product = $(this).data('product')
                $('.product_title').text(product.name_product)
                $('#product_img').prop('src', `${BASE_URL_API}/assets/img/upload/${product.image}`)
                $('.product_long').text(product.long_description)
                console.log(product.name_product)
            })
            $('#create').on('submit', function(e) {
                e.preventDefault()
                $.ajax({
                    url: `${BASE_URL_API}api/product/create`,
                    type: "POST",
                    dataType: "JSON",
                    data: new FormData(this),
                    processData: false,
                    contentType: false,
                    beforeSend: () => {

                    }
                }).done((res) => {
                    $('#createproduct').modal('hide')
                    window.location.href = ""
                }).fail((error) => {});
            })
            $('.showedit').on('click', function() {
                const product = $(this).data('product')
                $('#product_id').val(product.product_id);
                $("#product_name").val(product.name_product)
                $("#product_code").val(product.code_product)
                $("#image_g").val(product.image)
                $("#product_category").val(product.id)
                $("#product_detail ").val(product.detail_id)
            })
            $('#edit').on('submit', function(e) {
                e.preventDefault()
                const id = $('#product_id').val();
                var dataP = {
                    name_product: $("#product_name").val(),
                    code_product: $("#product_code").val(),
                    image: $("#base64input").val(),
                    id_category: $("#product_category").val(),
                    id_detail: $("#product_detail").val(),
                }
                console.log(dataP);
                $.ajax({
                    url: `${BASE_URL_API}api/product/update/${id}`,
                    type: "PUT",
                    dataType: "JSON",
                    contentType: 'application/json',
                    processData: false,
                    data: JSON.stringify(dataP),
                    beforeSend: () => {

                    }
                }).done((res) => {
                    $('#editproduct').modal('hide')
                    window.location.href = ""
                }).fail((error) => {});
            })

            $('.delete').on('click', function(e) {
                const product = $(this).data('product')
                const id = product.product_id
                e.preventDefault()
                Notiflix.Confirm.show(
                    'Delete Product',
                    'Do you agree with me?',
                    'Yes',
                    'No',
                    () => {
                        $.ajax({
                            url: `${BASE_URL_API}/api/product/delete/${id}`,
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

        function base64(element) {
            var file = element.files[0];
            var reader = new FileReader();
            reader.onloadend = function() {
                var res = reader.result.replace('data:image/jpeg;base64,', '')
                $("#base64input").val(res)
            }
            reader.readAsDataURL(file);
        }
    </script>