<?php
function get_page_section($pdo, $page_key, $section_key) {
    static $cache = [];
    $ck = $page_key . '::' . $section_key;
    if (!array_key_exists($ck, $cache)) {
        $stmt = $pdo->prepare('SELECT * FROM page_sections WHERE page_key = ? AND section_key = ?');
        $stmt->execute([$page_key, $section_key]);
        $row = $stmt->fetch();
        $cache[$ck] = $row ?: null;
    }
    return $cache[$ck];
}
