

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

                <div class="form-group">
                  <label class="col-md-2" for="receivable_image"><strong class="pull-right">Medicine Image</strong></label>
                  <div class="kv-avatar col-md-6">
                      <div class="file-loading">
                          <input id="receivable_image" name="receivable_image" type="file">
                      </div>
                  </div>
                </div>

    <div class="col-md-12 col-sm-12 col-xs-12" style="overflow-x: scroll">
      <div class="col-md-8 col-xs-12">

              <div class="form-group col-md-8 col-xs-12">
                  <label for="store">Store</label>
                  <select class="form-control select_group" id="store" name="store" required>
                    <?php foreach ($stores as $k => $v): ?>
                      <option value="<?php echo $v['id'] ?>"><?php echo $v['name'] ?></option>
                    <?php endforeach ?>
                  </select>
                </div>

                <div class="form-group col-md-6">
                  <label for="productId">Product/Item</label>
                  <select class="form-control select_group product" data-row-id="row_1" id="product_1" name="productId" style="width:100%;" onchange="getProductData(1)" required>
                  <option value=""></option>
                  <?php foreach ($products as $k => $v): if($v['qty']<=0) continue; ?>
                    <option value="<?php echo $v['id'] ?>"><?php echo $v['name'] ?></option>
                  <?php endforeach ?>
                </select>
                </div>

                <div class="form-group col-md-6">
                  <label for="refNumber">Ref Number</label>
                  <input type="text" class="form-control" id="refNumber" name="refNumber" placeholder="Enter ref number" autocomplete="off" required/>
                </div>

                <div class="form-group col-md-6">
                  <label for="cost">Unit Cost</label>
                  <input type="text" class="number form-control" id="cost" name="cost" placeholder="Enter cost" autocomplete="off" required />
                </div>

                <div class="form-group col-md-6">
                  <label for="price">Unit Sell Price</label>
                  <input type="text" class="number form-control" id="price" name="price" placeholder="Enter price" autocomplete="off" required />
                </div>

                <div class="form-group col-md-6">
                  <label for="qty">Qty</label>
                  <input type="text" class="number form-control" id="qty" name="qty" placeholder="Enter Qty" autocomplete="off" required />
                </div>

                <div class="form-group col-md-6">
                  <label for="defectedQty">Defected Qty</label>
                  <input type="text" class="number form-control" id="defectedQty" name="defectedQty" placeholder="Enter Defected Qty" autocomplete="off" required />
                </div>

                <div class="form-group col-md-6">
                  <label for="receivedDate">Received Date</label>
                  <input type="date" class="form-control" id="receivedDate" name="receivedDate" placeholder="Enter Received Date" autocomplete="off" required />
                </div>

                <div class="form-group col-md-6">
                  <label for="expDate">Expiry Date</label>
                  <input type="date" class="form-control" id="expDate" name="expDate" placeholder="Enter Expiry Date" autocomplete="off" required />
                </div>

                <div class="form-group col-md-12">
                  <label for="description">Description</label>
                  <textarea type="text" class="form-control" id="description" name="description" placeholder="Enter 
                  description" autocomplete="off">
                  </textarea>
                </div>
              
              </div>
              <!-- <div class="col-md-6 col-xs-12">

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

              </div> -->
      </div>
              </div>
              <!-- /.box-body -->

              <div class="box-footer">
                <button type="submit" class="btn btn-primary pull-right">Save</button>
                <a href="<?php echo base_url('receivables/') ?>" class="btn btn-warning"><i class="fa fa-arrow-left"></i> Back</a>
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

    $("#mainReceivableNav").addClass('active');
    $("#addReceivableNav").addClass('active');
    
    var btnCust = '<button type="button" class="btn btn-secondary" title="Add picture tags" ' + 
        'onclick="alert(\'Call your custom code here.\')">' +
        '<i class="glyphicon glyphicon-tag"></i>' +
        '</button>'; 
    $("#receivable_image").fileinput({
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