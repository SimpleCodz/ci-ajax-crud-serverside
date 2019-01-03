<!DOCTYPE html>
<html lang="en">
<head>
    <title>(CRUD) Ajax Jquery CodeIgniter Server-Side | SimpleCodz</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="<?=base_url();?>assets/bootstrap-4/css/bootstrap.min.css">
    <link rel="stylesheet" href="<?=base_url();?>assets/font-awesome-4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="<?=base_url();?>assets/DataTables-1.10.18/css/dataTables.bootstrap4.min.css">
</head>
<body>

    <div class="container pt-4">
        <h1 class="h3 text-center">CodeIgniter Ajax CRUD using Jquery with Datatables Server-side</h1>
        <p class="small text-center">by <a href="https://simplecodz.blogspot.com">SimpleCodz</a></p>
    
        <div class="mt-5 mb-4">
            <h4 class="card-title text-center">Contact List</h4>
            <button class="btn btn-sm btn-primary" onclick="new_contact()"><i class="fa fa-plus"></i> New Contact</button>
            <button class="btn btn-sm btn-secondary" onclick="reload()"><i class="fa fa-refresh"></i> Reload</button>
        </div>

        <table id="contact-list" class="table table-striped table-bordered">
            <thead>
                <tr>
                    <th>Phone Number</th>
                    <th>Name</th>
                    <th>Gender</th>
                    <th>Email</th>
                    <th>Address</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>

            </tbody>
            <tfoot>
                <tr>
                    <th>Phone Number</th>
                    <th>Name</th>
                    <th>Gender</th>
                    <th>Email</th>
                    <th>Address</th>
                    <th>Action</th>
                </tr>
            </tfoot>
        </table>
    </div>

    <!-- Bootstrap modal -->
    <div class="modal fade" id="modal_form" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modal_title">Contact</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body form">
                    <form action="#" id="form" class="form-horizontal">
                        <input type="hidden" value="" name="id"/> 
                        <div class="form-body">
                            <div class="form-group">
                                <label class="control-label">Phone Number</label>
                                <input name="phone_number" placeholder="Phone Number" class="form-control" type="text">
                                <span class="invalid-feedback"></span>
                            </div>
                            <div class="form-group">
                                <label class="control-label">Name</label>
                                <input name="name" placeholder="Name" class="form-control" type="text">
                                <span class="invalid-feedback"></span>
                            </div>
                            <div class="form-group">
                                <label class="control-label">Gender</label>
                                <select name="gender" class="form-control">
                                    <option value="">--Select Gender--</option>
                                    <option value="male">Male</option>
                                    <option value="female">Female</option>
                                </select>
                                <span class="invalid-feedback"></span>
                            </div>
                            <div class="form-group">
                                <label class="control-label">Email</label>
                                <input name="email" placeholder="Email" class="form-control" type="text">
                                <span class="invalid-feedback"></span>
                            </div>
                            <div class="form-group">
                                <label class="control-label">Address</label>
                                <textarea name="address" placeholder="Address" class="form-control"></textarea>
                                <span class="invalid-feedback"></span>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" id="btnSave" onclick="save()" class="btn btn-primary">Save</button>
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
    <!-- End Bootstrap modal -->

    <script src="<?=base_url();?>assets/jquery-3.3.1/jquery-3.3.1.min.js"></script>
    <script src="<?=base_url();?>assets/popper/popper.min.js"></script>
    <script src="<?=base_url();?>assets/bootstrap-4/js/bootstrap.min.js"></script>
    <script src="<?=base_url();?>assets/DataTables-1.10.18/js/jquery.dataTables.min.js"></script>
    <script src="<?=base_url();?>assets/DataTables-1.10.18/js/dataTables.bootstrap4.min.js"></script>
    <script src="<?=base_url();?>assets/sweetalert2/sweetalert2.all.min.js"></script>

    <script type="text/javascript">
    var save_label;
    var table;

    $(document).ready(function() {
        //DataTable
        table = $('#contact-list').DataTable({
            "processing": true,
            "serverSide": true,
            "order": [],
            "ajax": {
                "url": "<?=base_url('contact/list')?>",
                "type": "POST"
            },
            "columnDefs": [{
                "targets": [-1],
                "orderable": false,
            }],
        });

        $('#modal_form').on('hidden.bs.modal', function (e) {
            var inputs = $('#form input, #form textarea, #form select');
            inputs.removeClass('is-valid is-invalid');
        });
    });

    function swalert(method){
        Swal({
            title: 'Success',
            text: 'Data has been '+method,
            type: 'success'
        });
    };

    function new_contact()
    {
        save_label = 'add';
        $('#form')[0].reset(); // reset form on modals
        $('.form-group').removeClass('has-error'); // clear error class
        $('.help-block').empty(); // clear error string
        $('#modal_form').modal('show'); // show bootstrap modal
        $('.modal-title').text('New Contact'); // Set Title to Bootstrap modal title
    }

    function edit_contact(id)
    {
        save_label = 'update';
        $('#form')[0].reset(); // reset form on modals
        $('.form-group').removeClass('has-error'); // clear error class
        $('.help-block').empty(); // clear error string

        //Ajax Load data from ajax
        $.ajax({
            url : "<?=base_url('contact/edit/')?>/" + id,
            type: "GET",
            dataType: "JSON",
            success: function(data)
            {
                $('[name="id"]').val(data.id);
                $('[name="phone_number"]').val(data.phone_number);
                $('[name="name"]').val(data.name);
                $('[name="gender"]').val(data.gender);
                $('[name="email"]').val(data.email);
                $('[name="address"]').val(data.address);
                $('#modal_form').modal('show'); // show bootstrap modal when complete loaded
                $('.modal-title').text('Edit Contact'); // Set title to Bootstrap modal title
            },
            error: function (jqXHR, textStatus, errorThrown)
            {
                alert('Error get data from ajax');
            }
        });
    }

    function reload()
    {
        table.ajax.reload(null,false); //reload datatable ajax 
    }

    function save()
    {
        $('#btnSave').text('saving...'); //change button text
        $('#btnSave').attr('disabled',true); //set button disable 
        var url, method;

        if(save_label == 'add') {
            url = "<?=base_url('contact/add')?>";
            method = 'saved';
        } else {
            url = "<?=base_url('contact/update')?>";
            method = 'updated'
        }

        // ajax adding data to database
        $.ajax({
            url : url,
            type: "POST",
            data: $('#form').serialize(),
            dataType: "json",
            success: function(data)
            {
                //console.log(data);
                if(data.status) //if success close modal and reload ajax table
                {
                    $('#modal_form').modal('hide');
                    reload();
                    swalert(method);
                }
                else
                {
                    $.each(data.errors, function(key, value){
                        $('[name="'+key+'"]').addClass('is-invalid'); //select parent twice to select div form-group class and add has-error class
                        $('[name="'+key+'"]').next().text(value); //select span help-block class set text error string
                        if(value == ""){
                            $('[name="'+key+'"]').removeClass('is-invalid');
                            $('[name="'+key+'"]').addClass('is-valid');
                        }
                    });
                }
                $('#btnSave').text('save'); //change button text
                $('#btnSave').attr('disabled',false); //set button enable 
            },
            error: function (jqXHR, textStatus, errorThrown)
            {
                alert('Error adding / update data');
                $('#btnSave').text('save'); //change button text
                $('#btnSave').attr('disabled',false); //set button enable 
            }
        });

        $('#form input, #form textarea').on('keyup', function(){
            $(this).removeClass('is-valid is-invalid');            
        });
        $('#form select').on('change', function(){
            $(this).removeClass('is-valid is-invalid');
        });
    }

    function delete_contact(id)
    {
        Swal({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if(result.value) {
                // ajax delete data to database
                $.ajax({
                    url : "<?=base_url('contact/delete')?>/"+id,
                    type: "POST",
                    dataType: "JSON",
                    success: function(data)
                    {
                        reload();
                        swalert('deleted');
                    },
                    error: function (jqXHR, textStatus, errorThrown)
                    {
                        alert('Error deleting data');
                    }
                });
            }
        });
    }
    </script>
</body>
</html>