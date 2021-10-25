<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Orders_model extends CI_Model
{
	public function trackShipping($shippingNumber)
	{
		$this->db->select("TIMEDIFF(shippingInfo.endDate,shippingInfo.startDate) as timeDifference,shippingInfo.sourceId,shippingInfo.destinationId,shippingInfo.startDate");
		$this->db->select("shippingInfo.endDate,shippingInfo.statusId");
		$this->db->from($this->config->item('SHIPPING_TABLE')." shippingInfo");
		$this->db->where("shippingInfo.shippingNumber",$shippingNumber);
		$this->db->order_by("shippingInfo.trackingId","desc");
		$query = $this->db->get();
		if ($query->num_rows() > 0) {
            foreach ($query->result() as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return false;
	}
	
	public function trackShippingTime($shippingNumber)
	{
		$this->db->select("SEC_TO_TIME(SUM(TIME_TO_SEC(shippingInfo.endDate) - TIME_TO_SEC(shippingInfo.startDate))) as totalTimetaken");
		$this->db->from($this->config->item('SHIPPING_TABLE')." shippingInfo");
		$this->db->where("shippingInfo.shippingNumber",$shippingNumber);
		$this->db->where("shippingInfo.endDate!='0000-00-00 00:00:00'");
		$this->db->group_by("shippingInfo.shippingNumber");
		$query = $this->db->get();
		if ($query->num_rows() > 0) {
            foreach ($query->result() as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return false;
	}
}
?>