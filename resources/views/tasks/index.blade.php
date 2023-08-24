@extends('home')

@section('body')
    <a href="{{ route('task.add') }}" class="btn btn-primary">Create Task</a>
<task-list></task-list>

@endsection
