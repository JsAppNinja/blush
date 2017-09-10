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
                    <div class="col-lg-8">
                        <?php if (have_posts()) : ?>
                            <?php while (have_posts()) : the_post(); ?>
                                <div <?php post_class() ?> id="post-<?php the_ID(); ?>">
                                    <?php the_content('Read More') ?>
                                    <div class="clearfix"></div>

                                    <h4>Responsibilities</h4>

                                    <?=types_render_field('responsibilities', array("output", "html"))?>

                                    <h4>Requirements/Skills</h4>

                                    <?=types_render_field('required-skills', array("output", "html"))?>

                                    <div class="submit-container">

                                        <div class="row">
                                            <div class="col-lg-6 col-md-6 col-sm-12 copyright pull-left text-left">
                                                <button class="btn btn-primary btn-lg btn-submit btn-career-apply" id="btn-career-apply">Apply</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endwhile; ?>
                        <?php endif; ?>
                    </div>
                    <div class="col-lg-4">
                        <h4>How We Work</h4>
                        <?
                        /** Save the query for the home page before we do additional query_posts() calls */
                        $temp_query = $wp_query;
                        query_posts('p=311&post_type=page&posts_per_page=1');
                        while (have_posts()) : the_post();
                            the_content();
                        endwhile;
                        $wp_query = $temp_query; ?>

                        <div class="spacer clearfix"></div>
                        <div class="spacer clearfix"></div>

                        <h4>Work With Us</h4>
                        <?
                        /** Save the query for the home page before we do additional query_posts() calls */
                        $temp_query = $wp_query;
                        query_posts('p=309&post_type=page&posts_per_page=1');
                        while (have_posts()) : the_post();
                            the_content();
                        endwhile;
                        $wp_query = $temp_query; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php get_footer(); ?>