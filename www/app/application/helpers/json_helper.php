<?
function json_error($message, $data = array(), $response_code = 400)
{
    header('Content-Type: application/json');
    http_response_code($response_code);
    echo json_encode(array('status'=> 'error', 'message'=> $message, 'data'=> $data));
    exit;
}

function json_success($message = '', $data = array())
{
    header('Content-Type: application/json');
    echo json_encode(array('status'=> 'success', 'message'=> $message, 'data'=> $data));
}

function jsonp_success($message = '', $data = array())
{
    $CI =& get_instance();
    $CI->output->set_content_type('application/x-javascript');
    echo $_GET['callback'] . '(' . json_encode(array('status'=> 'success', 'message'=> $message, 'data'=> $data)) . ')';
}

function jsonp_result($result)
{
    $CI =& get_instance();
    $CI->output->set_content_type('application/x-javascript');
    echo $_GET['callback'] . '(' . json_encode($result) . ')';
}

function jsonp_list_result($result)
{
    $CI =& get_instance();

    $CI->output->set_content_type('application/x-javascript');

    $result = array('objects'=> $result);

    echo jsonp_result($result);
}

function grid_result($count, $objects)
{
    $CI =& get_instance();

    $obj = new stdClass;
    $obj->iTotalRecords = $count;
    $obj->iTotalDisplayRecords = $count;

    $updated_objects = array();
    foreach ($objects as $object) {
        if (!isset($object->DT_RowId) && $object->id) {
            $object->DT_RowId = $object->id;
        }
        $updated_objects[] = $object;
    }

    $obj->aaData = $updated_objects;
    $obj->sEcho = $CI->input->post('sEcho');

    return json_encode($obj);
}

function get_field_data($field, $field_data)
{
    if ($field_data) {
        foreach ($field_data as $data) {
            if ($field == $data->name) {
                return $data;
            }
        }
    }
}

function get_grid_offset()
{
    $CI =& get_instance();
    return intval($CI->input->post('iDisplayStart'));
}

function get_grid_filter($filter)
{
    $CI =& get_instance();
    return $CI->input->post('sSearch');
}

function get_grid_limit()
{
    $CI =& get_instance();
    $limit = intval($CI->input->post('iDisplayLength'));
    if (!$limit) {
        $limit = $CI->config->item('page_size');
    }
    return $limit;
}

function get_grid_ordering()
{
    $CI =& get_instance();
    $sort = $CI->input->post('iSortCol_0');
    if ($sort || $sort === "0") {
        $ordering = array();
        $ordering['sort'] = intval($sort);
        if ($CI->input->post('sSortDir_0')) {
            $ordering['dir'] = $CI->input->post('sSortDir_0');
        } else {
            $ordering['dir'] = 'ASC';
        }
        return $ordering;
    }
}

function get_sort_class($ordering, $col)
{
    $sort_col = $ordering['sort'];
    $parts = explode('.', $sort_col);
    $sort_col = array_pop($parts);
    //echo $sort_col;
    //print_r($parts);
    if ($sort_col == $col) {
        return strtolower($ordering['dir']);
    }
}