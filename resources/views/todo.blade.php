<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laravel Simple To-Do App</title>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background-color: #f3f4f6;
            margin: 0;
            padding: 20px;
            color: #333;
        }

        .container {
            max-width: 650px;
            margin: 0 auto 25px auto;
            background: #fff;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 25px;
            border-bottom: 2px solid #f3f4f6;
            padding-bottom: 15px;
        }

        .welcome-text {
            font-size: 1.1rem;
            color: #4b5563;
            font-weight: 500;
        }

        .btn-logout {
            background-color: #ef4444;
            color: white;
            border: none;
            padding: 8px 16px;
            border-radius: 6px;
            cursor: pointer;
            font-weight: 600;
            text-decoration: none;
            font-size: 0.9rem;
        }

        h2 {
            color: #1f2937;
            margin-top: 0;
            font-size: 1.8rem;
            font-weight: 700;
        }

        .alert-success {
            background-color: #ecfdf5;
            border: 1px solid #6ee7b7;
            color: #047857;
            padding: 12px 15px;
            border-radius: 8px;
            margin-bottom: 20px;
        }

        .alert-error {
            background-color: #fef2f2;
            border: 1px solid #fca5a5;
            color: #b91c1c;
            padding: 12px 15px;
            border-radius: 8px;
            margin-bottom: 20px;
        }

        .input-group {
            display: flex;
            gap: 10px;
            margin-bottom: 25px;
        }

        .input-control {
            flex: 1;
            padding: 12px;
            border: 1px solid #d1d5db;
            border-radius: 8px;
            font-size: 1rem;
            outline: none;
        }

        .btn-add {
            background-color: #4f46e5;
            color: white;
            border: none;
            padding: 12px 24px;
            border-radius: 8px;
            cursor: pointer;
            font-weight: 600;
            font-size: 1rem;
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
            padding: 16px;
            background: #f9fafb;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
        }

        .completed .task-title {
            text-decoration: line-through;
            color: #9ca3af;
        }

        .task-text {
            font-size: 1.05rem;
            font-weight: 500;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 12px;
            flex: 1;
        }

        .task-actions {
            display: flex;
            gap: 12px;
            font-size: 0.9rem;
        }

        .link-edit {
            color: #2563eb;
            text-decoration: none;
            font-weight: 600;
        }

        .btn-del {
            background: none;
            border: none;
            color: #dc2626;
            cursor: pointer;
            font-weight: 600;
            font-size: 0.9rem;
        }

        .token-box {
            background-color: #0f172a;
            color: #38bdf8;
            padding: 15px;
            border-radius: 8px;
            font-family: monospace;
            font-size: 0.85rem;
            word-break: break-all;
            max-height: 120px;
            overflow-y: auto;
            border-left: 4px solid #3b82f6;
        }

        .payload-box {
            background-color: #1e293b;
            color: #fbbf24;
            padding: 15px;
            border-radius: 8px;
            font-family: monospace;
            font-size: 0.85rem;
            overflow-x: auto;
            border-left: 4px solid #fbbf24;
        }

        .section-badge {
            display: inline-block;
            padding: 4px 8px;
            font-size: 0.75rem;
            font-weight: 700;
            border-radius: 4px;
            text-transform: uppercase;
            margin-bottom: 10px;
        }

        .badge-active {
            background-color: #e0e7ff;
            color: #4338ca;
        }

        .badge-external {
            background-color: #ffedd5;
            color: #9a3412;
            float: right;
        }

        .section-title {
            font-size: 1.2rem;
            color: #111827;
            font-weight: 700;
            margin-top: 30px;
            margin-bottom: 15px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .contact-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
            font-size: 0.9rem;
        }

        .contact-table th,
        .contact-table td {
            padding: 10px;
            text-align: left;
            border-bottom: 1px solid #e5e7eb;
        }

        .contact-table th {
            background-color: #f9fafb;
            color: #4b5563;
            font-weight: 600;
        }
    </style>
</head>

<body>

    @if(session('success'))
    <div class="alert-success" style="max-width: 650px; margin: 0 auto 20px auto;">{{ session('success') }}</div>
    @endif
    @if(session('api_error'))
    <div class="alert-error" style="max-width: 650px; margin: 0 auto 20px auto;">{{ session('api_error') }}</div>
    @endif

    <div class="container">
        <div class="header">
            <div class="welcome-text">Welcome, <strong>{{ Auth::user()->name ?? 'User' }}</strong></div>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="btn-logout">Logout</button>
            </form>
        </div>

        <h2>Tasks To-Do</h2>

        <form action="{{ route('tasks.store') }}" method="POST">
            @csrf
            <div class="input-group">
                <input type="text" name="title" class="input-control" placeholder="Enter a new task..." required autocomplete="off">
                <button type="submit" class="btn-add">Add Task</button>
            </div>
        </form>

        <div class="task-list">
            @forelse($tasks as $task)
            <div class="task-item {{ $task->is_completed ? 'completed' : '' }}">
                <form action="{{ route('tasks.toggle', $task->id) }}" method="POST" style="display:inline;" id="toggle-{{ $task->id }}">
                    @csrf
                    @method('PATCH')
                    <div class="task-text" onclick="document.getElementById('toggle-{{ $task->id }}').submit();">
                        <input type="checkbox" {{ $task->is_completed ? 'checked' : '' }} style="cursor:pointer;">
                        <div>
                            <div class="task-title" style="font-size: 1.05rem; font-weight: 600; color: #1f2937;">
                                {{ $task->title }}
                            </div>
                            <small style="color: #4f46e5; font-size: 0.8rem; display:block; margin-top:4px; font-family: monospace;">
                                @php
                                $output = $task->title;
                                if (class_exists('\Rupes\JetConverter\JetConverter')) {
                                $reverter = new \Rupes\JetConverter\JetConverter();
                                if (method_exists($reverter, 'reverseString')) $output = $reverter->reverseString($task->title);
                                elseif (method_exists($reverter, 'reversedString')) $output = $reverter->reversedString($task->title);
                                elseif (method_exists($reverter, 'convert')) $output = $reverter->convert($task->title);
                                else $output = strrev($task->title);
                                } else {
                                $output = strrev($task->title);
                                }
                                @endphp
                                Package Output: {{ $output }}
                            </small>
                        </div>
                    </div>
                </form>

                <div class="task-actions">
                    <a href="{{ route('tasks.edit', $task->id) }}" class="link-edit">Edit</a>
                    <form action="{{ route('tasks.destroy', $task->id) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn-del">Delete</button>
                    </form>
                </div>
            </div>
            @empty
            <div style="text-align:center; color:#6b7280; padding:20px; font-style:italic;">No tasks recorded.</div>
            @endforelse
        </div>
    </div>

    <div class="container">
        <span class="section-badge badge-active">Active</span>
        <div class="section-title" style="margin-top:0;">Secure Synchronization Token</div>
        <div class="token-box">{{ $secureJwtToken ?? 'No Token Generated' }}</div>
    </div>

    <div class="container">
        <div class="section-title" style="margin-top:0;">Decoded Task Payload Array</div>
        <div class="payload-box">
            <pre>{{ json_encode($taskPayload ?? [], JSON_PRETTY_PRINT) }}</pre>
        </div>
    </div>

    <div class="container">
        <div class="section-title" style="margin-top:0;">Create External Contact (cURL POST)</div>
        <form action="{{ route('contacts.store') }}" method="POST" style="margin-bottom: 20px;">
            @csrf
            <input type="text" name="name" class="input-control" placeholder="Contact Full Name" required style="width:100%; margin-bottom:12px; box-sizing:border-box;">
            <input type="email" name="email" class="input-control" placeholder="Email Address" required style="width:100%; margin-bottom:12px; box-sizing:border-box;">
            <input type="text" name="phone" class="input-control" placeholder="Phone Number" required style="width:100%; margin-bottom:15px; box-sizing:border-box;">
            <button type="submit" class="btn-add" style="width:100%;">Post to MockAPI Stream</button>
        </form>

        <div class="section-title">
            <span>Live MockAPI Stream (cURL GET)</span>
            <span class="section-badge badge-external">External API</span>
        </div>

        @if(!empty($externalContacts) && count($externalContacts) > 0)
        <table class="contact-table">
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
                    <td>{{ $contact['name'] ?? $contact['firstName'] ?? 'Unnamed Profile' }}</td>
                    <td>{{ $contact['Email'] ?? $contact['email'] ?? 'No Email Linked' }}</td>
                    <td>{{ $contact['phone'] ?? $contact['phoneNumber'] ?? 'No Phone' }}</td>
                    <td>
                        @if(isset($contact['id']) && $contact['id'] !== '0')
                        <form action="{{ route('wipeContact', $contact['id']) }}" method="POST" onsubmit="return confirm('Are you sure?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" style="color:#dc2626; cursor:pointer; font-weight:bold; border:none; background:none;">Wipe</button>
                        </form>
                        @else
                        <button type="button" style="color:#9ca3af; cursor:not-allowed; border:none; background:none;" disabled>Unavailable</button>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @else
        <div style="text-align:center; color:#6b7280; padding:10px 0;">No active users found on MockAPI endpoints.</div>
        @endif
    </div>

</body>

</html>