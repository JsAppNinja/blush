<?
function query_param($name)
{
    $params = array();

    $CI =& get_instance();
    $query_string = $_SERVER['REQUEST_URI'];
    $query_string = substr($query_string, strpos($query_string, "?") + 1);
    $parts = explode("&", $query_string);
    foreach ($parts as $part) {
        if ($part) {
            $parts2 = explode("=", $part);
            if (sizeof($parts2) > 1) {
                $params[$parts2[0]] = $parts2[1];
            }
        }
    }
    if (isset($params[$name]))
        return urldecode($params[$name]);
}

function active_nav($name) {
    if(isset($name) && $name) {
        echo " active ";
    }
}

function number_to_string($day_of_week)
{
    $days = array("First", "Second", "Third", "Fourth", "Fifth");
    return $days[$day_of_week - 1];
}

function dollarfy($num, $dec = 2)
{
    if (!$num) {
        return "";
    }
    $format = "%.$dec" . "f";
    $number = sprintf($format, $num);
    $str = strtok($number, ".");
    $dc = strtok(".");
    $str = commify($str);
    $return = "$" . "$str";

    if ($dec != 0) {
        $return = "$return" . ".$dc";
    }
    return ($return);
}

function commify($str)
{
    $n = strlen($str);
    if ($n <= 3) {
        $return = $str;
    } else {
        $pre = substr($str, 0, $n - 3);
        $post = substr($str, $n - 3, 3);
        $pre = commify($pre);
        $return = "$pre,$post";
    }
    return ($return);
}

;

function phone_part($phone, $part)
{
    if ($phone) {
        $parts = explode("-", $phone);
        if (isset($parts[$part - 1])) {
            return $parts[$part - 1];
        }
    }

    return "";
}

function percentage($price, $regular)
{
    if ($regular > 0) {
        return round((($regular - $price) / $regular) * 100, 0);
    }
    return 0;
}

function percentage_remaining($remaining, $total)
{
    if ($total > 0) {
        return round(($remaining / $total), 3);
    }
    return 0;
}

function youtube_embed($url)
{
    $parts = explode('watch?v=', $url);
    if (sizeof($parts) > 1) {
        $part = $parts[1];

        $parts = explode("&", $part);
        $uid = $parts[0];
        return "http://www.youtube.com/embed/" . $uid;
    }
}

function pretty_minutes($minutes)
{
    $hours = floor($minutes / 60);
    $mins = $minutes % 60;

    $pretty = "";
    if ($hours > 1) {
        $pretty = $hours . " hrs";
    } else if ($hours > 0) {
        $pretty = $hours . " hr";
    }

    if ($mins > 1) {
        $pretty .= " " . $mins . " mins";
    } else if ($mins > 0) {
        $pretty .= " " . $mins . " min";
    }

    return $pretty;
}

function phone_format($phone)
{
    $phone = preg_replace("/[^0-9]/", "", $phone);

    if (strlen($phone) == 7)
        return preg_replace("/([0-9]{3})([0-9]{4})/", "$1-$2", $phone);
    elseif (strlen($phone) == 10)
        return preg_replace("/([0-9]{3})([0-9]{3})([0-9]{4})/", "($1) $2-$3", $phone);
    else
        return $phone;
}

function clean_phone($phone) {
    $phone = str_replace("(", "", $phone);
    $phone = str_replace(")", "", $phone);
    $phone = str_replace("-", "", $phone);
    $phone = str_replace(" ", "", $phone);
    $phone = trim($phone);
    return $phone;
}

function youtube_id($str)
{
    if ($str) {
        $parts = explode("\?", $str);
        if (sizeof($parts > 1)) {
            $parts2 = explode("\&", $parts[1]);
            foreach ($parts2 as $part) {
                if (strpos($part, "v=") === 0) {
                    $parts3 = explode("\=", $part);
                    echo $parts3[1];
                }
            }
        }
    }
}

function urltitle($str)
{
    $str = strtolower($str);
    $trans = array(
        ' '  => '-',
        '-'  => '_dash_',
        '\'' => '',
        '&'  => '_and_',
        ','  => '_comma_',
        '%'  => '_prcnt_',
        '+'  => '_plus_',
        '/'  => '_slash_',
        '?'  => '_question_',
        '!'  => '_exclaim_',
        '('  => '_opt_',
        ')'  => '_cpt_',
        '"'  => '_inches_',
        '.'  => '_point_',
        '\'' => '%27'
    );
    return strtr($str, $trans);
}

function array_print($array)
{
    echo "<pre>";
    print_r($array);
    echo "</pre></br>";
}

function rev_urltitle($str)
{
    $trans = array(
        '-'                    => ' ',
        '_dash_'               => '-',
        '_and_'                => '&',
        '_comma_'              => ',',
        '_prcnt_'              => '%',
        '_plus_'               => '+',
        '_slash_'              => '/',
        '_question_'           => '?',
        '_opt_'                => '(',
        '_cpt_'                => ')',
        '_exclaim_'            => '!',
        '_inches_'             => '"',
        '_point_'              => '.',
        '%27'                  => '\''
    );
    return strtr($str, $trans);
}

function file_type_string($file_type)
{
    return str_replace(".", "-", str_replace('/', '_', $file_type));
}

function strip_html($string)
{
    if ($string) {
        include(APPPATH . 'libraries/htmlpurifier-4.3.0/library/HTMLPurifier.auto.php');
        $config = HTMLPurifier_Config::createDefault();
        $config->set('AutoFormat.RemoveSpansWithoutAttributes', TRUE);
        $config->set('HTML.TidyLevel', 'heavy');

        $purifier = new HTMLPurifier($config);
        $string = strip_tags($purifier->purify($string));
    }
    return $string;
}

function clean_smart_quotes($text)
{
    // First, replace UTF-8 characters.
    $text = str_replace(
        array("\xe2\x80\x98", "\xe2\x80\x99", "\xe2\x80\x9c", "\xe2\x80\x9d", "\xe2\x80\x93", "\xe2\x80\x94", "\xe2\x80\xa6"),
        array("'", "'", '"', '"', '-', '--', '...'),
        $text);
    // Next, replace their Windows-1252 equivalents.
    $text = str_replace(
        array(chr(145), chr(146), chr(147), chr(148), chr(150), chr(151), chr(133)),
        array("'", "'", '"', '"', '-', '--', '...'),
        $text);
    return $text;
}

function human_filesize($bytes, $decimals = 2)
{
    $sz = 'BKMGTP';
    $factor = floor((strlen($bytes) - 1) / 3);
    return sprintf("%.{$decimals}f", $bytes / pow(1024, $factor)) . @$sz[$factor];
}


function number_words($number)
{
    $array = array('First', 'Second', 'Third', 'Fourth', 'Fifth', 'Sixth');
    return $array[$number - 1];
}

function number_to_day($number)
{
    $days = array('', 'Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday');
    return $days[$number];
}

function number_to_month($number)
{
    $days = array('', 'January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December', 'Every Month');
    return $days[$number];
}

function log_echo($string)
{
    //echo $string."<br/>";
    log_message('info', $string);
}
?>