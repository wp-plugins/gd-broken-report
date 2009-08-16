<?php

/*
Plugin Name: GD Broken Report
Plugin URI: http://www.dev4press.com/plugins/gd-broken-report/
Description: Add report broken post to preset email address using templates.
Version: 1.2.1
Author: Milan Petrovic
Author URI: http://www.dev4press.com/
*/

require_once(dirname(__FILE__)."/config.php");
require_once(dirname(__FILE__)."/code/defaults.php");
require_once(dirname(__FILE__)."/gdragon/gd_debug.php");
require_once(dirname(__FILE__)."/gdragon/gd_functions.php");

if (!class_exists('GDBrokenReport')) {
    class GDBrokenReport {
        var $plugin_url;
        var $plugin_path;
        var $admin_plugin;
        var $is_bot;
        var $o;

        var $default_options;
        var $default_shortcode_gdposttable;

        function GDBrokenReport() {
            $gdd = new GDBrokenReportDefaults();
            $this->default_options = $gdd->default_options;
            $this->default_shortcode_gdposttable = $gdd->default_shortcode_gdposttable;

            $this->plugin_path_url();
            $this->install_plugin();
            $this->actions_filters();
        }

        function detect_bot() {
            $str = $_SERVER['HTTP_USER_AGENT'];
            $spiders = array("Teoma", "alexa", "froogle", "Gigabot", "inktomi", "looksmart", "URL_Spider_SQL", "Firefly", "NationalDirectory", "Ask Jeeves", "TECNOSEEK", "InfoSeek", "WebFindBot", "girafabot", "crawler", "www.galaxy.com", "Googlebot", "Scooter", "Slurp", "msnbot", "appie", "FAST", "WebBug", "Spade", "ZyBorg", "rabaz", "Baiduspider", "Feedfetcher-Google", "TechnoratiSnoop", "Rankivabot", "Mediapartners-Google", "Sogou web spider", "WebAlta Crawler");
            foreach($spiders as $spider) {
                if (ereg($spider, $str)) {
                    $this->is_bot = true;
                    return true;
                }
            }
            $this->is_bot = false;
            return false;
        }

        function plugin_path_url() {
            $this->plugin_url = WP_PLUGIN_URL.'/gd-broken-report/';
            $this->plugin_path = dirname(__FILE__)."/";
            define('GDBROKENREPORT_URL', $this->plugin_url);
            define('GDBROKENREPORT_PATH', $this->plugin_path);
        }

        function install_plugin() {
            $this->o = get_option('gd-broken-report');

            if ($this->o["build"] < $this->default_options["build"]) { }

            if (!is_array($this->o)) {
                update_option('gd-broken-report', $this->default_options);
                $this->o = get_option('gd-broken-report');
            }
            else {
                $this->o = gdFunctionsGDBR::upgrade_settings($this->o, $this->default_options);

                $this->o["version"] = $this->default_options["version"];
                $this->o["date"] = $this->default_options["date"];
                $this->o["status"] = $this->default_options["status"];
                $this->o["build"] = $this->default_options["build"];

                update_option('gd-broken-report', $this->o);
            }
        }

        function actions_filters() {
            add_action('init', array(&$this, 'init'));

            add_action('admin_init', array(&$this, 'admin_init'));
            add_action('admin_head', array(&$this, 'admin_head'));
            add_action('admin_menu', array(&$this, 'admin_menu'));

            add_filter('the_content', array(&$this, 'the_content'));

            add_shortcode(strtolower("gdbrokenreport"), array(&$this, "shortcode_gdbrokenreport"));
            add_shortcode(strtoupper("gdbrokenreport"), array(&$this, "shortcode_gdbrokenreport"));
        }

        function shortcode_gdbrokenreport($atts = array()) {
            $s = shortcode_atts($this->default_shortcode_gdposttable, $atts);
            if ($s["post_id"] == 0) {
                global $post;
                $post_id = $post->ID;
            } else $post_id = $s["post_id"];
            return $this->prepare_report($post_id);
        }

        function admin_init() {
            $this->l = get_locale();
            if(!empty($this->l)) {
                $moFile = dirname(__FILE__)."/languages/gd-broken-report-".$this->l.".mo";
                if (@file_exists($moFile) && is_readable($moFile)) load_textdomain('gd-broken-report', $moFile);
            }
        }

        function the_content($content) {
            global $post;

            if (is_admin() || $this->is_bot) return $content;

            if (!is_feed()) {
                if ((is_single() && $this->o["auto_insert_single"] == 1) ||
                    (is_page() && $this->o["auto_insert_page"] == 1) ||
                    (is_home() && $this->o["auto_insert_front"] == 1) ||
                    (is_archive() && $this->o["auto_insert_archive"] == 1) ||
                    (is_search() && $this->o["auto_insert_search"] == 1)
                ) {
                    if ($this->o["exclude_categories"] != "") {
                        $cats = explode(",", $this->o["exclude_categories"]);
                        if (in_category($cats)) return $content;
                    }
                    if ($this->o["exclude_posts"] != "") {
                        $posts = explode(",", $this->o["exclude_posts"]);
                        if (is_single($posts)) return $content;
                    }
                    if (is_page() && $this->o["exclude_pages"] != "") {
                        $pages = explode(",", $this->o["exclude_pages"]);
                        if (is_page($pages)) return $content;
                    }
                    $content .= $this->prepare_report($post->ID);
                }
            }

            return $content;
        }

        function prepare_report($post_id) {
            $report = "";
            $report_status = get_post_meta($post_id, "gd_broken_report", true);
            if ($report_status == "reported") {
                $report = file_get_contents($this->plugin_path."integrate/already_reported.txt");
            }
            else {
                $report_url = add_query_arg("report", $post_id);
                $report = file_get_contents($this->plugin_path."integrate/report_broken.txt");
                $report = str_replace('%URL%', $report_url, $report);
            }
            return $report;
        }

        function init() {
            if (is_admin() && isset($_GET["page"])) {
                if ($_GET["page"] == "gd-broken-report/gd-broken-report.php") {
                    $this->admin_plugin = true;
                }
            }
            if (isset($_GET["report"])) {
                $post_id = $_GET["report"];
                update_post_meta($post_id, "gd_broken_report", "reported");

                $email = file_get_contents($this->plugin_path."templates/report_email.txt");
                $email = str_replace('%POST_ID%', $post_id, $email);
                $email = str_replace('%POST_URL%', get_permalink($post_id), $email);
                $email = str_replace('%POST_EDIT%', trailingslashit(get_bloginfo("wpurl"))."wp-admin/post.php?action=edit&post=".$post_id, $email);
                wp_mail($this->o["report_email"], $this->o["report_email_subject"], $email, 'From: '.get_option('blogname').' <'.get_option('admin_email').'>');

                $url = remove_query_arg("report");
                wp_redirect($url);
                exit;
            }
            $this->detect_bot();
        }

        function admin_head() {
            if ($this->admin_plugin) {
                wp_admin_css('css/dashboard');
                echo('<link rel="stylesheet" href="'.$this->plugin_url.'css/admin.css" type="text/css" media="screen" />');
            }
        }

        function admin_menu() {
            add_options_page('GD Broken Report', "GD Broken Report", 10, __FILE__, array(&$this, "admin_menu_front"));
        }

        function admin_menu_front() {
            $options = $this->o;
            include($this->plugin_path.'admin/settings.php');
        }
    }

    $gdbr_debug = new gdDebugGDBR(GDBROKENREPORT_LOG_PATH);

    function wp_gdbr_dump($msg, $obj, $block = "none", $mode = "a+") {
        if (GDBROKENREPORT_DEBUG_ACTIVE) {
            global $gdbr_debug;
            $gdbr_debug->dump($msg, $obj, $block, $mode);
        }
    }

    $gdbr = new GDBrokenReport();

    /**
     * Render broken report block. Can be used for manual integration.
     *
     * @global object $post current post object within the loop
     * @global object $gdbr main plugin object
     * @param int $post_id id of the post to use
     * @param bool $echo echo results or return it as a string
     * @return string html with rendered contents
     */
    function gd_broken_report($post_id = 0, $echo = true) {
        global $post, $gdbr;
        if ($post_id == 0) $post_id = $post->ID;

        if ($echo) echo $gdbr->prepare_report($post_id);
        else return $gdbr->prepare_report($post_id);
    }
}

?>
