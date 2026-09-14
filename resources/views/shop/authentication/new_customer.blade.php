<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login – ShopZone</title>
    <link rel="icon" href="https://img.icons8.com/fluency/48/shopping-bag.png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/remixicon@4.2.0/fonts/remixicon.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    
</head>


<body class="auth-page">

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12 col-lg-5">
                <div class="auth-card">
                    <div class="auth-logo">{{ __(APPLICATION_NAME) }}</div>
                    <p class="text-center text-muted mb-4" style="font-size:.88rem">Create a free account and start
                        shopping!.</p>

                    <div id="alertBox" class="d-none"></div>

                    <form id="registerForm" method="POST" action="{{ route('shopping.store_new_customer') }}">
                        @csrf
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">First Name *</label>
                                <input type="text" class="form-control" id="regFirstName" required=""
                                    placeholder="" name="f_name" />
                                    @error('f_name')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Last Name *</label>
                                <input type="text" class="form-control" id="regLastName" required=""
                                    placeholder="" name="l_name" />
                                    @error('f_name')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                            </div>
                            <div class="col-12">
                                <label class="form-label">Email Address *</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0"><i
                                            class="ri-mail-line text-muted"></i></span>
                                    <input type="email" class="form-control border-start-0" id="regEmail"
                                        required="" placeholder="" name="email" />
                                    @error('email')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-12">
                                <label class="form-label">Phone Number</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0"><i
                                            class="ri-phone-line text-muted"></i></span>
                                    <input type="tel" class="form-control border-start-0" id="regPhone"
                                        placeholder="" name="mobile_number" />
                                    @error("mobile_number")
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-12">
                                <label class="form-label">CNIC Number</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0"><i
                                            class="ri-id-card-fill text-muted"></i></span>
                                    <input type="text" class="form-control border-start-0" id="regPhone"
                                        placeholder="" name="cnic_number" />
                                    </div>
                                    @error("cnic_number")
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                            </div>
                            <div class="col-12">
                                <label class="form-label">Password *</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0"><i
                                            class="ri-lock-line text-muted"></i></span>
                                    <input type="password" name="password" class="form-control border-start-0 border-end-0"
                                        id="regPassword" required="" placeholder="Min 8 characters"
                                        autocomplete="new-password">
                                    <button class="input-group-text bg-light border-start-0 toggle-pw" type="button"
                                        data-target="regPassword">
                                        <i class="ri-eye-line text-muted"></i>
                                    </button>
                                    @error("password")
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="mt-2" id="strengthWrap" style="display:none">
                                    <div class="progress" style="height:4px">
                                        <div class="progress-bar" id="strengthBar" style="transition:all .3s"></div>
                                    </div>
                                    <small id="strengthLabel" class="text-muted"></small>
                                </div>
                            </div>
                            <div class="col-12">
                                <label class="form-label">Confirm Password *</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0"><i
                                            class="ri-lock-check-line text-muted"></i></span>
                                    <input type="password" class="form-control border-start-0 border-end-0"
                                        id="regConfirm" required="" name="confirm_password" placeholder="Re-enter password"
                                        autocomplete="new-password">
                                    <button class="input-group-text bg-light border-start-0 toggle-pw" type="button"
                                        data-target="regConfirm">
                                        <i class="ri-eye-line text-muted"></i>
                                    </button>
                                    @error("confirm_password")
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <small id="matchMsg" class="d-none"></small>
                            </div>
                            <div class="col-md-12">
                                <label class="form-label">Address *</label>
                                <textarea name="customer_address" rows="7" class="form-control border-start-0 border-end-0" id="txtCustomerAddress" style="resize: none;">{{ old("customer_address") }}</textarea>
                            </div>
                        </div>

                        <button type="submit" class="btn-primary-custom w-100 justify-content-center mt-3"
                            id="registerBtn">
                            <i class="ri-user-add-line me-2"></i>Create Account
                        </button>
                    </form>

                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="{{ asset('js/utils.js') }}"></script>
</body>

</html>
