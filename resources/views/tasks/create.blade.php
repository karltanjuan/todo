@extends('tasks.master')

@section('title', 'Task List')

@section('main-content')

    <div class="container-fluid bg-white p-0">
        <section class="vh-100">
            <div class="container h-custom add-task-container">
                <div class="row d-flex justify-content-center h-100 mt-5">
                    <div class="col-md-6">
                        <h1>Add Task</h1>
                        <div class="divider d-flex align-items-center my-4"></div>
                        <div class="row">
                            
                            <div class="col-md-12">
                                <!-- Title input-->
                                <div class="form-outline mb-4 form-floating">
                                    <input type="text" id="title"
                                        class="form-control form-control-lg title"
                                        placeholder="Enter title"/>
                                    <label class="form-label" for="title">Title <span class="text-danger">*<span></label>
                                    <span class="err-title err-msg"></span>
                                </div>
                            </div>

                            <div class="col-md-12">
                                <!-- Content input-->
                                <div class="form-outline mb-4 form-floating">
                                    <input type="text" id="content"
                                        class="form-control form-control-lg content"
                                        placeholder="Enter content"/>
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
                                        <option value="to do" selected>To Do</option>
                                        <option value="in progress">In Progress</option>
                                        <option value="done">Done</option>
                                    </select>
                                    <label for="status">Status</label>
                                    <span class="err-status err-msg"></span>
                                </div>
                            </div>

                            <div class="col-md-12">
                                <p class="d-inline">Publication State:</p>
                                <span class="me-2">Draft</span>
                                <div class="form-check form-switch d-inline-block">
                                    <input class="form-check-input is_published" type="checkbox" id="is_published">
                                    <label class="form-check-label" for="is_published">Published</label>
                                </div>
                            </div>
                        </div>

                        <div class="text-center text-lg-start mt-4 pt-2 mb-5">
                            <a href="{{url('/tasks')}}" class="btn btn-secondary btn-lg"
                                style="padding-left: 2.5rem; padding-right: 2.5rem;">Back</a>
                            <button type="button" class="btn-add btn btn-primary btn-lg"
                                style="padding-left: 2.5rem; padding-right: 2.5rem;">Create</button>
                        </div>
                    </div>
                    <p class="text-center">&copy; {{ env('APP_NAME') }}. All Rights Reserved {{ date('Y') }}</p>
                </div>
            </div>
        </section>
    </div>

    <script>
        let click_counter = 0;
        $('.btn-add').on('click', function() {
            addTask();
        })

        function addTask() {
            $('.btn-add').html(`<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Loading...`);

            let is_published = $('#is_published').is(':checked') ? 1 : 0;

            var formData = new FormData();
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
                    url: '{{ url('tasks') }}',
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        if (response.code == "200") {
                            $('.btn-add').html('Create')
                            alert('New task created.');
                            setTimeout(function() {
                                window.location.href = '{{ url('/tasks') }}'
                            }, 2000)
                        } else {
                            $('.btn-add').html('Create').prop('disabled', false);
                            click_counter = 0;
                        }
                    },
                    error: function(xhr, status, error) {
                        var result = JSON.parse(xhr.responseText)
                        displayErrors(result.errors)
                        $('.btn-add').html('Create').prop('disabled', false);
                        click_counter = 0;
                    }
                })
            }
        }
    </script>

@endsection
