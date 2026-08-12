@extends('admin.admin_dashboard')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="card settings-card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <div>
                    <h5 class="mb-1">Portal Settings</h5>
                    <small class="text-muted">Store name, logo, favicon and contact details — sab yahan se control hoga.</small>
                </div>
            </div>
            <div class="card-body p-4">
                <form action="{{ route('settings.update') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="row g-4">
                        <div class="col-lg-8">
                            <div class="settings-section">
                                <h6 class="settings-section-title">Store details</h6>
                                <div class="row">
                                    <div class="col-md-6 mb-4">
                                        <label class="form-label" for="store_name">Store name</label>
                                        <input type="text" id="store_name" name="store_name"
                                            class="form-control @error('store_name') is-invalid @enderror"
                                            value="{{ old('store_name', $setting->store_name) }}" required>
                                        @error('store_name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-6 mb-4">
                                        <label class="form-label" for="store_tagline">Tagline</label>
                                        <input type="text" id="store_tagline" name="store_tagline"
                                            class="form-control @error('store_tagline') is-invalid @enderror"
                                            value="{{ old('store_tagline', $setting->store_tagline) }}"
                                            placeholder="Smart stock control">
                                        @error('store_tagline')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-6 mb-4">
                                        <label class="form-label" for="store_email">Email</label>
                                        <input type="email" id="store_email" name="store_email"
                                            class="form-control @error('store_email') is-invalid @enderror"
                                            value="{{ old('store_email', $setting->store_email) }}">
                                        @error('store_email')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-6 mb-4">
                                        <label class="form-label" for="store_phone">Phone</label>
                                        <input type="text" id="store_phone" name="store_phone"
                                            class="form-control @error('store_phone') is-invalid @enderror"
                                            value="{{ old('store_phone', $setting->store_phone) }}">
                                        @error('store_phone')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-12 mb-4">
                                        <label class="form-label" for="store_address">Address</label>
                                        <textarea id="store_address" name="store_address" rows="3"
                                            class="form-control @error('store_address') is-invalid @enderror">{{ old('store_address', $setting->store_address) }}</textarea>
                                        @error('store_address')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-6 mb-4">
                                        <label class="form-label" for="currency_code">Currency</label>
                                        <select id="currency_code" name="currency_code"
                                            class="form-select @error('currency_code') is-invalid @enderror" required>
                                            @foreach ($currencies as $code => $currency)
                                                <option value="{{ $code }}"
                                                    {{ old('currency_code', $setting->currency_code ?? 'PKR') === $code ? 'selected' : '' }}>
                                                    {{ $code }} — {{ $currency['name'] }} ({{ $currency['symbol'] }})
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('currency_code')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <small class="text-muted d-block mt-2">
                                            Selected currency will show across the whole portal.
                                        </small>
                                    </div>
                                    <div class="col-md-6 mb-4">
                                        <label class="form-label" for="footer_text">Footer text</label>
                                        <input type="text" id="footer_text" name="footer_text"
                                            class="form-control @error('footer_text') is-invalid @enderror"
                                            value="{{ old('footer_text', $setting->footer_text) }}">
                                        @error('footer_text')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-4">
                            <div class="settings-section mb-4">
                                <h6 class="settings-section-title">Portal logo</h6>
                                <div class="settings-preview">
                                    <img id="logoPreview" src="{{ $setting->logoUrl() }}" alt="Logo preview">
                                </div>
                                <label class="form-label mt-3" for="logo">Upload logo</label>
                                <input type="file" id="logo" name="logo" accept="image/*"
                                    class="form-control @error('logo') is-invalid @enderror"
                                    onchange="previewImage(this, 'logoPreview')">
                                @error('logo')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                                <small class="text-muted d-block mt-2">PNG / JPG / WEBP / SVG (max 2MB)</small>
                            </div>

                            <div class="settings-section">
                                <h6 class="settings-section-title">Favicon</h6>
                                <div class="settings-preview favicon">
                                    <img id="faviconPreview" src="{{ $setting->faviconUrl() }}" alt="Favicon preview">
                                </div>
                                <label class="form-label mt-3" for="favicon">Upload favicon</label>
                                <input type="file" id="favicon" name="favicon" accept="image/*,.ico"
                                    class="form-control @error('favicon') is-invalid @enderror"
                                    onchange="previewImage(this, 'faviconPreview')">
                                @error('favicon')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                                <small class="text-muted d-block mt-2">ICO / PNG / SVG (max 1MB)</small>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-end mt-2">
                        <button type="submit" class="btn btn-primary px-4">Save settings</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function previewImage(input, targetId) {
            if (!input.files || !input.files[0]) {
                return;
            }
            const reader = new FileReader();
            reader.onload = function (e) {
                document.getElementById(targetId).src = e.target.result;
            };
            reader.readAsDataURL(input.files[0]);
        }
    </script>
@endsection
