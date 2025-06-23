@extends("admin.adminlayout")
@section("adminbody")
<div class="container mt-5">
    <h2 class="mb-4">Create a New To-Do</h2>

    <form action="{{ route('todo.store') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label for="title" class="form-label">To-Do Title</label>
            <input type="text" name="title" id="title" class="form-control" placeholder="Enter title" required>
        </div>

        <div class="mb-3">
            <label for="message" class="form-label">Message</label>
            <textarea name="message" id="message" class="form-control" rows="4" placeholder="Write your task details..." required></textarea>
        </div>

        <button type="submit" class="btn btn-primary">Add To-Do</button>
    </form>

</div>
<script src="../js/admin/posttodo.js"></script>
@endsection
