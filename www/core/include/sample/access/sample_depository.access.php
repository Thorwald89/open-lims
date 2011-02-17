<?php
/**
 * @package sample
 * @version 0.4.0.0
 * @author Roman Konertz
 * @copyright (c) 2008-2010 by Roman Konertz
 * @license GPLv3
 * 
 * This file is part of Open-LIMS
 * Available at http://www.open-lims.org
 * 
 * This program is free software;
 * you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation;
 * version 3 of the License.
 * 
 * This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY;
 * without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. 
 * See the GNU General Public License for more details.
 * 
 * You should have received a copy of the GNU General Public License along with this program;
 * if not, see <http://www.gnu.org/licenses/>.
 */

/**
 * Sample Depository Access Class
 * @package sample
 */
class SampleDepository_Access
{
	const SAMPLE_DEPOSITORY_PK_SEQUENCE = 'core_sample_depositories_id_seq';

	private $id;
	
	private $toid;
	private $name;
	
	/**
	 * @param integer $id
	 */
	function __construct($id)
	{
		global $db;
			
		if ($id == null)
		{
			$this->id = null;
		}
		else
		{
			$sql = "SELECT * FROM ".constant("SAMPLE_DEPOSITORY_TABLE")." WHERE id='".$id."'";
			$res = $db->db_query($sql);			
			$data = $db->db_fetch_assoc($res);
			
			if ($data[id])
			{
				$this->id 		= $id;
				
				$this->toid		= $data[toid];
				$this->name		= $data[name];
			}
			else
			{
				$this->id = null;
			}
		}	
	}
	
	function __destruct()
	{
		if ($this->id)
		{
			unset($this->id);
	
			unset($this->toid);
			unset($this->name);
		}
	}
	
	/**
	 * @param integer $toid
	 * @param string $name
	 * @return integer
	 */
	public function create($toid, $name)
	{
		global $db;
		
		if ($name)
		{
			if (is_numeric($toid))
			{
				$toid_insert = $toid;
			}
			else
			{
				$toid_insert = "NULL";
			}
			
			$sql_write = "INSERT INTO ".constant("SAMPLE_DEPOSITORY_TABLE")." (id, toid, name) " .
					"VALUES (nextval('".self::SAMPLE_DEPOSITORY_PK_SEQUENCE."'::regclass), ".$toid_insert.", '".$name."')";
			$res_write = $db->db_query($sql_write);
			
			if ($db->db_affected_rows($res_write) == 1)
			{
				$sql_read = "SELECT id FROM ".constant("SAMPLE_DEPOSITORY_TABLE")." WHERE id = currval('".self::SAMPLE_DEPOSITORY_PK_SEQUENCE."'::regclass)";
				$res_read = $db->db_query($sql_read);
				$data_read = $db->db_fetch_assoc($res_read);
				
				$this->__construct($data_read[id]);
				
				return $data_read[id];
			}
			else
			{
				return null;
			}	
		}
		else
		{
			return null;
		}
	}
	
	/**
	 * @return bool
	 */
	public function delete()
	{
		global $db;
		
		if ($this->id)
		{
			$tmp_id = $this->id;
			
			$this->__destruct();
						
			$sql = "DELETE FROM ".constant("SAMPLE_DEPOSITORY_TABLE")." WHERE id = ".$tmp_id."";
			$res = $db->db_query($sql);
			
			if ($db->db_affected_rows($res) == 1)
			{
				return true;
			}
			else
			{
				return false;
			}
		}
		else
		{
			return false;	
		}
	}
	
	/**
	 * @return integer
	 */
	public function get_toid()
	{
		if ($this->toid)
		{
			return $this->toid;
		}
		else
		{
			return null;
		}
	}
	
	/**
	 * @return string
	 */
	public function get_name()
	{
		if ($this->name)
		{
			return $this->name;
		}
		else
		{
			return null;
		}
	}
	
	/**
	 * @param integer $toid
	 * @return bool
	 */
	public function set_toid($toid)
	{
		global $db;
		
		if ($this->id and is_numeric($toid))
		{
			$sql = "UPDATE ".constant("SAMPLE_DEPOSITORY_TABLE")." SET toid = '".$toid."' WHERE id = '".$this->id."'";
			$res = $db->db_query($sql);
			
			if ($db->db_affected_rows($res))
			{
				$this->toid = $toid;
				return true;
			}
			else
			{
				return false;
			}
		}
		else
		{
			return false;
		}
	}
	
	/**
	 * @param string $name
	 * @return bool
	 */
	public function set_name($name)
	{
		global $db;

		if ($this->id and $name)
		{
			$sql = "UPDATE ".constant("SAMPLE_DEPOSITORY_TABLE")." SET name = '".$name."' WHERE id = '".$this->id."'";
			$res = $db->db_query($sql);
			
			if ($db->db_affected_rows($res))
			{
				$this->name = $name;
				return true;
			}
			else
			{
				return false;
			}
		}
		else
		{
			return false;
		}
	}
	
	
	/**
	 * @param string $name
	 * @return bool
	 */
	public static function exist_id($id)
	{
		global $db;
			
		if (is_numeric($id))
		{
			$return_array = array();
			
			$name = trim(strtolower($name));
			
			$sql = "SELECT id FROM ".constant("SAMPLE_DEPOSITORY_TABLE")." WHERE id = '".$id."'";
			$res = $db->db_query($sql);
			$data = $db->db_fetch_assoc($res);
			
			if ($data[id])
			{
				return true;
			}
			else
			{
				return false;
			}
		}
		else
		{
			return false;
		}
	}
	
	/**
	 * @param string $name
	 * @return bool
	 */
	public static function exist_name($name)
	{
		global $db;
			
		if ($name)
		{
			$return_array = array();
			
			$name = trim(strtolower($name));
			
			$sql = "SELECT id FROM ".constant("SAMPLE_DEPOSITORY_TABLE")." WHERE TRIM(LOWER(name)) = '".$name."'";
			$res = $db->db_query($sql);
			$data = $db->db_fetch_assoc($res);
			
			if ($data[id])
			{
				return true;
			}
			else
			{
				return false;
			}
		}
		else
		{
			return false;
		}
	}
	
	/**
	 * @param integer $toid
	 * @return array
	 */
	public static function list_entries_by_toid($toid)
	{
		global $db;
			
		if (is_numeric($toid))
		{
			$return_array = array();
			
			$sql = "SELECT id FROM ".constant("SAMPLE_DEPOSITORY_TABLE")." WHERE toid = ".$toid." AND toid != id ORDER BY name";
			$res = $db->db_query($sql);
			
			while ($data = $db->db_fetch_assoc($res))
			{
				array_push($return_array,$data[id]);
			}
			
			if (is_array($return_array))
			{
				return $return_array;
			}
			else
			{
				return null;
			}
		}
		else
		{
			return null;
		}
	}
	
	/**
	 * @return array
	 */
	public static function list_root_entries()
	{
		global $db;
				
		$return_array = array();
		
		$sql = "SELECT id FROM ".constant("SAMPLE_DEPOSITORY_TABLE")." WHERE toid IS NULL OR toid = id ORDER BY name";
		$res = $db->db_query($sql);
		
		while ($data = $db->db_fetch_assoc($res))
		{
			array_push($return_array,$data[id]);
		}
		
		if (is_array($return_array))
		{
			return $return_array;
		}
		else
		{
			return null;
		}
	}
	
	/**
	 * @return array
	 */
	public static function list_entries()
	{
		global $db;
				
		$return_array = array();
		
		$sql = "SELECT id FROM ".constant("SAMPLE_DEPOSITORY_TABLE")." ORDER BY name";
		$res = $db->db_query($sql);
		
		while ($data = $db->db_fetch_assoc($res))
		{
			array_push($return_array,$data[id]);
		}
		
		if (is_array($return_array))
		{
			return $return_array;
		}
		else
		{
			return null;
		}
	}
 
} 

?>
