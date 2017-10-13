<?php
class Atest extends REST_Controller
{

 	public function __construct()
    {
        parent::__construct();
        $this->load->helper('json');
        $this->load->model('Registration');
    }

    public function index(){
    	echo "here on the test page";
    	// runTest();
    }

    public function runTest(){
    	$this->load->library('uuid');

    	$test_data = array(
	  					"customer_id"=> "732",
	  					"amount" => 50,
	  					"stripe_id"=> "so_mefakenonsenseshouldntmatter",
	  					"stripe_data"=> "serialized data",
	  					"uuid" => $this->uuid->v4(),
	  					'transaction_nbr' => strtoupper(random_string('alnum', 10)),
	  					'created' => timestamp_to_mysqldatetime(now()),
			            'deleted' => 0
    				);
    	print_r($test_data);
    	$transaction_id = $this->Transaction->add($data);
	}
}
