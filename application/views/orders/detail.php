

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1>
      Manage
      <small>Orders</small>
    </h1>
    <ol class="breadcrumb">
      <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
      <li class="active">Orders</li>
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
            <h3 class="box-title">Order Detail</h3>
          </div>
          <!-- /.box-header -->
<table class="table table-bordered">
  <thead>
    <th class="col-md-4"></th>
    <th class="col-md-8"></th>
  </thead>
  <tbody>
    <tr>
        <td>Customer Name</td>
        <td><?php echo $order_data['order']['customer_name'] ?></td>
    </tr>
    <tr>
        <td>Customer Address</td>
        <td><?php echo $order_data['order']['customer_address'] ?></td>
    </tr>
    <tr>
        <td>Customer Phone</td>
        <td><?php echo $order_data['order']['customer_phone'] ?></td>
    </tr>
  </tbody>
</table>


  <table class="table table-bordered" id="product_info_table">
    <thead>
      <tr>
        <th style="width:25%"><?php echo $_SESSION['Item'] ?></th>
        <th style="width:10%">Qty</th>
        <th style="width:10%">Rate</th>
        <th style="width:20%">Amount</th>
        <th style="width:10%"></th>
      </tr>
    </thead>

     <tbody>

      <?php if(isset($order_data['order_item'])): ?>
        <?php $x = 1; ?>
        <?php foreach ($order_data['order_item'] as $key => $val): ?>
          <?php //print_r($v); ?>
         <tr id="row_<?php echo $x; ?>">
           <td>
              <?php foreach ($products as $k => $v): if($val['product_id'] != $v['id']) continue; ?>
                <strong> <?php echo $v['name']; ?></strong>
                <?php endforeach ?>
            </td>
            <td>
            <?php echo $val['qty'] ?>  
            </td>
            <td>
              <?php echo $val['rate'] ?>
            </td>
            <td>
              <?php echo $val['amount'] ?>
            </td>
         </tr>
         <?php $x++; ?>
       <?php endforeach; ?>
     <?php endif; ?>
     </tbody>
  </table>

<table class="table table-bordered">
  <thead>
    <th class="col-md-4"></th>
    <th class="col-md-8"></th>
  </thead>
  <tbody>
    <tr>
        <td>Gross Amount</td>
        <td><?php echo $order_data['order']['gross_amount'] ?></td>
    </tr>
    <tr>
        <td>S-Charge <?php echo $company_data['service_charge_value'] ?> %</td>
        <td>
          <?php if($is_service_enabled == true): ?>
            <?php echo $order_data['order']['service_charge'] ?>
            <?php endif; ?>
        </td>
    </tr>
    <tr>
        <td>Vat <?php echo $company_data['vat_charge_value'] ?> %</td>
        <td>
           <?php if($is_vat_enabled == true): ?>
            <?php echo $order_data['order']['vat_charge'] ?>
            <?php endif; ?>
        </td>
    </tr>
    <tr>
        <td>Discount</td>
        <td>
           <?php echo $order_data['order']['discount'] ?>
        </td>
    </tr>
    <tr>
        <td>Net Amount</td>
        <td>
           <?php echo $order_data['order']['net_amount'] ?>
        </td>
    </tr>
    <tr>
        <td>Paid Status</td>
        <td>
          <label class="badge <?php echo $order_data['order']['paid_status']==1? 'bg-green': 'bg-yellow' ?>">
           <?php echo $order_data['order']['paid_status']==1? 'Paid': 'Unpaid' ?>
           </label>
        </td>
    </tr>
    <tr>
      <td><strong>Custom Attribute Values</strong></td>
      <td>
        <table class="table table-bordered">
        <thead>
          <th class="col-md-6">Custom Attribute</th>
          <th class="col-md-6">Value</th>
        </thead>
        <tbody>

          <?php foreach ($custom_attributes as $k => $v): 
            $attr_value = null;
            foreach ($order_custom_attribute_values as $obj) {
              if($obj['custom_attribute_id']==$v['id']){
                $attr_value=$obj;
                break;
              }
            }
            ?>
            <tr>
            <td><strong><?php echo $v['name']; ?></strong></td>
              <td><strong><?php echo ($attr_value!=null? $attr_value['value']: ''); ?></strong></td>
            </tr>
          <?php endforeach ?>
        </tbody>
      </table>
      </td>
    </tr>
    <tr>
      <td></td>
      <td>
        <?php if(in_array('updateOrder', $this->permission)) {
          echo '<a href="'.base_url('orders/update/'.$order_data['order']['id']).'" class="btn btn-default"><i class="fa fa-pencil"></i></a>';
            }

        if(in_array('deleteOrder', $this->permission)) { 
          echo ' <button type="button" class="btn btn-default" onclick="removeFunc('.$order_data['order']['id'].')" data-toggle="modal" data-target="#removeModal"><i class="fa fa-trash"></i></button>';
            }
            ?>
      </td>
    </tr>
  </tbody>
</table>


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

<?php if(in_array('deleteOrder', $user_permission)): ?>
<!-- remove brand modal -->
<div class="modal fade" tabindex="-1" role="dialog" id="removeModal">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">Remove order</h4>
      </div>

      <form role="form" action="<?php echo base_url('orders/remove') ?>" method="post" id="removeForm">
        <div class="modal-body">
          <p>Do you really want to remove?</p>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Cancel and Close</button>
          <button type="submit" class="btn btn-primary">Yes, Continue</button>
        </div>
      </form>


    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<?php endif; ?>

<script type="text/javascript">
  
  $(document).ready(function() {
    $(".select_group").select2();
    $("#description").wysihtml5();

    $("#mainorderNav").addClass('active');
    $("#manageorderNav").addClass('active');
    
    var btnCust = '<button type="button" class="btn btn-secondary" title="Add picture tags" ' + 
        'onclick="alert(\'Call your custom code here.\')">' +
        '<i class="glyphicon glyphicon-tag"></i>' +
        '</button>'; 
    $("#order_image").fileinput({
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


function removeFunc(id)
{
  if(id) {
    $("#removeForm").on('submit', function() {

      var form = $(this);

      // remove the text-danger
      $(".text-danger").remove();

      $.ajax({
        url: form.attr('action'),
        type: form.attr('method'),
        data: { order_id:id }, 
        dataType: 'json',
        success:function(response) {

          //manageTable.ajax.reload(null, false); 
          window.history.pushState("Url ", "push state", '/stock/orders');
          location.reload();

          if(response.success === true) {
            $("#messages").html('<div class="alert alert-success alert-dismissible" role="alert">'+
              '<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>'+
              '<strong> <span class="glyphicon glyphicon-ok-sign"></span> </strong>'+response.messages+
            '</div>');

            // hide the modal
            $("#removeModal").modal('hide');

          } else {

            $("#messages").html('<div class="alert alert-warning alert-dismissible" role="alert">'+
              '<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>'+
              '<strong> <span class="glyphicon glyphicon-exclamation-sign"></span> </strong>'+response.messages+
            '</div>'); 
          }
        }
      }); 

      return false;
    });
  }
}
</script>