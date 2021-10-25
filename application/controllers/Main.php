<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Main extends CI_Controller {
	
	public function __construct() {
        parent::__construct();
		$this->load->model('orders_model','ordersModel', TRUE);
    }
	
	public function index()
	{		
		$this->output->set_header('Last-Modified:'.gmdate('D, d M Y H:i:s').'GMT');
		$this->output->set_header('Cache-Control: no-store, no-cache, must-revalidate');
		$this->output->set_header('Cache-Control: post-check=0, pre-check=0',false);
		$this->output->set_header('Pragma: no-cache');
	
		if($this->input->post('action')=="placeOrder")
		{
			header('Content-Type: application/json');
			
			$orderNumber = $this->commonModel->GetSequence("orderSequence");
			$shippingNumber = $this->commonModel->GetSequence("shippingSequence");
			$createdDate = date('Y-m-d H:i:s');
			
			$insertData["orderNumber"]=$orderNumber;
			$insertData["shippingNumber"]=$shippingNumber;
			$insertData["createdDate"]=$createdDate;
			$ordersTable = $this->config->item('ORDERS_TABLE');
			
			$this->commonModel->insertRow($ordersTable,$insertData);
			
			$response["orderNumber"]=$orderNumber;
			$response["shippingNumber"]=$shippingNumber;
			$response["status"]="success";
			
			$json_response=json_encode($response);
			echo $json_response;
			exit;
		}

		if($this->input->post('action')=="trackShipping")
		{
			header('Content-Type: application/json');
			
			$shippingNumber = $this->input->post('shippingNumber');
			$trackShipping = $this->ordersModel->trackShipping($shippingNumber);
			$trackShippingTime = $this->ordersModel->trackShippingTime($shippingNumber);
			
			if($trackShipping)
			{
				foreach($trackShipping as $shippingInfo)
				{
					if($shippingInfo->statusId==1)
					{
						$statusName = "Intransit";
					}elseif($shippingInfo->statusId==2)
					{
						$statusName = "Destination";
					}elseif($shippingInfo->statusId==3)
					{
						$statusName = "Final Destination";
					}
					$response["shippingInfo"][]=array(
						"sourceId" => $shippingInfo->sourceId,
						"destinationId" => $shippingInfo->destinationId,
						"startDate" => $shippingInfo->startDate,
						"endDate" => $shippingInfo->endDate,
						"statusName" => $statusName,
						"timeDifference" => ($shippingInfo->endDate=='0000-00-00 00:00:00')?'00:00:00':$shippingInfo->timeDifference
					);
				}
				$response["status"]="success";
			}else{
				$response["status"]="noData";
			}
			
			if($trackShippingTime)
			{
				$response["timeDuration"]=$trackShippingTime[0]->totalTimetaken;
				$response["timeStatus"]="success";
			}else{
				$response["timeStatus"]="noData";
			}
			
			$json_response=json_encode($response);
			echo $json_response;
			exit;
		}

		$this->load->view('index');
	}
}