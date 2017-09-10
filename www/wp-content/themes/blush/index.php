<?php
if (!function_exists('wp') && !empty($_SERVER['SCRIPT_FILENAME']) && basename(__FILE__) == basename($_SERVER['SCRIPT_FILENAME'])) {
    die ('You do not have sufficient permissions to access this page!');
}
get_header();
$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
?>
    <script type="text/javascript" src="//s7.addthis.com/js/300/addthis_widget.js#pubid=ra-58a4bfed4ba5f31e"></script>
    <script async src="//"></script>
    <script>
        (adsbygoogle = window.adsbygoogle || []).push({
            google_ad_client: "ca-pub-7411249708944599",
            enable_page_level_ads: true
        });
    </script>
    <div id="content">
        <div class="body blog-list <?= ($paged==1) ? 'first' : '' ?>">
            <div class="container">
                <div class="row">
                    <?php if (have_posts()) : ?>
                        <?php $index = 0; ?>
                        <?php while (have_posts()) : the_post(); ?>
                            <?php if($index==0 && 1==$paged) { ?>

                                </div>
                            </div>
                            <div class="newsletter">
                                <div class="container">
                                    <div class="col-lg-12">
                                        <p><strong><em>Sign up for our newsletter to occasionally receive our tidbits and advice</em></strong></p>
                                        <?php gravity_form(7, false, false, false, '', true); ?>
                                    </div>
                                </div>
                            </div>
                            <div class="container">
                                <div class="featured">
                                    <div <?php post_class('blog-item') ?> id="post-<?php the_ID(); ?>">
                                        <div class="img-holder">
                                            <div class="row">
                                                <div class="col-lg-8 col-lg-offset-2">
                                                    <h4 class="text-center">Featured Article</h4>
                                                    <a href="<? the_permalink() ?>">
                                                        <?php the_post_thumbnail('full', array(
                                                            'class' => 'img-responsive post-thumbnail'
                                                        )); ?>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="title">
                                            <div class="row">
                                                <div class="col-lg-8 col-lg-offset-2">
                                                    <h3 class="text-center"><a href="<? the_permalink() ?>"><? the_title() ?></a></h3>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="container">
                                <div class="row">

                            <? } else { ?>

                                <div class="col-sm-6 col-md-4">
                                    <div <?php post_class('blog-item') ?> id="post-<?php the_ID(); ?>">
                                        <div class="img-holder">
                                            <a href="<? the_permalink() ?>">
                                                <?php the_post_thumbnail('full', array(
                                                    'class' => 'img-responsive post-thumbnail'
                                                )); ?>
                                            </a>
                                        </div>
                                        <div class="title">
                                            <h3><a href="<? the_permalink() ?>"><? the_title() ?></a></h3>
                                        </div>
                                    </div>
                                </div>
                            <?php }
                            $index++;
                            ?>
                        <?php endwhile; ?>
                    <?php endif; ?>
                </div>
                <div class="row">
                    <div class="col-xs-12">
                        <?php if (function_exists('wp_page_numbers')) {
                            wp_page_numbers();
                        } ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php get_footer(); ?>