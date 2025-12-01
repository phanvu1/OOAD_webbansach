<footer>
                <div class="flex-item block-footer">

                    <div class="box-item-footer">
                        <div class="logo-footer">
                            <a href="">
                                <img src="../view/resources/images/logonew.png" alt="">
                            </a>
                        </div> 
                        <p><b>Địa chỉ: </b><?php echo $thongtin['diachi']; ?></p>
                        <p><b>Email:</b> <?php echo $thongtin['email']; ?> </p>
                        <p><b>Điện thoại:</b> <a href="tel:<?php echo $thongtin['sodienthoai']; ?>"><?php echo $thongtin['sodienthoai']; ?> </a></p>
                    
                    </div>
                    <div class="box-item-footer">
                        <h3 class="title-footer">Thời gian hoạt động</h3>
                        <p>Thứ 2-6 (8h -> 21h)</p>
                        <p>Thứ 7 (8h -> 16h)</p>
                        <p>Chủ nhật (Nghỉ)</p>
                    </div>
                    <div class="box-item-footer">
                    <ul class="social-footer">
                            <h3 class="title-footer">SOCIAL PAGE</h3>

                            <li>
                                <a href="<?php echo $thongtin['facebook']; ?>" target="_blank">
                                    <i class="icon-facebook"></i>
                                </a>
                            </li>
                            <li>
                                <a href="<?php echo $thongtin['tiktok']; ?>" target="_blank">
                                <i class="icon-home"></i>
                                </a>
                            </li>
                            <!-- <li>
                                <a href="mailto:<?php echo $thongtin['email']; ?>">
                                    <i class="icon-mail"></i>
                                </a>
                            </li> -->
                        </ul>
                        <!-- <h3 class="title-footer">Fanepage</h3>
                   
                        <iframe
                            src="<?php echo $thongtin['facebook']; ?>/anngoc0701/"
                            width="397" height="170" style="border:none;overflow:hidden" scrolling="no" frameborder="0"
                            allowTransparency="true" allow="encrypted-media"> </iframe> -->

                    </div>
                </div>
            </footer>
            <div class="coopy-right">
                <p>Copyright © 2025 UniBook</p>
            </div>
        </article>
        
    </div>
       
    <script src="../view/resources/js/jquery.min.js"></script>
    <script src="../view/resources/slick/slick.min.js"></script>
    <script src="../view/resources/js/perfect-scrollbar.jquery.min.js"></script>
    <script type="text/javascript" src="../view/resources/js/common.js"></script>
    <script>
        $('.block-sub-cate').perfectScrollbar()
        // $('html').perfectScrollbar()
    </script>
    <script src="https://sp.zalo.me/plugins/sdk.js"></script>
    <div id="fb-root"></div>
    <script async defer crossorigin="anonymous"
        src="https://connect.facebook.net/vi_VN/sdk.js#xfbml=1&version=v6.0&appId=704558949717927&autoLogAppEvents=1"></script>
    <script>(function (d, s, id) {
        var js, fjs = d.getElementsByTagName(s)[0];
        if (d.getElementById(id)) return;
        js = d.createElement(s); js.id = id;
        js.src = "https://connect.facebook.net/en_US/sdk.js#xfbml=1&version=v3.0";
        fjs.parentNode.insertBefore(js, fjs);
    }(document, 'script', 'facebook-jssdk'));</script>
</body>
</html>
