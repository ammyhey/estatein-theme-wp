document.addEventListener('DOMContentLoaded', function () {
	document.querySelectorAll('.hamburger').forEach(function (btn) {
		btn.addEventListener('click', function () {
			btn.classList.toggle('is-active');
		});
	});

	var bar = document.querySelector('.announcement-bar');
	if (bar && sessionStorage.getItem('estatein-announce-closed') === '1') {
		bar.remove();
	}
	document.querySelectorAll('.announcement-close').forEach(function (btn) {
		btn.addEventListener('click', function () {
			var wrap = btn.closest('.announcement-bar');
			if (wrap) {
				wrap.remove();
				sessionStorage.setItem('estatein-announce-closed', '1');
			}
		});
	});

	initOrbitParallax();
	initEstateinSliders();
});

function pad2(n) {
	return String(n).padStart(2, '0');
}

function initEstateinSliders() {
	if (typeof window.jQuery === 'undefined' || !window.jQuery.fn.slick) {
		return;
	}
	var $ = window.jQuery;

	$('[data-estatein-slider]').each(function () {
		var $root = $(this);
		var $track = $root.find('.estatein-slider-track');
		if (!$track.length || $track.hasClass('slick-initialized')) {
			return;
		}

		var desktopSlides = parseInt($root.attr('data-slides'), 10) || 3;
		var $counter = $root.find('.estatein-slider-counter');
		var $current = $counter.find('.current');
		var $total = $counter.find('.total');
		var $prev = $root.find('.estatein-slider-prev');
		var $next = $root.find('.estatein-slider-next');

		function updateCounter(slick, index) {
			var slidesToShow = (slick && slick.options && slick.options.slidesToShow) ? slick.options.slidesToShow : desktopSlides;
			var total = slick.slideCount || $track.children().length;
			var page = Math.floor(index / slidesToShow) + 1;
			var pages = Math.max(1, Math.ceil(total / slidesToShow));
			// Figma-style: show slide position like 01 of 60 (item index based)
			var displayIndex = Math.min(index + 1, total);
			$current.text(pad2(displayIndex));
			$total.text(pad2(total));
			$prev.prop('disabled', index <= 0 && !slick.options.infinite);
			$next.prop('disabled', index >= total - slidesToShow && !slick.options.infinite);
		}

		$track.on('init reInit afterChange', function (event, slick, currentSlide) {
			var idx = typeof currentSlide === 'number' ? currentSlide : 0;
			updateCounter(slick, idx);
		});

		$track.slick({
			slidesToShow: desktopSlides,
			slidesToScroll: 1,
			infinite: false,
			arrows: false,
			dots: false,
			speed: 450,
			cssEase: 'cubic-bezier(0.25, 0.1, 0.25, 1)',
			adaptiveHeight: false,
			responsive: [
				{ breakpoint: 1200, settings: { slidesToShow: Math.min(2, desktopSlides) } },
				{ breakpoint: 768, settings: { slidesToShow: 1 } }
			]
		});

		$prev.on('click', function () {
			$track.slick('slickPrev');
		});
		$next.on('click', function () {
			$track.slick('slickNext');
		});
	});
}

function initOrbitParallax() {
	var orbit = document.querySelector('[data-parallax="orbit"]');
	if (!orbit || window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
		return;
	}

	var ticking = false;
	var targetX = 0;
	var targetY = 0;
	var currentX = 0;
	var currentY = 0;
	var scrollY = 0;

	function applyTransform() {
		currentX += (targetX - currentX) * 0.08;
		currentY += (targetY - currentY) * 0.08;
		var y = currentY + scrollY;
		orbit.style.transform = 'translate3d(' + currentX.toFixed(2) + 'px, ' + y.toFixed(2) + 'px, 0)';
		if (Math.abs(targetX - currentX) > 0.1 || Math.abs(targetY - currentY) > 0.1) {
			requestAnimationFrame(applyTransform);
		} else {
			ticking = false;
		}
	}

	function requestTick() {
		if (!ticking) {
			ticking = true;
			requestAnimationFrame(applyTransform);
		}
	}

	window.addEventListener('scroll', function () {
		scrollY = window.scrollY * -0.12;
		requestTick();
	}, { passive: true });

	var hero = document.querySelector('.hero-image-wrap') || document.querySelector('.hero-section');
	if (hero) {
		hero.addEventListener('mousemove', function (e) {
			var rect = hero.getBoundingClientRect();
			var mx = (e.clientX - rect.left) / rect.width - 0.5;
			var my = (e.clientY - rect.top) / rect.height - 0.5;
			targetX = mx * 28;
			targetY = my * 18;
			requestTick();
		});
		hero.addEventListener('mouseleave', function () {
			targetX = 0;
			targetY = 0;
			requestTick();
		});
	}
}
