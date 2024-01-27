<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;
use App\Http\Requests\AddTaskRequest;
use App\Http\Requests\EditTaskRequest;
use Illuminate\Validation\ValidationException;
use App\Http\Interfaces\TaskControllerInterface;

class TaskController extends Controller implements TaskControllerInterface
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $tasks = Task::where('user_id', auth()->user()->id)
                     ->orderBy('title', 'asc')
                     ->orderBy('created_at', 'asc')
                     ->get();
                
        return view('tasks.index', compact('tasks'));
    }

    /**
     * Show a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function list()
    {
        $tasks = Task::where('user_id', auth()->user()->id)
                     ->orderBy('title', 'asc')
                     ->orderBy('created_at', 'desc')
                     ->get();

        return response()->json($tasks);
    }

    public function getTaskbyStatus(Request $request)
    {
        $status = $request->input('status');

        if ($status != 'all') {
            $tasks = Task::where('status', $request->input('status'));
        } else {
            $tasks = Task::query();
        }

        $tasks = $tasks->where('user_id', auth()->user()->id)
                       ->orderBy('title', 'asc')
                       ->orderBy('created_at', 'asc')
                       ->get();

        return response()->json($tasks);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('tasks.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(AddTaskRequest $request)
    {
        try {

            $image_file = $request->file('image');
            $image_path = null;

            if ($image_file) {
                $allowed_types = ['jpg', 'jpeg', 'png', 'gif'];
                $extension = strtolower($image_file->getClientOriginalExtension());

                if (!in_array($extension, $allowed_types)) {
                    throw ValidationException::withMessages(['image' => 'Invalid file type. Only images (jpg, jpeg, png, gif) are allowed.']);
                }

                $max_size = 4 * 1024 * 1024; // 4 MB
                if ($image_file->getSize() > $max_size) {
                    throw ValidationException::withMessages(['image' => 'File size exceeds the maximum allowed (4MB).']);
                }

                $image_path = uniqid() . md5(1) . '_' . $image_file->getClientOriginalName();
                $image_path = $request->file('image')->storeAs('public/image', $image_path);

                $old_file = $request->input('old_file');
                if ($old_file) {
                    Storage::delete($old_file);
                }
            }

            $task_data = [
                'user_id'      => auth()->user()->id,
                'title'        => $request->input('title'),
                'content'      => $request->input('content'),
                'status'       => $request->input('status'),
                'is_published' => $request->input('is_published'),
                'is_trashed'   => 0
            ];

            if (!empty($image_path)) {
                $task_data['image'] = $image_path;
            }

            $task = Task::create($task_data);

            if ($task) {
                return response()->json([
                    'message' => 'Task created successfully',
                    'code'    => '200'
                ]);
            }
        } catch (ValidationException $e) {
            return response()->json([
                'errors' => $e->errors(),
            ], 422);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Task  $task
     * @return \Illuminate\Http\Response
     */
    // public function show(Task $task)
    // {
    //     return view('tasks.show');
    // }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Task  $task
     * @return \Illuminate\Http\Response
     */
    public function edit(Task $task)
    {
        if (auth()->user()->id !== $task->user_id) {
            abort(403, 'Unauthorized action.');
        }

        return view('tasks.edit', compact('task'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Models\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(EditTaskRequest $request)
    {
        try {
            $image_file = $request->file('image');
            $image_path = null;

            if ($image_file) {
                $allowed_types = ['jpg', 'jpeg', 'png', 'gif'];
                $extension = strtolower($image_file->getClientOriginalExtension());

                if (!in_array($extension, $allowed_types)) {
                    throw ValidationException::withMessages(['image' => 'Invalid file type. Only images (jpg, jpeg, png, gif) are allowed.']);
                }

                $max_size = 4 * 1024 * 1024; // 4 MB
                if ($image_file->getSize() > $max_size) {
                    throw ValidationException::withMessages(['image' => 'File size exceeds the maximum allowed (4MB).']);
                }

                $image_path = uniqid() . md5(1) . '_' . $image_file->getClientOriginalName();
                $image_path = $request->file('image')->storeAs('public/image', $image_path);

                $old_file = $request->input('old_file');
                if ($old_file) {
                    Storage::delete($old_file);
                }
            }

            $task_data = [
                'title'        => $request->input('title'),
                'content'      => $request->input('content'),
                'status'       => $request->input('status'),
                'is_published' => $request->input('is_published'),
            ];

            if (!empty($image_path)) {
                $task_data['image'] = $image_path;
            }

            $task = Task::where('id', 1)
                    ->where('user_id', auth()->user()->id)
                    ->update($task_data);

            if ($task) {
                return response()->json([
                    'message' => 'Task created successfully',
                    'code'    => '200'
                ]);
            }
        } catch (ValidationException $e) {
            return response()->json([
                'errors' => $e->errors(),
            ], 422);
        }
    }

    /**
     * Update the specified resource (status) in storage.
     *
     * @param  \App\Models\Request $request
     * @return \Illuminate\Http\Response
     */
    public function updateStatus(Request $request)
    {
        $task = Task::where('user_id', auth()->user()->id)
                    ->where('id', $request->input('id'))
                    ->first();

        if (!$task) {
            return response()->json(['message' => 'Task not found or does not belong to the authenticated user'], 404);
        }

        // Update the task status
        $task->status = $request->input('status');
        $task->save();

        return response()->json(['message' => 'Task status updated successfully', 'code' => 200]);
    }

    /**
     * Update the specified resource (is_published) in storage.
     *
     * @param  \App\Models\Request $request
     * @return \Illuminate\Http\Response
     */
    public function updateState(Request $request)
    {
        $task = Task::where('user_id', auth()->user()->id)
                    ->where('id', $request->input('id'))
                    ->first();

        if (!$task) {
        return response()->json(['message' => 'Task not found or does not belong to the authenticated user'], 404);
        }

        // Update the task status
        $task->is_published = (int) $request->input('is_published');
        $task->save();

        return response()->json(['message' => 'Task publication state updated successfully', 'code' => 200]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Task  $task
     * @return \Illuminate\Http\Response
     */
    public function destroy(Task $task)
    {
        // $task->delete();

        if (!$task) {
            return response()->json(['status' => 0]);
        }

        return response()->json(['status' => 1]);
    }
}