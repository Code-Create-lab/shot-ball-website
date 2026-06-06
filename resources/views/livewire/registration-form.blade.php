<form class="gsb-form-wrap" wire:submit="submit" novalidate
  x-data
  x-on:registration-saved.window="$nextTick(() => document.getElementById('registration-success')?.scrollIntoView({ behavior: 'smooth', block: 'center' }))"
  x-on:validation-failed.window="$nextTick(() => {
    const err = $el.querySelector('.field-error');
    if (!err) return;
    const field = err.closest('.form-field, .form-agree') || err;
    const input = field.querySelector('input, select, textarea');
    field.scrollIntoView({ behavior: 'smooth', block: 'center' });
    input?.focus({ preventScroll: true });
  })">
  @if ($submitted)
    <div id="registration-success" class="form-saved" role="status" aria-live="polite"
      style="text-align:center;padding:48px 24px;">
      <div
        style="display:inline-flex;align-items:center;justify-content:center;width:72px;height:72px;border-radius:50%;background:#ecfdf5;color:#059669;font-size:32px;margin-bottom:18px;">
        <i class="fas fa-circle-check" aria-hidden="true"></i>
      </div>
      <h3 style="margin:0 0 8px;">Registration submitted!</h3>
      <p style="margin:0 0 24px;color:var(--ink-soft);">
        Thanks for registering with the Goal Shot Ball Association of Bihar.
        Your certificate has also been emailed to you. We'll be in touch shortly.
      </p>
      @if ($certificateUrl)
        <a href="{{ $certificateUrl }}" class="btn-form primary"
          style="display:inline-flex;align-items:center;gap:8px;text-decoration:none;margin-bottom:14px;"
          target="_blank" rel="noopener">
          <i class="fas fa-file-arrow-down" aria-hidden="true"></i>
          Download certificate (PDF)
        </a>
        <br>
      @endif
      <button type="button" class="btn-form primary" wire:click="$set('submitted', false)">
        Register another player
      </button>
    </div>
  @else

    <!-- 01 Registration Type -->
    <section class="form-block">
      <div class="form-block-head">
        <span class="form-block-num">01</span>
        <h3 class="form-block-title">Registration type</h3>
      </div>
      <div class="form-row two">
        <div class="form-field">
          <label>Type of registration <span class="req">*</span></label>
          <select wire:model.blur="registration_type" required>
            <option value="">Select registration type&hellip;</option>
            <option>Men</option>
            <option>Women</option>
            <option>Boy</option>
            <option>Girl</option>
          </select>
          @error('registration_type') <span class="field-error">{{ $message }}</span> @enderror
        </div>
        <div class="form-field">
          <label>Event Type <span class="req">*</span></label>
          <select wire:model.blur="event_type" required>
            <option value="">Select event type&hellip;</option>
            <option>Senior</option>
            <option>Junior</option>
            <option>Sub-Junior</option>
          </select>
          @error('event_type') <span class="field-error">{{ $message }}</span> @enderror
        </div>
      </div>
    </section>

    <!-- 02 Personal Details -->
    <section class="form-block">
      <div class="form-block-head">
        <span class="form-block-num">02</span>
        <h3 class="form-block-title">Personal details</h3>
        <span class="form-block-hint">As per official documents</span>
      </div>
      <div class="form-row">
        <div class="form-field">
          <label>First name <span class="req">*</span></label>
          <input type="text" wire:model.blur="first_name" placeholder="Enter first name">
          @error('first_name') <span class="field-error">{{ $message }}</span> @enderror
        </div>
        <div class="form-field">
          <label>Middle name</label>
          <input type="text" wire:model.blur="middle_name" placeholder="Optional">
          @error('middle_name') <span class="field-error">{{ $message }}</span> @enderror
        </div>
        <div class="form-field">
          <label>Last name <span class="req">*</span></label>
          <input type="text" wire:model.blur="last_name" placeholder="Enter last name">
          @error('last_name') <span class="field-error">{{ $message }}</span> @enderror
        </div>
        <div class="form-field">
          <label>Date of birth <span class="req">*</span></label>
          <input type="date" wire:model.blur="dob">
          @error('dob') <span class="field-error">{{ $message }}</span> @enderror
        </div>
        <div class="form-field">
          <label>Father's name <span class="req">*</span></label>
          <input type="text" wire:model.blur="father_name" placeholder="Enter father's name">
          @error('father_name') <span class="field-error">{{ $message }}</span> @enderror
        </div>
        <div class="form-field">
          <label>Mother's name <span class="req">*</span></label>
          <input type="text" wire:model.blur="mother_name" placeholder="Enter mother's name">
          @error('mother_name') <span class="field-error">{{ $message }}</span> @enderror
        </div>
      </div>
    </section>

    <!-- 03 Contact Details -->
    <section class="form-block">
      <div class="form-block-head">
        <span class="form-block-num">03</span>
        <h3 class="form-block-title">Contact details</h3>
      </div>
      <div class="form-row">
        <div class="form-field field-full">
          <label>Address <span class="req">*</span> <span class="hint">(alphanumeric)</span></label>
          <input type="text" wire:model.blur="address" placeholder="House no, street, locality&hellip;">
          @error('address') <span class="field-error">{{ $message }}</span> @enderror
        </div>
        <div class="form-field">
          <label>Village / City <span class="req">*</span></label>
          <input type="text" wire:model.blur="village_city" placeholder="Letters only">
          @error('village_city') <span class="field-error">{{ $message }}</span> @enderror
        </div>
        <div class="form-field">
          <label>State / Province</label>
          <input type="text" wire:model="state" readonly>
        </div>
        <div class="form-field">
          <label>District <span class="req">*</span></label>
          <select wire:model.blur="district">
            <option value="">Select district&hellip;</option>
            @foreach ($districts as $district)
            <option>{{ $district }}</option>
            @endforeach
          </select>
          @error('district') <span class="field-error">{{ $message }}</span> @enderror
        </div>
        <div class="form-field">
          <label>Club 1 <span class="req">*</span></label>
          <input type="text" wire:model.blur="club1">
          @error('club1') <span class="field-error">{{ $message }}</span> @enderror
        </div>
        <div class="form-field">
          <label>Club 2</label>
          <input type="text" wire:model.blur="club2">
          @error('club2') <span class="field-error">{{ $message }}</span> @enderror
        </div>
        <div class="form-field">
          <label>Pincode <span class="req">*</span> <span class="hint">(6 digits)</span></label>
          <input type="text" wire:model.blur="pincode" inputmode="numeric" maxlength="6" placeholder="800001">
          @error('pincode') <span class="field-error">{{ $message }}</span> @enderror
        </div>
        <div class="form-field">
          <label>Country</label>
          <input type="text" wire:model="country" readonly>
        </div>
      </div>
    </section>

    <!-- 04 Identity & Access -->
    <section class="form-block">
      <div class="form-block-head">
        <span class="form-block-num">04</span>
        <h3 class="form-block-title">Identity &amp; access</h3>
      </div>
      <div class="form-row">
        <div class="form-field">
          <label>Aadhaar card number <span class="req">*</span> <span class="hint">(12 digits)</span></label>
          <input type="text" wire:model.blur="aadhaar" inputmode="numeric" maxlength="12" placeholder="XXXX XXXX XXXX">
          @error('aadhaar') <span class="field-error">{{ $message }}</span> @enderror
        </div>
        <div class="form-field">
          <label>Mobile number <span class="req">*</span></label>
          <input type="tel" wire:model.blur="mobile" inputmode="numeric" maxlength="10" placeholder="10-digit number">
          @error('mobile') <span class="field-error">{{ $message }}</span> @enderror
        </div>
        <div class="form-field">
          <label>Email address <span class="req">*</span></label>
          <input type="email" wire:model.blur="email" placeholder="you@example.com">
          @error('email') <span class="field-error">{{ $message }}</span> @enderror
        </div>
      </div>
    </section>

    <!-- 05 Document Uploads -->
    <section class="form-block">
      <div class="form-block-head">
        <span class="form-block-num">05</span>
        <h3 class="form-block-title">Document uploads</h3>
        <span class="form-block-hint">JPG or PNG &middot; max 2MB</span>
      </div>
      <div class="form-row two">

        <!-- Photo -->
        <div class="form-field">
          <label>Candidate photograph <span class="req">*</span> <span class="hint">(3cm &times; 4cm)</span></label>
          <div class="form-upload @if($photo) has-file @endif"
            x-data
            x-on:dragover.prevent="$el.classList.add('is-dragover')"
            x-on:dragleave.prevent="$el.classList.remove('is-dragover')"
            x-on:drop.prevent="
              $el.classList.remove('is-dragover');
              const f = $event.dataTransfer.files[0];
              if (f && f.type.startsWith('image/')) {
                const input = $el.querySelector('input[type=file]');
                const dt = new DataTransfer(); dt.items.add(f); input.files = dt.files;
                input.dispatchEvent(new Event('change', { bubbles: true }));
              }
            ">
            <svg class="upload-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"
              stroke-linecap="round" stroke-linejoin="round">
              <rect x="3" y="3" width="18" height="18" rx="2" />
              <circle cx="9" cy="9" r="2" />
              <path d="m21 15-3.086-3.086a2 2 0 0 0-2.828 0L6 21" />
            </svg>
            <div class="upload-text"><strong>Click to upload</strong> or drag &amp; drop</div>
            <div class="upload-meta">PNG, JPG up to 2MB</div>

            @if ($photo)
            <div class="upload-preview" style="display:flex;">
              <img class="upload-thumb" src="{{ $photo->temporaryUrl() }}" alt="Preview" />
              <div class="upload-info">
                <span class="upload-name">{{ $photo->getClientOriginalName() }}</span>
                <span class="upload-size">{{ number_format($photo->getSize() / 1024, 0) }} KB</span>
                <span class="upload-status">
                  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"
                    stroke-linejoin="round">
                    <polyline points="20 6 9 17 4 12" />
                  </svg>
                  Uploaded
                </span>
              </div>
              <button type="button" class="upload-remove" wire:click="$set('photo', null)" aria-label="Remove file">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                  stroke-linejoin="round">
                  <line x1="18" y1="6" x2="6" y2="18" />
                  <line x1="6" y1="6" x2="18" y2="18" />
                </svg>
              </button>
            </div>
            @endif

            <div wire:loading wire:target="photo" class="upload-meta">Uploading&hellip;</div>
            <input type="file" wire:model="photo" accept="image/*">
          </div>
          @error('photo') <span class="field-error">{{ $message }}</span> @enderror
        </div>

        <!-- Signature -->
        <div class="form-field">
          <label>Candidate signature <span class="req">*</span> <span class="hint">(2cm &times; 1cm)</span></label>
          <div class="form-upload @if($signature) has-file @endif"
            x-data
            x-on:dragover.prevent="$el.classList.add('is-dragover')"
            x-on:dragleave.prevent="$el.classList.remove('is-dragover')"
            x-on:drop.prevent="
              $el.classList.remove('is-dragover');
              const f = $event.dataTransfer.files[0];
              if (f && f.type.startsWith('image/')) {
                const input = $el.querySelector('input[type=file]');
                const dt = new DataTransfer(); dt.items.add(f); input.files = dt.files;
                input.dispatchEvent(new Event('change', { bubbles: true }));
              }
            ">
            <svg class="upload-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"
              stroke-linecap="round" stroke-linejoin="round">
              <path d="M20 19.5 4 19.5" />
              <path d="M4 16c4-3 6-9 10-9 2 0 3 1 3 3s-2 4-5 4-5-2-5-2" />
            </svg>
            <div class="upload-text"><strong>Click to upload</strong> or drag &amp; drop</div>
            <div class="upload-meta">PNG, JPG up to 2MB</div>

            @if ($signature)
            <div class="upload-preview" style="display:flex;">
              <img class="upload-thumb" src="{{ $signature->temporaryUrl() }}" alt="Preview" />
              <div class="upload-info">
                <span class="upload-name">{{ $signature->getClientOriginalName() }}</span>
                <span class="upload-size">{{ number_format($signature->getSize() / 1024, 0) }} KB</span>
                <span class="upload-status">
                  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"
                    stroke-linejoin="round">
                    <polyline points="20 6 9 17 4 12" />
                  </svg>
                  Uploaded
                </span>
              </div>
              <button type="button" class="upload-remove" wire:click="$set('signature', null)" aria-label="Remove file">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                  stroke-linejoin="round">
                  <line x1="18" y1="6" x2="6" y2="18" />
                  <line x1="6" y1="6" x2="18" y2="18" />
                </svg>
              </button>
            </div>
            @endif

            <div wire:loading wire:target="signature" class="upload-meta">Uploading&hellip;</div>
            <input type="file" wire:model="signature" accept="image/*">
          </div>
          @error('signature') <span class="field-error">{{ $message }}</span> @enderror
        </div>
      </div>
    </section>

    <!-- Agreement -->
    <div class="form-agree">
      <input type="checkbox" id="terms" wire:model.blur="terms">
      <label for="terms">
        I agree to all the <a href="#">terms and conditions</a> of Goal Shot Ball Association of Bihar, and
        confirm that the information provided above is accurate to the best of my knowledge.
      </label>
    </div>
    @error('terms') <span class="field-error">{{ $message }}</span> @enderror

    <!-- Actions -->
    <div class="form-actions">
      <button type="button" class="btn-form ghost" wire:click="$refresh" wire:loading.attr="disabled">Reset</button>
      <button type="submit" class="btn-form primary" wire:loading.attr="disabled" wire:target="submit">
        <span wire:loading.remove wire:target="submit">Save &amp; continue</span>
        <span wire:loading wire:target="submit">Saving&hellip;</span>
        <svg wire:loading.remove wire:target="submit" width="16" height="16" viewBox="0 0 24 24" fill="none"
          stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
          <line x1="5" y1="12" x2="19" y2="12" />
          <polyline points="12 5 19 12 12 19" />
        </svg>
      </button>
    </div>

  @endif
</form>
