<?php

class GDBrokenReportDefaults {
    var $default_options = array(
        "version" => "1.2.2",
        "date" => "2009.08.28.",
        "status" => "Stable",
        "build" => 14,
        "disable_autoinsert" => 0,
        "exclude_categories" => "",
        "exclude_posts" => "",
        "exclude_pages" => "",
        "auto_insert_front" => 1,
        "auto_insert_single" => 1,
        "auto_insert_page" => 1,
        "auto_insert_archive" => 1,
        "auto_insert_search" => 1,
        "report_email" => "",
        "report_email_subject" => "Broken Post Report"
    );

    var $default_shortcode_gdposttable = array(
        "post_id" => 0
    );

    function GDBrokenReportDefaults() {}
}

?>
