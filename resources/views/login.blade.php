<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>{{env('APP_NAME')}}</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta content="" name="keywords">
    <meta content="" name="description">

    <!-- Favicon -->
    <link href="{{ asset('img/favicon.ico') }}" rel="icon">

    <!-- Google Web Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Heebo:wght@400;500;600&family=Inter:wght@700;800&display=swap"
        rel="stylesheet">
        
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"
        integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />

    <link rel="stylesheet" href="{{asset('/css/styles.css')}}">
    
</head>

<body>
    <div class="container-fluid bg-white p-0">
        <section class="vh-100">
            <div class="container h-custom">
                <div class="row d-flex justify-content-center h-100 mt-5">
                    <div class="col-md-6">
                        <h1>User Login</h1>
                        <div class="divider d-flex align-items-center my-4"></div>
                        <div class="row">
                           
                            <div class="col-md-12">
                                <!-- Email input-->
                                <div class="form-outline mb-4 form-floating">
                                    <input type="text" id="email"
                                        class="form-control form-control-lg email"
                                        placeholder="Enter email address"/>
                                    <label class="form-label" for="email">Email Address <span class="text-danger">*<span></label>
                                    <span class="err-email err-msg"></span>
                                </div>
                            </div>

                            <div class="col-md-12">
                                <!-- Password input -->
                                <div class="form-outline mb-4 form-floating">
                                    <input type="password" id="password" class="form-control form-control-lg password"
                                        placeholder="Enter password" tabindex="16"/>
                                    <label class="form-label" for="password">Password <span class="text-danger">*<span></label>
                                    <span class="err-password err-msg"></span>
                            
                                </div>
                            </div>
                        </div>

                        <a href="{{url('register')}}">Don't have an account yet? Register</a>

                        <div class="text-center text-lg-start mt-4 pt-2 mb-5">
                            <button type="button" class="btn-login btn btn-primary btn-lg"
                                style="padding-left: 2.5rem; padding-right: 2.5rem;">Login</button>
                        </div>
                    </div>
                    <p class="text-center">&copy; {{ env('APP_NAME') }}. All Rights Reserved {{ date('Y') }}</p>
                </div>
            </div>
        </section>
    </div>

    <!-- JavaScript Libraries -->
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>

    <script src="{{ asset('js/main.js') }}"></script>

    <script>
        let click_counter = 0;
        $('.btn-login').on('click', function() {
            authenticate();
        })

        function authenticate() {
            $('.btn-login').html(`<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Loading...`);

            var formData = new FormData();
            formData.append('_token', "{{ csrf_token() }}");
            formData.append('email', $('#email').val());
            formData.append('password', $('#password').val());

            if (click_counter === 0) {
                click_counter++;
                $(this).prop('disabled', true);

                $.ajax({
                    url: '{{ route('postLogin') }}',
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        if (response.code == "200") {
                            $('.btn-login').html('Login')

                            alert('Successfully Login');

                            setTimeout(function() {
                                window.location.href = '{{ url('/tasks') }}'
                            }, 2000)
                        } else {
                            displayErrors(JSON.parse(response.errors));
                            $('.btn-login').html('Login').prop('disabled', false);
                            click_counter = 0;
                        }
                    },
                    error: function(xhr, status, error) {
                        var result = JSON.parse(xhr.responseText)
                        displayErrors(result.errors)
                        $('.btn-login').html('Login').prop('disabled', false);
                        click_counter = 0;
                    }
                })
            }
        }
    </script>

</body>

</html>
