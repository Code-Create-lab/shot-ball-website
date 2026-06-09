@extends('layouts.app')

@section('content')

  <!-- ==================== HERO ==================== -->
  <section class="gsb-hero" id="home">
    <div class="hero-blob b1"></div>
    <div class="hero-blob b2"></div>
    <div class="gsb-hero-grid"></div>

    <div class="gsb-hero-inner">
      <div class="hero-content">
        <h1>
          <span class="word"><span>Where</span></span>
          <span class="word"><span>Champions</span></span>
          <br>
          <span class="word"><span class="highlight">Take</span></span>
          <span class="word"><span class="highlight">The</span></span>
          <span class="word"><span class="highlight">Shot.</span></span>
        </h1>

        <p class="lead">
          We promote the fast-paced sport of Goal Shot Ball across Bihar — uniting young athletes,
          building skill, and competing at national and international levels.
        </p>

        <div class="gsb-hero-actions">
          <a href="#register" class="btn-magnetic">
            Join the Team
            <span class="arrow"><i class="far fa-arrow-right"></i></span>
          </a>
        </div>

        <a class="hero-news" href="{{ asset('assets/img/game_4thSenior_game.jpeg') }}" target="_blank" rel="noopener"
          aria-label="Latest news: 4th Senior Men's and Women's Goal Shot Ball Bihar State Championship 2026 — view poster">
          <span class="hero-news-thumb">
            <img src="{{ asset('assets/img/game_4thSenior_game.jpeg') }}"
              alt="4th Senior Men's and Women's Goal Shot Ball Bihar State Championship 2026 poster" loading="lazy"
              width="160" height="90">
          </span>
          <span class="hero-news-body">
            <span class="hero-news-badge"><span class="hero-news-dot"></span> Latest News</span>
            <span class="hero-news-title">4th Senior Men's &amp; Women's Goal Shot Ball — Bihar State Championship
              2026</span>
            <span class="hero-news-meta">
              <span><i class="fas fa-calendar-day" aria-hidden="true"></i> 7–9 June 2026</span>
              <span><i class="fas fa-location-dot" aria-hidden="true"></i> Begusarai, Bihar</span>
            </span>
          </span>
          <span class="hero-news-arrow"><i class="fas fa-arrow-right" aria-hidden="true"></i></span>
        </a>
      </div>

      <div class="gsb-hero-visual">
        <div class="gsb-hero-card" data-tilt>
          <div class="gsb-slider" data-slider aria-roledescription="carousel" aria-label="Goal Shot Ball highlights">
            <div class="gsb-slider-track" data-slider-track>

              @for ($i = 1; $i <= 31; $i++)
              <div class="gsb-slide" role="group" aria-roledescription="slide" aria-label="{{ $i }} of 31">
                <img src="{{ asset('assets/img/sliders/slider' . $i . '.jpeg') }}" alt="Goal Shot Ball highlight {{ $i }}"
                  loading="{{ $i === 1 ? 'eager' : 'lazy' }}" width="460" height="575">
                <div class="tag">
                  <div class="tag-text">
                    Goal Shot Ball
                    <span>Highlights</span>
                  </div>
                  <div class="tag-icon"><i class="fas fa-futbol"></i></div>
                </div>
              </div>
              @endfor

            </div>

            <button class="gsb-slider-arrow prev" type="button" data-slider-prev aria-label="Previous slide">
              <i class="fas fa-chevron-left" aria-hidden="true"></i>
            </button>
            <button class="gsb-slider-arrow next" type="button" data-slider-next aria-label="Next slide">
              <i class="fas fa-chevron-right" aria-hidden="true"></i>
            </button>

            <div class="gsb-slider-dots" data-slider-dots role="tablist" aria-label="Choose slide"></div>
          </div>
        </div>
      </div>
    </div>

    <div class="scroll-cue">
      <span>Scroll</span>
      <span class="line"></span>
    </div>
  </section>

  <!-- ==================== MARQUEE ==================== -->
  <section class="gsb-marquee" aria-hidden="true">
    <div class="marquee-track">
      @for ($i = 0; $i < 7; $i++)
      <img class="marquee-logo" src="{{ asset('assets/img/logo.png') }}" alt="" aria-hidden="true">
      <span>Goal Shot Ball Association</span>
      @endfor
    </div>
  </section>

  <!-- ==================== MEMBERS ==================== -->
  <section class="gsb-section" id="members">
    <div class="gsb-container">
      <div class="section-intro">
        <span class="section-label center">Members</span>
        <h2 class="section-title">Meet Our <span class="accent">Members</span></h2>
        <p>The faces behind the federation — coaches, athletes, and the team that drives Bihar's Goal Shot Ball forward.
        </p>
      </div>

      <div class="members-slider">
        <button class="members-arrow prev" type="button" data-members-prev aria-label="Previous members">
          <i class="fas fa-chevron-left" aria-hidden="true"></i>
        </button>

        <div class="members-track" data-stagger data-members-track>
          @forelse (($members ?? collect()) as $member)
          <article class="member-card">
            <div class="member-img">
              <img src="{{ asset('storage/' . $member->image_path) }}"
                alt="{{ $member->name }}, {{ strip_tags($member->role) }}">
            </div>
            <div class="member-info">
              <h3>{{ $member->name }}</h3>
              <span class="role">{!! nl2br(e($member->role)) !!}</span>
            </div>
          </article>
          @empty
          {{-- No members in DB yet — show the original static roster as fallback. --}}
          {{-- <article class="member-card">
            <div class="member-img">
              <img src="{{ asset('assets/img/members/navin_ GSBAB.jpeg') }}"
                alt="Navin Kumar Singh, Chief Patron">
            </div>
            <div class="member-info">
              <h3>Navin Kumar Singh</h3>
              <span class="role">Chief Patron, GSBAB</span>
            </div>
          </article> --}}

          {{--
          <article class="member-card">
            <div class="member-img">
              <img src="{{ asset('assets/img/members/Sumit Kumar_Chairman GSBAB.jpeg') }}" alt="Sumit Kumar, Chairman">
            </div>
            <div class="member-info">
              <h3>Sumit Kumar</h3>
              <span class="role">Chairman, GSBAB</span>
            </div>
          </article>
          --}}

          <article class="member-card">
            <div class="member-img">
              <img src="{{ asset('assets/img/members/Durgesh Nandan.jpeg') }}" alt="Durgesh Nandan, Vice President">
            </div>
            <div class="member-info">
              <h3>Durgesh Nandan</h3>
              <span class="role">Assistant Commissioner of state taxes<br>President GSBAB</span>
            </div>
          </article>

          <article class="member-card">
            <div class="member-img">
              <img src="{{ asset('assets/img/members/Ram Pravesh Kumar _Secretary General GSBAB.jpeg') }}"
                alt="Ram Pravesh Kumar, Secretary General">
            </div>
            <div class="member-info">
              <h3>Ram Pravesh Kumar</h3>
              <span class="role">Secretary General, GSBAB</span>
            </div>
          </article>

          <article class="member-card">
            <div class="member-img">
              <img src="{{ asset('assets/img/members/AMIT KUMAR VERMA_Senior Vice President GSBAB.jpeg') }}"
                alt="Amit Kumar Verma, Senior Vice President">
            </div>
            <div class="member-info">
              <h3>Amit Kumar Verma</h3>
              <span class="role">Senior Vice President, GSBAB</span>
            </div>
          </article>

          <article class="member-card">
            <div class="member-img">
              <img src="{{ asset('assets/img/members/Gaurav Kuma_Treasure GSBAB.jpeg') }}" alt="Gaurav Kumar, Treasurer">
            </div>
            <div class="member-info">
              <h3>Gaurav Kumar</h3>
              <span class="role">Treasurer, GSBAB</span>
            </div>
          </article>

          <article class="member-card">
            <div class="member-img">
              <img src="{{ asset('assets/img/members/Rakesh_ranjan_joint_secretary GSBAB.jpeg') }}"
                alt="Rakesh Ranjan, Joint Secretary">
            </div>
            <div class="member-info">
              <h3>Rakesh Ranjan</h3>
              <span class="role">Joint Secretary, GSBAB</span>
            </div>
          </article>

          <article class="member-card">
            <div class="member-img">
              <img src="{{ asset('assets/img/members/VIKKI KUMAR JOINT SECRETARY GSBAB.jpeg') }}"
                alt="Vikki Kumar, Joint Secretary">
            </div>
            <div class="member-info">
              <h3>Vikki Kumar</h3>
              <span class="role">Joint Secretary, GSBAB</span>
            </div>
          </article>

          <article class="member-card">
            <div class="member-img">
              <img src="{{ asset('assets/img/members/rituKumari.jpeg') }}"
                alt="Ritu Kumari, Vice President">
            </div>
            <div class="member-info">
              <h3>Ritu Kumari</h3>
              <span class="role">Vice President, GSBAB</span>
            </div>
          </article>
          @endforelse
        </div>

        <button class="members-arrow next" type="button" data-members-next aria-label="Next members">
          <i class="fas fa-chevron-right" aria-hidden="true"></i>
        </button>
      </div>
    </div>
  </section>

  <!-- ==================== PLAYERS ==================== -->
  <section class="gsb-section" id="players">
    <div class="gsb-container">
      <div class="section-intro">
        <span class="section-label center">Our Athletes</span>
        <h2 class="section-title">Meet The <span class="accent">Players</span></h2>
        <p>The athletes representing Bihar on national arenas and international stages.</p>
      </div>

      <div class="players-split">
        <div class="players-col" data-side="national">
          <div class="players-col-head">
            <span class="players-col-icon"><i class="fas fa-flag" aria-hidden="true"></i></span>
            <div class="players-col-title">
              <h3>National</h3>
              <p>Representing Bihar across India</p>
            </div>
          </div>
          <div class="players-grid" data-stagger>
            @foreach (['slider32', 'slider33'] as $img)
            <div class="player-card">
              <a class="player-zoom" href="{{ asset('assets/img/sliders/' . $img . '.jpeg') }}" data-player-zoom
                aria-label="View player portrait">
                <img src="{{ asset('assets/img/sliders/' . $img . '.jpeg') }}" alt="Player portrait" loading="lazy">
                <span class="player-zoom-icon"><i class="fas fa-magnifying-glass-plus" aria-hidden="true"></i></span>
              </a>
              <div class="player-meta">
                <span class="player-tag">National</span>
              </div>
            </div>
            @endforeach
          </div>
        </div>

        <div class="players-col" data-side="international">
          <div class="players-col-head">
            <span class="players-col-icon"><i class="fas fa-globe" aria-hidden="true"></i></span>
            <div class="players-col-title">
              <h3>International</h3>
              <p>Bihar talent on the world stage</p>
            </div>
          </div>
          <div class="players-grid" data-stagger>
            @foreach (['slider2','slider34', 'slider35' ,'slider16'] as $img)
            <div class="player-card">
              <a class="player-zoom" href="{{ asset('assets/img/sliders/' . $img . '.jpeg') }}" data-player-zoom
                aria-label="View player portrait">
                <img src="{{ asset('assets/img/sliders/' . $img . '.jpeg') }}" alt="Player portrait" loading="lazy">
                <span class="player-zoom-icon"><i class="fas fa-magnifying-glass-plus" aria-hidden="true"></i></span>
              </a>
              <div class="player-meta">
                <span class="player-tag intl">International</span>
              </div>
            </div>
            @endforeach
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- ==================== REGISTRATION FORM ==================== -->
  <section class="gsb-section gsb-form-section" id="register">
    <div class="gsb-container">
      <div class="section-intro reveal-up">
        <span class="section-label center">Goal Shot Ball Association of Bihar Registration</span>
        <h2 class="section-title">Create your <span class="accent">account</span></h2>
        <p>Fill in your details below. All required fields are marked with an asterisk.</p>
      </div>


      <!-- Trigger to expand form -->
      <div class="form-trigger-wrap reveal-up" id="formTriggerWrap">
        <div class="form-trigger-card">
          <div class="form-trigger-info">
            <span class="form-trigger-badge">
              <i class="fas fa-user-plus"></i>
              Player Registration
            </span>
            <h3>Ready to register?</h3>
            <p>Takes about 3 minutes. Have your Aadhaar, photo, and signature ready.</p>
          </div>
          <button type="button" class="btn-form primary form-trigger-btn" id="formTriggerBtn"
            aria-controls="formCollapse" aria-expanded="false">
            Register Now
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"
              stroke-linecap="round" stroke-linejoin="round">
              <line x1="5" y1="12" x2="19" y2="12" />
              <polyline points="12 5 19 12 12 19" />
            </svg>
          </button>
        </div>
      </div>

      <!-- Collapsible form -->
      <div class="form-collapse" id="formCollapse">
        <div class="form-collapse-inner">
          @livewire('registration-form')

          <p class="form-foot">
            Need help? Call <a href="tel:+918083319186">8083319186</a>.
          </p>
        </div>
      </div>
      <!-- /form-collapse -->
    </div>
  </section>

  <!-- ==================== TESTIMONIALS ==================== -->
  <section class="gsb-section testimonial-section">
    <div class="gsb-container">
      <div class="section-intro">
        <span class="section-label center">Testimonials</span>
        <h2 class="section-title">What Our <span class="accent">Athletes</span> Say</h2>
      </div>

      <div class="testi-carousel owl-carousel owl-theme" data-testi-carousel>
        @php
          $testimonials = [
            ['A', 'Amit Verma', 'The game has attracted many participants due to its unique concept, exciting gameplay, and team spirit. Students are highly motivated and eager to improve their skills, making the future of the sport very promising.'],
            ['B', 'Bhavya Kumari', 'Their energy, commitment, and love for the game demonstrate that Goal Shot Ball is rapidly gaining popularity and recognition.'],
            ['S', 'Saksham Raj', 'Goal Shot Ball is a very exciting and enjoyable game. We really love playing it because it improves our fitness, teamwork, and concentration. The game is easy to learn, full of action, and keeps us motivated to participate every day. We would like more opportunities to play and compete in Goal Shot Ball tournaments.'],
            ['R', 'Roshani Kumari', 'Goal Shot Ball is one of the most interesting sports we have played. It is fun, challenging, and helps us develop confidence and sportsmanship. Every match is exciting, and we look forward to playing it with our friends. We believe this game has a bright future and should be introduced to more students across the country.'],
          ];
        @endphp

        @foreach ($testimonials as [$avatar, $name, $quote])
        <article class="testi-card">
          <span class="quote-mark">&ldquo;</span>
          <div class="stars">
            @for ($s = 0; $s < 5; $s++)<i class="fa-solid fa-star"></i>@endfor
          </div>
          <p>{{ $quote }}</p>
          <div class="testi-author">
            <span class="testi-avatar" aria-hidden="true">{{ $avatar }}</span>
            <div>
              <h5>{{ $name }}</h5>
            </div>
          </div>
        </article>
        @endforeach
      </div>
    </div>
  </section>

  <!-- ==================== PRESS / NEWSPAPER CLIPPINGS ==================== -->
  <section class="gsb-section press-section" id="press">
    <div class="gsb-container">
      <div class="section-intro">
        <span class="section-label center">In the Press</span>
        <h2 class="section-title">Goal Shot Ball <span class="accent">Makes Headlines</span></h2>
        <p>Newspaper coverage of our tournaments, athletes, and growing presence across Bihar.</p>
      </div>

      <div class="press-grid" data-stagger>
        @for ($n = 1; $n <= 16; $n++)
        <a class="press-clip" href="{{ asset('assets/img/news/news' . $n . '.jpeg') }}" target="_blank" rel="noopener"
          aria-label="Newspaper clipping {{ $n }} — open full image">
          <img src="{{ asset('assets/img/news/news' . $n . '.jpeg') }}" alt="Goal Shot Ball newspaper clipping {{ $n }}"
            loading="lazy">
          <span class="press-zoom"><i class="fas fa-magnifying-glass-plus" aria-hidden="true"></i></span>
        </a>
        @endfor
      </div>

      <div class="press-more-wrap">
        <button type="button" class="press-more-btn" data-press-toggle aria-expanded="false">
          <span class="press-more-label">View More</span>
          <i class="fas fa-chevron-down" aria-hidden="true"></i>
        </button>
      </div>
    </div>
  </section>

@endsection

@push('styles')
<style>
  .field-error {
    display: block;
    margin-top: 6px;
    color: #dc2626;
    font-size: 0.82rem;
    font-weight: 500;
  }
  .form-field input.is-invalid,
  .form-field select.is-invalid {
    border-color: #dc2626;
  }
  [wire\:loading] { display: none; }
</style>
@endpush
