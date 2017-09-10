<?
function base_detect_url($uri = '')
{
    $url = base_url($uri);

    if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == "on") {
        $url = str_replace('http:', 'https:', $url);
    }
    return $url;
}

function app_url($uri = '')
{
    $url = site_detect_url($uri);
    return $url;
}

function admin_url($uri = '')
{
    $url = site_detect_url('admin/'.$uri);
    return $url;
}

function site_detect_url($uri = '')
{
    $url = site_url($uri);

    if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == "on") {
        $url = str_replace('http:', 'https:', $url);
    }
    return $url;
}

function get_avatar($size=IMG_SIZE_SM, $user = NULL) {
    $CI =& get_instance();
    $prefix = '';
    switch($size) {
        case IMG_SIZE_SM:
            $prefix = 'sm_';
            break;
        case IMG_SIZE_MD:
            $prefix = 'md_';
            break;
        case IMG_SIZE_LG:
            $prefix = 'lg_';
            break;
    }

    if(!$user) {
        $user = get_user();
    }
    if($user && isset($user->picture)) {
        return $CI->config->item('upload_url').$prefix.$user->picture;
    } else {
        return get_gravatar('abc123@email.com', $size);
    }
}

/**
 * Get either a Gravatar URL or complete image tag for a specified email address.
 *
 * @param string $email The email address
 * @param string $s Size in pixels, defaults to 80px [ 1 - 2048 ]
 * @param string $d Default imageset to use [ 404 | mm | identicon | monsterid | wavatar ]
 * @param string $r Maximum rating (inclusive) [ g | pg | r | x ]
 * @param boole $img True to return a complete IMG tag False for just the URL
 * @param array $atts Optional, additional key/value attributes to include in the IMG tag
 * @return String containing either just a URL or a complete image tag
 * @source http://gravatar.com/site/implement/images/php/
 */
function get_gravatar( $email, $s = 80, $d = 'mm', $r = 'g', $img = false, $atts = array() ) {
    $url = 'https://www.gravatar.com/avatar/';
    $url .= md5( strtolower( trim( $email ) ) );
    $url .= "?s=$s&d=$d&r=$r";
    if ( $img ) {
        $url = '<img src="' . $url . '"';
        foreach ( $atts as $key => $val )
            $url .= ' ' . $key . '="' . $val . '"';
        $url .= ' />';
    }
    return $url;
}

function ssl_url($uri = '')
{
    $url = site_url($uri);
    $url = str_replace('http:', 'https:', $url);
    return $url;
}

function full_url()
{
    $ci =& get_instance();
    $return = $ci->config->site_url() . $ci->uri->uri_string();
    if (count($_GET) > 0) {
        $get = array();
        foreach ($_GET as $key => $val) {
            $get[] = $key . '=' . $val;
        }
        $return .= '?' . implode('&', $get);
    }
    return $return;
}

function page_url($page_id)
{
    $CI =& get_instance();
    $CI->load->model('Page');
    $page = $CI->Page->load($page_id);
    if ($page) {
        return site_detect_url('site/' . urltitle($page->menu_title));
    }
}

function file_url($file, $is_public = FALSE)
{
    $CI =& get_instance();
    return app_url('files/download/' . $file->uuid);
}

function url_parts($url)
{
    $url = str_replace(site_detect_url(), '', $url);
    $parts = explode('/', $url);
    return $parts;
}

function auto_link($str, $type = 'both', $popup = FALSE)
{
    if ($type != 'email') {
        if (preg_match_all("#(^|\s|\()((http(s?)://)|(www\.))(\w+[^\s\)\<]+)#i", $str, $matches)) {
            $pop = ($popup == TRUE) ? " target=\"_blank\" " : "";

            for ($i = 0; $i < count($matches['0']); $i++) {
                $period = '';
                if (preg_match("|\.$|", $matches['6'][$i])) {
                    $period = '.';
                    $matches['6'][$i] = substr($matches['6'][$i], 0, -1);
                }

                $str = str_replace($matches['0'][$i],
                  $matches['1'][$i] . '<a href="http' .
                    $matches['4'][$i] . '://' .
                    $matches['5'][$i] .
                    $matches['6'][$i] . '"' . $pop . '>http' .
                    $matches['4'][$i] . '://' .
                    $matches['5'][$i] .
                    $matches['6'][$i] . '</a>' .
                    $period, $str);
            }
        }
    }

    if ($type != 'url') {
        if (preg_match_all("/([a-zA-Z0-9_\.\-\+]+)@([a-zA-Z0-9\-]+)\.([a-zA-Z0-9\-\.]*)/i", $str, $matches)) {
            for ($i = 0; $i < count($matches['0']); $i++) {
                $period = '';
                if (preg_match("|\.$|", $matches['3'][$i])) {
                    $period = '.';
                    $matches['3'][$i] = substr($matches['3'][$i], 0, -1);
                }

                //$str = str_replace($matches['0'][$i], safe_mailto($matches['1'][$i].'@'.$matches['2'][$i].'.'.$matches['3'][$i]).$period, $str);
                $str = str_replace($matches['0'][$i], mailto($matches['1'][$i] . '@' . $matches['2'][$i] . '.' . $matches['3'][$i]) . $period, $str);
            }
        }
    }

    return $str;
}

if (!function_exists('http_response_code'))
{
    function http_response_code($newcode = NULL)
    {
        static $code = 200;
        if($newcode !== NULL)
        {
            header('X-PHP-Response-Code: '.$newcode, true, $newcode);
            if(!headers_sent())
                $code = $newcode;
        }
        return $code;
    }
}

?>