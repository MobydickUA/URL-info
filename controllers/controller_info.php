<?php 

class Controller_info extends Controller
{
	function __construct()
	{
		$this->model = new Model_info();
	}

	function action_index()
	{	
		$this->view('main_view');
	}

	function action_statistics($id)
	{	
		if(!isset($id)) {
			$data = $this->model->getData();
			$this->view('statistics',['data' => $data]);
		}
		else
			$this->view('graph_stat');
	}

	function action_get_graph_data()
	{
		if(isset($_POST['url']))
		{
			$tmp = explode('/statistics/', $_POST['url']);
			if(isset($tmp[1]))
				$id = $tmp[1];
			$data = $this->model->findById($id);
			echo json_encode($data);
		}
	}

	function action_set_urls()
	{
		$urls = explode("\n", $_POST['urls']);
		$res = [];
		foreach ($urls as $url) {
			if($url == '' || $url == '\n' || $url == ' ')
				continue;
			array_push($res, $this->getStatusAndTitle($url));
		}
		$this->model->saveRequests($res);
		echo(json_encode($res));
	}

	function getStatusAndTitle($url)
	{
		$ch = curl_init($url);
		curl_setopt($ch, CURLOPT_HEADER, true);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
		curl_setopt($ch, CURLOPT_TIMEOUT,10);
		$output = curl_exec($ch);
		$httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
		curl_close($ch);

		$doc = new DOMDocument();
		@$doc->loadHTML($output);
		$nodes = $doc->getElementsByTagName('title');
		$title = $nodes->item(0)->nodeValue;
		return ['url' => $url, 'status' => !$httpcode ? 404 : $httpcode, 'title' => !$title ? '404 | Not Found' : $title];
	}
}