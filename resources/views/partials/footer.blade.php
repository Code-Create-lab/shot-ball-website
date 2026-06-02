  <!-- ==================== CTA BANNER ==================== -->
  <section class="gsb-section" id="contact" style="padding-top: 0;">
    <div class="gsb-container">
      <div class="gsb-cta-banner reveal-scale">
        <div>
          <h2>Ready to take the shot?</h2>
          <p>Register today and become part of Bihar's official Goal Shot Ball Federation network.</p>
        </div>
        <a href="#register" class="btn-magnetic" style="background: var(--ink); color: #fff;">
          Register Now
          <span class="arrow"><i class="far fa-arrow-right"></i></span>
        </a>
      </div>
    </div>
  </section>

  <!-- ==================== FOOTER ==================== -->
  <footer class="gsb-footer">
    <div class="gsb-container">
      <div class="footer-grid">
        <div class="footer-col footer-brand">
          <img class="footer-logo" src="{{ asset('assets/img/logo.png') }}" alt="Goal Shot Ball Association of Bihar">
          <h4 class="footer-brand-name">Goal Shot Ball Association of Bihar</h4>
          <p>Affiliated with National &amp; International Federations.</p>
          <p class="footer-affiliation">Affiliated: Fit India Government Registration</p>
        </div>

        <div class="footer-col">
          <h4>Quick Links</h4>
          <ul>
            <li><a href="#home"><i class="far fa-angle-right"></i> Home</a></li>
            <li><a href="#about"><i class="far fa-angle-right"></i> About Us</a></li>
            <li><a href="#members"><i class="far fa-angle-right"></i> Members</a></li>
            <li><a href="#players"><i class="far fa-angle-right"></i> Players</a></li>
          </ul>
        </div>

        <div class="footer-col">
          <h4>Explore</h4>
          <ul>
            <li><a href="#press"><i class="far fa-angle-right"></i> Press</a></li>
            <li><a href="#register"><i class="far fa-angle-right"></i> Registration</a></li>
            <li><a href="#" data-contact-open><i class="far fa-angle-right"></i> Contact</a></li>
          </ul>
        </div>

        <div class="footer-col">
          <h4>Contact</h4>
          <ul class="footer-contact">
            <li>
              <i class="fas fa-location-dot" aria-hidden="true"></i>
              <span>Kamruddinpur, Ward No-5, Begusarai (Bihar)</span>
            </li>
            <li>
              <i class="fas fa-phone" aria-hidden="true"></i>
              <span>
                <a href="tel:+918083319186">8083319186</a>,
                <a href="tel:+917479469850">7479469850</a>,
                <a href="tel:+917979906275">7979906275</a>
              </span>
            </li>
            <li>
              <i class="fas fa-envelope" aria-hidden="true"></i>
              <a href="mailto:bihargoalshotball@gmail.com">bihargoalshotball@gmail.com</a>
            </li>
            <li>
              <i class="fas fa-envelope" aria-hidden="true"></i>
              <a href="mailto:Verma.amit8083@gmail.com">Verma.amit8083@gmail.com</a>
            </li>
          </ul>
        </div>
      </div>

      <div class="footer-bottom">
        <p style="margin: 0;">
          Copyright <i class="far fa-copyright"></i> {{ date('Y') }}
          <a href="{{ url('/') }}">Goal Shot Ball Association of Bihar</a>. All rights reserved.
        </p>
        <p class="footer-credit" style="margin: 0;">
          Designed &amp; Developed by
          <a href="https://www.instagram.com/10xcart" target="_blank" rel="noopener noreferrer" class="credit-link">
            <span class="credit-name">10xCart</span>
            <i class="fab fa-instagram"></i>
          </a>
        </p>
      </div>
    </div>
  </footer>

  <!-- ==================== CONTACT MODAL ==================== -->
  <div class="contact-modal" data-contact-modal aria-hidden="true">
    <div class="contact-modal__backdrop" data-contact-close></div>
    <div class="contact-modal__dialog" role="dialog" aria-modal="true" aria-labelledby="contactModalTitle">
      <button type="button" class="contact-modal__close" data-contact-close aria-label="Close contact form">
        <i class="fas fa-times" aria-hidden="true"></i>
      </button>

      <div class="contact-modal__head">
        <span class="section-label">Get in touch</span>
        <h3 id="contactModalTitle">Contact <span class="accent">Us</span></h3>
        <p>Have a question about Goal Shot Ball? Send us a message and we'll get back to you.</p>
      </div>

      <form class="contact-form" data-contact-form novalidate>
        <div class="contact-field">
          <label for="cmName">Name <span class="req">*</span></label>
          <input type="text" id="cmName" name="name" autocomplete="name" placeholder="Your full name" required>
        </div>
        <div class="contact-field">
          <label for="cmEmail">Email <span class="req">*</span></label>
          <input type="email" id="cmEmail" name="email" autocomplete="email" placeholder="you@example.com" required>
        </div>
        <div class="contact-field">
          <label for="cmMessage">Message <span class="req">*</span></label>
          <textarea id="cmMessage" name="message" rows="4" placeholder="How can we help?" required></textarea>
        </div>

        <button type="submit" class="contact-form__submit">
          <span>Send Message</span>
          <i class="far fa-arrow-right" aria-hidden="true"></i>
        </button>

        <p class="contact-form__success" data-contact-success role="status" aria-live="polite" hidden>
          <i class="fas fa-circle-check" aria-hidden="true"></i> Thanks! Your message has been sent.
        </p>
      </form>
    </div>
  </div>

  <!-- Back to top -->
  <button class="back-to-top-modern" aria-label="Back to top">
    <i class="fas fa-arrow-up"></i>
  </button>
