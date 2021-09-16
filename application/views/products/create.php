

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1>
      Manage
      <small><?php echo $_SESSION['Items'] ?></small>
    </h1>
    <ol class="breadcrumb">
      <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
      <li class="active"><?php echo $_SESSION['Items'] ?></li>
    </ol>
  </section>

  <!-- Main content -->
  <section class="content">
    <!-- Small boxes (Stat box) -->
    <div class="row">
      <div class="col-md-12 col-xs-12">

        <div id="messages"></div>

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
            <h3 class="box-title">Add <?php echo $_SESSION['Item'] ?></h3>
          </div>
          <!-- /.box-header -->
          <form role="form" action="<?php base_url('users/create') ?>" method="post" enctype="multipart/form-data">
              <div class="box-body">

                <?php echo validation_errors(); ?>

                <!-- <div class="form-group">

                  <label class="col-md-2" for="product_image"><strong class="pull-right">Medicine Image</strong></label>
                  <div class="kv-avatar col-md-6">
                      <div class="file-loading">
                          <input id="product_image" name="product_image" type="file">
                      </div>
                  </div>
                </div> -->

            <div class="col-md-12 col-sm-12 col-xs-12" style="overflow-x: scroll">
              <div class="col-md-4 col-xs-12">
                <div class="form-group">
                  <label for="product_name"><?php echo $_SESSION['Item'] ?> name</label>
                  <input type="text" class="form-control" id="product_name" name="product_name" placeholder="Enter product name" autocomplete="off" required/>
                </div>

                <div class="form-group">
                  <label for="sku">Identifier</label>
                  <input type="text" class="form-control" id="sku" name="sku" placeholder="Enter sku" autocomplete="off" required />
                </div>

                <!-- <div class="form-group">
                  <label for="price">Unit Price</label>
                  <input type="text" class="number form-control" id="price" name="price" placeholder="Enter price" autocomplete="off" required />
                </div>

                <div class="form-group">
                  <label for="qty">Qty</label>
                  <input type="text" class="number form-control" id="qty" name="qty" placeholder="Enter Qty" autocomplete="off" required />
                </div> -->

                <div class="form-group">
                  <label for="minQty">Min Qty</label>
                  <input type="text" class="number form-control" id="minQty" name="minQty" placeholder="Enter Minimum Qty" autocomplete="off" required />
                </div>

                <!-- <div class="form-group">
                  <label for="expDate">Expiry Date</label>
                  <input type="date" class="form-control" id="expDate" name="expDate" placeholder="Enter Expiry Date" autocomplete="off" required />
                </div> -->

                <div class="form-group">
                  <label for="minQty">Notify Before Exp Days</label>
                  <input type="number" class="form-control" id="beforeExpDays" name="beforeExpDays" autocomplete="off" min="0" required />
                </div>
              
                <div class="form-group">
                  <label for="description">Description</label>
                  <textarea type="text" class="form-control" id="description" name="description" placeholder="Enter 
                  description" autocomplete="off">
                  </textarea>
                </div>
                
              </div>
              <div class="col-md-4 col-xs-12">

                <?php if($attributes): ?>
                  <?php foreach ($attributes as $k => $v): ?>
                    <div class="form-group">
                      <label for="groups"><?php echo $v['attribute_data']['name'] ?></label>
                      <select class="form-control select_group" id="attributes_value_id" name="attributes_value_id[]" multiple="multiple" required>
                        <?php foreach ($v['attribute_value'] as $k2 => $v2): ?>
                          <option value="<?php echo $v2['id'] ?>"><?php echo $v2['value'] ?></option>
                        <?php endforeach ?>
                      </select>
                    </div>    
                  <?php endforeach ?>
                <?php endif; ?>

                <div class="form-group">
                  <label for="brands">Brands</label>
                  <select class="form-control select_group" id="brands" name="brands[]" multiple="multiple" required>
                    <?php foreach ($brands as $k => $v): ?>
                      <option value="<?php echo $v['id'] ?>"><?php echo $v['name'] ?></option>
                    <?php endforeach ?>
                  </select>
                </div>

                <div class="form-group">
                  <label for="category">Category</label>
                  <select class="form-control select_group" id="category" name="category[]" multiple="multiple" required>
                    <?php foreach ($category as $k => $v): ?>
                      <option value="<?php echo $v['id'] ?>"><?php echo $v['name'] ?></option>
                    <?php endforeach ?>
                  </select>
                </div>

                <!-- <div class="form-group">
                  <label for="store">Store</label>
                  <select class="form-control select_group" id="store" name="store" required>
                    <?php foreach ($stores as $k => $v): ?>
                      <option value="<?php echo $v['id'] ?>"><?php echo $v['name'] ?></option>
                    <?php endforeach ?>
                  </select>
                </div> -->

                <div class="form-group">
                  <label for="store">Availability</label>
                  <select class="form-control" id="availability" name="availability" required>
                    <option value="1">Yes</option>
                    <option value="2">No</option>
                  </select>
                </div>


              </div>
              <div class="col-md-4 col-xs-12">
                <div class="form-group">

                  <div class="col-md-12">
                    <label class="col-md-2" for="product_image"><strong class="pull-right">Image</strong></label>
                  </div>
                  <div class="kv-avatar col-md-6">
                      <div class="file-loading">
                          <input id="product_image" name="product_image" type="file">
                      </div>
                  </div>
                </div>
              </div>
      </div>
              </div>
              <!-- /.box-body -->

              <div class="box-footer">
                <button type="submit" class="btn btn-primary pull-right">Save</button>
                <a href="<?php echo base_url('products/') ?>" class="btn btn-warning"><i class="fa fa-arrow-left"></i> Back</a>
              </div>
            </form>
          <!-- /.box-body -->
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
    $(".select_group").select2();
    $("#description").wysihtml5();

    $("#mainProductNav").addClass('active');
    $("#addProductNav").addClass('active');
    
    var btnCust = '<button type="button" class="btn btn-secondary" title="Add picture tags" ' + 
        'onclick="alert(\'Call your custom code here.\')">' +
        '<i class="glyphicon glyphicon-tag"></i>' +
        '</button>'; 
    $("#product_image").fileinput({
        overwriteInitial: true,
        maxFileSize: 1500,
        showClose: false,
        showCaption: false,
        browseLabel: '',
        removeLabel: '',
        browseIcon: '<i class="glyphicon glyphicon-folder-open"></i>',
        removeIcon: '<i class="glyphicon glyphicon-remove"></i>',
        removeTitle: 'Cancel or reset changes',
        elErrorContainer: '#kv-avatar-errors-1',
        msgErrorClass: 'alert alert-block alert-danger',
        // defaultPreviewContent: '<img src="/uploads/default_avatar_male.jpg" alt="Your Avatar">',
        layoutTemplates: {main2: '{preview} ' /*+  btnCust */+ ' {remove} {browse}'},
        allowedFileExtensions: ["jpg", "png", "gif"]
    });

  });
</script>