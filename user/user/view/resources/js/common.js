const common = (function () {

	var iframe;

	const _generateSlider = function () {

		const elIndexBanner = document.getElementById('block-banner');

		if (!elIndexBanner) { return; }

		$(elIndexBanner).slick({
			infinite: true,
			autoplay: true,
			autoplaySpeed: 3000,
			slidesToShow: 1,
			slidesToScroll: 1,
			arrows: true,
			dots: true,
			prevArrow: '<span class="icon-btn-pre"><span class="icon-btn-pre"></span></span>',
			nextArrow: '<span class="icon-btn-next"><span class="icon-btn-next"></span></span>',
		})
	}; //end_generateSlider
	const _generateFacebookFanpage = function () {
		(function (d, s, id) {
			var js, fjs = d.getElementsByTagName(s)[0];
			if (d.getElementById(id)) return;
			js = d.createElement(s); js.id = id;
			js.src = 'https://connect.facebook.net/vi_VN/sdk.js#xfbml=1&version=v3.2&appId=704558949717927&autoLogAppEvents=1';
			fjs.parentNode.insertBefore(js, fjs);
		}(document, 'script', 'facebook-jssdk'));
	}; //end_generateFacebookFanpage
	const _producthotSlider = function () {

		const elproducthotSlider = document.getElementById('product-hot');

		if (!elproducthotSlider) { return; }

		$(elproducthotSlider).slick({
			infinite: true,
			autoplay: true,
			autoplaySpeed: 3000,
			slidesToShow: 4,
			slidesToScroll: 1,
			arrows: true,
			dots: false,
			prevArrow: '<span class="icon-btn-pre"></span>',
			nextArrow: '<span class="icon-btn-next"></span>',
			responsive: [
				{
					breakpoint: 641,
					settings: {
						slidesToShow: 2,
						slidesToScroll: 1,
						infinite: true,
						dots: false
					}
				},
			]
		})
	}; //end_generateSlider
	const _newsNoibat = function () {

		const elnewsNoiBat = document.getElementById('tin-tuc-noi-bat');

		if (!elnewsNoiBat) { return; }

		$(elnewsNoiBat).slick({
			infinite: false,
			autoplay: true,
			autoplaySpeed: 3000,
			slidesToShow: 4,
			slidesToScroll: 1,
			arrows: false,
			dots: false,
			prevArrow: '<span class="icon-btn-pre"></span>',
			nextArrow: '<span class="icon-btn-next"></span>',
			responsive: [
				{
					breakpoint: 641,
					settings: {
						slidesToShow: 2,
						slidesToScroll: 1,
						infinite: true,
						dots: false,
						arrows: true,
					}
				},
			]
		})
	}; //end_generateSlider
	// const _productDetailImage = function () {

	// 	const elImgDetailProduct = document.getElementById('block-img-product-detail');
	// 	const elThumImgDetailProduct = document.getElementById('block-thum-img-detail');
	// 	if (!elImgDetailProduct || !elThumImgDetailProduct) { return; }

	// 	$(elImgDetailProduct).slick({
	// 		infinite: true,
	// 		autoplay: true,
	// 		autoplaySpeed: 3000,
	// 		slidesToShow: 1,
	// 		slidesToScroll: 1,
	// 		arrows: false,
	// 		dots: false,
	// 		prevArrow: '<span class="icon-btn-pre"></span>',
	// 		nextArrow: '<span class="icon-btn-next"></span>',
	// 		asNavFor: '#block-thum-img-detail'
	// 	})
	// 	$(elThumImgDetailProduct).slick({
	// 		autoplaySpeed: 3000,
	// 		slidesToShow: 3,
	// 		slidesToScroll: 1,
	// 		arrows: true,
	// 		dots: false,
	// 		focusOnSelect: true,
	// 		prevArrow: '<span class="icon-btn-pre"></span>',
	// 		nextArrow: '<span class="icon-btn-next"></span>',
	// 		asNavFor: '#block-img-product-detail'
	// 	})
	// }; //end_generateSlider

	const _productDetailImage = function () {
		const elImgDetailProduct = document.getElementById('block-img-product-detail');
		const elThumImgDetailProduct = document.getElementById('block-thum-img-detail');
		if (!elImgDetailProduct || !elThumImgDetailProduct) {
			return;
		}
		const mainSlideCount = $(elImgDetailProduct).find('.img-detail-product').length;
		const thumbSlideCount = $(elThumImgDetailProduct).find('.img-thum-detail-product').length;

		// Nếu chỉ có 1 slide, không khởi tạo carousel
		if (mainSlideCount <= 1 && thumbSlideCount <= 1) {
			$(elImgDetailProduct).find('.img-detail-product').show();
			$(elThumImgDetailProduct).find('.img-thum-detail-product').show();
			return;
		}

		// Khởi tạo Slick cho ảnh chính
		$(elImgDetailProduct).slick({
			infinite: true,
			autoplay: true,
			autoplaySpeed: 3000,
			slidesToShow: 1,
			slidesToScroll: 1,
			arrows: false,
			dots: false,
			fade: true,
			asNavFor: '#block-thum-img-detail'
		});

		// Khởi tạo Slick cho thumbnail
		$(elThumImgDetailProduct).slick({
			slidesToShow: Math.min(3, thumbSlideCount), // Hiển thị tối đa 3 slide
			slidesToScroll: 1,
			arrows: true,
			dots: false,
			focusOnSelect: true,
			prevArrow: '<span class="icon-btn-pre"></span>',
			nextArrow: '<span class="icon-btn-next"></span>',
			asNavFor: '#block-img-product-detail',
			responsive: [
				{
					breakpoint: 768,
					settings: {
						slidesToShow: Math.min(2, thumbSlideCount) // 2 slide trên mobile
					}
				}
			]
		});

		// Đảm bảo đồng bộ khi scroll hoặc click
		$(elThumImgDetailProduct).on('afterChange', function (event, slick, currentSlide) {
			$(elImgDetailProduct).slick('slickGoTo', currentSlide);
		});

		$(elImgDetailProduct).on('afterChange', function (event, slick, currentSlide) {
			$(elThumImgDetailProduct).slick('slickGoTo', currentSlide);
		});
	};

	// Khởi tạo khi DOM sẵn sàng
	// $(document).ready(function () {
	// 	_productDetailImage();
	// });

	const _slideSubMenuProductMoblie = function () {
		if (_detectDevice()) {
			const elproductTypeItem = document.getElementById('box-type-product');

			if (!elproductTypeItem) { return; }

			$(elproductTypeItem).slick({
				infinite: true,
				autoplay: true,
				autoplaySpeed: 3000,
				slidesToShow: 2,
				slidesToScroll: 1,
				arrows: true,
				dots: false,
				prevArrow: '<span class="icon-btn-pre"></span>',
				nextArrow: '<span class="icon-btn-next"></span>',
			})
		}
	}
	// const _actionMenu = function (e) {
	// 	$('.block-sub-cate-parent >li >a').click(function (e) {
	// 		$(this).siblings('.block-sub-cate-child').slideToggle(800);
	// 		$(this).closest('li').siblings().find('ul').hide()
	// 		// $(this).siblings().removeClass('active')
	// 		// $(this).parent().addClass('active').siblings('.active').removeClass('active')
	// 		// console.log($(this).parent().hasClass('active'))
	// 		// setTimeout(function(){
	// 		// console.log($(this).parent().hasClass('active'))

	// 		if ($(this).parent().hasClass('active') === true) {
	// 			$(this).parent().removeClass('active')

	// 		} else {
	// 			$('.block-sub-cate-parent >li').removeClass('active')
	// 			$(this).parent().addClass('active')
	// 		}
	// 		// },1000)

	// 	})
	// }; //end_actionMenu
	const _actionMenu = function () {
		// // Điều chỉnh font-size cho các <a>
		//     $('.box-type-product > li > a').each(function () {
		//         const $link = $(this);
		//         const maxWidth = $link.parent().width(); // Chiều rộng của <li>
		//         let fontSize = 14; // Font-size khởi đầu

		//         // Tạo span tạm để đo chiều rộng chữ
		//         const $temp = $('<span>').text($link.text()).css({
		//             position: 'absolute',
		//             visibility: 'hidden',
		//             fontSize: fontSize + 'px',
		//             whiteSpace: 'nowrap',
		//             padding: '7px 15px', // Phù hợp với CSS của <a>
		//             boxSizing: 'border-box'
		//         });
		//         $('body').append($temp);
		//         let textWidth = $temp.width();
		//         $temp.remove();

		//         // Giảm font-size nếu chữ quá dài
		//         while (textWidth > maxWidth && fontSize > 12) {
		//             fontSize -= 1;
		//             $temp.css('fontSize', fontSize + 'px').text($link.text());
		//             $('body').append($temp);
		//             textWidth = $temp.width();
		//             $temp.remove();
		//         }

		//         // Áp dụng font-size
		//         $link.css('fontSize', fontSize + 'px');
		//     });
		$('.block-sub-cate-parent > li').on({
			mouseenter: function () {
				$(this).find('.block-sub-cate-child').stop().slideDown(400);
				$(this).siblings().find('.block-sub-cate-child').stop().slideUp(400);
				$('.block-sub-cate-parent > li').removeClass('active');
				$(this).addClass('active');
			},
			mouseleave: function () {
				$(this).find('.block-sub-cate-child').stop().slideUp(400);
				$(this).removeClass('active');
			}
		});
	}; // end_actionMenu
	const _actionCateMenu = function (e) {
		const eltopNavMenu = document.getElementById('top-cch-navmenu');

		if (!eltopNavMenu) { return; }
		$(eltopNavMenu).hover(function () {
			// $(this).css("background-color", "yellow");
			$('.block-opacity').addClass('block-opacity-active')
		}, function () {
			// $(this).css("background-color", "pink");
			$('.block-opacity').removeClass('block-opacity-active')

		});
	}
	const _detectDevice = function () {
		var isMobile = false; //initiate as false

		// device detection
		const is_firefox = navigator.userAgent.toLowerCase().indexOf('firefox') > -1,
			is_android = navigator.platform.toLowerCase().indexOf("android") > -1;
		if (/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|ipad|iris|kindle|Android|Silk|lge |maemo|midp|mmp|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows (ce|phone)|xda|xiino/i.test(navigator.userAgent) ||
			/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i.test(navigator.userAgent.substr(0, 4))) isMobile = true;
		else if (is_firefox && is_android) { isMobile = true; }

		return isMobile;
	}; //end_detectDevice 
	const _calcAndPrintPriceProduct = function (elCurrent) {
		const $elCartDetailInfoGroup = $($(elCurrent).parents('.cart-detail__info-group')[0]);
		const elCartDetailPriceCol = $elCartDetailInfoGroup.find('.cart-detail__price-col')[0];
		const elCartDetailFinalPriceCol = $elCartDetailInfoGroup.find('.cart-detail__final-price-col')[0];

		const intPriceValue = elCartDetailPriceCol.dataset.price;
		const intFinalPriceValue = elCartDetailFinalPriceCol.dataset.finalPrice;

		// $(elCartDetailPriceCol).find('.cart-detail__price-txt').html(_formatMoney(intPriceValue * Number(elCurrent.value)) + ' VNĐ');
		// $(elCartDetailFinalPriceCol).find('.cart-detail__final-price-txt').html(_formatMoney(intFinalPriceValue * Number(elCurrent.value)) + ' VNĐ');
	}
	const _showOrHideQuantityBlock = function () {
		const $objElQuantityInputOuter = $('.cart-detail__quantity-input-controls'),
			$objElQuantitySelectOuter = $('.cart-detail__quantity-select-controls');
		$objElQuantityInput = $('.-quantity-input');
		$objElQuantitySelect = $('.-quantity-select');

		if ($objElQuantityInputOuter.length <= 0 || $objElQuantitySelectOuter.length <= 0) { return; }

		var dataCurQuantity;
		function setIsShow() {
			if (_detectDevice()) {
				$objElQuantityInputOuter.removeClass('is-show');
				$objElQuantitySelectOuter.addClass('is-show');
				$objElQuantitySelect.each(function (key, $elQuantitySelect) {
					dataCurQuantity = parseInt($($elQuantitySelect).parents('.cart-detail__quanity-col')[0].dataset.curQuantity);
					$($elQuantitySelect).find('option').removeAttr('selected');
					$($elQuantitySelect[dataCurQuantity - 1]).prop('selected', 'selected');
				})
			} else {
				$objElQuantityInputOuter.addClass('is-show');
				$objElQuantitySelectOuter.removeClass('is-show');
				$objElQuantityInput.each(function (key, $elQuantityInput) {
					dataCurQuantity = $($elQuantityInput).parents('.cart-detail__quanity-col')[0].dataset.curQuantity;
					$elQuantityInput.value = dataCurQuantity;
				})
			}
		}

		setIsShow();

		$(window).on('resize', function () {
			setIsShow();
		});
	}; // end_showOrHideQuantityBlock
	const _validateCartDetailForm = function () {
		const objElQuantityInput = document.getElementsByClassName('-quantity-input');
		const objElQuantitySelect = document.getElementsByClassName('-quantity-select');

		const objElQuantityIncreaseBtn = document.getElementsByClassName('increase-btn');
		const objElQuantityDecreaseBtn = document.getElementsByClassName('decrease-btn');

		if (!objElQuantityInput ||
			!objElQuantitySelect) {
			return;
		}

		var quantityValue, quantityLimit, currentKeyValue, elCurrent, controlsOuter, elQuantityCol;
		var increaseInput, decreaseInput;

		const regexLetter = /^[a-zA-Z]+$/,
			regexSpecialChar = /[ !@#$%^&*()_+\-=\[\]{};':"\\|,.<>\/?]/,
			exceptKey = [13, 17, 35, 36, 46];

		$(objElQuantityInput).on('keydown', function (ev) {
			elCurrent = ev.target;
			controlsOuter = $(elCurrent).parents('.-quantity-select')[0];
			quantityLimit = elCurrent.dataset.limitQuantity;
			currentKeyValue = ev.keyCode;

			if (exceptKey.indexOf(currentKeyValue) != -1) {
				return;
			} else {
				elQuantityCol = $(elCurrent).parents('.cart-detail__quanity-col')[0];
				if (currentKeyValue == 8) {
					setTimeout(function () {
						if (elCurrent.value != '') {
							elQuantityCol.dataset.curQuantity = parseInt(elCurrent.value);
							_calcAndPrintPriceProduct(elCurrent);
						} else {
							elQuantityCol.dataset.curQuantity = 1;
							_calcAndPrintPriceProduct(elCurrent);
						}
					});
					return;
				}
				currentKeyValue = ev.key;
				if (regexLetter.test(currentKeyValue) || regexSpecialChar.test(quantityValue)) {
					ev.preventDefault();
					return;
				}

				setTimeout(function () {
					quantityValue = elCurrent.value;
					if (parseInt(quantityValue) > parseInt(quantityLimit)) {
						elCurrent.value = parseInt(quantityLimit);
					}

					elQuantityCol.dataset.curQuantity = parseInt(elCurrent.value);

					_calcAndPrintPriceProduct(elCurrent);
				});
			}
		});

		$(objElQuantitySelect).on('change', function (ev) {
			elCurrent = ev.target;

			$(elCurrent).parents('.cart-detail__quanity-col')[0].dataset.curQuantity = parseInt(elCurrent.value);

			_calcAndPrintPriceProduct(elCurrent);
		});

		$(objElQuantityIncreaseBtn).on('click', function (ev) {
			ev.preventDefault();
			elCurrent = ev.target;
			increaseInput = $(elCurrent).prev()[0];
			if (increaseInput.value == '') {
				increaseInput.value = 1;
			}
			if (parseInt(increaseInput.value) < parseInt(increaseInput.dataset.limitQuantity)) {
				increaseInput.value = parseInt(increaseInput.value) + 1;
				$(increaseInput).parents('.cart-detail__quanity-col')[0].dataset.curQuantity = increaseInput.value;
			}

			// _calcAndPrintPriceProduct(increaseInput); 
		});

		$(objElQuantityDecreaseBtn).on('click', function (ev) {
			ev.preventDefault();
			elCurrent = ev.target;
			decreaseInput = $(elCurrent).next()[0];
			if (decreaseInput.value == '') {
				decreaseInput.value = 1;
				return;
			}
			if (parseInt(decreaseInput.value) > 1) {
				decreaseInput.value = parseInt(decreaseInput.value) - 1;
				$(decreaseInput).parents('.cart-detail__quanity-col')[0].dataset.curQuantity = decreaseInput.value;
			}
		});
	}; // end_validateCartDetailForm
	const _modalShowPopup = function () {
		$('.dataModal').on('click', function (e) {
			const dataModal = $(this).attr('data-id');
			const idModalShow = document.getElementById(`${dataModal}`);
			$(idModalShow).addClass("block-modal-active")
			$('.block-opacity-modal').remove();
			$('.mrtung').append('<div class="block-opacity-modal block-opacity-modal-active" id="opacity"><div>');
			e.preventDefault();
		})
		$('.closepopup').on('click', function () {
			const idModalClose = $(this).parents('.block-modal-active').attr('id');
			const ModalisClose = document.getElementById(`${idModalClose}`);
			ModalisClose.classList.remove("block-modal-active");
			$('.block-opacity-modal').remove();
		});
	}
	const _checkActiveMenu = function () {
		const elblockBreadcrumbs = document.getElementById('block-breadcrumbs')
		if (!elblockBreadcrumbs) {
			return;
		} else {
			const elIdSubParent = $(elblockBreadcrumbs).find('#sub-parent').attr('data-id');
			const elIdSubChild = $(elblockBreadcrumbs).find('#sub-child').attr('data-id');
			const idMenuActiveShow = document.getElementById('menu-' + `${elIdSubParent}`);
			const idMenuChildActiveShow = document.getElementById('menu-child-' + `${elIdSubChild}`);
			console.log(idMenuChildActiveShow)
			$(idMenuActiveShow).addClass('active');
			$(idMenuChildActiveShow).addClass('active');
		}
	}
	$('.block-card-scroll').perfectScrollbar()
	const _showMenuMoblie = function () {
		$('.mobile-toggle').click(function () {
			$('.mobile-toggle .button_toggle .line2').toggleClass('line_hidden');
			$('.mobile-toggle .button_toggle .line:nth-child(1)').toggleClass('line-close-1');
			$('.mobile-toggle .button_toggle .line:nth-child(3)').toggleClass('line-close-3');
			$('.cch-navmenu').toggleClass('cch-navmenu-show');
			$('.block-cart-buy').removeClass('block-cart-buy-show-mb');
			$('.block-search').removeClass('block-search-show');
			if ($('.cch-navmenu').hasClass('cch-navmenu-show')) {
				$('html').css('overflow', 'hidden')
				$('.mrtung').append('<div class="block-opacity-modal block-opacity-modal-active" id="opacity"><div>');
				e.preventDefault();
			} else {
				$('.block-opacity-modal').remove();
				$('html').css('overflow-y', 'scroll')
			}

		});
	}
	const _showCardMoblie = function () {
		$('#block-card').click(function () {
			$('.block-cart-buy').toggleClass('block-cart-buy-show-mb');
			$('.block-search').removeClass('block-search-show');
		});
	}
	const _showSearchMoblie = function () {
		$('#search-moblie').click(function () {
			$('.block-search').toggleClass('block-search-show');
			$('.block-cart-buy').removeClass('block-cart-buy-show-mb');

		});
	}
	const _closeOpacity = function () {
		// $("body").delegate("#opacity", "click", function () {
		// 	$('.mobile-toggle .button_toggle .line2').removeClass('line_hidden');
		// 	$('.mobile-toggle .button_toggle .line:nth-child(1)').removeClass('line-close-1');
		// 	$('.mobile-toggle .button_toggle .line:nth-child(3)').removeClass('line-close-3');
		// 	$('.cch-navmenu').removeClass('cch-navmenu-show');
		// 	$('.block-opacity-modal').remove();
		// 	$('html').css('overflow-y', 'scroll')
		// 	$('.block-modal').removeClass('block-modal-active')
		// });
	}
	return {
		init: function () {
			_generateSlider();
			_producthotSlider();
			_actionMenu();
			_productDetailImage();
			_actionCateMenu();
			_newsNoibat();
			_showOrHideQuantityBlock();
			_validateCartDetailForm();
			_modalShowPopup();
			_showMenuMoblie();
			_showCardMoblie();
			_showSearchMoblie();
			_slideSubMenuProductMoblie();
			_generateFacebookFanpage();
			_checkActiveMenu();
			_closeOpacity();
		}
	};
})();

common.init();


document.getElementById("filter-icon").addEventListener("click", function () {
	var filterMenu = document.getElementById("filter-menu");
	filterMenu.style.display = filterMenu.style.display === "block" ? "none" : "block";
});

$(document).ready(function () {
	//Lấy tỉnh thành
	$.getJSON('https://esgoo.net/api-tinhthanh/1/0.htm', function (data_tinh) {
		if (data_tinh.error == 0) {
			$.each(data_tinh.data, function (key_tinh, val_tinh) {
				$("#city").append('<option value="' + val_tinh.id + '">' + val_tinh.full_name + '</option>');
				$("#city1").append('<option value="' + val_tinh.id + '">' + val_tinh.full_name + '</option>');
			});
			$("#city").change(function (e) {
				var idtinh = $(this).val();
				//Lấy quận huyện
				$.getJSON('https://esgoo.net/api-tinhthanh/2/' + idtinh + '.htm', function (data_quan) {
					if (data_quan.error == 0) {
						$("#district").html('<option value="0">Quận Huyện</option>');
						$("#ward").html('<option value="0">Phường Xã</option>');
						$.each(data_quan.data, function (key_quan, val_quan) {
							$("#district").append('<option value="' + val_quan.id + '">' + val_quan.full_name + '</option>');
						});
						//Lấy phường xã  
						$("#district").change(function (e) {
							var idquan = $(this).val();
							$.getJSON('https://esgoo.net/api-tinhthanh/3/' + idquan + '.htm', function (data_phuong) {
								if (data_phuong.error == 0) {
									$("#ward").html('<option value="0">Phường Xã</option>');
									$.each(data_phuong.data, function (key_phuong, val_phuong) {
										$("#ward").append('<option value="' + val_phuong.id + '">' + val_phuong.full_name + '</option>');

									});
								}
							});
						});

					}
				});
			});
			$("#city1").change(function (e) {
				var idtinh = $(this).val();
				//Lấy quận huyện
				$.getJSON('https://esgoo.net/api-tinhthanh/2/' + idtinh + '.htm', function (data_quan) {
					if (data_quan.error == 0) {
						$("#district1").html('<option value="0">Quận Huyện</option>');
						$("#ward1").html('<option value="0">Phường Xã</option>');
						$.each(data_quan.data, function (key_quan, val_quan) {
							$("#district1").append('<option value="' + val_quan.id + '">' + val_quan.full_name + '</option>');
						});
						//Lấy phường xã  
						$("#district1").change(function (e) {
							var idquan = $(this).val();
							$.getJSON('https://esgoo.net/api-tinhthanh/3/' + idquan + '.htm', function (data_phuong) {
								if (data_phuong.error == 0) {
									$("#ward1").html('<option value="0">Phường Xã</option>');
									$.each(data_phuong.data, function (key_phuong, val_phuong) {
										$("#ward1").append('<option value="' + val_phuong.id + '">' + val_phuong.full_name + '</option>');

									});
								}
							});
						});

					}
				});
			});
		}
	});
});

function updateInfo(event, element) {
	event.preventDefault();

	// Tìm phần tử cha có class "item-diachi"
	var addressItem = element.closest(".item-diachi");

	// Lấy thông tin địa chỉ từ phần tử cha
	var fullName = addressItem.querySelector("p").innerText;
	var phone = addressItem.querySelector("span").innerText.replace("| ", "").trim();
	var addressText = addressItem.querySelector("label").innerText;

	console.log("Dữ liệu:", fullName, phone, addressText);

	// Đổ dữ liệu vào form sửa
	document.getElementById("full-name1").value = fullName;
	document.getElementById("phone1").value = phone;
	document.getElementById("text1").value = addressText;

	// Hiển thị form sửa địa chỉ
	document.getElementById("edit-address").style.display = "block";
	console.log("Form sửa đã hiển thị!");
}



document.addEventListener("DOMContentLoaded", function () {
	const checkbox = document.getElementById("set-default");
	const radioButtons = document.querySelectorAll('input[name="shipping_address"]');

	function updateDefaultDisplay() {
		document.querySelectorAll(".macdinh").forEach(el => el.style.visibility = "hidden");

		const selectedRadio = document.querySelector('input[name="shipping_address"]:checked');

		if (selectedRadio) {
			const targetDiv = selectedRadio.closest(".item-diachi").querySelector(".macdinh");
			targetDiv.style.visibility = "visible";
		}
	}

	function setAsDefault() {
		if (checkbox.checked) {
			updateDefaultDisplay();
		}
	}

	// Khi chọn radio mới, "Mặc định" vẫn giữ nguyên trừ khi checkbox được check
	radioButtons.forEach(radio => {
		radio.addEventListener("change", function () {
			checkbox.checked = false; // Khi đổi radio, checkbox bỏ chọn
		});
	});

	checkbox.addEventListener("change", setAsDefault);

	updateDefaultDisplay();
});
document.addEventListener("DOMContentLoaded", function () {
	const radioButtons = document.querySelectorAll('input[name="shipping_address"]');

	function updateShippingInfo() {
		// Lấy radio được chọn
		const selectedRadio = document.querySelector('input[name="shipping_address"]:checked');
		if (selectedRadio) {
			// Lấy khối chứa địa chỉ (item-diachi)
			const container = selectedRadio.closest(".item-diachi");
			// Lấy thông tin từ các phần tử con
			const name = container.querySelector("p")?.innerText || "";
			let phone = container.querySelector("span")?.innerText || "";
			phone = phone.replace("| ", ""); // Loại bỏ ký tự "| " nếu có
			const address = container.querySelector("label")?.innerText || "";

			// Cập nhật thông tin vào phần "Thông tin nhận hàng"
			document.getElementById("receiver-name").innerText = name;
			document.getElementById("receiver-phone").innerText = phone;
			document.getElementById("receiver-address").innerText = address;

			// Kiểm tra xem có phần tử chứa chữ "Mặc định" không
			const defaultLabel = container.querySelector(".macdinh");

			if (defaultLabel) {
				console.log("Default label innerText:", defaultLabel.innerText);
				if (defaultLabel.innerText.toLowerCase().includes("mặc định")) {
					document.getElementById("default3").innerText = defaultLabel.innerText;
				} else {
					document.getElementById("default3").innerText = "";
				}
			} else {
				console.log("Không tìm thấy phần tử 'macdinh'");
				document.getElementById("default3").innerText = "";
			}
		}
	}

	// Lắng nghe sự thay đổi của các radio
	radioButtons.forEach(radio => {
		radio.addEventListener("change", updateShippingInfo);
	});

	// Cập nhật thông tin khi tải trang
	updateShippingInfo();
});



document.addEventListener("DOMContentLoaded", function () {
	const radioButtons = document.querySelectorAll('input[name="shipping_address"]');

	// Hàm cập nhật thông tin nhận hàng
	function updateInfo() {

		const selectedRadio = document.querySelector('input[name="shipping_address"]:checked');
		const addressLabel = selectedRadio.nextElementSibling.nextElementSibling;
		const nameLabel = selectedRadio.previousElementSibling.previousElementSibling;
		const phoneLabel = selectedRadio.previousElementSibling;

		// Cập nhật thông tin vào phần "Thông tin nhận hàng"
		document.getElementById('receiver-name').textContent = nameLabel.textContent.trim();
		document.getElementById('receiver-phone').textContent = phoneLabel.textContent.trim();
		document.getElementById('receiver-address').textContent = addressLabel.textContent.trim();
	}

	// Lắng nghe sự kiện thay đổi của các radio button
	radioButtons.forEach(radio => {
		radio.addEventListener("change", updateInfo);
	});

	// Cập nhật thông tin ngay khi tải trang (địa chỉ mặc định)
	updateInfo();
});

document.addEventListener("DOMContentLoaded", function () {
	const btnShow = document.getElementById("diff-addr");
	const container = document.getElementById("container-address");

	if (!btnShow || !container) return;

	btnShow.addEventListener("click", function () {
		container.style.display = "block";

		// Tìm nút đóng sau khi vùng chứa đã hiển thị
		const btnClose = container.querySelector(".close");
		if (btnClose) {
			btnClose.addEventListener("click", function () {
				container.style.display = "none";
			});
		}
	});
});
document.addEventListener("DOMContentLoaded", function () {
	const radioButtons = document.querySelectorAll('input[name="payment"]');
	const codButton = document.getElementById("button-cod");
	const bankButton = document.getElementById("button-bank");


	radioButtons.forEach((radio) => {
		radio.addEventListener("change", function () {
			if (this.value === "cod") {
				bankButton.style.display = "none";
				codButton.style.display = "block";

			} else if (this.value === "bank") {
				codButton.style.display = "none";
				bankButton.style.display = "block";
			}
		});
	});

});
document.addEventListener("DOMContentLoaded", function () {
	const editForm = document.getElementById("edit-address"); // Form Sửa
	const addForm = document.getElementById("add-address"); // Form Thêm
	const btnEditList = document.querySelectorAll(".sua"); // Các nút "Sửa"
	const btnAdd = document.getElementById("address-new"); // Nút "Thêm địa chỉ mới"
	const btnCancelEdit = document.getElementById("btn-huy1"); // Nút hủy của form sửa
	const btnCancelAdd = document.getElementById("btn-huy"); // Nút hủy của form thêm

	// Ẩn cả hai form khi tải trang
	editForm.style.display = "none";
	addForm.style.display = "none";
	btnEditList.forEach((btn) => {
		btn.addEventListener("click", function () {
			console.log("Nút Sửa được bấm!");

			const addressItem = this.closest(".item-diachi");
			if (addressItem) {
				const name = addressItem.querySelector("p").innerText.trim();
				const phone = addressItem.querySelector("span").innerText.replace("|", "").trim();
				const address = addressItem.querySelector("label").innerText.trim();

				console.log("Dữ liệu:", name, phone, address);

				// Gán dữ liệu vào form sửa
				document.getElementById("full-name1").value = name;
				document.getElementById("phone1").value = phone;

				// Cắt địa chỉ thành các phần: Phường, Quận, Thành phố
				const addressParts = address.split(", ").reverse(); // Đảo ngược thứ tự để dễ lấy
				const ward = addressParts[0] || ""; // Phường/Xã
				const district = addressParts[1] || ""; // Quận/Huyện
				const city = addressParts[2] || ""; // Thành phố/Tỉnh
				const detail = addressParts.slice(3).reverse().join(", ") || ""; // Địa chỉ cụ thể (số nhà, đường)

				document.getElementById("text1").value = detail; // Địa chỉ cụ thể

				// Hiển thị form sửa
				addForm.style.display = "none";
				editForm.style.display = "block";
				editForm.style.position = "relative";
				editForm.style.zIndex = "1";
				console.log("Form sửa đã hiển thị!");

				// Gán giá trị Thành phố/Tỉnh
				setTimeout(() => {
					document.getElementById("city1").value = city;
					loadDistricts(city, district); // Load danh sách quận dựa vào thành phố
					loadWards(district, ward); // Load danh sách phường dựa vào quận
				}, 300);
			} else {
				console.log("Không tìm thấy phần tử chứa địa chỉ!");
			}
		});
	});

	// Sự kiện mở form Thêm
	btnAdd.addEventListener("click", function (event) {
		event.preventDefault(); // Ngăn chặn reload trang
		editForm.style.display = "none"; // Ẩn form sửa nếu đang mở
		addForm.style.display = "block";
		addForm.style.position = "relative"; /* Giữ nguyên vị trí */
		addForm.style.z - index == "1";
	});

	// Sự kiện đóng form Sửa
	btnCancelEdit.addEventListener("click", function (event) {
		event.preventDefault();
		editForm.style.display = "none";
	});

	// Sự kiện đóng form Thêm
	btnCancelAdd.addEventListener("click", function (event) {
		event.preventDefault();
		addForm.style.display = "none";
	});

});

document.addEventListener("DOMContentLoaded", function () {
	const btnThanhToan = document.getElementById("button-bank");
	const bank = document.getElementById("infor-bank");
	const bankButton = document.getElementById("button-bank");
	const codButton = document.getElementById("button-cod");
	btnThanhToan.addEventListener("click", function (event) {
		event.preventDefault();
		bankButton.style.display = "none";
		codButton.style.display = "block";
		bank.style.display = "block";
		bank.style.position = "relative";
		bank.style.z - index == "1";

	});
});

document.addEventListener('DOMContentLoaded', function () {
	// Đặt lại trạng thái modal khi trang tải
	const modals = document.querySelectorAll('.modal');
	modals.forEach(modal => {
		modal.style.display = 'none'; // Đảm bảo modal ẩn khi trang tải
	});

	// Hàm mở modal
	window.openModal = function (modalId) {
		const modal = document.getElementById(modalId);
		if (modal) {
			modal.style.display = 'flex'; // Sử dụng flex để căn giữa
		} else {
			console.error(`Modal với ID ${modalId} không tồn tại`);
		}
	};

	// Hàm đóng modal
	window.closeModal = function (modalId) {
		const modal = document.getElementById(modalId);
		if (modal) {
			modal.style.display = 'none';
		} else {
			console.error(`Modal với ID ${modalId} không tồn tại`);
		}
	};
});
function showToast(message, type) {
	const toastContainer = document.getElementById('toast-container');
	if (!toastContainer) {
		console.error('Toast container not found!');
		return;
	}
	const toast = document.createElement('div');
	toast.className = `toast ${type}`;
	toast.textContent = message;
	toastContainer.appendChild(toast);

	// Hiển thị toast với animation
	setTimeout(() => {
		toast.classList.add('show');
	}, 100);


	setTimeout(() => {
		toast.classList.remove('show');
		setTimeout(() => {
			toast.remove();
		}, 300); // Chờ animation kết thúc trước khi xóa
	}, 2000);
}


