<?php

if (isset($_POST['gdbr_saving'])) {
    $options["exclude_categories"] = $_POST["exclude_categories"];
    $options["exclude_posts"] = $_POST["exclude_posts"];
    $options["exclude_pages"] = $_POST["exclude_pages"];
    $options["report_email"] = $_POST["report_email"];
    $options["auto_insert_front"] = isset($_POST['auto_insert_front']) ? 1 : 0;
    $options["auto_insert_single"] = isset($_POST['auto_insert_single']) ? 1 : 0;
    $options["auto_insert_page"] = isset($_POST['auto_insert_page']) ? 1 : 0;
    $options["auto_insert_archive"] = isset($_POST['auto_insert_archive']) ? 1 : 0;
    $options["auto_insert_search"] = isset($_POST['auto_insert_search']) ? 1 : 0;
    update_option('gd-broken-report', $options);

?>
<div id="message" class="updated fade" style="background-color: rgb(255, 251, 204);"><p><strong><?php _e("Settings saved.", "gd-broken-report"); ?></strong></p></div>
<?php } ?>

<div class="gdsr"><div class="wrap">
<h2 class="gdbrlogopage">GD Broken Report: <?php _e("Settings", "gd-broken-report"); ?></h2>
<form method="post">
<table class="form-table"><tbody>
<tr><th scope="row"><?php _e("Auto Insert", "gd-broken-report"); ?></th>
    <td>
        <input type="checkbox" name="auto_insert_front" id="auto_insert_front"<?php if ($options["auto_insert_front"] == 1) echo " checked"; ?> /><label style="margin-left: 5px;" for="integrate_dashboard"><?php _e("For posts on the front page", "gd-broken-report"); ?></label><br />
        <input type="checkbox" name="auto_insert_single" id="auto_insert_single"<?php if ($options["auto_insert_single"] == 1) echo " checked"; ?> /><label style="margin-left: 5px;" for="integrate_dashboard"><?php _e("For single post", "gd-broken-report"); ?></label><br />
        <input type="checkbox" name="auto_insert_page" id="auto_insert_page"<?php if ($options["auto_insert_page"] == 1) echo " checked"; ?> /><label style="margin-left: 5px;" for="integrate_dashboard"><?php _e("For single page", "gd-broken-report"); ?></label><br />
        <input type="checkbox" name="auto_insert_archive" id="auto_insert_archive"<?php if ($options["auto_insert_archive"] == 1) echo " checked"; ?> /><label style="margin-left: 5px;" for="integrate_dashboard"><?php _e("For posts in the archive", "gd-broken-report"); ?></label><br />
        <input type="checkbox" name="auto_insert_search" id="auto_insert_search"<?php if ($options["auto_insert_search"] == 1) echo " checked"; ?> /><label style="margin-left: 5px;" for="integrate_dashboard"><?php _e("For posts on search results", "gd-broken-report"); ?></label>
    </td>
</tr>
<tr><th scope="row"><?php _e("Exclude On", "gd-broken-report"); ?></th>
    <td>
        <table cellpadding="0" cellspacing="0" class="previewtable">
            <tr>
                <td width="150"><?php _e("Categories", "gd-broken-report"); ?>:</td>
                <td><input type="text" name="exclude_categories" id="exclude_categories" value="<?php echo $options["exclude_categories"]; ?>" style="width: 500px;" /></td>
            </tr>
            <tr>
                <td width="150"><?php _e("Posts", "gd-broken-report"); ?>:</td>
                <td><input type="text" name="exclude_posts" id="exclude_posts" value="<?php echo $options["exclude_posts"]; ?>" style="width: 500px;" /></td>
            </tr>
            <tr>
                <td width="150"><?php _e("Pages", "gd-broken-report"); ?>:</td>
                <td><input type="text" name="exclude_pages" id="exclude_pages" value="<?php echo $options["exclude_pages"]; ?>" style="width: 500px;" /></td>
            </tr>
        </table>
        <div class="gdsr-table-split"></div>
        <?php _e("Add one or more category, page or post id's separated by commas.", "gd-broken-report"); ?>
    </td>
</tr>
<tr><th scope="row"><?php _e("Send Report", "gd-broken-report"); ?></th>
    <td>
        <table cellpadding="0" cellspacing="0" class="previewtable">
            <tr>
                <td width="150"><?php _e("Email", "gd-broken-report"); ?>:</td>
                <td><input type="text" name="report_email" id="report_email" value="<?php echo $options["report_email"]; ?>" style="width: 500px;" /></td>
            </tr>
            <tr>
                <td width="150"><?php _e("Subject", "gd-broken-report"); ?>:</td>
                <td><input type="text" name="report_email" id="report_email" value="<?php echo $options["report_email_subject"]; ?>" style="width: 500px;" /></td>
            </tr>
        </table>
    </td>
</tr>
</tbody></table>
<p class="submit"><input type="submit" class="inputbutton" value="Save Settings" name="gdbr_saving"/></p>
</form>
</div></div>