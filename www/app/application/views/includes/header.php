<!DOCTYPE html>
<html <? if(isset($angular_app)) { ?>ng-app="<?=$angular_app?>"<? } ?>>
<head>
    <title><?=$this->config->item('site_title') . " - " . $page_title?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="/assets/images/favicon.png">

    <script type="text/javascript">
        var SITE_URL = '<?= site_detect_url() ?>';
    </script>
    <? if (IS_TEST) {
        include(APPPATH . 'views/includes/assets-header.html');
    } else {
        include(APPPATH . 'views/includes/prod/assets-header.html');
    } ?>
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
    

    <!-- Facebook Conversion Code for Members -->
    <script>(function() {
            var _fbq = window._fbq || (window._fbq = []);
            if (!_fbq.loaded) {
                var fbds = document.createElement('script');
                fbds.async = true;
                fbds.src = '//connect.facebook.net/en_US/fbds.js';
                var s = document.getElementsByTagName('script')[0];
                s.parentNode.insertBefore(fbds, s);
                _fbq.loaded = true;
            }
        })();
        window._fbq = window._fbq || [];
    </script>
</head>
<body id="app">

<header id="header">
    <div class="container">
        <? if (get_user_id()) { ?>
            <a href="<?=site_url('dashboard')?>" class="brand pull-left"
               title="<?=$this->config->item('site_title')?>"><?=$this->config->item('site_title')?></a>
        <? } else { ?>
            <a href="<?=$this->config->item('domain_url')?>" class="brand pull-left"
               title="<?=$this->config->item('site_title')?>"><?=$this->config->item('site_title')?></a>
        <? } ?>
        <? if (get_user_id()) { ?>
            <a id="logout-link" href="<?= site_detect_url('accounts/logout/' . random_string())?>/">Logout</a>

            <div class="btn-group pull-right light-group">
                <a href="<?= site_detect_url('dashboard')?>" class="btn btn-lg <? if (isset($page_active_dashboard)) {
                    echo active_nav($page_active_dashboard);
                }?>" id="btn-dashboard">Dashboard</a> <a href="<?= site_detect_url('my_account')?>"
                                                         class='btn btn-lg <? if (isset($page_active_my_account)) {
                                                             echo active_nav($page_active_my_account);
                                                         }?>' id="btn-my-acount">My Account</a>
            </div>
        <? } ?>
    </div>
    <div class="clearfix"></div>
</header>

<div id="page">