<?php
include_once '../controller/menucontroller.php';
?>
<article class="container block-content">
            <div class="flex-item">
                <aside class="cch-navmenu">
                    <!-- <p class="text"><img src="../view/resources/images/icon/user1.png" alt="">Thông tin cá nhân</p> -->
                    <div class="top-cch-navmenu">
                        <div class="box-icon-menu">
                            <span></span>
                            <span></span>
                            <span></span>
                            <p>MENU</p>
                        </div>
                        <p>DANH MỤC</p>
                    </div>
                    <div class="block-show-menu">

                        <div class="block-sub-cate">
                        <ul class="block-sub-cate-parent">
    <?php foreach ($danhmuc_list as $danhmuc): ?>
        <li>
        <a href="../controller/index.php?pg=sanpham&iddanhmuc=<?php echo $danhmuc['iddanhmuc']; ?>">
           <?php echo htmlspecialchars($danhmuc['tendanhmuc']); ?>
 
                <?php
                $subCategories = getSubCategories($danhmuc['iddanhmuc']);
                if (!empty($subCategories)) {
                    echo '<i class="icon-btn-next"></i>';
                }
                ?>
            </a>

            <?php if (!empty($subCategories)): ?>
                <ul class="block-sub-cate-child">
                    <?php foreach ($subCategories as $sub): ?>
                        <li>
                        <a href="../controller/index.php?pg=sanpham&iddanhmuc=<?php echo $danhmuc['iddanhmuc']; ?>&idchitietdanhmuc=<?php echo $sub['idchitietdanhmuc']; ?>">
                                            <?php echo htmlspecialchars($sub['tenchitietdanhmuc']); ?>
                                        </a>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>
        </li>
    <?php endforeach; ?>
</ul>
                            <div class="share-social">
                                <p class="address"><i class="icon-home"></i><?php echo $thongtin['diachi']; ?></p>
                                <p class="address"><i class="icon-phone"></i><?php echo $thongtin['sodienthoai']; ?> </p>
                                <p class="address"><i class="icon-mail"></i><?php echo $thongtin['email']; ?></p>
                              
                            </div>
                            
                        </div>
                        
                    </div>
                 
                </aside>  