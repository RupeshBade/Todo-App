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

        .todo-container,
        .jwt-dashboard {
            background-color: #ffffff;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            width: 100%;
            max-width: 600px;
            box-sizing: border-box;
            margin-bottom: 25px;
        }

        h2,
        h3 {
            margin-top: 0;
            color: #333;
            font-weight: 700;
            border-bottom: 2px solid #eaeaea;
            padding-bottom: 10px;
        }

        h2 {
            font-size: 24px;
        }

        h3 {
            font-size: 18px;
            margin-top: 15px;
        }

        .input-group,
        .contact-form {
            display: flex;
            gap: 10px;
            margin-bottom: 25px;
        }

        .contact-form {
            flex-direction: column;
            background: #f8fafc;
            padding: 15px;
            border-radius: 8px;
            border: 1px solid #e2e8f0;
        }

        .input-group input,
        .contact-form input {
            flex: 1;
            padding: 10px 14px;
            border: 1px solid #ccc;
            border-radius: 6px;
            font-size: 14px;
        }

        .btn-add {
            background-color: #4f46e5;
            color: white;
            border: none;
            padding: 10px 16px;
            border-radius: 6px;
            font-weight: 600;
            cursor: pointer;
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
        }

        .task-item.completed {
            border-left-color: #10b981;
            background-color: #f0fdf4;
        }

        .task-item.completed .task-title {
            text-decoration: line-through;
            color: #94a3b8;
        }

        .action-buttons {
            display: flex;
            gap: 8px;
        }

        .btn-edit {
            color: #2563eb;
            text-decoration: none;
            font-size: 13px;
            font-weight: 600;
            padding: 6px 12px;
        }

        .btn-delete,
        .btn-action-del {
            background: none;
            border: none;
            color: #dc2626;
            font-size: 13px;
            font-weight: 600;
            cursor: pointer;
            padding: 4px 8px;
            border-radius: 4px;
        }

        .btn-action-del:hover {
            background-color: #fef2f2;
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
        }

        .decoded-output {
            background-color: #f8fafc;
            border: 1px solid #e2e8f0;
            padding: 12px;
            border-radius: 8px;
            font-family: 'Courier New', Courier, monospace;
            font-size: 12px;
            max-height: 220px;
            overflow-y: auto;
            white-space: pre-wrap;
        }

        .user-bar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 1px solid #e5e7eb;
        }

        .success-message {
            background-color: #dcfce7;
            color: #166534;
            padding: 10px;
            border-radius: 6px;
            margin-bottom: 15px;
            font-size: 14px;
            width: 100%;
            box-sizing: border-box;
        }

        .error-message {
            background-color: #fef2f2;
            color: #991b1b;
            border: 1px solid #fca5a5;
            padding: 10px;
            border-radius: 6px;
            margin-bottom: 15px;
            font-size: 14px;
            width: 100%;
            box-sizing: border-box;
        }

        .api-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
            font-size: 13px;
        }

        .api-table th {
            background-color: #f8fafc;
            color: #64748b;
            text-align: left;
            padding: 10px;
            font-weight: 600;
            border-bottom: 1px solid #e2e8f0;
        }

        .api-table td {
            padding: 10px;
            color: #334155;
            border-bottom: 1px solid #f1f5f9;
        }
    </style>
</head>

<body>

    @if(session('success'))
    <div class="success-message">
        {{ session('success') }}
    </div>
    @endif

    @if(session('api_error'))
    <div class="error-message">
        {{ session('api_error') }}
    </div>
    @endif

    <div class="todo-container">
        <div class="user-bar">
            <div class="user-info">Welcome, {{ auth()->user()->name }}</div>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" style="background-color:#ef4444; color:white; border:none; padding:8px 14px; border-radius:6px; cursor:pointer; font-weight:600;">Logout</button>
            </form>
        </div>

        <h2>Tasks To-Do</h2>

        <form action="{{ route('tasks.store') }}" method="POST" class="input-group">
            @csrf
            <input type="text" name="title" placeholder="Enter a new task..." required autocomplete="off">
            <button type="submit" class="btn-add">Add Task</button>
        </form>

        <div class="task-list">
            @php $reverter = new \Rupes\JetConverter\JetConverter(); @endphp
            @forelse($tasks as $task)
            <div class="task-item {{ $task->is_completed ? 'completed' : '' }}">
                <form action="{{ route('tasks.toggle', $task->id) }}" method="POST" style="display:inline;" id="toggle-{{ $task->id }}">
                    @csrf
                    @method('PATCH')
                    <div style="display:flex; align-items:center; gap:10px; cursor:pointer;" onclick="document.getElementById('toggle-{{ $task->id }}').submit();">
                        <input type="checkbox" {{ $task->is_completed ? 'checked' : '' }}>
                        <div style="display: flex; flex-direction: column;">
                            <span class="task-title">{{ $reverter->convert($task->title) }}</span>
                            <small style="color: #94a3b8; font-size: 11px;">Package Output: {{ $task->title }}</small>
                        </div>
                    </div>
                </form>
                <div class="action-buttons">
                    <a href="{{ route('tasks.edit', $task->id) }}" class="btn-edit">Edit</a>
                    <form action="{{ route('tasks.destroy', $task->id) }}" method="POST" style="display:inline;">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn-delete">Delete</button>
                    </form>
                </div>
            </div>
            @empty
            <p style="color: #94a3b8; text-align: center; font-size: 14px;">No tasks available.</p>
            @endforelse
        </div>
    </div>

    <div class="todo-container">
        <h3>Create External Contact (cURL POST)</h3>
        <form action="{{ route('contacts.store') }}" method="POST" class="contact-form">
            @csrf
            <input type="text" name="name" placeholder="Contact Full Name (e.g. Anil Sharma)" required>
            <input type="email" name="email" placeholder="Email Address" required>
            <input type="text" name="phone" placeholder="Phone Number" required>
            <button type="submit" class="btn-add" style="margin-top:5px;">Send POST Request</button>
        </form>
    </div>

    <div class="jwt-dashboard">
        <div class="jwt-header">
            <span>Secure Synchronization Token</span>
            <span class="jwt-badge">Active</span>
        </div>
        <div class="jwt-terminal" style="margin-bottom: 20px;">
            {{ $secureJwtToken }}
        </div>

        @php
        $decoderPackage = new \Intern\JwtConverter\JwtConverter();
        try {
        $decodedData = $decoderPackage->decode($secureJwtToken);
        } catch (\Exception $e) {
        $decodedData = ['error' => $e->getMessage()];
        }
        @endphp

        <div class="jwt-header">
            <span>Decoded Data Array</span>
            <span class="jwt-badge" style="background-color: #d1fae5; color: #065f46;">Verified</span>
        </div>
        <pre class="decoded-output">{{ print_r($decodedData, true) }}</pre>

        <div class="jwt-header" style="margin-top: 15px;">
            <span>Live JSONPlaceholder Users (cURL GET)</span>
            <span class="jwt-badge" style="background-color: #fef3c7; color: #92400e;">External API</span>
        </div>

        @if(empty($externalContacts))
        <p style="font-size: 13px; color: #64748b; text-align: center; margin: 10px 0;">No external contacts found or connection offline.</p>
        @else
        <div style="max-height: 250px; overflow-y: auto;">
            <table class="api-table">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($externalContacts as $contact)
                    <tr>
                        <td>{{ $contact['firstName'] }}</td>
                        <td>{{ $contact['email'] }}</td>
                        <td>{{ $contact['phoneNumber'] }}</td>
                        <td>
                            <form action="{{ route('contacts.destroy', $contact['subjectId']) }}" method="POST" style="display:inline;">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn-action-del">Wipe</button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @endif
    </div>

</body>

</html>