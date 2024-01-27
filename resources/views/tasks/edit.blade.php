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
            <div class="container h-custom edit-task-container">
                <div class="row d-flex justify-content-center h-100 mt-5">
                    <div class="col-md-6">
                        <h1>Edit Task</h1>
                        <div class="divider d-flex align-items-center my-4"></div>
                        <div class="row">
                           
                            <div class="col-md-12">
                                <!-- Title input-->
                                <div class="form-outline mb-4 form-floating">
                                    <input type="text" id="title"
                                        class="form-control form-control-lg title"
                                        placeholder="Enter title" value="{{$task->title}}"/>
                                    <label class="form-label" for="title">Title <span class="text-danger">*<span></label>
                                    <span class="err-title err-msg"></span>
                                </div>
                            </div>

                            <div class="col-md-12">
                                <!-- Content input-->
                                <div class="form-outline mb-4 form-floating">
                                    <input type="text" id="content"
                                        class="form-control form-control-lg content"
                                        placeholder="Enter content" value="{{$task->content}}"/>
                                    <label class="form-label" for="content">Content <span class="text-danger">*<span></label>
                                    <span class="err-content err-msg"></span>
                                </div>
                            </div>

                            <div class="col-md-12">
                                <!-- Image input-->
                                <div class="form-outline mb-4 form-floating">
                                    <input type="file" id="image" class="form-control form-control-lg image" accept="image/*"/>
                                    <label class="form-label" for="image">Image</label>
                                    <span class="err-image err-msg"></span>
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="form-floating mb-4">
                                    <select class="status form-select" id="status">
                                        <option value="to do" {{ $task->status === 'to do' ? 'selected' : '' }}>To Do</option>
                                        <option value="in progress" {{ $task->status === 'in progress' ? 'selected' : '' }}>In Progress</option>
                                        <option value="done" {{ $task->status === 'done' ? 'selected' : '' }}>Done</option>
                                    </select>
                                    <label for="status">Status</label>
                                    <span class="err-status err-msg"></span>
                                </div>
                            </div>

                            <div class="col-md-12">
                                <p class="d-inline">Publication State:</p>
                                <span class="me-2">Draft</span>
                                <div class="form-check form-switch d-inline-block">
                                    <input class="form-check-input is_published" type="checkbox" id="is_published" {{ $task->is_published ? 'checked' : '' }} >
                                    <label class="form-check-label" for="is_published">Published</label>
                                </div>
                            </div>
                        </div>

                        <div class="text-center text-lg-start mt-4 pt-2 mb-5">
                            <a href="{{url('/tasks')}}" class="btn btn-secondary btn-lg"
                                style="padding-left: 2.5rem; padding-right: 2.5rem;">Back</a>
                            <button type="button" class="btn-edit btn btn-primary btn-lg"
                                style="padding-left: 2.5rem; padding-right: 2.5rem;">Update</button>
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
        $('.btn-edit').on('click', function() {
            editTask();
        })

        function editTask() {
            $('.btn-edit').html(`<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Loading...`);

            let is_published = $('#is_published').is(':checked') ? 1 : 0;

            var formData = new FormData();
            formData.append('_method', "PUT");
            formData.append('_token', "{{ csrf_token() }}");
            formData.append('title', $('#title').val());
            formData.append('content', $('#content').val());
            formData.append('image', $('#image')[0].files[0]);
            formData.append('status', $('#status').val());
            formData.append('is_published', is_published);

            if (click_counter === 0) {
                click_counter++;
                $(this).prop('disabled', true);

                $.ajax({
                    url: '{{ url('tasks/') }}/{{app('request')->segment(2)}}',
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        if (response.code == "200") {
                            $('.btn-edit').html('Update')
                            alert('Task updated.');
                            setTimeout(function() {
                                window.location.href = '{{ url('/tasks') }}'
                            }, 2000)
                        } else {
                            $('.btn-edit').html('Update').prop('disabled', false);
                            click_counter = 0;
                        }
                    },
                    error: function(xhr, status, error) {
                        var result = JSON.parse(xhr.responseText)
                        displayErrors(result.errors)
                        $('.btn-edit').html('Update').prop('disabled', false);
                        click_counter = 0;
                    }
                })
            }
        }
    </script>

</body>

</html>
