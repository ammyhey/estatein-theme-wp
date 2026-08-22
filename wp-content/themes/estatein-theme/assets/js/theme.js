document.addEventListener('DOMContentLoaded', function () {
	initMobileNav();
	initAnnouncementBar();
	initPlaceholderLinks();
	initHeroParallax();
	initEstateinSliders();
	initSectionFade();
	initCtaParallax();
	initNewsletterIcons();
	initNewsletterA11y();
});

function initMobileNav() {
	document.querySelectorAll('.hamburger[data-bs-toggle="collapse"]').forEach(function (btn) {
		var selector = btn.getAttribute('data-bs-target');
		var target = selector ? document.querySelector(selector) : null;
		if (!target) {
			return;
		}
		var openLabel = btn.getAttribute('aria-label') || 'Toggle navigation';
		var closeLabel = btn.getAttribute('data-close-label') || 'Close navigation';
		target.addEventListener('shown.bs.collapse', function () {
			btn.classList.add('is-active');
			btn.setAttribute('aria-expanded', 'true');
			btn.setAttribute('aria-label', closeLabel);
		});
		target.addEventListener('hidden.bs.collapse', function () {
			btn.classList.remove('is-active');
			btn.setAttribute('aria-expanded', 'false');
			btn.setAttribute('aria-label', openLabel);
		});
	});
}

function initAnnouncementBar() {
	try {
		sessionStorage.removeItem('estatein-announce-closed');
	} catch (e) {}
	document.querySelectorAll('.announcement-close').forEach(function (btn) {
		btn.addEventListener('click', function () {
			var wrap = btn.closest('.announcement-bar');
			if (wrap) {
				wrap.remove();
				var brand = document.querySelector('.navbar-brand');
				if (brand) {
					brand.focus();
				}
			}
		});
	});
}

function initPlaceholderLinks() {
	document.addEventListener('click', function (event) {
		var link = event.target.closest('a[href="#"]');
		if (link) {
			event.preventDefault();
		}
	});
}

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
			accessibility: true,
			speed: window.matchMedia('(prefers-reduced-motion: reduce)').matches ? 0 : 450,
			cssEase: 'cubic-bezier(0.25, 0.1, 0.25, 1)',
			adaptiveHeight: false,
			responsive: [
				{ breakpoint: 576, settings: { slidesToShow: 1 } },
				{ breakpoint: 992, settings: { slidesToShow: 2 } }
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

function initHeroParallax() {
	if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
		return;
	}

	var wrap = document.querySelector('.hero-image-wrap');
	if (!wrap) {
		return;
	}

	var image = wrap.querySelector('.hero-image-clip .hero-image, .hero-image-clip .hero-placeholder');
	var orbit = wrap.querySelector('[data-parallax="orbit"]');
	if (!image && !orbit) {
		return;
	}

	var mouseX = 0;
	var mouseY = 0;
	var imgX = 0;
	var imgY = 0;
	var orbX = 0;
	var orbY = 0;
	var scrollImg = 0;
	var scrollOrb = 0;
	var ticking = false;
	var imageScale = 1.12;

	function applyTransform() {
		imgX += (mouseX * 22 - imgX) * 0.08;
		imgY += (mouseY * 16 - imgY) * 0.08;
		orbX += (-mouseX * 28 - orbX) * 0.08;
		orbY += (-mouseY * 20 - orbY) * 0.08;

		if (image) {
			image.style.transform = 'translate3d(' + imgX.toFixed(2) + 'px, ' + (imgY + scrollImg).toFixed(2) + 'px, 0) scale(' + imageScale + ')';
		}
		if (orbit) {
			orbit.style.transform = 'translate3d(' + orbX.toFixed(2) + 'px, ' + (orbY + scrollOrb).toFixed(2) + 'px, 0)';
		}

		var stillMoving =
			Math.abs(mouseX * 22 - imgX) > 0.1 ||
			Math.abs(mouseY * 16 - imgY) > 0.1 ||
			Math.abs(-mouseX * 28 - orbX) > 0.1 ||
			Math.abs(-mouseY * 20 - orbY) > 0.1;

		if (stillMoving) {
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
		scrollImg = window.scrollY * 0.12;
		scrollOrb = window.scrollY * -0.12;
		requestTick();
	}, { passive: true });

	wrap.addEventListener('mousemove', function (e) {
		var rect = wrap.getBoundingClientRect();
		mouseX = (e.clientX - rect.left) / rect.width - 0.5;
		mouseY = (e.clientY - rect.top) / rect.height - 0.5;
		requestTick();
	});
	wrap.addEventListener('mouseleave', function () {
		mouseX = 0;
		mouseY = 0;
		requestTick();
	});
}

function revealCardsIn(root) {
	if (!root) {
		return;
	}
	var cards = root.querySelectorAll('.slick-slide:not(.slick-cloned) .card-reveal');
	if (!cards.length) {
		cards = root.querySelectorAll('.card-reveal');
	}
	cards.forEach(function (card, i) {
		card.style.setProperty('--card-delay', (i * 0.1) + 's');
		window.setTimeout(function () {
			card.classList.add('is-visible');
		}, 20);
	});
}

function initSectionFade() {
	var els = document.querySelectorAll('.content-section, .footer-cta, .hero-feature-grid');
	if (!els.length) {
		return;
	}
	if (window.matchMedia('(prefers-reduced-motion: reduce)').matches || !('IntersectionObserver' in window)) {
		els.forEach(function (el) {
			el.classList.add('is-inview');
			revealCardsIn(el);
		});
		return;
	}
	var io = new IntersectionObserver(function (entries) {
		entries.forEach(function (entry) {
			if (entry.isIntersecting) {
				entry.target.classList.add('is-inview');
				revealCardsIn(entry.target);
				io.unobserve(entry.target);
			}
		});
	}, { threshold: 0.12, rootMargin: '0px 0px -8% 0px' });
	els.forEach(function (el) {
		io.observe(el);
	});
}

function initCtaParallax() {
	if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
		return;
	}
	var cta = document.querySelector('.footer-cta');
	if (!cta) {
		return;
	}
	var layers = cta.querySelectorAll('[data-parallax="cta"]');
	if (!layers.length) {
		return;
	}

	var mouseX = 0;
	var mouseY = 0;
	var current = [];
	layers.forEach(function () {
		current.push({ x: 0, y: 0 });
	});
	var ticking = false;

	function layerTransform(el, x, y) {
		if (el.classList.contains('footer-cta-deco--right')) {
			return 'translate3d(' + x.toFixed(2) + 'px, ' + y.toFixed(2) + 'px, 0) rotate(180deg) scaleY(-1)';
		}
		return 'translate3d(' + x.toFixed(2) + 'px, ' + y.toFixed(2) + 'px, 0)';
	}

	function applyTransform() {
		var rect = cta.getBoundingClientRect();
		var scrollShift = (window.innerHeight / 2 - (rect.top + rect.height / 2)) * 0.18;
		var stillMoving = false;

		layers.forEach(function (el, i) {
			var dir = parseFloat(el.getAttribute('data-parallax-dir')) || 1;
			var targetX = mouseX * 28 * dir;
			var targetY = mouseY * 18 * dir + scrollShift * dir;
			current[i].x += (targetX - current[i].x) * 0.08;
			current[i].y += (targetY - current[i].y) * 0.08;
			el.style.transform = layerTransform(el, current[i].x, current[i].y);
			if (Math.abs(targetX - current[i].x) > 0.1 || Math.abs(targetY - current[i].y) > 0.1) {
				stillMoving = true;
			}
		});

		if (stillMoving) {
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

	window.addEventListener('scroll', requestTick, { passive: true });
	cta.addEventListener('mousemove', function (e) {
		var rect = cta.getBoundingClientRect();
		mouseX = (e.clientX - rect.left) / rect.width - 0.5;
		mouseY = (e.clientY - rect.top) / rect.height - 0.5;
		requestTick();
	});
	cta.addEventListener('mouseleave', function () {
		mouseX = 0;
		mouseY = 0;
		requestTick();
	});
}

function initNewsletterIcons() {
	document.querySelectorAll('.newsletter-form').forEach(function (wrap) {
		if (wrap.dataset.iconsBound === '1') {
			return;
		}
		var emailSrc = wrap.getAttribute('data-icon-email');
		var sendSrc = wrap.getAttribute('data-icon-send');
		var place = function () {
			var emailEl = wrap.querySelector('.email-container .nf-field-element');
			var submitEl = wrap.querySelector('.submit-container .nf-field-element');
			if (!emailEl || !submitEl || !emailSrc || !sendSrc) {
				return false;
			}
			if (!emailEl.querySelector('.newsletter-icon-email')) {
				var mail = document.createElement('img');
				mail.className = 'newsletter-icon newsletter-icon-email';
				mail.src = emailSrc;
				mail.alt = '';
				mail.setAttribute('aria-hidden', 'true');
				mail.width = 24;
				mail.height = 24;
				emailEl.appendChild(mail);
			}
			if (!submitEl.querySelector('.newsletter-icon-send')) {
				var send = document.createElement('img');
				send.className = 'newsletter-icon newsletter-icon-send';
				send.src = sendSrc;
				send.alt = '';
				send.setAttribute('aria-hidden', 'true');
				send.width = 24;
				send.height = 24;
				submitEl.appendChild(send);
			}
			wrap.dataset.iconsBound = '1';
			return true;
		};
		if (place()) {
			return;
		}
		var mo = new MutationObserver(function () {
			if (place()) {
				mo.disconnect();
			}
		});
		mo.observe(wrap, { childList: true, subtree: true });
	});
}

function initNewsletterA11y() {
	document.querySelectorAll('.newsletter-form').forEach(function (wrap) {
		var apply = function () {
			var form = wrap.querySelector('form');
			if (form && !form.getAttribute('aria-label')) {
				form.setAttribute('aria-label', 'Newsletter');
			}
			var input = wrap.querySelector('input[type="email"]');
			if (input) {
				if (!input.id) {
					input.id = 'estatein-newsletter-email';
				}
				if (!input.getAttribute('aria-label') && !document.querySelector('label[for="' + input.id + '"]')) {
					input.setAttribute('aria-label', 'Email');
				}
				input.setAttribute('autocomplete', 'email');
				input.setAttribute('aria-required', 'true');
			}
			var submit = wrap.querySelector('input[type="submit"], button[type="submit"]');
			if (submit && !submit.getAttribute('aria-label')) {
				var submitName = (submit.value || submit.textContent || '').trim();
				submit.setAttribute('aria-label', submitName || 'Subscribe');
			}
			wrap.querySelectorAll('.newsletter-icon').forEach(function (img) {
				img.alt = '';
				img.setAttribute('aria-hidden', 'true');
			});
			if (input) {
				var err = wrap.querySelector('.nf-error-msg');
				if (err) {
					if (!err.id) {
						err.id = input.id + '-error';
					}
					err.setAttribute('role', 'alert');
					input.setAttribute('aria-invalid', 'true');
					input.setAttribute('aria-describedby', err.id);
				} else {
					input.removeAttribute('aria-invalid');
					if (input.getAttribute('aria-describedby') && input.getAttribute('aria-describedby').indexOf('-error') !== -1) {
						input.removeAttribute('aria-describedby');
					}
				}
			}
			return !!(input && submit);
		};
		if (apply()) {
			var mo = new MutationObserver(apply);
			mo.observe(wrap, { childList: true, subtree: true });
			return;
		}
		var wait = new MutationObserver(function () {
			if (apply()) {
				wait.disconnect();
				var mo = new MutationObserver(apply);
				mo.observe(wrap, { childList: true, subtree: true });
			}
		});
		wait.observe(wrap, { childList: true, subtree: true });
	});
}
