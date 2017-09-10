<?php

// Theme Options
// by Space Chimp Media (http://spacechimpmedia.com/)

$themename = "Blush";
$shortname = "blush";
$version = "1.0";

// Create theme options
global $options;
$options = array(
    array("name" => "Social Networks",
        "type" => "section"),

    array("name" => "Instagram URL",
        "desc" => "The URL to the Instagram Account.  If left blank, it will hide the instagram icon.",
        "id" => $shortname . "_instagram_url",
        "type" => "text",
        "std" => ""),

    array("name" => "Facebook URL",
        "desc" => "The URL to the Facebook Profile.  If left blank, it will hide the yelp icon.",
        "id" => $shortname . "_facebook_url",
        "type" => "text",
        "std" => ""),

    array("name" => "Twitter URL",
        "desc" => "The URL to the Twitter Profile.  If left blank, it will hide the yelp icon.",
        "id" => $shortname . "_twitter_url",
        "type" => "text",
        "std" => ""),

    array("name" => "Spotify Playlist Embed",
        "desc" => "The iframe code for the spotify playlist.",
        "id" => $shortname . "_spotify_embed",
        "type" => "text",
        "std" => ""),

    array("type" => "close")
);

function blush_add_admin()
{

    global $themename, $shortname, $options;

    if (isset ($_GET['page']) && ($_GET['page'] == basename(__FILE__))) {

        if (isset ($_REQUEST['action']) && ('save' == $_REQUEST['action'])) {

            foreach ($options as $value) {
                if (array_key_exists('id', $value)) {
                    if (isset($_REQUEST[$value['id']])) {
                        update_option($value['id'], $_REQUEST[$value['id']]);
                    } else {
                        delete_option($value['id']);
                    }
                }
            }
            header("Location: admin.php?page=" . basename(__FILE__) . "&saved=true");
        } else if (isset ($_REQUEST['action']) && ('reset' == $_REQUEST['action'])) {
            foreach ($options as $value) {
                if (array_key_exists('id', $value)) {
                    delete_option($value['id']);
                }
            }
            header("Location: admin.php?page=" . basename(__FILE__) . "&reset=true");
        }
    }

    add_menu_page($themename, $themename." Options", 'administrator', basename(__FILE__), $shortname.'_admin');
    add_submenu_page(basename(__FILE__), $themename . ' Options', 'Theme Options', 'administrator', basename(__FILE__), $shortname.'_admin'); // Default
}

function blush_admin()
{

    global $themename, $shortname, $version, $options;
    $i = 0;

    if (isset ($_REQUEST['saved']) && ($_REQUEST['saved'])) echo '<div id="message" class="updated fade"><p><strong>' . $themename . ' settings saved.</strong></p></div>';
    if (isset ($_REQUEST['reset']) && ($_REQUEST['reset'])) echo '<div id="message" class="updated fade"><p><strong>' . $themename . ' settings reset.</strong></p></div>';

    ?>

<div class="wrap ">
    <div class="options_wrap">
        <h2 class="settings-title"><?php echo $themename; ?> Settings</h2>

        <form method="post">

            <?php foreach ($options as $value) {
            switch ($value['type']) {
                case "section":
                    ?>
	<div class="section_wrap">
	<h3 class="section_title"><?php echo $value['name']; ?></h3>
 	<div class="section_body">

<?php
                    break;
                case 'text':
                    ?>

                        <div class="options_input options_text">
                            <div class="options_desc"><?php echo $value['desc']; ?></div>
                            <span class="labels"><label
                                for="<?php echo $value['id']; ?>"><?php echo $value['name']; ?></label></span>
                            <input name="<?php echo $value['id']; ?>" id="<?php echo $value['id']; ?>"
                                   type="<?php echo $value['type']; ?>"
                                   value="<?php if (get_option($value['id']) != "") {
                                       echo stripslashes(get_option($value['id']));
                                   } else {
                                       echo $value['std'];
                                   } ?>"/>
                        </div>

                        <?php
                    break;
                case 'textarea':
                    ?>
                        <div class="options_input options_textarea">
                            <div class="options_desc"><?php echo $value['desc']; ?></div>
                            <span class="labels"><label
                                for="<?php echo $value['id']; ?>"><?php echo $value['name']; ?></label></span>
                            <textarea name="<?php echo $value['id']; ?>" type="<?php echo $value['type']; ?>" cols=""
                                      rows=""><?php if (get_option($value['id']) != "") {
                                echo stripslashes(get_option($value['id']));
                            } else {
                                echo $value['std'];
                            } ?></textarea>
                        </div>

                        <?php
                    break;
                case 'select':
                    ?>
                        <div class="options_input options_select">
                            <div class="options_desc"><?php echo $value['desc']; ?></div>
                            <span class="labels"><label
                                for="<?php echo $value['id']; ?>"><?php echo $value['name']; ?></label></span>
                            <select name="<?php echo $value['id']; ?>" id="<?php echo $value['id']; ?>">
                                <?php foreach ($value['options'] as $option) { ?>
                                <option <?php if (get_option($value['id']) == $option) {
                                    echo 'selected="selected"';
                                } ?>><?php echo $option; ?></option><?php } ?>
                            </select>
                        </div>

                        <?php
                    break;
                case "radio":
                    ?>
                        <div class="options_input options_select">
                            <div class="options_desc"><?php echo $value['desc']; ?></div>
                            <span class="labels"><label
                                for="<?php echo $value['id']; ?>"><?php echo $value['name']; ?></label></span>
                            <?php foreach ($value['options'] as $key => $option) {
                            $radio_setting = get_option($value['id']);
                            if ($radio_setting != '') {
                                if ($key == get_option($value['id'])) {
					$checked = "checked='checked'";
					} else {
                                    $checked = "";
                                }
                            } else {
                                if ($key == $value['std']) {
					$checked = "checked='checked'";
				} else {
                                    $checked = "";
                                }
                            }?>
                            <input type="radio" name="<?php echo $value['id']; ?>"
                                   value="<?php echo $key; ?>" <?php echo $checked; ?> /><?php echo $option; ?><br/>
                            <?php } ?>
                        </div>

                        <?php
                    break;
                case "checkbox":
                    ?>
                        <div class="options_input options_checkbox">
                            <div class="options_desc"><?php echo $value['desc']; ?></div>
                            <?php if (get_option($value['id'])) { $checked = "checked='checked'"; } else {
                            $checked = "";
                        } ?>
                            <input type="checkbox" name="<?php echo $value['id']; ?>" id="<?php echo $value['id']; ?>"
                                   value="true" <?php echo $checked; ?> />
                            <label for="<?php echo $value['id']; ?>"><?php echo $value['name']; ?></label>
                        </div>

                        <?php
                    break;
                case "close":
                    $i++;
                    ?>
                        <span class="submit"><input class="button button-primary" name="save<?php echo $i; ?>" type="submit"
                                                    value="Save Changes"/></span>
</div><!--#section_body-->
</div><!--#section_wrap-->

                        <?php break;
            }
        }
            ?>

            <input type="hidden" name="action" value="save"/>
<span class="submit">
<input name="save" type="submit" class="button button-primary" value="Save All Changes"/>
</span>
        </form>

        <form method="post">
<span class="submit">
<input name="reset" type="submit" class="button" value="Reset All Options"/>
<input type="hidden" name="action" value="reset"/>
</span>
        </form>
        <br/>
    </div>
    <!--#options-wrap-->

</div> <!--#wrap-->
<?php
}

function blush_add_init() {

    global $shortname;
    $file_dir=get_bloginfo('template_directory');
    wp_enqueue_style($shortname."Css", $file_dir."/functions/theme-options.css", false, "1.0", "all");
    wp_enqueue_script($shortname."Script", $file_dir."/functions/theme-options.js", false, "1.0");

}

add_action('admin_init', $shortname.'_add_init');
add_action('admin_menu', $shortname.'_add_admin');
?>