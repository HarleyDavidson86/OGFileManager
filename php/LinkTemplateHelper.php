<?php

class LinkTemplateHelper {

    public static function buildLink($absPathToFile, $name, $description) {
        $link  = '<div class="ogfm-wrapper">';
        $link .= '<a class="ogfm-link" href="'.$absPathToFile.'">'.$name.'</a>';
        $link .= '<p class="ogfm-description">'.$description.'</p>';
        $link .= '</div>';
        return $link;
    }
}