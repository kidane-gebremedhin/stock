

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Manage
        <small>Groups</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">groups</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
      <!-- Small boxes (Stat box) -->
      <div class="row">
        <div class="col-md-12 col-xs-12">
          
          <?php if($this->session->flashdata('success')): ?>
            <div class="alert alert-success alert-dismissible" role="alert">
              <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
              <?php echo $this->session->flashdata('success'); ?>
            </div>
          <?php elseif($this->session->flashdata('error')): ?>
            <div class="alert alert-error alert-dismissible" role="alert">
              <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
              <?php echo $this->session->flashdata('error'); ?>
            </div>
          <?php endif; ?>

          <div class="box">
            <div class="box-header">
              <h3 class="box-title">Add Group</h3>
            </div>
            <form role="form" action="<?php base_url('groups/create') ?>" method="post">
              <div class="box-body">

                <?php echo validation_errors(); ?>

                <div class="form-group">
                  <div class="col-md-12" style="margin-bottom: 20px;">
                    <label class="col-md-2" for="group_name">Group Name</label>
                    <div class="col-md-6">
                      <input type="text" class="form-control" id="group_name" name="group_name" placeholder="Enter group name">
                    </div>
                  </div>
                </div>

                <div class="form-group">
                  <label for="permission">Permission</label>

                  <table class="table table-responsive table-hover">
                    <thead>
                      <tr>
                        <th> <label><strong>Allow All &nbsp; &nbsp; &nbsp; <input type="checkbox" value="all" class="minimal"></strong></label></th>
                        <th><label><input type="checkbox" value="create" class="minimal"> Create</label></th>
                        <th><label><input type="checkbox" value="update" class="minimal"> Update</label></th>
                        <th><label><input type="checkbox" value="view" class="minimal"> View</label></th>
                        <th><label><input type="checkbox" value="delete" class="minimal"> Delete</label></th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr>
                        <td>Users</td>
                        <td><input type="checkbox" name="permission[]" id="permission" value="createUser" class="create minimal"></td>
                        <td><input type="checkbox" name="permission[]" id="permission" value="updateUser" class="update minimal"></td>
                        <td><input type="checkbox" name="permission[]" id="permission" value="viewUser" class="view minimal"></td>
                        <td><input type="checkbox" name="permission[]" id="permission" value="deleteUser" class="delete minimal"></td>
                      </tr>
                      <tr>
                        <td>Groups</td>
                        <td><input type="checkbox" name="permission[]" id="permission" value="createGroup" class="create minimal"></td>
                        <td><input type="checkbox" name="permission[]" id="permission" value="updateGroup" class="update minimal"></td>
                        <td><input type="checkbox" name="permission[]" id="permission" value="viewGroup" class="view minimal"></td>
                        <td><input type="checkbox" name="permission[]" id="permission" value="deleteGroup" class="delete minimal"></td>
                      </tr>
                      <tr>
                        <td>Brands</td>
                        <td><input type="checkbox" name="permission[]" id="permission" value="createBrand" class="create minimal"></td>
                        <td><input type="checkbox" name="permission[]" id="permission" value="updateBrand" class="update minimal"></td>
                        <td><input type="checkbox" name="permission[]" id="permission" value="viewBrand" class="view minimal"></td>
                        <td><input type="checkbox" name="permission[]" id="permission" value="deleteBrand" class="delete minimal"></td>
                      </tr>
                      <tr>
                        <td>Units</td>
                        <td><input type="checkbox" name="permission[]" id="permission" value="createUnit" class="create minimal"></td>
                        <td><input type="checkbox" name="permission[]" id="permission" value="updateUnit" class="update minimal"></td>
                        <td><input type="checkbox" name="permission[]" id="permission" value="viewUnit" class="view minimal"></td>
                        <td><input type="checkbox" name="permission[]" id="permission" value="deleteUnit" class="delete minimal"></td>
                      </tr>
                      <tr>
                        <td>Category</td>
                        <td><input type="checkbox" name="permission[]" id="permission" value="createCategory" class="create minimal"></td>
                        <td><input type="checkbox" name="permission[]" id="permission" value="updateCategory" class="update minimal"></td>
                        <td><input type="checkbox" name="permission[]" id="permission" value="viewCategory" class="view minimal"></td>
                        <td><input type="checkbox" name="permission[]" id="permission" value="deleteCategory" class="delete minimal"></td>
                      </tr>
                      <tr>
                        <td>Stores</td>
                        <td><input type="checkbox" name="permission[]" id="permission" value="createStore" class="create minimal"></td>
                        <td><input type="checkbox" name="permission[]" id="permission" value="updateStore" class="update minimal"></td>
                        <td><input type="checkbox" name="permission[]" id="permission" value="viewStore" class="view minimal"></td>
                        <td><input type="checkbox" name="permission[]" id="permission" value="deleteStore" class="delete minimal"></td>
                      </tr>
                      <tr>
                        <td>Attributes</td>
                        <td><input type="checkbox" name="permission[]" id="permission" value="createAttribute" class="create minimal"></td>
                        <td><input type="checkbox" name="permission[]" id="permission" value="updateAttribute" class="update minimal"></td>
                        <td><input type="checkbox" name="permission[]" id="permission" value="viewAttribute" class="view minimal"></td>
                        <td><input type="checkbox" name="permission[]" id="permission" value="deleteAttribute" class="delete minimal"></td>
                      </tr>
                      <tr>
                        <td>Medicines and Drugs</td>
                        <td><input type="checkbox" name="permission[]" id="permission" value="createProduct" class="create minimal"></td>
                        <td><input type="checkbox" name="permission[]" id="permission" value="updateProduct" class="update minimal"></td>
                        <td><input type="checkbox" name="permission[]" id="permission" value="viewProduct" class="view minimal"></td>
                        <td><input type="checkbox" name="permission[]" id="permission" value="deleteProduct" class="delete minimal"></td>
                      </tr>
                      <tr>
                        <td>Orders</td>
                        <td><input type="checkbox" name="permission[]" id="permission" value="createOrder" class="create minimal"></td>
                        <td><input type="checkbox" name="permission[]" id="permission" value="updateOrder" class="update minimal"></td>
                        <td><input type="checkbox" name="permission[]" id="permission" value="viewOrder" class="view minimal"></td>
                        <td><input type="checkbox" name="permission[]" id="permission" value="deleteOrder" class="delete minimal"></td>
                      </tr>
                      <tr>
                        <td>Reports</td>
                        <td> - </td>
                        <td> - </td>
                        <td><input type="checkbox" name="permission[]" id="permission" value="viewReports" class="view minimal"></td>
                        <td> - </td>
                      </tr>
                      <tr>
                        <td>Company</td>
                        <td> - </td>
                        <td><input type="checkbox" name="permission[]" id="permission" value="updateCompany" class="update minimal"></td>
                        <td> - </td>
                        <td> - </td>
                      </tr>
                      <tr>
                        <td>Profile</td>
                        <td> - </td>
                        <td> - </td>
                        <td><input type="checkbox" name="permission[]" id="permission" value="viewProfile" class="view minimal"></td>
                        <td> - </td>
                      </tr>
                      <tr>
                        <td>Setting</td>
                        <td>-</td>
                        <td><input type="checkbox" name="permission[]" id="permission" value="updateSetting" class="update minimal"></td>
                        <td> - </td>
                        <td> - </td>
                      </tr>
                    </tbody>
                  </table>
                  
                </div>
              </div>
              <!-- /.box-body -->

              <div class="box-footer">
                <button type="submit" class="btn btn-primary">Save Changes</button>
                <a href="<?php echo base_url('groups/') ?>" class="btn btn-warning">Back</a>
              </div>
            </form>
          </div>
          <!-- /.box -->
        </div>
        <!-- col-md-12 -->
      </div>
      <!-- /.row -->
      

    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->

<script type="text/javascript">
  $(document).ready(function() {
    $("#mainGroupNav").addClass('active');
    $("#addGroupNav").addClass('active');

    /*$('input[type="checkbox"].minimal').iCheck({
      checkboxClass: 'icheckbox_minimal-blue',
      radioClass   : 'iradio_minimal-blue'
    });*/
    /*$('input[type="checkbox"].minimal, input[type="radio"].minimal').iCheck({
      checkboxClass: 'icheckbox_flat-green',
      radioClass   : 'iradio_flat-green'
    })*/

  });


  $(document).on('change', '.minimal', function(e){
    var type=$(this).val();
    switch (type){
      case 'create':
        if($(this).is(':checked'))
          $('.create').attr('checked', true)
        else
          $('.create').removeAttr('checked')
        break;
      case 'update':
        if($(this).is(':checked'))
          $('.update').attr('checked', true)
        else
          $('.update').removeAttr('checked')
        break;
      case 'view':
        if($(this).is(':checked'))
          $('.view').attr('checked', true)
        else
          $('.view').removeAttr('checked')
        break;
      case 'delete':
        if($(this).is(':checked'))
          $('.delete').attr('checked', true)
        else
          $('.delete').removeAttr('checked')
        break;
      case 'all':
        if($(this).is(':checked'))
          $('.minimal').attr('checked', true)
        else
          $('.minimal').removeAttr('checked')
        break;
      default:
        break;

    }
    })

  </script>

