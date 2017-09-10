<!DOCTYPE html>
<html <? if(isset($angular_app)) { ?>ng-app="<?=$angular_app?>"<? } ?>>
<head>
    <title><?=$this->config->item('site_title') . " Administration - " . $page_title?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="/assets/images/favicon.png">

    <script type="text/javascript">
        var SITE_URL = '<?= site_detect_url() ?>';
    </script>
    <? if(IS_TEST) {
        include(APPPATH.'views/includes/assets-header.html');
    } else {
        include(APPPATH.'views/includes/prod/assets-header.html');
    } ?>
    <? if (!IS_TEST) { ?>
        <script>
            (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
                (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
                m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
            })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

            ga('create', 'UA-65313779-1', 'auto');
            ga('send', 'pageview');

        </script>

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
                _fbq.push(['addPixelId', '633563586786492']);
            })();
            window._fbq = window._fbq || [];
            window._fbq.push(['track', 'PixelInitialized', {}]);
        </script>
        <noscript><img height="1" width="1" alt="" style="display:none" src="https://www.facebook.com/tr?id=633563586786492&amp;ev=PixelInitialized" /></noscript>
    <? } ?>
</head>
<body id="admin">

<header id="header">
    <div class="container">
        <a href="/" class="brand pull-left" title="<?=$this->config->item('site_title')?>"><?=$this->config->item('site_title')?></a>

        <div class="user-meta pull-right">
            <a id="logout-link" href="<?= site_detect_url('accounts/logout')?>/">Logout</a>
            <div class="user-name hidden-xs">
                <img src="<?= get_avatar(IMG_SIZE_SM) ?>" class="img-circle img-thumbnail"/>
                <h4><?= get_user_name()?></h4>
            </div>
        </div>
    </div>
    <div class="clearfix"></div>
</header>

<div id="page">