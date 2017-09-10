<?php
if (!function_exists('wp') && !empty($_SERVER['SCRIPT_FILENAME']) && basename(__FILE__) == basename($_SERVER['SCRIPT_FILENAME'])) {
    die ('You do not have sufficient permissions to access this page!');
}
get_header();
?>

    <div id="content">
        <div id="lede">
            <div class="lede-image"></div>

            <div class="stripe">
                <div class="container">
                    <h4>
                        Life can be mean. Let's talk behind its back. <sup>TM</sup>
                    </h4>
                    <a href="#modal-video" class="btn btn-lg btn-watch pull-right" data-toggle="modal" onclick="trackFBQ('WatchIntroVideo');"
                       data-url="//www.youtube.com/embed/J-5-lAvd8Nc"><i class="fa fa-play-circle-o"></i> Watch Video

                        <div class="message-for-girls-video video-thumb"></div>
                    </a>
                    <button type="button" class='btn btn-lg btn-newsletter pull-right' id="btn-dear-blush" onclick="trackFBQ('DearBlush');">
                        Dear Blush
                    </button>

                </div>
            </div>
        </div>
        <div class="about-sections">
            <div class="separator-section" id="how-it-works">
                <div class="title">
                    <div class="inner">
                        <div class="container">
                            <a name="life-coaching"></a>

                            <h3>How We Help</h3>
                        </div>
                    </div>
                </div>
                <div class="container">
                    <div class="row">
                        <?
                        /** Save the query for the home page before we do additional query_posts() calls */
                        $temp_query = $wp_query;
                        query_posts('p=44&post_type=page&posts_per_page=1');
                        while (have_posts()) : the_post();
                            the_content();
                        endwhile;
                        $wp_query = $temp_query; ?>
                    </div>
                </div>
            </div>

            <div class="separator-section" id="plans">
                <a name="plans"></a>

                <div class="title">
                    <div class="inner">
                        <div class="container">
                            <h3>Subscription Plans</h3>
                            <span class="blush-journal">Blush Journal = 1 credit</span>
                            <span class="video-session"> Video Session = 2 credits</span>
                        </div>
                    </div>
                </div>
                <div class="container">

                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12">
                            <?
                            /** Save the query for the home page before we do additional query_posts() calls */
                            $temp_query = $wp_query;
                            query_posts('p=329&post_type=page&posts_per_page=1');
                            while (have_posts()) : the_post();
                                the_content();
                            endwhile;
                            $wp_query = $temp_query; ?>
                        </div>
                    </div>

                    <div class="row" style="margin-top:1em">
                        <div class="col-lg-4 col-md-4 col-sm-4">
                            <div class="plan-item" id="the-touch-up">
                                <h4>The Touch-up</h4>
                                <h5><sup>$</sup><span>79</span></h5>

                                <div class="credits"><strong>4</strong> credits per month</div>
                                <a href="/app/accounts/registration/" class="join" onclick="trackFBQ('ChooseTouchUp');">JOIN</a>
                            </div>
                            <div class="xicon touchup"></div>
                        </div>
                        <div class="col-lg-4 col-md-4 col-sm-4">
                            <div class="plan-item" id="the-essentials">
                                <h4>The Essentials</h4>
                                <h5><sup>$</sup><span>149</span></h5>

                                <div class="credits"><strong>8</strong> credits per month</div>
                                <a href="/app/accounts/registration/" class="join" onclick="trackFBQ('ChooseEssentials');">JOIN</a>
                            </div>
                            <div class="xicon essentials"></div>
                        </div>
                        <div class="col-lg-4 col-md-4 col-sm-4">
                            <div class="plan-item-best" id="the-makeover">
                                <div class="best-value">BEST VALUE</div>
                                <div class="plan-item">
                                    <h4>The Makeover</h4>
                                    <h5><sup>$</sup><span>249</span></h5>

                                    <div class="credits"><strong>16</strong> credits per month</div>
                                    <a href="/app/accounts/registration/" class="join" onclick="trackFBQ('ChooseMakeover');">JOIN</a>
                                </div>
                            </div>
                            <div class="xicon makeover"></div>
                        </div>

                    </div>
                </div>
            </div>


            <div class="separator-section" id="pricing">

                <div class="title">
                    <div class="inner">
                        <div class="container">
                            <h3>Pay As You Go</h3>
                            <span class="blush-journal">Blush Journal = 1 credit</span>
                            <span class="video-session"> Video Session = 2 credits</span>
                        </div>
                    </div>
                </div>
                <div class="container">
                    <div class="row">
                        <div class="col-lg-4 col-md-4 col-sm-12">
                            <?
                            /** Save the query for the home page before we do additional query_posts() calls */
                            $temp_query = $wp_query;
                            query_posts('p=118&post_type=page&posts_per_page=1');
                            while (have_posts()) : the_post();
                                the_content();
                            endwhile;
                            $wp_query = $temp_query; ?>
                        </div>
                        <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12" id="pricing-video">
                            <h4 class="video">Video Session</h4>
                            <h5><sup>$</sup><span>50</span></h5>
                        </div>
                        <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12" id="pricing-journal">
                            <h4 class="diary">Journal Entry</h4>
                            <h5><sup>$</sup><span>25</span></h5>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="section-title hidden-xs">
            <div class="container">
                <div class="frame framex">
                    <h2>About the Service</h2>
                    <h5>Online coaching at the tip of your fingers! Start becoming the person you want to be.</h5>
                </div>
            </div>
        </div>

        <div class="section" id="why-blush">
            <div class="container">
                <div class="row">
                    <div class="col-lg-7 col-md-7 col-sm-4 hidden-xs">
                        <img src="/assets/images/why_blush.png" alt="Why Blush" class="img-responsive"/>
                    </div>
                    <div class="col-lg-5 col-md-5 col-sm-8 col-xs-12 content">
                        <?
                        /** Save the query for the home page before we do additional query_posts() calls */
                        $temp_query = $wp_query;
                        query_posts('p=112&post_type=page&posts_per_page=1');
                        while (have_posts()) : the_post();
                            the_content();
                        endwhile;
                        $wp_query = $temp_query; ?>
                    </div>


                    <div class="message-for-moms-dads-video video-thumb hidden-xs">
                        <span>Message for Moms &amp; Dads</span>
                        <a href="#modal-video" class="watch-button" data-toggle="modal" onclick="trackFBQ('WatchMomsDadsVideo');"
                           data-url="//www.youtube.com/embed/AZRbaVucmxI">Watch Video</a>
                    </div>

                    <a href="#modal-video" class="watch-button-mobile visible-xs" data-toggle="modal"
                       data-url="//www.youtube.com/embed/AZRbaVucmxI">Watch Video</a>
                </div>
            </div>
            <div class="stripe">
                <div class="container">
                    <div class="clouds"></div>
                </div>
            </div>
        </div>

        <div class="section" id="coaching-vs-counseling">
            <div class="container">
                <div class="row">
                    <div class="col-lg-5 col-md-7 col-sm-8 col-xs-12 content">
                        <?
                        /** Save the query for the home page before we do additional query_posts() calls */
                        $temp_query = $wp_query;
                        query_posts('p=116&post_type=page&posts_per_page=1');
                        while (have_posts()) : the_post();
                            the_content();
                        endwhile;
                        $wp_query = $temp_query; ?>
                    </div>
                    <div class="col-lg-7 col-md-7 col-sm-4 hidden-xs">
                        <img src="/assets/images/how_it_works.png" alt="Coaching Vs. Counseling" class="img-responsive"/>
                    </div>
                </div>
                <div class="kxan-video video-thumb hidden-xs">
                    <span>Blush on KXAN</span>
                    <a href="#modal-video" class="watch-button" data-toggle="modal" onclick="trackFBQ('WatchKXANVideo');"
                       data-url="http://up.anv.bz/latest/anvload.html?key=eyJtIjoiTElOIiwicCI6ImRlZmF1bHQiLCJ2IjoiMzIxMzA3IiwicGx1Z2lucyI6eyJkZnAiOnsiYWRUYWdVcmwiOiJodHRwOi8vcHViYWRzLmcuZG91YmxlY2xpY2submV0L2dhbXBhZC9hZHM/c3o9MXgxMDAwJml1PS81Njc4L2xpbi5LWEFOL3N0dWRpbzUxMi9saWZlLWNvYWNoaW5nLXdpdGgtYmx1c2gvZGV0YWlsJmNpdV9zenM9MzAweDI1MCZnZGZwX3JlcT0xJmVudj12cCZvdXRwdXQ9eG1sX3Zhc3QyJmFkX3J1bGU9MSJ9LCJhbmFseXRpY3MiOnsicGRiIjoiNDMwMTY4ODYifSwib21uaXR1cmUiOnsicHJvZmlsZSI6IkxJTiIsImFjY291bnQiOiJkcHNkcHNreGFuLGRwc2dsb2JhbCIsInRyYWNraW5nU2VydmVyIjoibGludHYuMTIyLjJvNy5uZXQifX19">Watch
                        Video</a>
                </div>

                <a href="#modal-video" class="watch-button watch-button-kxan-mobile" data-toggle="modal" onclick="trackFBQ('WatchKXANVideo');"
                   data-url="http://up.anv.bz/latest/anvload.html?key=eyJtIjoiTElOIiwicCI6ImRlZmF1bHQiLCJ2IjoiMzIxMzA3IiwicGx1Z2lucyI6eyJkZnAiOnsiYWRUYWdVcmwiOiJodHRwOi8vcHViYWRzLmcuZG91YmxlY2xpY2submV0L2dhbXBhZC9hZHM/c3o9MXgxMDAwJml1PS81Njc4L2xpbi5LWEFOL3N0dWRpbzUxMi9saWZlLWNvYWNoaW5nLXdpdGgtYmx1c2gvZGV0YWlsJmNpdV9zenM9MzAweDI1MCZnZGZwX3JlcT0xJmVudj12cCZvdXRwdXQ9eG1sX3Zhc3QyJmFkX3J1bGU9MSJ9LCJhbmFseXRpY3MiOnsicGRiIjoiNDMwMTY4ODYifSwib21uaXR1cmUiOnsicHJvZmlsZSI6IkxJTiIsImFjY291bnQiOiJkcHNkcHNreGFuLGRwc2dsb2JhbCIsInRyYWNraW5nU2VydmVyIjoibGludHYuMTIyLjJvNy5uZXQifX19">Watch
                    Video</a>
            </div>
        </div>
        <div class="separator-section" id="blog">

            <div class="title">
                <div class="inner">
                    <div class="container">
                        <h3>Blog</h3>
                    </div>
                </div>
            </div>

            <div class="container">
                <div class="row">
                    <div class="col-lg-8">
                        <?
                        $temp_query = $wp_query;
                        query_posts(array('post_type' => 'post', 'posts_per_page' => 1, 'order' => 'DESC', 'orderby' => 'id'));
                        global $more;
                        $more = 0;
                        while (have_posts()) : the_post();
                            ?>
                            <div class="row featured-post">
                                <div class="col-lg-5 hidden-xs">
                                    <?php the_post_thumbnail('full', array(
                                        'class' => 'img-responsive post-thumbnail'
                                    )); ?>
                                    <div class="tags hidden-xs">
                                        <?php the_tags('<i class="fa fa-tags fa-2x"></i> <span>', '</span><span>', '</span>'); ?>
                                    </div>
                                </div>
                                <div class="col-lg-7 col-xs-12">
                                    <h3><a href="<? the_permalink() ?>"><? the_title() ?></a></h3>

                                    <div class="meta">
                                        <? the_time('M j, Y'); ?> | By <? the_author() ?>
                                    </div>
                                    <?php the_content(__('Read More')) ?>
                                </div>
                            </div>
                            <?
                        endwhile;
                        $wp_query = $temp_query; ?>
                    </div>
                    <div class="col-lg-4">
                        <?
                        $temp_query = $wp_query;
                        query_posts(array('post_type' => 'post', 'posts_per_page' => 3, 'order' => 'DESC', 'orderby' => 'id', 'offset' => 1));
                        global $more;
                        $more = 0;
                        while (have_posts()) : the_post();
                            ?>
                            <div class="row post">
                                <div class="col-xs-4">
                                    <?php the_post_thumbnail('full', array(
                                        'class' => 'img-responsive post-thumbnail'
                                    )); ?>
                                </div>
                                <div class="col-xs-8">
                                    <h4><a href="<? the_permalink() ?>"><? the_title() ?></a></h4>
                                </div>
                            </div>
                            <?
                        endwhile;
                        $wp_query = $temp_query; ?>
                    </div>
                </div>
            </div>
        </div>

        <div class="section-title coaches">
            <a name="coaches"></a>

            <div class="container">
                <div class="frame framey">
                    <h2 class="meet-coaches">Meet Our Blush Coaches!</h2>
                    <h5>Let us match you with one of our coaches! We’re all really cool…and if you don’t believe us,
                        here’s
                        proof.</h5>
                </div>
            </div>
        </div>

        <div class="section" id="coaches">
            <div class="container">
                <?
                $temp_query = $wp_query;
                query_posts(array('post_type' => 'coach', 'posts_per_page' => 100, 'order' => 'ASC', 'orderby' => 'date'));
                ?>
                <? if ($wp_query->found_posts > 4) { ?>
                <a href="#" id="coach-slides-next" class="next next-dark">Next</a>
                <a href="#" id="coach-slides-prev" class="prev prev-dark">Previous</a>
                <? } ?>

                <div id="coach-slides" class="clearfix">

                        <?
                        $counter = 0;
                        while (have_posts()) : the_post();
                            if ($counter % 3 == 0) {
                                echo "<div class='slide row'>";
                            }
                            ?>

                            <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12 coach">
                                <a href="<? the_permalink() ?>"><? the_post_thumbnail(array(200, 245)) ?></a>
                                <h4>
                                    <a href="<? the_permalink() ?>"><?= types_render_field('first-name', array("output", "html")) ?></a>
                                </h4>
                                <h5><?= types_render_field('title-or-company', array("output", "html")) ?></h5>
                                <?= types_render_field('intro', array("output", "html")) ?>
                                <!--
                        <div class="social">
                            <a class="facebook"
                               href="<?php echo types_render_field('facebook-url', array("raw" => true)) ?>"></a>
                            <a class="twitter"
                               href="<?php echo types_render_field('twitter-url', array("raw" => true)) ?>"></a>
                        </div>-->
                            </div>
                            <?
                            $counter++;
                            if ($counter % 3 == 0 || $counter == $wp_query->found_posts) {
                                echo "</div>";
                            }
                            ?>
                        <? endwhile;
                        $wp_query = $temp_query; ?>
                    </div>

            </div>
        </div>

        <div class="section hidden-xs" id="testimonials">
            <div class="container">
                <div id="testimonials-nav">
                    <div class="links"></div>
                </div>
                <?
                $temp_query = $wp_query;
                query_posts(array('post_type' => 'testimonial', 'posts_per_page' => 100, 'order' => 'ASC', 'orderby' => 'id'));
                ?>
                <? if ($wp_query->found_posts > 2) { ?>
                <? } ?>

                <div id="testimonial-slides" class="row">

                    <?
                    $counter = 0;
                    while (have_posts()) : the_post();
                        if ($counter % 2 == 0) {
                            echo "<div class='slide'>";
                        }
                        ?>

                        <div class="col-lg-6 col-md-6 col-sm-6">
                            <div class="testimonial <? if ($counter % 2 != 0) { ?>odd pull-right<? } ?>">
                                <div class="content">
                                    <?= content(35, true); ?>
                                </div>
                                <span class="author">- <?= types_render_field('author', array("output", "html")) ?></span>
                            </div>
                        </div>
                        <?
                        $counter++;
                        if ($counter % 2 == 0 || $counter == $wp_query->found_posts) {
                            echo "</div>";
                        }
                        ?>
                    <? endwhile;
                    $wp_query = $temp_query; ?>
                </div>

                <div class="row">
                    <div class="col-lg-12 text-center">
                        <button class="btn btn-yellow btn-lg tell-story-button">Tell Your Story</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="section visible-xs" id="testimonials-mobile">
            <div class="container">
                <div id="testimonials-mobile-nav">
                    <div class="links"></div>
                </div>
                <?
                $temp_query = $wp_query;
                query_posts(array('post_type' => 'testimonial', 'posts_per_page' => 100, 'order' => 'ASC', 'orderby' => 'id'));
                ?>
                <? if ($wp_query->found_posts > 2) { ?>
                <? } ?>

                <div id="testimonial-mobile-slides" class="row">

                    <?
                    $counter = 0;
                    while (have_posts()) : the_post(); ?>
                        <div class='slide'>
                            <div class="col-xs-12">
                                <div class="testimonial <? if ($counter % 2 != 0) { ?>odd pull-right<? } ?>">
                                    <div class="content">
                                        <?= content(35, true); ?>
                                    </div>
                                <span
                                    class="author">- <?= types_render_field('author', array("output", "html")) ?></span>
                                </div>
                            </div>
                        </div>
                    <? endwhile;
                    $wp_query = $temp_query; ?>
                </div>

                <div class="row">
                    <div class="col-lg-12 text-center">
                        <button class="btn btn-yellow btn-lg tell-story-button">Tell Your Story</button>
                    </div>
                </div>
            </div>
        </div>


        <div class="section" id="featured-on">
            <div class="container">
                <h3 class="featured">FEATURED ON:</h3>

                <?
                $temp_query = $wp_query;
                query_posts(array('post_type' => 'featured', 'posts_per_page' => 100, 'order' => 'DESC', 'orderby' => 'id'));
                ?>
                <? if ($wp_query->found_posts > 4) { ?>
                    <a href="#" id="coach-slides-next" class="next next-dark">Next</a>
                    <a href="#" id="coach-slides-prev" class="prev prev-dark">Previous</a>
                <? } ?>

                <div id="feature-slides">

                    <?
                    $counter = 0;
                    while (have_posts()) : the_post();
                        if ($counter % 4 == 0) {
                            echo "<div class='slide row'>";
                        }
                        ?>

                        <div class="col-lg-3 col-md-3 col-xs-6 feature">
                            <a href="<?php echo types_render_field('featured-url', array("raw" => true)) ?>">
                                <? the_post_thumbnail(array(150, 150), array(
                                    'class' => 'img-responsive post-thumbnail'
                                )) ?>
                            </a>
                        </div>
                        <?
                        $counter++;
                        if ($counter % 4 == 0 || $counter == $wp_query->found_posts) {
                            echo "</div>";
                        }
                        ?>
                    <? endwhile;
                    $wp_query = $temp_query; ?>
                </div>

            </div>
        </div>


        <div class="section hidden-xs hidden-sm" id="get-started">
            <div class="container">
                <div class="pitch">
                    <!--<h2>Getting Started with Blush is Easy</h2>
                    <h5>Create an account, answer a few questions, and we will match you with a Blush Coach!</h5>-->
                </div>
                <div class="clearfix"></div>
                <div class="actions">
                    <a href="https://www.joinblush.com/app/accounts/registration/" class="btn btn-pink btn-lg">Get
                        Started</a>
                    <button class="btn btn-pink btn-lg contact-us-button" onclick="trackFBQ('ContactUs');">Contact Us
                    </button>
                </div>
                <div class="clearfix"></div>
            </div>
        </div>


        <div class="section visible-xs visible-sm" id="get-started-mobile">
            <div class="container">
                <div class="pitch">
                    <h2>Getting Started with Blush is Easy</h2>
                    <h5>Create an account, answer a few questions, and we will match you with a Blush Coach!</h5>
                </div>
                <div class="clearfix"></div>
                <div class="actions">
                    <a href="https://www.joinblush.com/app/accounts/registration/" class="btn get-started-button">Get
                        Started</a>
                    <button class="btn contact-us-button" onclick="trackFBQ('ContactUs');">Contact Us</button>
                </div>
                <div class="clearfix"></div>
            </div>
        </div>
    </div>
<? if (!is_scm_test()) { ?>
    <!-- Qualaroo for joinblush.com -->
    <!-- Paste this code right after the <body> tag on every page of your site. -->
    <script type="text/javascript">
        var _kiq = _kiq || [];
        (function () {
            setTimeout(function () {
                var d = document, f = d.getElementsByTagName('script')[0], s = d.createElement('script');
                s.type = 'text/javascript';
                s.async = true;
                s.src = '//s3.amazonaws.com/ki.js/49497/b53.js';
                f.parentNode.insertBefore(s, f);
            }, 1);
        })();
    </script>
<? } ?>
    <script type="text/javascript">
        app.load_pricing();
    </script>


    <div class="modal fade" id="modal-video">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-body"></div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div><!-- /.modal -->
<?php get_footer(); ?>