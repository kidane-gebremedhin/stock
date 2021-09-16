

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1>
      Transactions
      <small>Data</small>
    </h1>
    <ol class="breadcrumb">
      <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
      <li class="active">Transactions</li>
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
            <h3 class="box-title">Transactions Data</h3>
          </div>
          <!-- /.box-header -->
          <div class="box-body">

        <div class="col-md-12 col-xs-12">
          <form class="form-inline" action="<?php echo base_url('receivables/transactionsData') ?>" method="POST">
            <div class="form-group">
              <label for="date">Product </label>
              <select class="searchElem form-control" name="selected_product" id="selected_product">
                <option value="">-- --</option>
                <?php foreach ($products as $key => $value): ?>
                  <option value="<?php echo $value['id'] ?>" <?php if($value['id'] == $selected_product) { echo "selected"; } ?>><?php echo $value['name']; ?></option>
                <?php endforeach ?>
              </select>
            </div>
            <div class="form-group">
              <label for="fromDate" style="margin-left: 100px">From Date</label>
              <input type="date" value="<?php echo $fromDate; ?>" class="searchElem form-control" id="fromDate" name="fromDate" placeholder="From Date" autocomplete="off" />
            </div>
            <div class="form-group">
              <label for="toDate">To Date</label>
              <input type="date" value="<?php echo $toDate; ?>" class="searchElem form-control" id="toDate" name="toDate" placeholder="To Date" autocomplete="off" />
            </div>
            <button type="submit" class="btn btn-default">Submit</button>
          </form>
          <hr>
        </div>

            <div class="col-md-12 col-sm-12 col-xs-12" style="overflow-x: scroll">
            <table id="manageTable" class="table table-bordered table-striped">
              <thead>
              <tr>
                <th>Product</th>
                <th>Date</th>
                <th>Qty</th>
                <th>Type</th>
              </tr>
              </thead>
              <tbody>
              <?php foreach ($transactions as $k => $v): ?>
                <tr>
                  <td><?php echo $v['product']; ?> </td>
                  <td><?php echo $v['date']; ?> </td>
                  <td><?php echo $v['qty']; ?> </td>
                  <td><?php echo $v['type']; ?> </td>
                </tr>
              <?php endforeach ?>
              </tbody>
            </table>

          </div>
          
          </div>
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

      <form role="form" action="<?php echo base_url('receivables/remove') ?>" method="post" id="removeForm">
        <div class="modal-body">
          <p>Do you really want to remove?</p>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-primary">Save changes</button>
        </div>
      </form>


    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<?php endif; ?>



<script type="text/javascript">
var base_url = "<?php echo base_url(); ?>";

$(document).ready(function() {

    $(".searchElem").on('change', function() {
      $(".text-danger").remove();

      $.ajax({
        url: base_url + 'receivables/transactionsData',
        type: 'POST',
        data: { fromDate: fromDate, toDate: toDate, selected_product: selected_product }, 
        dataType: 'json',
        success:function(response) {


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
});


</script>
