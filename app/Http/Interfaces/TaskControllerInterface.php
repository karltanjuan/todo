<?php

namespace App\Http\Interfaces;

use Illuminate\Http\Request;
use App\Models\Task;
use App\Http\Requests\AddTaskRequest;
use App\Http\Requests\EditTaskRequest;

interface TaskControllerInterface
{
    public function index();
    public function list();
    public function getTaskbyStatus(Request $request);
    public function create();
    public function store(AddTaskRequest $request);
    public function edit(Task $task);
    public function update(EditTaskRequest $request);
    public function updateStatus(Request $request);
    public function updateState(Request $request);
    public function destroy(Task $task);
}