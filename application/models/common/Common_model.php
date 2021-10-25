<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Common_model extends CI_Model
{
	public function GetSequence($Type){
	   $query=$this->db->query("select next_sequenceval('$Type') as nextsequence");
	   $row=$query->result();
	   return $row["0"]->nextsequence;
	}
	
	public function insertRow($tableName,$columnData)
	{
		$this->db->insert($tableName,$columnData);
		return $this->db->insert_id();
	}
	
	public function updateRow($tableName,$columnData,$whereCondition)
	{
		$this->db->where($whereCondition);
		$this->db->update($tableName,$columnData);
	}
	
	public function updateRowmdKey($tableName,$mdKey,$columnKey,$id,$updateData)
	{
		$this->db->trans_start();
		$this->db->where("md5(CONCAT('".$mdKey."',".$columnKey."))",$id);
		$this->db->update($tableName,$updateData);
		$this->db->trans_complete();  
		if($this->db->trans_status() === FALSE)
		{
			return false;
		}else{
			if($this->db->affected_rows() > 0)
    		{
    			return true;
    		}else{
    			return false;
    		}
		}
	}
	
	public function updateRowwithoutmdKey($tableName,$columnKey,$id,$updateData)
	{
		$this->db->trans_start();
		$this->db->where($columnKey,$id);
		$this->db->update($tableName,$updateData);
		$affected_rows = $this->db->affected_rows();
		$this->db->trans_complete();
		if ($this->db->trans_status() === FALSE) {
			return false;
		}else if ($this->db->trans_status() === TRUE) {
			if($affected_rows > 0)
			{
				return true;
			}else{
				return false;
			}
		}
	}
}
?>