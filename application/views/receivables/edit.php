

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
            <h3 class="box-title">Edit <?php echo $_SESSION['Item'] ?></h3>
          </div>
          <!-- /.box-header -->
          <form role="form" action="<?php base_url('users/update') ?>" method="post" enctype="multipart/form-data">
              <div class="box-body">

                <div class="col-md-6">
                <div class="form-group col-md-10">
                  <label for="receivedDate">Received Date<?php echo $receivable_data['receivedDate'] ?></label>
                  <input type="date" class="form-control" id="receivedDate" name="receivedDate" value="<?php echo $receivable_data['receivedDate'] ?>" placeholder="Enter Received Date" autocomplete="off" required />
                </div>
              </div>
                <div class="col-md-6">
                <div class="form-group col-md-10" style="display: <?php echo ($user_data['store_id']==0? 'block': 'none') ?>">
                  <label for="store">Store</label>
                  <select class="form-control" id="store" name="store" required>
                    <?php foreach ($stores as $k => $v): ?>
                      <option value="<?php echo $v['id'] ?>" <?php echo ($receivable_data['store_id']==$v['id']? 'selected': '') ?>><?php echo $v['name'] ?></option>
                    <?php endforeach ?>
                  </select>
                </div>
                </div>

                <?php echo validation_errors(); ?>

                <table class="table table-bordered" id="product_info_table">
                  <thead>
                    <tr>
                      <th style="width:15%"><?php echo $_SESSION['Item'] ?></th>
                      <th style="width:15%">Ref Number</th>
                      <th style="width:10%">Qty</th>
                      <th style="width:10%">Defected Qty</th>
                      <th style="width:10%">Expiry Date</th>
                      <th style="width:10%">Unit Cost</th>
                      <th style="width:15%">Unit Sell Price</th>
                      <th style="width:20%">Description</th>
                      <th style="width:10%"><button type="button" id="add_row" class="btn btn-default"><i class="fa fa-plus"></i></button></th>
                    </tr>
                  </thead>

                   <tbody>
                     <tr id="row_1">
                       <td>
                        <select class="form-control select_group product" data-row-id="row_1" id="product_1" name="productId[]" style="width:100%;" onchange="getProductData(1)" required>
                            <option value=""></option>
                            <?php foreach ($products as $k => $v): ?>
                              <option value="<?php echo $v['id'] ?>" <?php echo ($receivable_data['productId']==$v['id']? 'selected': '') ?>><?php echo $v['name'] ?></option>
                            <?php endforeach ?>
                          </select>
                        </td>
                        <td>
                          <input type="text" name="refNumber[]" value="<?php echo $receivable_data['refNumber'] ?>" id="refNumber_1" class="form-control" autocomplete="off" required/>
                        </td>
                        <td><input type="number" name="qty[]" value="<?php echo $receivable_data['qty'] ?>" id="qty_1" class="form-control" min="1" required onkeyup="getTotal(1)"></td>
                        <td>
                          <input type="text" name="defectedQty[]" min="0" value="<?php echo $receivable_data['defectedQty'] ?>" id="defectedQty_1" class="number form-control" autocomplete="off" required />
                        </td>
                        <td>
                          <input type="date" name="expDate[]" value="<?php echo $receivable_data['expDate'] ?>" id="expDate_1" class="form-control" autocomplete="off" required />
                        </td>
                        <td>
                          <input type="text" name="cost[]" value="<?php echo $receivable_data['cost'] ?>" id="cost_1" class="number form-control" onkeyup="getTotal(1)" autocomplete="off">
                          <input type="hidden" name="cost_value[]" value="<?php echo $receivable_data['cost'] ?>" id="cost_value_1" class="form-control" autocomplete="off">
                        </td>
                        <td>
                          <input type="text" name="price[]" value="<?php echo $receivable_data['price'] ?>" id="price_1" class="number form-control" autocomplete="off" required />
                        </td>
                        <td>
                          <textarea type="text" name="description[]" id="description_1" class="form-control" autocomplete="off"><?php echo $receivable_data['description'] ?></textarea>
                          </textarea>
                        </td>
                        <td><button type="button" class="btn btn-default" onclick="removeRow('1')"><i class="fa fa-close"></i></button></td>
                     </tr>
                   </tbody>
                </table>

                <br /> <br/>

                <div class="col-md-6 col-xs-12 pull pull-right">

                  <div class="form-group">
                    <label for="gross_amount" class="col-sm-5 control-label">Gross Amount</label>
                    <div class="col-sm-7">
                      <input type="text" class="form-control" id="gross_amount" name="gross_amount" disabled autocomplete="off">
                      <input type="hidden" class="form-control" id="gross_amount_value" name="gross_amount_value" autocomplete="off">
                    </div>
                  </div>

                </div>
              </div>
              <!-- /.box-body -->

              <div class="box-footer">
                <button type="submit" class="btn btn-success pull-right"><i class="fa fa-usd"></i> <strong>Update Receivable</strong></button>
                <a href="<?php echo base_url('orders/') ?>" class="btn btn-warning"><i class="fa fa-arrow-left"></i> Back</a>
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
    $("#manageReceivableNav").addClass('active');
    
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