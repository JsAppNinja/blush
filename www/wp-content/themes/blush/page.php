<?php
if (!function_exists('wp') && !empty($_SERVER['SCRIPT_FILENAME']) && basename(__FILE__) == basename($_SERVER['SCRIPT_FILENAME'])) {
    die ('You do not have sufficient permissions to access this page!');
}
get_header();
?>

    <div id="content">
        <div class="page-title">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12">
                        <h1><?= single_post_title() ?></h1>
                    </div>
                </div>
            </div>
        </div>
        <div class="body">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12">
                        <?php if (have_posts()) : ?>
                            <?php while (have_posts()) : the_post(); ?>
                                <div <?php post_class() ?> id="post-<?php the_ID(); ?>">
                                    <?php the_content('Read More') ?>
                                    <div class="clearfix"></div>
                                </div>
                            <?php endwhile; ?>
                        <?php endif; ?>
                        <?php if (function_exists('wp_page_numbers')) {
                            wp_page_numbers();
                        } ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php get_footer(); ?>