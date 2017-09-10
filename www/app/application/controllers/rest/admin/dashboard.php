<?
class Dashboard extends REST_Controller {

    function __construct() {
        parent::__construct();
        $this->validate_admin();

        $this->load->model(array('Transaction','Diary', 'Event', 'Configuration'));
        $this->load->helper('json');
    }

    public function index_get() {
        $result = new stdClass;
        $result->customers = $this->User->get_count(NULL, USER_TYPE_CUSTOMER);
        $result->counselors = $this->User->get_count(NULL, USER_TYPE_COUNSELOR);
        $result->transactions = $this->Transaction->get_count();
        $result->events = $this->Event->get_count();
        $result->config = $this->Configuration->get_all_map();

        $result->last_30_customers = $this->User->get_count_last_30(USER_TYPE_CUSTOMER);
        $result->last_30_diaries = $this->Diary->get_count_last_30();
        $result->last_30_videos = $this->Event->get_count_last_30();
        $result->last_30_transactions = $this->Transaction->get_count_last_30();
        $result->last_30_money = $this->Transaction->get_amount_last_30();

        $this->response($result);
    }
}
?>