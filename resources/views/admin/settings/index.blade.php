@include('admin.include.header')
    <div class="body-wrapper-inner">
        <div class="container-fluid">

            {{-- Page Header --}}
            <div class="row">
                <div class="col-lg-12 d-flex align-items-strech">
                    <div class="card card-body py-3">
                        <div class="row align-items-center">
                            <div class="col-12">
                                <div class="d-sm-flex align-items-center justify-space-between">
                                    <h4 class="mb-4 mb-sm-0 card-title">Site Settings</h4>
                                    <nav aria-label="breadcrumb" class="ms-auto">
                                        <ol class="breadcrumb">
                                            <li class="breadcrumb-item d-flex align-items-center">
                                                <a class="text-muted text-decoration-none d-flex">
                                                    <iconify-icon icon="solar:home-2-line-duotone" class="fs-6"></iconify-icon>
                                                </a>
                                            </li>
                                            <li class="breadcrumb-item" aria-current="page">
                                                <span class="badge fw-medium fs-2 bg-primary-subtle text-primary">Configuration</span>
                                            </li>
                                            <li class="breadcrumb-item" aria-current="page">
                                                <span class="badge fw-medium fs-2 bg-primary-subtle text-primary">
                                                    {{ ucfirst($group) }}
                                                </span>
                                            </li>
                                        </ol>
                                    </nav>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Alerts --}}
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show text-center mt-3" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <div class="row">

                {{-- LEFT: Tab Navigation --}}
                <div class="col-lg-3">
                    <div class="card">
                        <div class="card-body p-2">
                            <div class="nav flex-column nav-pills">

                                <a class="nav-link hstack gap-3 {{ $group == 'general' ? 'active' : '' }}"
                                   href="{{ route('admin.settings.index', ['group' => 'general']) }}">
                                    <iconify-icon icon="solar:settings-line-duotone" class="fs-5"></iconify-icon>
                                    General
                                </a>

                                <a class="nav-link hstack gap-3 {{ $group == 'contact' ? 'active' : '' }}"
                                   href="{{ route('admin.settings.index', ['group' => 'contact']) }}">
                                    <iconify-icon icon="solar:phone-line-duotone" class="fs-5"></iconify-icon>
                                    Contact Info
                                </a>

                                <a class="nav-link hstack gap-3 {{ $group == 'social' ? 'active' : '' }}"
                                   href="{{ route('admin.settings.index', ['group' => 'social']) }}">
                                    <iconify-icon icon="solar:share-line-duotone" class="fs-5"></iconify-icon>
                                    Social Links
                                </a>

                                <a class="nav-link hstack gap-3 {{ $group == 'homepage' ? 'active' : '' }}"
                                   href="{{ route('admin.settings.index', ['group' => 'homepage']) }}">
                                    <iconify-icon icon="solar:home-angle-line-duotone" class="fs-5"></iconify-icon>
                                    Homepage
                                </a>

                            </div>
                        </div>
                    </div>

                    {{-- Group Info Card --}}
                    <div class="card mt-3">
                        <div class="card-body">
                            <h6 class="fw-semibold mb-2">
                                @if($group == 'general')
                                    <iconify-icon icon="solar:settings-line-duotone" class="me-1"></iconify-icon>
                                    General
                                @elseif($group == 'contact')
                                    <iconify-icon icon="solar:phone-line-duotone" class="me-1"></iconify-icon>
                                    Contact Info
                                @elseif($group == 'social')
                                    <iconify-icon icon="solar:share-line-duotone" class="me-1"></iconify-icon>
                                    Social Links
                                @elseif($group == 'homepage')
                                    <iconify-icon icon="solar:home-angle-line-duotone" class="me-1"></iconify-icon>
                                    Homepage
                                @endif
                            </h6>
                            <small class="text-muted">
                                @if($group == 'general')
                                    Site name, logo, favicon and company RERA number.
                                @elseif($group == 'contact')
                                    Phone, WhatsApp, email, office address and Google Maps embed.
                                @elseif($group == 'social')
                                    Links to all social media profiles.
                                @elseif($group == 'homepage')
                                    Hero section, about text and stats counters.
                                @endif
                            </small>
                            <div class="mt-2">
                                <span class="badge bg-primary-subtle text-primary">
                                    {{ count($settings) }} settings
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- RIGHT: Settings Form --}}
                <div class="col-lg-9">
                    <div class="card">
                        <div class="card-body">

                            <h5 class="card-title mb-4 pb-2 border-bottom">
                                @if($group == 'general')
                                    <iconify-icon icon="solar:settings-line-duotone" class="me-2"></iconify-icon>
                                    General Settings
                                @elseif($group == 'contact')
                                    <iconify-icon icon="solar:phone-line-duotone" class="me-2"></iconify-icon>
                                    Contact Information
                                @elseif($group == 'social')
                                    <iconify-icon icon="solar:share-line-duotone" class="me-2"></iconify-icon>
                                    Social Media Links
                                @elseif($group == 'homepage')
                                    <iconify-icon icon="solar:home-angle-line-duotone" class="me-2"></iconify-icon>
                                    Homepage Settings
                                @endif
                            </h5>

                            @if(count($settings) > 0)

                                <form action="{{ route('admin.settings.update') }}"
                                      method="POST"
                                      enctype="multipart/form-data">
                                    @csrf
                                    <input type="hidden" name="group" value="{{ $group }}">

                                    <div class="row">
                                        @foreach($settings as $setting)

                                        {{-- Full width for textarea and video, half for rest --}}
                                        <div class="col-md-{{ in_array($setting->type, ['textarea', 'video']) ? '12' : '6' }} mb-4">

                                            @if($setting->type === 'textarea')
                                            {{-- ===== TEXTAREA ===== --}}
                                                <label class="form-label fw-semibold">
                                                    {{ $setting->label }}
                                                </label>
                                                <textarea class="form-control"
                                                          name="settings[{{ $setting->key }}]"
                                                          rows="4"
                                                          placeholder="{{ $setting->label }}">{{ $setting->value }}</textarea>

                                            @elseif($setting->type === 'image')
                                            {{-- ===== IMAGE UPLOAD ===== --}}
                                                <label class="form-label fw-semibold">{{ $setting->label }}</label>
                                                @if($setting->value)
                                                    <div class="mb-2">
                                                        <img src="{{ asset('public/'.$setting->value) }}"
                                                             alt="{{ $setting->label }}"
                                                             height="50"
                                                             style="object-fit:contain; max-width:150px; background:#f8f9fa; padding:4px; border-radius:6px;">
                                                        <small class="text-muted d-block mt-1">Current {{ strtolower($setting->label) }}</small>
                                                    </div>
                                                @endif
                                                <input class="form-control"
                                                       type="file"
                                                       name="settings[{{ $setting->key }}]"
                                                       accept="image/*">
                                                <div class="form-text">Leave empty to keep current image.</div>

                                            @elseif($setting->type === 'video')
                                            {{-- ===== VIDEO URL ===== --}}
                                                <label class="form-label fw-semibold">{{ $setting->label }}</label>
                                                @if($setting->value)
                                                    <div class="mb-2">
                                                        <a href="{{ $setting->value }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                                            <iconify-icon icon="solar:play-circle-line-duotone" class="me-1"></iconify-icon>
                                                            Preview Current Video
                                                        </a>
                                                    </div>
                                                @endif
                                                <div class="form-floating">
                                                    <input type="url"
                                                           class="form-control"
                                                           name="settings[{{ $setting->key }}]"
                                                           id="setting_{{ $setting->key }}"
                                                           placeholder="{{ $setting->label }}"
                                                           value="{{ $setting->value }}">
                                                    <label for="setting_{{ $setting->key }}">{{ $setting->label }}</label>
                                                </div>
                                                <div class="form-text">Enter a YouTube or direct video URL.</div>

                                            @else
                                            {{-- ===== DEFAULT TEXT ===== --}}
                                                <div class="form-floating">
                                                    <input type="text"
                                                           class="form-control"
                                                           name="settings[{{ $setting->key }}]"
                                                           id="setting_{{ $setting->key }}"
                                                           placeholder="{{ $setting->label }}"
                                                           value="{{ $setting->value }}">
                                                    <label for="setting_{{ $setting->key }}">
                                                        {{ $setting->label }}
                                                    </label>
                                                </div>

                                            @endif

                                        </div>
                                        @endforeach
                                    </div>

                                    {{-- Submit --}}
                                    <div class="d-flex justify-content-end gap-2 mt-2 pt-3 border-top">
                                        <button type="submit" class="btn btn-primary px-4 hstack gap-2">
                                            <iconify-icon icon="solar:diskette-line-duotone" class="fs-4"></iconify-icon>
                                            Save Settings
                                        </button>
                                    </div>

                                </form>

                            @else

                                <div class="text-center py-5 text-muted">
                                    <iconify-icon icon="solar:settings-line-duotone" class="fs-1 d-block mb-3"></iconify-icon>
                                    <p class="mb-1">No settings found for this group.</p>
                                    <small>Make sure the <code>settings</code> table has rows with <code>group = '{{ $group }}'</code></small>
                                </div>

                            @endif

                        </div>
                    </div>
                </div>

            </div>

        </div>
    </div>
@include('admin.include.footer')