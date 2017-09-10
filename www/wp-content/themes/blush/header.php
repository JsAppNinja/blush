<? if (strpos($_SERVER['SERVER_NAME'], 'scmreview') > 0) {
    $blush_url = 'http://blush.scmreview.com';
} else {
    $blush_url = 'https://www.joinblush.com';
}
?>
<!DOCTYPE html>
<html>
<head>
    <title><?php bloginfo('name'); ?> | <?php is_front_page() ? bloginfo('description') : wp_title(''); ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="pingback" href="<?php bloginfo('pingback_url'); ?>"/>
    <meta name="p:domain_verify" content="fe2d08000ffa8fbbc2ece6ba92b2b324"/>
    <!-- HTML5 shim, for IE6-8 support of HTML5 elements -->
    <!--[if lt IE 9]>
    <link href="<?php bloginfo('template_url'); ?>/css/ie.css" rel="stylesheet">
    <![endif]-->
    <link rel="shortcut icon" href="/assets/images/favicon.png">

    <script type="text/javascript">
        var TEMPLATE_URL = '<?php bloginfo('template_url'); ?>';
    </script>
    <? gravity_form_enqueue_scripts(4, true); ?>
    <?php wp_head(); ?>
    <link rel="stylesheet" type="text/css" media="all" href="<?php bloginfo('stylesheet_url'); ?>"/>
    <? if (!is_scm_test()) { ?>
        <script type="text/javascript"> !function(e){if(!window.pintrk){window.pintrk=function(){window.pintrk.queue.push(Array.prototype.slice.call(arguments))};var n=window.pintrk;n.queue=[],n.version="3.0";var t=document.createElement("script");t.async=!0,t.src=e;var r=document.getElementsByTagName("script")[0];r.parentNode.insertBefore(t,r)}}("https://s.pinimg.com/ct/core.js"); pintrk('load','2612697799671'); pintrk('page', { page_name: 'My Page', page_category: 'My Page Category' }); </script> <noscript> <img height="1" width="1" style="display:none;" alt="" src="https://ct.pinterest.com/v3/?tid=2612697799671&noscript=1" /> </noscript>

        <!-- Hotjar Tracking Code for http://joinblush.com -->
        <script>
            (function(h,o,t,j,a,r){
                h.hj=h.hj||function(){(h.hj.q=h.hj.q||[]).push(arguments)};
                h._hjSettings={hjid:305283,hjsv:5};
                a=o.getElementsByTagName('head')[0];
                r=o.createElement('script');r.async=1;
                r.src=t+h._hjSettings.hjid+j+h._hjSettings.hjsv;
                a.appendChild(r);
            })(window,document,'//static.hotjar.com/c/hotjar-','.js?sv=');
        </script>

        <script>
            (function (i, s, o, g, r, a, m) {
                i['GoogleAnalyticsObject'] = r;
                i[r] = i[r] || function () {
                        (i[r].q = i[r].q || []).push(arguments)
                    }, i[r].l = 1 * new Date();
                a = s.createElement(o),
                    m = s.getElementsByTagName(o)[0];
                a.async = 1;
                a.src = g;
                m.parentNode.insertBefore(a, m)
            })(window, document, 'script', '//www.google-analytics.com/analytics.js', 'ga');

            ga('create', 'UA-65313779-1', 'auto');
            ga('send', 'pageview');

        </script>


        <!-- Facebook Pixel Code -->
        <script>
            !function (f, b, e, v, n, t, s) {
                if (f.fbq)return;
                n = f.fbq = function () {
                    n.callMethod ?
                        n.callMethod.apply(n, arguments) : n.queue.push(arguments)
                };
                if (!f._fbq)f._fbq = n;
                n.push = n;
                n.loaded = !0;
                n.version = '2.0';
                n.queue = [];
                t = b.createElement(e);
                t.async = !0;
                t.src = v;
                s = b.getElementsByTagName(e)[0];
                s.parentNode.insertBefore(t, s)
            }(window,
                document, 'script', 'https://connect.facebook.net/en_US/fbevents.js');

            fbq('init', '633563586786492');
            fbq('track', "PageView");</script>
        <noscript><img height="1" width="1" style="display:none"
                       src="https://www.facebook.com/tr?id=633563586786492&ev=PageView&noscript=1"
            /></noscript>
        <!-- End Facebook Pixel Code -->


    <? } ?>
    <style>
        .single.single-coach .st_facebook_large,
        .single.single-coach .st_twitter_large,
        .single.single-coach .st_email_large,
        .single.single-coach .st_sharethis_large,
        .single.single-coach .st_plusone_large,
        .single.single-coach .st_pinterest_large,
        .single.single-career .st_facebook_large,
        .single.single-career .st_twitter_large,
        .single.single-career .st_email_large,
        .single.single-career .st_sharethis_large,
        .single.single-career .st_plusone_large,
        .single.single-career .st_pinterest_large,
        .page-id-307 .st_facebook_large,
        .page-id-307 .st_twitter_large,
        .page-id-307 .st_email_large,
        .page-id-307 .st_sharethis_large,
        .page-id-307 .st_plusone_large,
        .page-id-307 .st_pinterest_large,
        .home.page .st_facebook_large,
        .home.page .st_twitter_large,
        .home.page .st_email_large,
        .home.page .st_sharethis_large,
        .home.page .st_plusone_large,
        .home.page .st_pinterest_large {
            display: none !important;
        }
    </style>
</head>
<body <?php body_class(); ?>>

<header id="header">
    <div class="container">
        <a href="<?= bloginfo('wpurl') ?>" class="brand pull-left"
           title="<?= bloginfo('name') ?>"><?= bloginfo('name') ?></a>
        <a class="nav-toggle visible-xs visible-sm" href="#"><span></span></a>
        <?php wp_nav_menu(array(
            'menu_class' => 'nav navbar-nav',
            'container' => 'false',
            'menu' => 'top'
        )); ?>

        <!--
        <div class="btn-group pull-right" id="logged_out" style="display:none">
            <a href="<?= $blush_url ?>/app/accounts/registration/" class='btn btn-lg btn-blue' id="btn-get-started">Get
                Started</a>
            <button type="button" class='btn btn-lg btn-purple-2' id="btn-login">Login</button>
        </div>

        <div class="btn-group pull-right" id="logged_in" style="display:none">
            <a href="<?= $blush_url ?>/app/dashboard" class='btn btn-lg btn-purple-2' id="btn-my-account">My Account</a>
        </div>
        -->
        <div id="login-box" style="display:none">
            <form action="<?= $blush_url ?>/app/accounts/login" method="post">
                <div class="form-group username">
                    <label class="sr-only" for="login-username">Username</label>
                    <span class="prefix"><i class="fa fa-user"></i></span>
                    <input type="text" class="form-control" id="login-username" placeholder="username" name="username">
                </div>

                <div class="form-group password">
                    <label class="sr-only" for="login-username">Password</label>
                    <span class="prefix"><i class="fa fa-lock"></i></span>
                    <input type="password" class="form-control" id="login-password" placeholder="password"
                           name="password">
                </div>

                <div class="clearfix"></div>

                <p class="text-right">
                    <small>
                        <a href="<?= $blush_url ?>/app/login/forgot">forgot password?</a>
                    </small>
                </p>

                <button class="pull-left btn btn-yellow btn-lg">Login</button>
                <ul class="pull-right social list-inline">
                    <?
                    $facebook = get_option('blush_facebook_url');
                    $twitter = get_option('blush_twitter_url');
                    ?>
                    <? if ($facebook) { ?>
                        <li class="facebook"><a target="_blank" href="<?= $facebook ?>">Check Us Out on Facebook</a>
                        </li><? } ?>
                    <? if ($twitter) { ?>
                        <li class="twitter"><a target="_blank" href="<?= $twitter ?>">Follow Us on Twitter</a>
                        </li><? } ?>
                </ul>

                <div class="clearfix"></div>
            </form>
        </div>
    </div>
    <div class="clearfix"></div>

</header>

<div id="page">
