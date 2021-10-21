<?php
function get_rating($pos, $tot, $popularity) {
    if ($tot <= 20) {
        return intval((($popularity / 5) + $pos) / ($tot + 20) * 100);
    }
    else {
        return intval(($pos / $tot) * 100);
    }
}
function get_rating_no_pop($pos, $tot) {
    if ($tot > 0) {
        return intval(($pos / $tot) * 100);
    }
    else {
        return 0;
    }
}
?>
