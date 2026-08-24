<?php
function current_page_url() {
    $scheme = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
    $host = $_SERVER['HTTP_HOST'] ?? 'localhost';
    $uri = $_SERVER['REQUEST_URI'] ?? '';
    return $scheme . '://' . $host . $uri;
}

function render_share_buttons($title, $url = null) {
    $url = $url ?: current_page_url();
    $encoded_url = urlencode($url);
    $encoded_title = urlencode($title);
    ?>
    <div class="share-buttons">
        <span class="share-buttons_label">Share:</span>
        <a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo $encoded_url; ?>" class="share-buttons_link is-facebook" target="_blank" rel="noopener" title="Share on Facebook"><i class="fa-brands fa-facebook-f"></i></a>
        <a href="https://twitter.com/intent/tweet?url=<?php echo $encoded_url; ?>&text=<?php echo $encoded_title; ?>" class="share-buttons_link is-twitter" target="_blank" rel="noopener" title="Share on Twitter/X"><i class="fa-brands fa-twitter"></i></a>
        <a href="https://api.whatsapp.com/send?text=<?php echo $encoded_title; ?>%20<?php echo $encoded_url; ?>" class="share-buttons_link is-whatsapp" target="_blank" rel="noopener" title="Share on WhatsApp"><i class="fa-brands fa-whatsapp"></i></a>
        <button type="button" class="share-buttons_link is-copy" title="Copy link" data-share-url="<?php echo htmlspecialchars($url); ?>"><i class="fa-solid fa-link"></i></button>
    </div>
    <?php
}
?>
<script>
document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.share-buttons_link.is-copy').forEach(function (btn) {
        btn.addEventListener('click', function () {
            var url = btn.getAttribute('data-share-url');
            navigator.clipboard.writeText(url).then(function () {
                var original = btn.innerHTML;
                btn.innerHTML = '<i class="fa-solid fa-check"></i>';
                setTimeout(function () { btn.innerHTML = original; }, 1500);
            });
        });
    });
});
</script>
