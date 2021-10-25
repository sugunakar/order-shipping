<?php $this->load->view('includes/header');?>

<br />
<br />
<style>
.ml10px{margin-left:10px;}
</style>
<div class="container-fluid">
  <div class="col col-md-12">
    <div class="row">
      <div class="col-sm-3">
        <div class="row">
          <div class="panel panel-primary">
            <div class="panel-heading">
              <h3 class="panel-title">Place Order</h3>
            </div>
            <div class="panel-body">
              <div class="row">
                <div class="col-sm-12 text-center"> Please your order using below button. </div>
                <div class="col-sm-12 text-center orderResponse" style="display:none;"><br />
                  <strong>Order Number:</strong> <span class="orderNumber"></span><br />
                  <strong>Shipping Number:</strong> <span class="shippingNumber"></span> </div>
              </div>
            </div>
            <div class="panel-footer text-center"> <a href="javascript:;" class="btn btn-sm btn-success btnPlaceOrder">PLACE ORDER</a> </div>
          </div>
        </div>
      </div>
      <div class="col-sm-3">
        <div class="row ml10px">
          <div class="panel panel-primary">
            <div class="panel-heading">
              <h3 class="panel-title">Order Tracking</h3>
            </div>
            <div class="panel-body">
              <div class="row">
                <div class="col-sm-12">
                  <div class="input-group"> <span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
                    <input id="shippingId" type="text" class="form-control" name="shippingId" value="" placeholder="Enter Shipping  ID">
                  </div>
                </div>
              </div>
            </div>
            <div class="panel-footer text-center"> <a href="javascript:;" class="btn btn-sm btn-info btnTrackOrder">TRACK</a> </div>
          </div>
        </div>
      </div>
      <div class="col-sm-6 trackingHistory" style="display:none;">
        <div class="row ml10px">
          <div class="panel panel-primary">
            <div class="panel-heading">
              <h3 class="panel-title">Tracking History</h3>
            </div>
            <div class="panel-body">
              <div class="row">
                <div class="col-sm-12">
                  <div class="table-responsive">
                    <table class="table table-bordered">
                      	<thead>
                        	<tr>
                            	<th>Source</th>
                                <th>Destination</th>
                                <th>Start Date</th>
                                <th>End Date</th>
                                <th>Time</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody id="ThistoryBody">
                        	
                        </tbody>
                    </table>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<?php $this->load->view('includes/footer');?>
<script type="text/javascript">
$( document ).ready(function() {
	$(document).on('click', '.btnPlaceOrder', function() {
			$('.orderResponse').hide();
			$formData = new FormData();
			$formData.append("action", "placeOrder");
	  		$.ajax({
				type: 'post',
				url: "<?php echo site_url()?>",
				data: $formData,
				processData: false,
				contentType: false,
				success: function (response) {
					if(response.status=="success")
					{
						$('.orderResponse').show();
						$('.orderNumber').html(response.orderNumber);
						$('.shippingNumber').html(response.shippingNumber);
					}
				},
				error: function (err) {
					window.alert("Something went wrong? ---"+err);
				}
			});
	});
	
	$(document).on('click', '.btnTrackOrder', function() {
			$('.trackingHistory').hide();
			$('.ThistoryBody').html("");
			$shippingId = $('#shippingId').val();
			$formData = new FormData();
			$formData.append("shippingNumber", $shippingId);
			$formData.append("action", "trackShipping");
	  		$.ajax({
				type: 'post',
				url: "<?php echo site_url()?>",
				data: $formData,
				processData: false,
				contentType: false,
				success: function (response) {
					if(response.status=="success")
					{
						$('.trackingHistory').show();
						$responseHTML = "";
						$.each(response.shippingInfo, function(i, item) {
							$responseHTML +='<tr>';
								$responseHTML +='<td>'+item.sourceId+'</td>';
								$responseHTML +='<td>'+item.destinationId+'</td>';
								$responseHTML +='<td>'+item.startDate+'</td>';
								$responseHTML +='<td>'+item.endDate+'</td>';
								$responseHTML +='<td>'+item.timeDifference+'</td>';
								$responseHTML +='<td>'+item.statusName+'</td>';
							$responseHTML +='</tr>';
						});
						
						if(response.timeStatus=="success")
						{
							$responseHTML +='<tr>';
								$responseHTML +='<th class="text-right" alight="right" colspan="4">Total Time</th>';
								$responseHTML +='<td colspan="2">'+response.timeDuration+'</td>';
							$responseHTML +='</tr>';
						}
						
						$('#ThistoryBody').html($responseHTML);
					}
					
					
				},
				error: function (err) {
					window.alert("Something went wrong? ---"+err);
				}
			});
	});
});
</script>
