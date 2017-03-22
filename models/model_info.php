<?php

class Model_info extends Model
{
	public function getData()
	{
		$res = [];
		$tmp = $this->db->query("SELECT * FROM requests ORDER BY created_at DESC;");		
		while($row = $tmp->fetch_assoc())
			array_push($res,$row);
		return $res;
	}

	public function saveRequests($data)
	{
		$q = "INSERT INTO requests (`url`, `title`, `status`) VALUES";
		foreach ($data as $row) {
			$q .= ' ("' . htmlspecialchars($this->db->real_escape_string($row['url'])) . '", "' . $row['title'] . '", "' . $row['status'] . '"),';
		}
		$q = rtrim($q, ',') . ';';
		$this->db->query($q);
		return $this->db->error;
	}

	public function findById($id)
	{
		if(intval($id) <= 0)
			return 0;
		$res = [];
		$tmp = $this->db->query("SELECT count(*) as number, status, DATE_FORMAT(created_at,'%d.%m.%Y') as date FROM requests WHERE url=(SELECT url FROM requests WHERE id=$id) GROUP BY status,DATE_FORMAT(created_at,'%d.%m.%Y') ORDER BY date;");
		if($this->db->error)
			return 0;
		while($row = $tmp->fetch_assoc())
			array_push($res,$row);
		$url = $this->db->query("SELECT url FROM requests WHERE id=$id");
		return $this->format($res, $url->fetch_assoc());
	}

	private function format($data, $url)
	{
		$res = [];
		$dates =[];
		$statuses = [];
		foreach ($data as $row) {
			array_push($dates, $row['date']);
			array_push($statuses, $row['status']);
		}
		$dates = array_unique($dates);
		$statuses = array_unique($statuses);
		
		sort($dates);
		sort($statuses);

		$dates_index = array_flip($dates);
		$dates_num = count($dates);

		foreach($data as $row)
		{
			$flag = 0;
			foreach($res as &$item)
			{
				if($item['name'] == $row['status'])
				{
					$item['data'][$dates_index[$row['date']]] = intval($row['number']);
					$flag = 1;
				}
			}
			if(!$flag)
			{
				$tmp = [];
				for($j = 0; $j < $dates_num; $j++)
					$tmp[$j] = 0;
				$tmp[$dates_index[$row['date']]] = intval($row['number']);
				array_push($res, ['name' => $row['status'], 'data' => $tmp]);
			}
		}
		return [$res, $dates, $url];
	}
}