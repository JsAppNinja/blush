<?
include_once('functions/theme-options.php');
include_once('functions/shortcodes.php');
include_once(ABSPATH.'app/application/version.php');

function theme_widgets_init()
{

    register_sidebar(array(
        'name' => 'Sidebar',
        'id' => 'sidebar',
        'before_widget' => '<div class="clearfix"></div><div id="%1$s" class="widget %2$s">',
        'after_widget' => '</div>',
        'before_title' => '<h4 class="widgettitle">',
        'after_title' => '</h4>'
    ));

    register_sidebar(array(
        'name' => 'Sidebar Contact Us',
        'id' => 'sidebar-contact',
        'before_widget' => '<div class="clearfix"></div><div id="%1$s" class="widget %2$s">',
        'after_widget' => '</div>',
        'before_title' => '<h4 class="widgettitle">',
        'after_title' => '</h4>'
    ));
}

function is_scm_test()
{
    return strpos($_SERVER['SERVER_NAME'], 'scmreview') > 0;
}

add_action('widgets_init', 'theme_widgets_init');

/** Load up scripts/styles */
function theme_scripts_styles()
{
    $prefix = '/';
    if(false && !is_scm_test()) {
        $prefix = 'https://joinblush.com/';
    }

    wp_enqueue_style('bootstrap', $prefix.'assets/stylesheets/lib/bootstrap.css', NULL, APPVERSION);
    wp_enqueue_style('glyphicons', $prefix.'assets/stylesheets/lib/glyphicons.css', NULL, APPVERSION);
    wp_enqueue_style('font-awesome', $prefix.'assets/stylesheets/lib/font-awesome.css', NULL, APPVERSION);
    wp_enqueue_style('base', $prefix.'assets/stylesheets/base.css', NULL, APPVERSION);
    wp_enqueue_style('blog', $prefix.'assets/stylesheets/blog.css', NULL, APPVERSION);
    wp_enqueue_style('responsive', $prefix.'assets/stylesheets/responsive.css', NULL, APPVERSION);

    wp_enqueue_script('html5shiv', $prefix.'assets/scripts/lib/html5shiv.js', NULL, APPVERSION);
    wp_enqueue_script('modernizr', $prefix.'assets/scripts/lib/modernizr.js', NULL, APPVERSION);
    wp_enqueue_script('underscore', $prefix.'assets/scripts/lib/underscore.js', array('jquery'), APPVERSION);
    wp_enqueue_script('bootstrap', $prefix.'assets/scripts/lib/bootstrap.js', array('jquery'), APPVERSION);
    wp_enqueue_script('jquery-validate', $prefix.'assets/scripts/lib/jquery/jquery.validate.js', array('jquery'), APPVERSION);
    wp_enqueue_script('jquery-cycle', $prefix.'assets/scripts/lib/jquery/jquery.cycle.all.js', array('jquery'), APPVERSION);
    wp_enqueue_script('jquery-mask', $prefix.'assets/scripts/lib/jquery/jquery.maskedinput-1.3.1.min.js', array('jquery'), APPVERSION);
    wp_enqueue_script('loggly', $prefix.'assets/scripts/lib/loggly.tracker.js', NULL, APPVERSION);
    wp_enqueue_script('blush', $prefix.'assets/scripts/app.js', array('jquery', 'bootstrap', 'underscore', 'loggly'), APPVERSION);
    wp_enqueue_script('theme', $prefix.'assets/scripts/theme.js', array('jquery', 'bootstrap'), APPVERSION);
}

add_action('wp_enqueue_scripts', 'theme_scripts_styles');
add_theme_support('post-thumbnails');

/** Sets up the custom menus in the header/footer */
add_action('init', 'register_theme_menus');
function register_theme_menus()
{
    register_nav_menu('header-menu', __('Header Menu'));
    register_nav_menu('footer-menu', __('Footer Menu'));
}

/** Get Footer **/
function blush_footer()
{
    ?>
    <div class="container">
        <div class="row">
            <div class="col-lg-12 hidden-xs hidden-sm text-left logo">
                <a href="http://www.joinblush.com" class="brand"
                   title="<?= bloginfo('name') ?>"><?= bloginfo('name') ?></a>
            </div>
        </div>
        <div class="row visible-xs visible-sm nav-toggle-container">
            <a class="nav-toggle visible-xs visible-sm" href="#"><span></span></a>
        </div>
        <div class="row">
            <div class="col-lg-12 text-right footer-menu">
                <?php wp_nav_menu(array(
                    'menu_class' => 'list-inline hidden-xs hidden-sm',
                    'container' => 'false',
                    'theme_location' => 'footer-menu'
                )); ?>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-6 col-md-6 col-xs-5 copyright pull-left text-left mt2em">
                <p class="small">&copy; <?= date("Y") ?> Blush, All Rights Reserved</p>
            </div>
            <div class="col-lg-6 col-md-6 col-xs-7 social text-right pull-right mt2em">
                <ul class="social list-inline text-center ">
                    <li class="stripe"><a href="http://stripe.com"></a></li>
                    <?
                    $instagram = get_option('blush_instagram_url');
                    $facebook = get_option('blush_facebook_url');
                    $twitter = get_option('blush_twitter_url');
                    $spotify = get_option('blush_spotify_embed');
                    ?>
                    <? if ($instagram) { ?>
                        <li class="instagram"><a target="_blank" href="<?= $instagram ?>">Check Us Out on Instagram</a>
                        </li><? } ?>
                    <? if ($facebook) { ?>
                        <li class="facebook"><a target="_blank" href="<?= $facebook ?>">Check Us Out on Facebook</a>
                        </li><? } ?>
                    <? if ($twitter) { ?>
                        <li class="twitter"><a target="_blank" href="<?= $twitter ?>">Follow Us on Twitter</a>
                        </li><? } ?>
                    <? if ($spotify) { ?>
                        <li class="spotify"><a data-toggle="modal" href="#spotify_modal">Spotify Playlist</a>
                        </li><? } ?>
                </ul>
                <div class="clearfix"></div>
            </div>
        </div>
    </div>
    <?
    exit;
}

;

add_action('wp_ajax_blush_footer', 'blush_footer');
add_action('wp_ajax_nopriv_blush_footer', 'blush_footer');

/** Get Footer **/
function spotify_modal()
{
    $spotify = get_option('blush_spotify_embed');
    ?>
    <div class="modal fade" id="spotify-modal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-body">
                    <iframe src="<?= $spotify ?>" width="530" height="400" frameborder="0"
                            allowtransparency="true"></iframe>
                </div>
            </div>
        </div>
    </div>
    <?
    exit;
}

;

add_action('wp_ajax_spotify_modal', 'spotify_modal');
add_action('wp_ajax_nopriv_spotify_modal', 'spotify_modal');

/******** SHORTCODES ***************/


function content($limit, $add_elipsis = false)
{
    global $post;
    $content = explode(' ', get_the_content(), $limit);
    if (count($content) >= $limit) {
        array_pop($content);
        if ($add_elipsis) {
            $content = implode(" ", $content).'...';
        } else {
            $content = implode(" ", $content);
        }
    } else {
        $content = implode(" ", $content);
    }
    //$content .= ' <a href="' . get_permalink() . '">read more</a>';
    $content = preg_replace('/\[.+\]/', '', $content);
    $content = apply_filters('the_content', $content);
    $content = str_replace(']]>', ']]&gt;', $content);
    return $content;
}

?>