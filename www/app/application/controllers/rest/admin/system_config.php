<?
class System_Config extends REST_Controller
{

    function __construct()
    {
        parent::__construct();
        $this->validate_admin();
        $this->load->model(array('Configuration'));
    }

    public function config_get() {
        $response = new stdClass;
        $response->config = $this->Configuration->get_all_map();

        $this->response($response);
    }

    public function config_post() {
        $config = $this->post('config');
        $this->Configuration->update_all($config);

        json_success('Configuration Updated Successfully');
    }
}

?>