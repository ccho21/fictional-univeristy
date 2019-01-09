<?php
/**
 * Created by PhpStorm.
 * User: zzabk
 * Date: 11/12/2018
 * Time: 1:56 PM
 */
?>

<form class="search-form" method="GET" action="<?php echo esc_url(site_url('/'))?>">
    <label class="headline headline--medium" for="s">Perform a New Search</label>
    <div class="search-form-row">
        <input placeholder="What are you looking for" class="s" id="s" type="search" name="s">
        <input class="search-submit" type="submit" value="Search">
    </div>
</form>
