<?php

function make_slug($text, $suffix = '') {
    $slug = strtolower(trim($text));
    $slug = preg_replace('/[^a-z0-9]+/', '-', $slug);
    $slug = trim($slug, '-');
    if ($slug === '') {
        $slug = 'item';
    }
    return $suffix ? $slug . '-' . $suffix : $slug;
}

function unique_slug($pdo, $table, $text, $ignore_id = null) {
    $base = make_slug($text);
    $slug = $base;
    $i = 1;
    while (true) {
        $sql = "SELECT id FROM {$table} WHERE slug = ?" . ($ignore_id ? ' AND id != ?' : '');
        $params = $ignore_id ? [$slug, $ignore_id] : [$slug];
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        if (!$stmt->fetch()) {
            return $slug;
        }
        $i++;
        $slug = $base . '-' . $i;
    }
}

function save_single_upload($field, $upload_subdir, $allowed_ext) {
    if (empty($_FILES[$field]) || $_FILES[$field]['error'] !== UPLOAD_ERR_OK) {
        if (!empty($_FILES[$field]) && $_FILES[$field]['error'] !== UPLOAD_ERR_NO_FILE) {
            error_log("save_single_upload($field): PHP upload error code " . $_FILES[$field]['error'] . ' (check upload_max_filesize/post_max_size)');
        }
        return null;
    }
    $orig = $_FILES[$field]['name'];
    $ext = strtolower(pathinfo($orig, PATHINFO_EXTENSION));
    if (!in_array($ext, $allowed_ext, true)) {
        error_log("save_single_upload($field): rejected extension '$ext' for '$orig'");
        return null;
    }
    $dest_dir = __DIR__ . '/../uploads/' . $upload_subdir . '/';
    if (!is_dir($dest_dir) && !mkdir($dest_dir, 0755, true) && !is_dir($dest_dir)) {
        error_log("save_single_upload($field): could not create directory $dest_dir (check filesystem permissions)");
        return null;
    }
    if (!is_writable($dest_dir)) {
        error_log("save_single_upload($field): directory $dest_dir is not writable by PHP (check filesystem permissions/ownership)");
        return null;
    }
    $safe_name = bin2hex(random_bytes(8)) . '.' . $ext;
    $dest_path = $dest_dir . $safe_name;
    if (move_uploaded_file($_FILES[$field]['tmp_name'], $dest_path)) {
        return 'uploads/' . $upload_subdir . '/' . $safe_name;
    }
    error_log("save_single_upload($field): move_uploaded_file() failed writing to $dest_path");
    return null;
}

function save_indexed_upload($field, $index, $upload_subdir, $allowed_ext) {
    if (empty($_FILES[$field]['name'][$index]) || $_FILES[$field]['error'][$index] !== UPLOAD_ERR_OK) {
        return null;
    }
    $orig = $_FILES[$field]['name'][$index];
    $ext = strtolower(pathinfo($orig, PATHINFO_EXTENSION));
    if (!in_array($ext, $allowed_ext, true)) {
        return null;
    }
    $dest_dir = __DIR__ . '/../uploads/' . $upload_subdir . '/';
    if (!is_dir($dest_dir) && !mkdir($dest_dir, 0755, true) && !is_dir($dest_dir)) {
        error_log("save_indexed_upload($field): could not create directory $dest_dir (check filesystem permissions)");
        return null;
    }
    if (!is_writable($dest_dir)) {
        error_log("save_indexed_upload($field): directory $dest_dir is not writable by PHP");
        return null;
    }
    $safe_name = bin2hex(random_bytes(8)) . '.' . $ext;
    $dest_path = $dest_dir . $safe_name;
    if (move_uploaded_file($_FILES[$field]['tmp_name'][$index], $dest_path)) {
        return 'uploads/' . $upload_subdir . '/' . $safe_name;
    }
    error_log("save_indexed_upload($field): move_uploaded_file() failed writing to $dest_path");
    return null;
}

function save_uploaded_files($files_field, $upload_subdir, $allowed_ext) {
    $saved = [];
    if (empty($_FILES[$files_field])) {
        return $saved;
    }
    $file = $_FILES[$files_field];
    $count = is_array($file['name']) ? count($file['name']) : 0;
    $dest_dir = __DIR__ . '/../uploads/' . $upload_subdir . '/';
    if (!is_dir($dest_dir) && !mkdir($dest_dir, 0755, true) && !is_dir($dest_dir)) {
        error_log("save_uploaded_files($files_field): could not create directory $dest_dir (check filesystem permissions)");
        return $saved;
    }
    if (!is_writable($dest_dir)) {
        error_log("save_uploaded_files($files_field): directory $dest_dir is not writable by PHP");
        return $saved;
    }
    for ($i = 0; $i < $count; $i++) {
        if ($file['error'][$i] !== UPLOAD_ERR_OK || $file['name'][$i] === '') {
            continue;
        }
        $orig = $file['name'][$i];
        $ext = strtolower(pathinfo($orig, PATHINFO_EXTENSION));
        if (!in_array($ext, $allowed_ext, true)) {
            continue;
        }
        $safe_name = bin2hex(random_bytes(8)) . '.' . $ext;
        $dest_path = $dest_dir . $safe_name;
        if (move_uploaded_file($file['tmp_name'][$i], $dest_path)) {
            $saved[] = [
                'path' => 'uploads/' . $upload_subdir . '/' . $safe_name,
                'original_name' => $orig,
                'ext' => $ext,
            ];
        }
    }
    return $saved;
}
