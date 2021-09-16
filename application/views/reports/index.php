

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Reports
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Reports</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
      <!-- Small boxes (Stat box) -->
      <div class="row">

        <div class="col-md-12 col-xs-12">
          <form class="form-inline" action="<?php echo base_url('reports/') ?>" method="POST">
            <div class="form-group">
              <label for="date">Year</label>
              <select class="form-control" name="select_year" id="select_year">
                <?php foreach ($report_years as $key => $value): ?>
                  <option value="<?php echo $value ?>" <?php if($value == $selected_year) { echo "selected"; } ?>><?php echo $value; ?></option>
                <?php endforeach ?>
              </select>
            </div>
            <div class="form-group">
              <label for="fromDate" style="margin-left: 100px">From Date</label>
              <input type="date" value="<?php echo $fromDate; ?>" class="form-control" id="fromDate" name="fromDate" placeholder="From Date" autocomplete="off" required />
            </div>
            <div class="form-group">
              <label for="toDate">To Date</label>
              <input type="date" value="<?php echo $toDate; ?>" class="form-control" id="toDate" name="toDate" placeholder="To Date" autocomplete="off" required />
            </div>
            <button type="submit" class="btn btn-default">Submit</button>
          </form>
        </div>

        <br /> <br />


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
              <h3 class="box-title">Total <strong>Issuables/Orders</strong> From <u><i><strong><?php echo $fromDate; ?></strong></i></u> - <u><i><strong><?php echo $toDate; ?></strong></i></u> </h3>
            </div>
            <table id="datatables" class="table table-bordered table-striped">
                <thead>
                <tr>
                  <th><?php echo $_SESSION['Item'] ?></th>
                  <th>Gross Amount</th>
                  <th>Vat Charge</th>
                  <th>Service Charge</th>
                  <th>Discount</th>
                  <th>Net Amount</th>
                </tr>
                </thead>
                <tbody>

                  <?php 
                  $total_gross_amount=0;
                  $total_net_amount=0;
                  foreach ($order_data_by_date as $k => $v): $total_gross_amount+=$v['gross_amount']; $total_net_amount+=$v['net_amount']; ?>
                    <tr>
                      <td><?php echo $k; ?></td>
                      <td><?php echo $v['gross_amount'];?></td>
                      <td><?php echo $v['vat_charge'];?></td>
                      <td><?php echo $v['service_charge'];?></td>
                      <td><?php echo $v['discount'];?></td>
                      <td><?php echo $v['net_amount'];?></td>
                    </tr>
                  <?php endforeach ?>
                  
                </tbody>
                <tbody>
                  <tr>
                      <th></th>
                    <th>Total Gross Amount: <u><strong><?php echo $company_currency .' ' . $total_gross_amount; ?></strong></u></th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th>Total Net Amount: <u><strong><?php echo $company_currency .' ' . $total_net_amount; ?></strong></u></th>
                  </tr>
                </tbody>
              </table>
          </div>
          
          <div class="box">
            <div class="box-header">
              <h3 class="box-title">Total Paid(Net Amount) - Report</h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
              <div class="chart">
                <canvas id="barChart" style="height:250px"></canvas>
              </div>
            </div>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->
          <div class="box">
            <div class="box-header">
              <h3 class="box-title">Total Paid <strong>Issuables/Orders</strong> - Report Data</h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
              <table id="datatables" class="table table-bordered table-striped">
                <thead>
                <tr>
                  <th>Month - Year</th>
                  <th>Amount</th>
                </tr>
                </thead>
                <tbody>

                  <?php 
                  $total_gross_amount_sum=0;
                  $total_net_amount_sum=0;
                  foreach ($results as $k => $v): $total_gross_amount_sum+=$v['total_gross_amount']; $total_net_amount_sum+=$v['total_net_amount']; ?>
                    <tr>
                      <td><?php echo $k; ?></td>
                      <td><?php 
                        echo $company_currency .' ' . $v['total_gross_amount'];
                      ?></td>
                      <td><?php 
                        echo $company_currency .' ' . $v['total_net_amount'];
                      ?></td>
                    </tr>
                  <?php endforeach ?>
                  
                </tbody>
                <tbody>
                  <tr>
                    <td></td>
                    <td>Total Gross Amount: <u><strong><?php echo $company_currency .' ' . $total_gross_amount_sum; ?></strong></u></td>
                    <td>Total Net Amount: <u><strong><?php echo $company_currency .' ' . $total_net_amount_sum; ?></strong></u></td>
                  </tr>
                </tbody>
              </table>

              <hr>
            </div>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->
        </div>
        <?php $order_data_by_date; ?>
        <!-- col-md-12 -->
      </div>
      <!-- /.row -->
      

    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->

  <script type="text/javascript">

    $(document).ready(function() {
      $("#reportNav").addClass('active');
    }); 

    var report_data = <?php echo '[' . implode(',', array_map(function($item){return $item['total_net_amount']; }, $results)) . ']'; ?>;
    

    $(function () {
    /* ChartJS
     * -------
     * Here we will create a few charts using ChartJS
     */
     var areaChartData = {
      labels  : ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'],
      datasets: [
        {
          label               : 'Electronics',
          fillColor           : 'rgba(210, 214, 222, 1)',
          strokeColor         : 'rgba(210, 214, 222, 1)',
          pointColor          : 'rgba(210, 214, 222, 1)',
          pointStrokeColor    : '#c1c7d1',
          pointHighlightFill  : '#fff',
          pointHighlightStroke: 'rgba(220,220,220,1)',
          data                : report_data
        }
      ]
    }

    //-------------
    //- BAR CHART -
    //-------------
    var barChartCanvas                   = $('#barChart').get(0).getContext('2d')
    var barChart                         = new Chart(barChartCanvas)
    var barChartData                     = areaChartData
    barChartData.datasets[0].fillColor   = '#00a65a';
    barChartData.datasets[0].strokeColor = '#00a65a';
    barChartData.datasets[0].pointColor  = '#00a65a';
    var barChartOptions                  = {
      //Boolean - Whether the scale should start at zero, or an order of magnitude down from the lowest value
      scaleBeginAtZero        : true,
      //Boolean - Whether grid lines are shown across the chart
      scaleShowGridLines      : true,
      //String - Colour of the grid lines
      scaleGridLineColor      : 'rgba(0,0,0,.05)',
      //Number - Width of the grid lines
      scaleGridLineWidth      : 1,
      //Boolean - Whether to show horizontal lines (except X axis)
      scaleShowHorizontalLines: true,
      //Boolean - Whether to show vertical lines (except Y axis)
      scaleShowVerticalLines  : true,
      //Boolean - If there is a stroke on each bar
      barShowStroke           : true,
      //Number - Pixel width of the bar stroke
      barStrokeWidth          : 2,
      //Number - Spacing between each of the X value sets
      barValueSpacing         : 5,
      //Number - Spacing between data sets within X values
      barDatasetSpacing       : 1,
      //String - A legend template
      legendTemplate          : '<ul class="<%=name.toLowerCase()%>-legend"><% for (var i=0; i<datasets.length; i++){%><li><span style="background-color:<%=datasets[i].fillColor%>"></span><%if(datasets[i].label){%><%=datasets[i].label%><%}%></li><%}%></ul>',
      //Boolean - whether to make the chart responsive
      responsive              : true,
      maintainAspectRatio     : true
    }

    barChartOptions.datasetFill = false
    barChart.Bar(barChartData, barChartOptions)
  })
  </script>
