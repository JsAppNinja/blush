<?php
if (!function_exists('wp') && !empty($_SERVER['SCRIPT_FILENAME']) && basename(__FILE__) == basename($_SERVER['SCRIPT_FILENAME'])) {
    die ('You do not have sufficient permissions to access this page!');
}
get_header();
?>
    <script type="text/javascript" src="//s7.addthis.com/js/300/addthis_widget.js#pubid=ra-58a4bfed4ba5f31e"></script>
    <script async src="//"></script>
    <script>
        (adsbygoogle = window.adsbygoogle || []).push({
            google_ad_client: "ca-pub-7411249708944599",
            enable_page_level_ads: true
        });
    </script>
    <style> div.body p {
            font-size: 1.1em;
        } </style>
    <div id="content">
        <div class="newsletter">
            <div class="container">
                <div class="col-lg-12">
                    <p><strong><em>Sign up for our newsletter to occasionally receive our tidbits and
                                advice</em></strong></p>
                    <?php gravity_form(7, false, false, false, '', true); ?>
                </div>
            </div>
        </div>
        <div class="body">
            <div class="container">
                <div class="row">
                    <div class="col-lg-9 col-md-9 col-sm-8">
                        <?php if (have_posts()) : ?>
                            <?php while (have_posts()) : the_post(); ?>
                                <div <?php post_class() ?> id="post-<?php the_ID(); ?>">
                                    <div class="title">
                                        <div class="row">
                                            <div class="col-lg-12">
                                                <h1><a href="<? the_permalink() ?>"><? the_title() ?></a></h1>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="meta">
                                        <div class="row">
                                            <div class="col-lg-4">
                                                <? the_time('M j, Y'); ?> by <? the_author() ?>
                                            </div>
                                            <div class="col-lg-8">
                                                <?php the_tags('Tags: ', ', ', '<br />'); ?>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="featured-image">
                                        <div class="row">
                                            <div class="col-lg-12">
                                                <?php the_post_thumbnail('full', array(
                                                    'class' => 'img-responsive post-thumbnail'
                                                )); ?>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="body">
                                        <div class="row">
                                            <div class="col-lg-12">
                                                <?php the_content() ?>
                                            </div>
                                            <div class="col-lg-12">
                                                <?php comments_template('', true); ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="clearfix"></div>

                            <?php endwhile; ?>
                        <?php endif; ?>
                        <?php if (function_exists('wp_page_numbers')) {
                            wp_page_numbers();
                        } ?>
                    </div>
                    <div class="col-lg-3 col-sm-4 col-md-3 hidden-xs">
                        <? get_sidebar() ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php get_footer(); ?>