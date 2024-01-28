@extends('tasks.master')

@section('title', 'Task List')

@section('main-content')

<style>
    a {
        text-decoration: none;
    }
</style>

<div class="container">
    <h2 class="mt-3">Tasks</h2>
    <a class="btn btn-primary" href="{{url('tasks/create')}}"><i class="fa-regular fa-plus"></i> Add Task</a>
    {{-- <a class="btn btn-outline-danger" href="{{url('tasks/trash')}}"><i class="fa-regular fa-trash-can"></i> Trash</a> --}}

    <div class="row mt-5">
        <div class="col-md-2">
            <select class="filter-status form-select" id="filter-status">
                <option value="all">All</option>
                <option value="to do">To Do</option>
                <option value="in progress">In Progress</option>
                <option value="done">Done</option>
            </select>
        </div>
    </div>

    <table class="table table-bordered table-striped tasks-table mt-3" id="tasks-table">
        <thead>
            <tr>
                <th>Title</th>
                <th>Content</th>
                <th>Image</th>
                <th>Status</th>
                <th>Draft/Published</th>
                <th>Created</th>
                <th>Updated</th>
                <th>Move to Trash</th>
            </tr>
        </thead>

        <tbody>
            @if (count($tasks) > 0)
                @foreach ($tasks as $task)
                <tr>
                    <td>{{ ucwords($task->title) }}</td>
                    <td>{{ ucwords($task->content) }}</td>
                    <td class="d-flex mx-auto">
                        @php
                            $image = str_replace('public', 'storage', $task->image);
                        @endphp

                        @if(!empty($image))
                            <img src="{{asset($image)}}" alt="image" class="d-block mx-auto img-fluid img-preview" style="width: 150px;height:150px;">
                        @endif
                    </td>
                    <td>
                        <select class="status form-select" id="status" data-id="{{$task->id}}">
                            <option value="to do" {{ $task->status === 'to do' ? 'selected' : '' }}>To Do</option>
                            <option value="in progress" {{ $task->status === 'in progress' ? 'selected' : '' }}>In Progress</option>
                            <option value="done" {{ $task->status === 'done' ? 'selected' : '' }}>Done</option>
                        </select>
                    </td>
                    <td>
                        <div class="form-check form-switch">
                            <input class="form-check-input is_published" type="checkbox" id="is_published" {{ $task->is_published ? 'checked' : '' }} data-id="{{$task->id}}">
                            <label class="form-check-label" for="is_published">
                                {{ $task->is_published ? 'Published' : 'Draft' }}
                            </label>
                        </div>

                    </td>
                    <td>{{ date('m/d/y', strtotime($task->created_at))}}</td>
                    <td>{{ date('m/d/y', strtotime($task->updated_at))}}</td>
                    <td class="d-flex mx-auto">
                        <a href="{{ url('tasks/' . $task->id . '/edit') }}" class="btn btn-outline-primary btn-sm btn-edit" id="btn-edit">
                            <i class="fa-regular fa-pen-to-square"></i>
                        </a>
                        <a href="javascript:void(0)" data-id="{{$task->id}}" class="btn btn-outline-danger btn-sm btn-trash" id="btn-trash">
                            <i class="fa-regular fa-trash-can"></i>
                        </a>

                    </td>
                </tr>
                @endforeach
            @else
                <tr>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td class="text-center">No records found.</td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
            @endif
        </tbody>
    </table>
</div>

<script src="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/umd/simple-datatables.min.js" crossorigin="anonymous"></script>
<script>
    window.addEventListener('DOMContentLoaded', event => {
        const task_table = document.getElementById('tasks-table');
        let table_instance;

        if (task_table) {
            table_instance = new simpleDatatables.DataTable(task_table);
        }

        $(document).on('change', '.filter-status', function() {
            let status = $(this).val()

            var formData = new FormData();
                formData.append('_token', "{{ csrf_token() }}");
                formData.append('status', status);

                $.ajax({
                    url: '{{ url('tasks/getTaskbyStatus') }}',
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        let html = ''

                        if (response.length > 0) {
                            $.each(response, function(index, val) {

                                var image = val.image
                                var image_html = ''

                                if (image && typeof image !== "undefined") {
                                    image = image.replace('public', 'storage');
                                    image_html = `<img src="${image}" alt="image" class="d-block mx-auto img-fluid img-preview" style="width: 150px;height:150px;">`
                                }

                                html += `
                                <tr>
                                    <td>${val.title}</td>
                                    <td>${val.content}</td>
                                    <td>
                                        ${image_html}
                                    </td>
                                    <td>
                                        <select class="status form-select" id="status" data-id="${val.id}">
                                            <option value="to do" ${val.status === 'to do' ? 'selected' : ''}>To Do</option>
                                            <option value="in progress" ${val.status === 'in progress' ? 'selected' : ''}>In Progress</option>
                                            <option value="done" ${val.status === 'done' ? 'selected' : ''}>Done</option>
                                        </select>
                                    </td>

                                    <td>
                                        <div class="form-check form-switch">
                                            <input class="form-check-input is_published" type="checkbox" id="is_published" ${val.is_published ? 'checked' : '' } data-id="${val.id}">
                                            <label class="form-check-label" for="is_published">
                                                ${val.is_published ? 'Published' : 'Draft'}
                                            </label>
                                        </div>

                                    </td>
                                    <td>${moment(val.created_at).format("MM/DD/YY")}</td>
                                    <td>${moment(val.updated_at).format("MM/DD/YY")}</td>
                                    <td class="d-flex mx-auto">
                                        <a href="tasks/${val.id}/edit" class="btn btn-outline-primary btn-sm btn-edit" id="btn-edit">
                                            <i class="fa-regular fa-pen-to-square"></i>
                                        </a>
                                        <a href="javascript:void(0)" data-id="${val.id}" class="btn btn-outline-danger btn-sm btn-trash" id="btn-trash">
                                            <i class="fa-regular fa-trash-can"></i>
                                        </a>
                                    </td>
                                </tr>
                                `
                            })
                        } else {
                            html += `<tr>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td class="text-center">No records found.</td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                    </tr>`
                        }

                        $('.tasks-table > tbody').html(html)

                        // Destroy and create new instance of data table
                        table_instance.destroy();
                        $('.tasks-table > tbody').html(html);
                        table_instance = new simpleDatatables.DataTable(task_table);
                        
                    },
                    error: function(xhr, status, error) {
                        var result = JSON.parse(xhr.responseText)
                        console.log(result)
                    }
                })
        });
    });

  
    $(document).on('change', '.status', function() {
        let id     = $(this).data('id')
        let status = $(this).val()

        var formData = new FormData();
            formData.append('_token', "{{ csrf_token() }}");
            formData.append('id', parseInt(id));
            formData.append('status', status);

            $.ajax({
                url: '{{ url('tasks/updateStatus') }}',
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    if (response.code == "200") {
                        alert('Status updated');
                    }
                },
                error: function(xhr, status, error) {
                    var result = JSON.parse(xhr.responseText)
                    console.log(result)
                }
            })
    });

    $(document).on('change', '.is_published', function() {
        let id           = $(this).data('id')
        var is_published = $(this).is(':checked') ? 1 : 0;
        
        if (is_published === 1) {
            $(this).next('label').text('Published');
        } else {
            $(this).next('label').text('Draft');
        }

        var formData = new FormData();
            formData.append('_token', "{{ csrf_token() }}");
            formData.append('id', parseInt(id));
            formData.append('is_published', is_published);

            $.ajax({
                url: '{{ url('tasks/updateState') }}',
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    if (response.code == "200") {
                        alert('Publication state updated');
                    }
                },
                error: function(xhr, status, error) {
                    var result = JSON.parse(xhr.responseText)
                    console.log(result)
                }
            })
    });

    $(document).on('click', '.btn-trash', function() {
        let id           = $(this).data('id')

        var formData = new FormData();
            formData.append('_token', "{{ csrf_token() }}");
            formData.append('id', parseInt(id));

            $.ajax({
                url: '{{ url('tasks/trash') }}',
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    if (response.code == "200") {
                        alert(response.message);
                        window.location.href = "{{url('/tasks')}}";
                    }
                },
                error: function(xhr, status, error) {
                    var result = JSON.parse(xhr.responseText)
                    console.log(result)
                }
            })
    });
</script>
@endsection