@php
// Securely reverse the string back so the user can edit human text
$reverter = new \Rupes\JetConverter\JetConverter();
$readableTitle =($task->title);
@endphp

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Task</title>
</head>

<body style="font-family: sans-serif; display: flex; justify-content: center; align-items: center; min-height: 80vh; background-color: #f9fafb; margin: 0;">

    <div style="background: white; border: 1px solid #e5e7eb; padding: 30px; border-radius: 12px; width: 350px; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);">
        <h3 style="margin-top: 0; color: #111827; font-size: 20px; margin-bottom: 20px;">Edit Task</h3>

        <form action="{{ route('tasks.update', $task->id) }}" method="POST">
            @csrf
            @method('PUT')

            <label style="display: block; font-size: 14px; font-weight: 600; color: #374151; margin-bottom: 6px;">Task Title:</label>

            <input type="text" name="title" value="{{ $readableTitle }}" required
                style="width: 100%; padding: 10px; border: 1px solid #d1d5db; border-radius: 6px; box-sizing: border-box; margin-bottom: 20px; font-size: 15px;">

            <div style="display: flex; justify-content: flex-start; align-items: center;">
                <button type="submit"
                    style="background-color: #4f46e5; color: white; border: none; padding: 10px 16px; border-radius: 6px; font-weight: 600; font-size: 14px; cursor: pointer;">
                    Update Task
                </button>

                <a href="{{ route('tasks.index') }}"
                    style="margin-left: 12px; text-decoration: none; color: #4b5563; font-size: 14px; background-color: #f3f4f6; padding: 10px 16px; border-radius: 6px; font-weight: 600; text-align: center;">
                    Cancel
                </a>
            </div>
        </form>
    </div>

</body>

</html>