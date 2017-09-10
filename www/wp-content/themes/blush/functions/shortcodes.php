<?
function theme_section_break($atts) {
    return "<div class='clearfix'></div>";
}
add_shortcode( 'section_break', 'theme_section_break' );

function theme_section($atts, $content='') {
    $cols = $atts['cols'];
    $type = $atts['type'];

    $class_name = $type;
    if($cols==2) {
        $class_name.=" col-lg-6";
    }

    $out = '<div class="section-block '.$class_name.'"><div class="content">'.$content.'</div></div>';
    return $out;
}
add_shortcode( 'section', 'theme_section' );
?>