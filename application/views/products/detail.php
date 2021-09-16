

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
            <h3 class="box-title"><?php echo $_SESSION['Item'] ?> Detail</h3>
          </div>
          <!-- /.box-header -->
<table class="table table-bordered">
  <thead>
    <th class="col-md-4"></th>
    <th class="col-md-8"></th>
  </thead>
  <tbody>
    <tr>
        <td><?php echo $_SESSION['Item'] ?> Image</td>
        <td><a href="<?php echo base_url() . $product_data['image'] ?>" target="_blank"><img src="<?php echo base_url() . $product_data['image'] ?>" width="150" height="150" class="img-circle"></a></td>
    </tr>  
    <tr>
        <td><?php echo $_SESSION['Item'] ?> name</td>
        <td><?php echo $product_data['name']; ?></td>
    </tr>
    <tr>
        <td>Identifier</td>
        <td><?php echo $product_data['sku']; ?></td>
    </tr>
    <tr>
        <td>Price</td>
        <td><?php echo $product_data['price']; ?></td>
    </tr>
    <tr>
        <td>Qty</td>
        <td><?php echo $product_data['qty']; ?></td>
    </tr>
    <tr>
        <td>Min Qty</td>
        <td><?php echo $product_data['minQty']; ?></td>
    </tr>
    <tr>
        <td>Notify Before Exp Days</td>
        <td><?php echo $product_data['beforeExpDays']; ?></td>
    </tr>
    <tr>
        <td>Description</td>
        <td><?php echo $product_data['description']; ?></td>
    </tr>
    <tr>
        <td>Attributes</td>
        <td>
        <?php $attribute_id = json_decode($product_data['attribute_value_id']); ?>
        <?php if($attributes && $attribute_id): ?>
        <table>
          <?php foreach ($attributes as $k => $v): ?>
          <tr>
            <td><label><?php echo $v['attribute_data']['name'] ?></label></td>
            <td>
            <?php foreach ($v['attribute_value'] as $k2 => $v2): if(!in_array($v2['id'], $attribute_id)) continue; ?>
                <label class="badge bg-green"><?php echo $v2['value']; ?></label>
            <?php endforeach ?>
            </td>
          </tr>
        <?php endforeach ?>
        </table>
        <?php endif; ?>
        </td>
    </tr>
    <tr>
        <td>Brands</td>
        <td> <?php $brand_data = json_decode($product_data['brand_id']); ?>
        <?php if($brand_data): ?>
        <?php foreach ($brands as $k => $v): if(!in_array($v['id'], $brand_data)) continue; ?>
        <label class="badge bg-green"> <?php echo $v['name']; ?></label>
        <?php endforeach ?>
        <?php endif; ?>
        </td>
    </tr>
    <tr>
        <td>Category</td>
        <td><?php $category_data = json_decode($product_data['category_id']); ?>
        <?php if($category_data): ?>
      <?php foreach ($category as $k => $v): if(!in_array($v['id'], $category_data)) continue; ?>
        <label class="badge bg-green"> <?php echo $v['name']; ?></label>
        <?php endforeach ?>
        <?php endif; ?>
        </td>
    </tr>
    <tr>
        <td>Availability</td>
        <td>
          <label class="badge <?php echo $product_data['availability'] == 1? 'bg-green': 'bg-red'?>">
          <?php echo $product_data['availability'] == 1? "Availabile": 'Not Availabile'; ?>
        </label>
        </td>
    </tr>
    <tr>
      <td></td>
      <td>
        <?php if(in_array('updateProduct', $this->permission)) {
          echo '<a href="'.base_url('products/update/'.$product_data['id']).'" class="btn btn-default"><i class="fa fa-pencil"></i></a>';
            }

        if(in_array('deleteProduct', $this->permission)) { 
          echo ' <button type="button" class="btn btn-default" onclick="removeFunc('.$product_data['id'].')" data-toggle="modal" data-target="#removeModal"><i class="fa fa-trash"></i></button>';
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

<?php if(in_array('deleteProduct', $user_permission)): ?>
<!-- remove brand modal -->
<div class="modal fade" tabindex="-1" role="dialog" id="removeModal">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">Remove <?php echo $_SESSION['Item'] ?></h4>
      </div>

      <form role="form" action="<?php echo base_url('products/remove') ?>" method="post" id="removeForm">
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

    $("#mainProductNav").addClass('active');
    $("#manageProductNav").addClass('active');
    
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
        data: { product_id:id }, 
        dataType: 'json',
        success:function(response) {

          //manageTable.ajax.reload(null, false); 
          window.history.pushState("Url ", "push state", '/stock/products');
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