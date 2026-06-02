<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="IE=edge">
    <title>@yield('title', 'Goal Shot Ball Association of Bihar — Official')</title>
    <meta name="description" content="@yield('meta_description', 'Goal Shot Ball Association of Bihar. Affiliated with Goal Shot Ball Association of India and recognized by Asian and International Federations.')">
    <meta name="keywords" content="Goal Shot Ball, Bihar sports, GSB association, sports federation, India sports">
    <meta name="robots" content="index, follow">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#F59E0B">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="author" content="Goal Shot Ball Association of Bihar">

    {{-- Canonical --}}
    <link rel="canonical" href="@yield('canonical', url()->current())">

    {{-- Open Graph (Facebook, WhatsApp, LinkedIn) --}}
    <meta property="og:type" content="@yield('og_type', 'website')">
    <meta property="og:site_name" content="Goal Shot Ball Association of Bihar">
    <meta property="og:title" content="@yield('og_title', View::yieldContent('title', 'Goal Shot Ball Association of Bihar — Official'))">
    <meta property="og:description" content="@yield('og_description', View::yieldContent('meta_description', 'Goal Shot Ball Association of Bihar. Affiliated with Goal Shot Ball Association of India and recognized by Asian and International Federations.'))">
    <meta property="og:url" content="@yield('canonical', url()->current())">
    <meta property="og:image" content="@yield('og_image', asset('assets/img/og-cover.jpg'))">
    <meta property="og:image:width" content="1200">
    <meta property="og:image:height" content="630">
    <meta property="og:locale" content="en_IN">

    {{-- Twitter Card --}}
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="@yield('og_title', View::yieldContent('title', 'Goal Shot Ball Association of Bihar — Official'))">
    <meta name="twitter:description" content="@yield('og_description', View::yieldContent('meta_description', 'Goal Shot Ball Association of Bihar. Affiliated with Goal Shot Ball Association of India and recognized by Asian and International Federations.'))">
    <meta name="twitter:image" content="@yield('og_image', asset('assets/img/og-cover.jpg'))">

    <link rel="icon" href="{{ asset('assets/img/logo.png') }}" type="image/png">
    <link rel="apple-touch-icon" href="{{ asset('assets/img/logo.png') }}">

    {{-- Structured data: helps Google show a rich org panel --}}
    <script type="application/ld+json">
  {
    "@@context": "https://schema.org",
    "@@type": "SportsOrganization",
    "name": "Goal Shot Ball Association of Bihar",
    "alternateName": "GSBAB",
    "url": "{{ url('/') }}",
    "logo": "{{ asset('assets/img/logo.png') }}",
    "description": "Goal Shot Ball Association of Bihar. Affiliated with Goal Shot Ball Association of India and recognized by Asian and International Federations.",
    "sport": "Goal Shot Ball",
    "address": {
      "@@type": "PostalAddress",
      "streetAddress": "Kamruddinpur, Ward No-5",
      "addressLocality": "Begusarai",
      "addressRegion": "Bihar",
      "addressCountry": "IN"
    },
    "email": "bihargoalshotball@gmail.com",
    "telephone": "+91-8083319186",
    "contactPoint": [{
      "@@type": "ContactPoint",
      "telephone": "+91-8083319186",
      "contactType": "customer service",
      "areaServed": "IN",
      "availableLanguage": ["en", "hi"]
    }]
  }
  </script>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Barlow+Condensed:wght@400;500;600;700;800;900&family=Barlow:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">

    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/fontawesome.min.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.carousel.min.css">
    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.theme.default.min.css">
    <link rel="stylesheet" href="{{ asset('assets/css/magnific-popup.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/modern.css') }}">
    @livewireStyles
    @stack('styles')
</head>

<body>

    <!-- Scroll progress -->
    <div class="scroll-progress" aria-hidden="true"></div>

    @include('partials.header')

    @yield('content')

    @include('partials.footer')

    <!-- ==================== SCRIPTS ==================== -->
    <script src="{{ asset('assets/js/vendor/jquery-3.7.1.min.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.min.js"></script>
    <script src="{{ asset('assets/js/jquery.magnific-popup.min.js') }}"></script>
    <script>
        jQuery(function($) {
            $('.press-grid').magnificPopup({
                delegate: 'a.press-clip',
                type: 'image',
                gallery: {
                    enabled: true,
                    navigateByImgClick: true,
                    preload: [0, 2]
                },
                mainClass: 'mfp-with-zoom',
                zoom: {
                    enabled: true,
                    duration: 300,
                    opener: function(el) {
                        return el.find('img');
                    }
                },
                image: {
                    titleSrc: function(item) {
                        return item.el.attr('aria-label') || '';
                    }
                }
            });

            $('.players-split').magnificPopup({
                delegate: 'a.player-zoom',
                type: 'image',
                gallery: {
                    enabled: true,
                    navigateByImgClick: true,
                    preload: [0, 2]
                },
                mainClass: 'mfp-with-zoom',
                zoom: {
                    enabled: true,
                    duration: 300,
                    opener: function(el) {
                        return el.find('img');
                    }
                },
                image: {
                    titleSrc: function(item) {
                        return item.el.attr('aria-label') || '';
                    }
                }
            });

            $('[data-press-toggle]').on('click', function() {
                var $btn = $(this);
                var expanded = $btn.attr('aria-expanded') === 'true';
                $btn.attr('aria-expanded', String(!expanded));
                $('.press-grid').toggleClass('show-all', !expanded);
                $btn.find('.press-more-label').text(expanded ? 'View More' : 'View Less');
                if (expanded) {
                    $('html, body').animate({
                        scrollTop: $('#press').offset().top - 80
                    }, 350);
                }
            });

            // Contact modal
            (function() {
                var modal = document.querySelector('[data-contact-modal]');
                if (!modal) return;
                var dialog = modal.querySelector('.contact-modal__dialog');
                var form = modal.querySelector('[data-contact-form]');
                var success = modal.querySelector('[data-contact-success]');
                var lastFocused = null;

                function open(e) {
                    if (e) e.preventDefault();
                    lastFocused = document.activeElement;
                    modal.classList.add('is-open');
                    modal.setAttribute('aria-hidden', 'false');
                    document.body.style.overflow = 'hidden';
                    var first = dialog.querySelector('input, textarea, button');
                    if (first) setTimeout(function() {
                        first.focus();
                    }, 120);
                }

                function close() {
                    modal.classList.remove('is-open');
                    modal.setAttribute('aria-hidden', 'true');
                    document.body.style.overflow = '';
                    if (lastFocused) lastFocused.focus();
                }

                document.querySelectorAll('[data-contact-open]').forEach(function(el) {
                    el.addEventListener('click', open);
                });
                modal.querySelectorAll('[data-contact-close]').forEach(function(el) {
                    el.addEventListener('click', close);
                });
                document.addEventListener('keydown', function(e) {
                    if (e.key === 'Escape' && modal.classList.contains('is-open')) close();
                });

                form.addEventListener('submit', function(e) {
                    e.preventDefault();
                    if (!form.checkValidity()) {
                        form.reportValidity();
                        return;
                    }
                    success.hidden = false;
                    form.reset();
                    setTimeout(function() {
                        success.hidden = true;
                        close();
                    }, 1800);
                });
            })();

            $('[data-testi-carousel]').owlCarousel({
                loop: true,
                margin: 24,
                nav: true,
                dots: true,
                autoplay: true,
                autoplayHoverPause: true,
                autoplayTimeout: 5000,
                navText: ['<i class="fas fa-arrow-left"></i>', '<i class="fas fa-arrow-right"></i>'],
                responsive: {
                    0: {
                        items: 1
                    },
                    768: {
                        items: 2
                    },
                    1024: {
                        items: 3
                    }
                }
            });
        });
    </script>
    <script src="{{ asset('assets/js/modern.js') }}" defer></script>
    @livewireScripts
    @stack('scripts')
</body>

</html>
