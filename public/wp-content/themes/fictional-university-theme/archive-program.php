<?php
/**
 * Created by PhpStorm.
 * User: zzabk
 * Date: 10/31/2018
 * Time: 9:58 PM
 */
?>

<?php

get_header();
pageBanner(array(
        'title' => 'All Programs',
        'subtitle' => 'There is something for everyone Have a look around.'
));
?>


    <div class="container container--narrow page-section">
        <ul class="link-list min-list">
            <?php
            while (have_posts()) {
                the_post(); ?>
                <li><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></li>
            <?php }
            echo paginate_links();
            ?>
        </ul>

    </div>

<?php get_footer();

?>