<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laravel Simple To-Do App</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f4f6f9;
            margin: 0;
            padding: 40px 20px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            box-sizing: border-box;
        }

        .todo-container {
            background-color: #ffffff;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            width: 100%;
            max-width: 500px;
            box-sizing: border-box;
        }

        h2 {
            margin-top: 0;
            color: #333;
            font-size: 24px;
            font-weight: 700;
            border-bottom: 2px solid #eaeaea;
            padding-bottom: 10px;
        }

        .input-group {
            display: flex;
            gap: 10px;
            margin-bottom: 25px;
        }

        .input-group input {
            flex: 1;
            padding: 10px 14px;
            border: 1px solid #ccc;
            border-radius: 6px;
            font-size: 14px;
            transition: border-color 0.2s;
        }

        .input-group input:focus {
            outline: none;
            border-color: #4f46e5;
        }

        .btn-add {
            background-color: #4f46e5;
            color: white;
            border: none;
            padding: 10px 16px;
            border-radius: 6px;
            font-weight: 600;
            cursor: pointer;
            transition: background 0.2s;
        }

        .btn-add:hover {
            background-color: #4338ca;
        }

        .task-list {
            display: flex;
            flex-direction: column;
            gap: 12px;
        }

        .task-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background-color: #fafafa;
            padding: 12px 16px;
            border-radius: 8px;
            border-left: 4px solid #cbd5e1;
            transition: transform 0.15s, box-shadow 0.15s;
        }

        .task-item:hover {
            transform: translateY(-2px);
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
        }

        .task-item.completed {
            border-left-color: #10b981;
            background-color: #f0fdf4;
        }

        .task-item.completed .task-title {
            text-decoration: line-through;
            color: #94a3b8;
        }

        .task-title-wrapper {
            display: flex;
            align-items: center;
            gap: 10px;
            flex: 1;
            cursor: pointer;
        }

        .task-title {
            font-size: 15px;
            color: #334155;
            word-break: break-word;
        }

        .action-buttons {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .btn-edit {
            color: #2563eb;
            text-decoration: none;
            font-size: 13px;
            font-weight: 600;
            padding: 6px 12px;
            border-radius: 4px;
            transition: background 0.2s;
        }

        .btn-edit:hover {
            background-color: #eff6ff;
        }

        .btn-delete {
            background: none;
            border: none;
            color: #dc2626;
            font-size: 13px;
            font-weight: 600;
            padding: 6px 12px;
            border-radius: 4px;
            cursor: pointer;
            transition: background 0.2s;
        }

        .btn-delete:hover {
            background-color: #fef2f2;
        }

        .toggle-form {
            display: inline;
        }

        .jwt-dashboard {
            width: 100%;
            max-width: 500px;
            margin-top: 25px;
            padding: 20px;
            background-color: #ffffff;
            border: 1px solid #e5e7eb;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
            box-sizing: border-box;
        }

        .jwt-header {
            margin-top: 0;
            color: #0f172a;
            font-size: 16px;
            font-weight: 700;
            display: flex;
            align-items: center;
            justify-content: space-between;
            border-bottom: 1px solid #f1f5f9;
            padding-bottom: 10px;
            margin-bottom: 12px;
        }

        .jwt-badge {
            background-color: #e0e7ff;
            color: #4f46e5;
            padding: 3px 8px;
            border-radius: 4px;
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
        }

        .jwt-terminal {
            background-color: #1e293b;
            color: #38bdf8;
            padding: 14px;
            border-radius: 8px;
            font-family: 'Courier New', Courier, monospace;
            font-size: 12px;
            word-break: break-all;
            max-height: 120px;
            overflow-y: auto;
            line-height: 1.5;
            border: 1px solid #334155;
        }

        .decoded-output {
            background-color: #f8fafc;
            border: 1px solid #e2e8f0;
            padding: 12px;
            border-radius: 8px;
            font-family: 'Courier New', Courier, monospace;
            font-size: 12px;
            color: #334155;
            margin: 0;
            max-height: 150px;
            overflow-y: auto;
        }

        .user-bar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 1px solid #e5e7eb;
        }

        .user-info {
            font-size: 14px;
            font-weight: 600;
            color: #334155;
        }

        .btn-logout {
            background-color: #ef4444;
            color: white;
            border: none;
            padding: 8px 14px;
            border-radius: 6px;
            font-size: 13px;
            font-weight: 600;
            cursor: pointer;
            transition: background 0.2s;
        }

        .btn-logout:hover {
            background-color: #dc2626;
        }

        .success-message {
            background-color: #dcfce7;
            color: #166534;
            padding: 10px;
            border-radius: 6px;
            margin-bottom: 15px;
            font-size: 14px;
        }
    </style>
</head>

<body>

    <div class="todo-container">

        <div class="user-bar">

            <div class="user-info">
                Welcome, {{ auth()->user()->name }}
            </div>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="btn-logout">
                    Logout
                </button>
            </form>

        </div>

        <h2>Tasks To-Do</h2>

        @if(session('success'))
        <div class="success-message">
            {{ session('success') }}
        </div>
        @endif

        <form action="{{ route('tasks.store') }}" method="POST" class="input-group">
            @csrf
            <input type="text" name="title" placeholder="Enter a new task..." required autocomplete="off">
            <button type="submit" class="btn-add">Add Task</button>
        </form>

        <div class="task-list">
            @php
            // Instantiate the JetConverter service to reverse strings on demand
            $reverter = new \Rupes\JetConverter\JetConverter();
            @endphp

            @forelse($tasks as $task)
            <div class="task-item {{ $task->is_completed ? 'completed' : '' }}">

                <form action="{{ route('tasks.toggle', $task->id) }}" method="POST" class="toggle-form" id="toggle-{{ $task->id }}">
                    @csrf
                    @method('PATCH')
                    <div class="task-title-wrapper" onclick="document.getElementById('toggle-{{ $task->id }}').submit();">
                        <input type="checkbox" {{ $task->is_completed ? 'checked' : '' }} style="cursor: pointer;">

                        <div style="display: flex; flex-direction: column;">
                            <span class="task-title">{{ $reverter->convert($task->title) }}</span>

                            <small style="color: #94a3b8; font-size: 11px; margin-top: 2px;">
                                Package Output: {{ $task->title }}
                            </small>
                        </div>
                    </div>
                </form>

                <div class="action-buttons">
                    <a href="{{ route('tasks.edit', $task->id) }}" class="btn-edit">Edit</a>

                    <form action="{{ route('tasks.destroy', $task->id) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn-delete">Delete</button>
                    </form>
                </div>

            </div>
            @empty
            <p style="color: #94a3b8; text-align: center; font-size: 14px;">No tasks available. Add one above!</p>
            @endforelse
        </div>
    </div>

    <div class="jwt-dashboard">
        <div class="jwt-header">
            <span>Secure Synchronization Token</span>
            <span class="jwt-badge">JwtConverter Active</span>
        </div>
        <!-- <p style="font-size: 13px; color: #64748b; margin: 0 0 12px 0; line-height: 1.4;">
            This cryptographically signed string is generated automatically by your custom package using the task list payload array:
        </p> -->
        <div class="jwt-terminal" style="margin-bottom: 20px;">
            {{ $secureJwtToken ?? 'No sync token has been loaded for this task array execution.' }}
        </div>

        @php
        $decoderPackage = new \Intern\JwtConverter\JwtConverter();
        $extractedPayload = isset($secureJwtToken) && !str_contains($secureJwtToken, 'pending package logic')
        ? $decoderPackage->decode($secureJwtToken)
        : null;
        @endphp

        <div class="jwt-header" style="border-top: 1px solid #f1f5f9; padding-top: 15px; margin-top: 15px;">
            <span>Decoded Data Array</span>
            <span class="jwt-badge" style="background-color: #d1fae5; color: #065f46;">Verified</span>
        </div>
        <!-- <p style="font-size: 13px; color: #64748b; margin: 0 0 12px 0; line-height: 1.4;">
            Your package read the signed token string directly from your application flow, validated its payload integrity, and parsed it back out:
        </p> -->
        <pre class="decoded-output">{{
            $extractedPayload ? print_r($extractedPayload, true) : 'Waiting for a valid token string sequence...' 
        }}</pre>
    </div>

</body>

</html>