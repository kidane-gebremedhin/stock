

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1>
      Add
      <small>Receivables</small>
    </h1>
    <ol class="breadcrumb">
      <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
      <li class="active">Receivables</li>
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
            <h3 class="box-title">Add Receivables</h3>
          </div>
          <!-- /.box-header -->
          <form role="form" action="<?php base_url('orders/create') ?>" method="post" class="form-horizontal">
              <div class="box-body">

                <div class="col-md-6">
                <div class="form-group col-md-10">
                  <label for="receivedDate">Received Date</label>
                  <input type="date" class="form-control" id="receivedDate" name="receivedDate" placeholder="Enter Received Date" autocomplete="off" required />
                </div>
              </div>
                <div class="col-md-6">
                <div class="form-group col-md-10" style="display: <?php echo ($user_data['store_id']==0? 'block': 'none') ?>">
                  <label for="store">Store</label>
                  <select class="form-control" id="store" name="store" required>
                    <?php foreach ($stores as $k => $v): ?>
                      <option value="<?php echo $v['id'] ?>" <?php echo ($user_data['store_id']==$v['id']? 'selected': '') ?>><?php echo $v['name'] ?></option>
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
                              <option value="<?php echo $v['id'] ?>"><?php echo $v['name'] ?></option>
                            <?php endforeach ?>
                          </select>
                        </td>
                        <td>
                          <input type="text" name="refNumber[]" id="refNumber_1" class="form-control" autocomplete="off" required/>
                        </td>
                        <td><input type="number" name="qty[]" id="qty_1" class="form-control" min="1" required onkeyup="getTotal(1)"></td>
                        <td>
                          <input type="text" name="defectedQty[]" min="0" id="defectedQty_1" class="number form-control" autocomplete="off" min="0" required />
                        </td>
                        <td>
                          <input type="date" name="expDate[]" id="expDate_1" class="form-control" autocomplete="off" required />
                        </td>
                        <td>
                          <input type="text" name="cost[]" id="cost_1" class="number form-control" onkeyup="getTotal(1)" autocomplete="off">
                          <input type="hidden" name="cost_value[]" id="cost_value_1" class="form-control" autocomplete="off">
                        </td>
                        <td>
                          <input type="text" name="price[]" id="price_1" class="number form-control" autocomplete="off" required />
                        </td>
                        <td>
                          <textarea type="text" name="description[]" id="description_1" class="form-control" autocomplete="off">
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
                <button type="submit" class="btn btn-success pull-right"><i class="fa fa-usd"></i> <strong>Save Receivable</strong></button>
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
  var base_url = "<?php echo base_url(); ?>";

  $(document).ready(function() {
    localStorage.clear();
    $(".select_group").select2();
    // $("#description").wysihtml5();

    $("#mainOrdersNav").addClass('active');
    $("#addOrderNav").addClass('active');
    
    var btnCust = '<button type="button" class="btn btn-secondary" title="Add picture tags" ' + 
        'onclick="alert(\'Call your custom code here.\')">' +
        '<i class="glyphicon glyphicon-tag"></i>' +
        '</button>'; 
  
    // Add new row in the table 
    $("#add_row").unbind('click').bind('click', function() {
      var table = $("#product_info_table");
      var count_table_tbody_tr = $("#product_info_table tbody tr").length;
      var row_id = count_table_tbody_tr + 1;

      $.ajax({
          url: base_url + '/orders/getTableProductRow/',
          type: 'post',
          dataType: 'json',
          success:function(response) {
            
              // console.log(reponse.x);
               var html = '<tr id="row_'+row_id+'">'+
                   '<td>'+ 
                    '<select class="form-control select_group product" data-row-id="'+row_id+'" id="product_'+row_id+'" name="productId[]" style="width:100%;" onchange="getProductData('+row_id+')" required>'+
                        '<option value=""></option>';
                        $.each(response, function(index, value) {
                          //alert(localStorage.getItem(value.id))
                          //if(!localStorage.getItem(value.id) && value.id)
                           /*if(response[index].qty <=0) 
                             return;*/
                          html += '<option value="'+value.id+'">'+value.name+'</option>';             
                        });
                        
                      html += '</select>'+
                    '</td>'+ 
                    '<td><input type="text" name="refNumber[]" id="refNumber_'+row_id+'" class="form-control" required></td>'+
                    '<td><input type="number" name="qty[]" id="qty_'+row_id+'" class="form-control" onkeyup="getTotal('+row_id+')" required></td>'+
                    '<td><input type="number" name="defectedQty[]" min="0" id="defectedQty_'+row_id+'" class="form-control" required></td>'+
                    '<td><input type="date" name="expDate[]" id="expDate_'+row_id+'" class="form-control" required></td>'+
                    '<td><input type="text" name="cost[]" id="cost_'+row_id+'" class="number form-control" onkeyup="getTotal('+row_id+')" required><input type="hidden" name="cost_value[]" id="cost_value_'+row_id+'" class="form-control"></td>'+
                    '<td><input type="text" name="price[]" id="price_'+row_id+'" class="number form-control" required></td>'+
                    '<td><textarea type="text" name="description[]" id="description_'+row_id+'" class="form-control"></textarea></td>'+
                    '<td><button type="button" class="btn btn-default" onclick="removeRow(\''+row_id+'\')"><i class="fa fa-close"></i></button></td>'+
                    '</tr>';

                if(count_table_tbody_tr >= 1) {
                $("#product_info_table tbody tr:last").after(html);  
              }
              else {
                $("#product_info_table tbody").html(html);
              }

              $(".product").select2();

          }
        });

      return false;
    });

  }); // /document

  function getTotal(row = null) {
    if(row) {
      var total = 0;
      for(var x = 1; x <= $('.select_group').length; x++) {

        var subTotal = Number($("#cost_"+x).val()) * Number($("#qty_"+x).val());
        total += subTotal;
      } 

        total = total.toFixed(2);
      $("#gross_amount").val(total);
      
      //subAmount();

      /*var qty=Number($("#qty_"+row).val());
      var maxQty=Number($("#qty_"+row).attr('max'));
      if(qty > maxQty)
        $("#qty_"+row).css('background', 'red');
      else
        $("#qty_"+row).css('background', '#fff');*/

    } else {
      alert('no row !! please refresh the page');
    }
  }

  function productAlreadySelected(row_id){
    var productId=$("#product_"+row_id).val();
    var count=0;
    $('.select_group').each(function(){
      if(productId==$(this).val()){
        count++;
      }
    })
    
    return count > 1;
  }
  // get the product information from the server
  function getProductData(row_id)
  {
    if(productAlreadySelected(row_id)){
      alert($("#product_"+row_id+' option:selected').text()+" already selected, Please consider updating its quantity or selecting other product.");
      $("#product_"+row_id).val('').select2();
      //$("#qty_"+row_id).val(0).keyup();
      $("#rate_"+row_id).val("");
      $("#rate_value_"+row_id).val("");
      $("#qty_"+row_id).val("").keyup();
      $("#cost_"+row_id).val("");
      $("#cost_value_"+row_id).val("");
      return;
    }

    $("#qty_"+row_id).css('background', '#fff');

    var product_id = $("#product_"+row_id).val();    
    if(product_id == "") {
      $("#rate_"+row_id).val("");
      $("#rate_value_"+row_id).val("");

      $("#qty_"+row_id).val("");           

      $("#cost_"+row_id).val("");
      $("#cost_value_"+row_id).val("");

    } else {
      $.ajax({
        url: base_url + 'orders/getProductValueById',
        type: 'post',
        data: {product_id : product_id},
        dataType: 'json',
        success:function(response) {
          // setting the rate value into the rate input field
          
          $("#rate_"+row_id).val(response.price);
          $("#rate_value_"+row_id).val(response.price);
          $("#qty_"+row_id).val(1);
          //$("#qty_"+row_id).attr('max', response.qty)

          $("#qty_value_"+row_id).val(1);
          /*
          var total = Number(response.price) * 1;
          total = total.toFixed(2);
          $("#cost_"+row_id).val(total);
          $("#cost_value_"+row_id).val(total);*/
          
          subAmount();
        } // /success
      }); // /ajax function to fetch the product data 
    }
  }

  // calculate the total amount of the order
  function subAmount() {
    var service_charge = <?php echo ($company_data['service_charge_value'] > 0) ? $company_data['service_charge_value']:0; ?>;
    var vat_charge = <?php echo ($company_data['vat_charge_value'] > 0) ? $company_data['vat_charge_value']:0; ?>;

    var tableProductLength = $("#product_info_table tbody tr").length;
    var totalSubAmount = 0;
    for(x = 0; x < tableProductLength; x++) {
      var tr = $("#product_info_table tbody tr")[x];
      var count = $(tr).attr('id');
      count = count.substring(4);

      totalSubAmount = Number(totalSubAmount) + Number($("#cost_"+count).val());
    } // /for

    totalSubAmount = totalSubAmount.toFixed(2);
f
    // sub total
    $("#gross_amount").val(totalSubAmount);
    $("#gross_amount_value").val(totalSubAmount);

    // vat
    var vat = (Number($("#gross_amount").val())/100) * vat_charge;
    vat = vat.toFixed(2);
    $("#vat_charge").val(vat);
    $("#vat_charge_value").val(vat);

    // service
    var service = (Number($("#gross_amount").val())/100) * service_charge;
    service = service.toFixed(2);
    $("#service_charge").val(service);
    $("#service_charge_value").val(service);
    
    // total amount
    var totalAmount = (Number(totalSubAmount) + Number(vat) + Number(service));
    totalAmount = totalAmount.toFixed(2);
    // $("#net_amount").val(totalAmount);
    // $("#totalAmountValue").val(totalAmount);

    var discount = $("#discount").val();
    if(discount) {
      var grandTotal = Number(totalAmount) - Number(discount);
      grandTotal = grandTotal.toFixed(2);
      $("#net_amount").val(grandTotal);
      $("#net_amount_value").val(grandTotal);
    } else {
      $("#net_amount").val(totalAmount);
      $("#net_amount_value").val(totalAmount);
      
    } // /else discount 

  } // /sub total amount

  function removeRow(tr_id)
  {
    $("#product_info_table tbody tr#row_"+tr_id).remove();
    subAmount();
  }
  $(document).on('change', '.select_group', function(e){
    /*var productId=$(this).val();
    if(productId==null)
      return;
    localStorage.setItem(productId, productId);*/
    // var productId=$(this).val();
    // var count=0;
    // $('.select_group').each(function(){
    //   if(productId==$(this).val()){
    //     count++;
    //   }
    // })
    // if(count > 1){
    //   alert($(this).closest('.select_group').find(':selected').text()+" already selected");
    //   $(this).val('').select2();
    // }
   // alert($(this).closest('.select_group').find(':selected').remove())
  })
</script>

