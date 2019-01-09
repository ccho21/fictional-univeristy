<?php
/**
 * Created by PhpStorm.
 * User: zzabk
 * Date: 11/12/2018
 * Time: 2:56 PM
 */
?>
<?php
if (!is_user_logged_in()) {
    wp_redirect(esc_url(site_url('/')));
    exit;
}

get_header();


while (have_posts()) {
    the_post();
    pageBanner();
    ?>

    <div class="container container--narrow page-section">
        <div class="create-note">
            <h2 class="headline headline--medium">Create New Note</h2>
            <input type="text" placeholder="Title" class="new-note-title">
            <textarea name="" id="" cols="30" rows="10" placeholder="Your note here..." class="new-note-body"></textarea>
            <span class="submit-note">Create Note</span>
            <span class="note-limit-message">Note limit reached: Delete an existing note to make room for oa new one.</span>
        </div>

        <ul class="min-list link-list" id="my-notes">
            <?php
            $userNote = new WP_Query(
                array(
                    'post_type' => 'note',
                    'post_per_page' => -1,
                    'author' => get_current_user_id()
                ));
            while($userNote->have_posts()) {
                $userNote->the_post(); ?>

                <li data-id="<?php the_ID(); ?>">
                    <input class="note-title-field" type="text" value="<?php echo str_replace('Private:', '', esc_attr(get_the_title())); ?>" readonly>
                    <span class="edit-note"><i class="fa fa-pencil" aria-hidden="true"></i> Edit</span>
                    <span class="delete-note"><i class="fa fa-trash-o" aria-hidden="true"></i> Delete</span>

                    <textarea class="note-body-field" name="" id="" cols="30" rows="10" readonly><?php echo esc_textarea(get_the_content()); ?></textarea>
                    <span class="update-note btn btn--blue btn--small"><i class="fa fa-arrow-right" aria-hidden="true"></i> Save</span>
                </li>

            <?php } ?>
        </ul>
    </div>

<?php }

get_footer();

?>