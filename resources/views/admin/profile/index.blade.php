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
                                    <h4 class="mb-4 mb-sm-0 card-title">My Profile</h4>
                                    <nav aria-label="breadcrumb" class="ms-auto">
                                        <ol class="breadcrumb">
                                            <li class="breadcrumb-item d-flex align-items-center">
                                                <a class="text-muted text-decoration-none d-flex">
                                                    <iconify-icon icon="solar:home-2-line-duotone" class="fs-6"></iconify-icon>
                                                </a>
                                            </li>
                                            <li class="breadcrumb-item" aria-current="page">
                                                <span class="badge fw-medium fs-2 bg-primary-subtle text-primary">Admin</span>
                                            </li>
                                            <li class="breadcrumb-item" aria-current="page">
                                                <span class="badge fw-medium fs-2 bg-primary-subtle text-primary">Profile</span>
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
            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show text-center mt-3" role="alert">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
            @if($errors->any())
                <div class="alert alert-danger text-center mt-3">
                    Please fix the errors below.
                </div>
            @endif

            <div class="row">

                {{-- LEFT: Profile Card --}}
                <div class="col-lg-4">
                    <div class="card">
                        <div class="card-body py-5 text-center">

                            {{-- Avatar --}}
                            <div class="mb-3">
                                @if($admin->avatar)
                                    <img src="{{ asset('public/'.$admin->avatar) }}"
                                         alt="{{ $admin->name }}"
                                         class="rounded-circle"
                                         width="100" height="100"
                                         style="object-fit:cover; border:3px solid #214A8B;">
                                @else
                                    <div class="rounded-circle d-flex align-items-center justify-content-center mx-auto"
                                         style="width:100px;height:100px;background:#214A8B;border:3px solid #D4AF37;">
                                        <iconify-icon icon="solar:user-bold" class="text-white" style="font-size:48px;"></iconify-icon>
                                    </div>
                                @endif
                            </div>

                            <h5 class="fw-bold mb-1">{{ $admin->name }}</h5>
                            <p class="text-muted mb-1">{{ $admin->email }}</p>
                            @if($admin->phone)
                                <p class="text-muted mb-1">
                                    <iconify-icon icon="solar:phone-line-duotone" class="me-1"></iconify-icon>
                                    {{ $admin->phone }}
                                </p>
                            @endif
                            <span class="badge bg-primary-subtle text-primary px-3 py-1 mt-1">Administrator</span>

                            <hr class="my-4">

                            <div class="text-start">
                                <div class="d-flex align-items-center gap-2 mb-3">
                                    <iconify-icon icon="solar:calendar-line-duotone" class="text-muted fs-5"></iconify-icon>
                                    <div>
                                        <small class="text-muted d-block">Member Since</small>
                                        <small class="fw-semibold">
                                            {{ \Carbon\Carbon::parse($admin->created_at)->format('d M Y') }}
                                        </small>
                                    </div>
                                </div>
                                <div class="d-flex align-items-center gap-2 mb-3">
                                    <iconify-icon icon="solar:shield-check-line-duotone" class="text-success fs-5"></iconify-icon>
                                    <div>
                                        <small class="text-muted d-block">Role</small>
                                        <small class="fw-semibold">Super Admin</small>
                                    </div>
                                </div>
                                <div class="d-flex align-items-center gap-2">
                                    <iconify-icon icon="solar:clock-circle-line-duotone" class="text-muted fs-5"></iconify-icon>
                                    <div>
                                        <small class="text-muted d-block">Last Updated</small>
                                        <small class="fw-semibold">
                                            {{ \Carbon\Carbon::parse($admin->updated_at)->format('d M Y, h:i A') }}
                                        </small>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>

                    {{-- Quick Links --}}
                    <div class="card mt-3">
                        <div class="card-body p-2">
                            <a href="{{ route('admin.dashboard') }}"
                               class="btn btn-outline-primary w-100 hstack gap-2 justify-content-center mb-2">
                                <iconify-icon icon="solar:widget-5-line-duotone"></iconify-icon>
                                Go to Dashboard
                            </a>
                            <a href="{{ route('admin.settings.index') }}"
                               class="btn btn-outline-secondary w-100 hstack gap-2 justify-content-center">
                                <iconify-icon icon="solar:settings-line-duotone"></iconify-icon>
                                Site Settings
                            </a>
                        </div>
                    </div>
                </div>

                {{-- RIGHT: Edit Form --}}
                <div class="col-lg-8">

                    <form action="{{ route('admin.profile.update') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        {{-- ===== Section 1: Personal Info ===== --}}
                        <div class="card mb-4">
                            <div class="card-body">
                                <h5 class="card-title mb-4 pb-2 border-bottom">
                                    <iconify-icon icon="solar:user-circle-line-duotone" class="me-2"></iconify-icon>
                                    Personal Information
                                </h5>
                                <div class="row">

                                    {{-- Name --}}
                                    <div class="col-md-6">
                                        <div class="form-floating mb-3">
                                            <input type="text"
                                                   class="form-control @error('name') is-invalid @enderror"
                                                   name="name"
                                                   id="name"
                                                   placeholder="Full Name"
                                                   value="{{ old('name', $admin->name) }}"
                                                   required>
                                            <label for="name">Full Name *</label>
                                            @error('name')
                                                <span class="text-danger text-sm">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>

                                    {{-- Email --}}
                                    <div class="col-md-6">
                                        <div class="form-floating mb-3">
                                            <input type="email"
                                                   class="form-control @error('email') is-invalid @enderror"
                                                   name="email"
                                                   id="email"
                                                   placeholder="Email"
                                                   value="{{ old('email', $admin->email) }}"
                                                   required>
                                            <label for="email">Email Address *</label>
                                            @error('email')
                                                <span class="text-danger text-sm">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>

                                    {{-- Phone --}}
                                    <div class="col-md-6">
                                        <div class="form-floating mb-3">
                                            <input type="text"
                                                   class="form-control @error('phone') is-invalid @enderror"
                                                   name="phone"
                                                   id="phone"
                                                   placeholder="Phone"
                                                   value="{{ old('phone', $admin->phone) }}">
                                            <label for="phone">Phone Number</label>
                                            @error('phone')
                                                <span class="text-danger text-sm">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>

                                    {{-- Avatar --}}
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label fw-semibold">Avatar</label>
                                        @if($admin->avatar)
                                            <div class="mb-2 d-flex align-items-center gap-3">
                                                <img src="{{ asset('public/'.$admin->avatar) }}"
                                                     alt="{{ $admin->name }}"
                                                     class="rounded-circle"
                                                     width="45" height="45"
                                                     style="object-fit:cover;">
                                                <small class="text-muted">Current avatar</small>
                                            </div>
                                        @endif
                                        <input class="form-control" type="file" name="avatar" accept="image/*">
                                        <div class="form-text">Used in sidebar and header. Square image recommended.</div>
                                    </div>

                                    {{-- Profile Photo --}}
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label fw-semibold">Profile Photo</label>
                                        @if($admin->profile_photo)
                                            <div class="mb-2 d-flex align-items-center gap-3">
                                                <img src="{{ asset('public/'.$admin->profile_photo) }}"
                                                     alt="{{ $admin->name }}"
                                                     class="rounded"
                                                     width="45" height="45"
                                                     style="object-fit:cover;">
                                                <small class="text-muted">Current profile photo</small>
                                            </div>
                                        @endif
                                        <input class="form-control" type="file" name="profile_photo" accept="image/*">
                                        <div class="form-text">Larger photo used on profile page.</div>
                                    </div>

                                </div>
                            </div>
                        </div>

                        {{-- ===== Section 2: Change Password ===== --}}
                        <div class="card mb-4">
                            <div class="card-body">
                                <h5 class="card-title mb-4 pb-2 border-bottom">
                                    <iconify-icon icon="solar:lock-password-line-duotone" class="me-2"></iconify-icon>
                                    Change Password
                                    <small class="text-muted fw-normal fs-6 ms-2">— Leave blank to keep current</small>
                                </h5>
                                <div class="row">

                                    {{-- Current Password --}}
                                    <div class="col-md-12">
                                        <div class="form-floating mb-3">
                                            <input type="password"
                                                   class="form-control @error('current_password') is-invalid @enderror"
                                                   name="current_password"
                                                   id="current_password"
                                                   placeholder="Current Password">
                                            <label for="current_password">Current Password</label>
                                            @error('current_password')
                                                <span class="text-danger text-sm">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>

                                    {{-- New Password --}}
                                    <div class="col-md-6">
                                        <div class="form-floating mb-3">
                                            <input type="password"
                                                   class="form-control @error('new_password') is-invalid @enderror"
                                                   name="new_password"
                                                   id="new_password"
                                                   placeholder="New Password">
                                            <label for="new_password">New Password</label>
                                            @error('new_password')
                                                <span class="text-danger text-sm">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>

                                    {{-- Confirm Password --}}
                                    <div class="col-md-6">
                                        <div class="form-floating mb-3">
                                            <input type="password"
                                                   class="form-control"
                                                   name="new_password_confirmation"
                                                   id="new_password_confirmation"
                                                   placeholder="Confirm New Password">
                                            <label for="new_password_confirmation">Confirm New Password</label>
                                        </div>
                                    </div>

                                    <div class="col-12">
                                        <div class="py-2 px-3 rounded" style="background:#e8f4fd;">
                                            <small>
                                                <iconify-icon icon="solar:info-circle-line-duotone" class="me-1"></iconify-icon>
                                                Password must be at least 6 characters. Only fill this section if you want to change your password.
                                            </small>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>

                        {{-- Submit --}}
                        <div class="d-flex justify-content-end gap-2 mb-4">
                            <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-secondary px-4">
                                Cancel
                            </a>
                            <button type="submit" class="btn btn-primary px-4 hstack gap-2">
                                <iconify-icon icon="solar:diskette-line-duotone" class="fs-4"></iconify-icon>
                                Update Profile
                            </button>
                        </div>

                    </form>

                </div>

            </div>

        </div>
    </div>
@include('admin.include.footer')