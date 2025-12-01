
<aside class="block-main-content">

<div class="block-banner" id="block-banner">
<?php foreach ($banners as $banner): ?>
            <div class="item-banner">
                <img src="../../<?php echo $banner['hinhanh']; ?>" alt="<?php echo $banner['mota']; ?>">
            </div>
        <?php endforeach; ?>
</div>