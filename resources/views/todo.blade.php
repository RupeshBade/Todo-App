<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>To do App</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap');

        :root {
            --bg-app: #f1f5f9;
            --surface-card: #ffffff;
            --primary: #4f46e5;
            --primary-gradient: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%);
            --primary-hover: #4338ca;
            --primary-light: #f5f3ff;
            --text-main: #0f172a;
            --text-muted: #64748b;
            --border-color: #e2e8f0;
            --danger: #ef4444;
            --danger-light: #fef2f2;
            --success: #10b981;
            --radius-lg: 16px;
            --radius-md: 12px;
            --shadow-sm: 0 1px 3px 0 rgba(0, 0, 0, 0.05);
            --shadow-md: 0 4px 12px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -1px rgba(0, 0, 0, 0.03);
            --shadow-xl: 0 20px 25px -5px rgba(0, 0, 0, 0.05), 0 10px 10px -5px rgba(0, 0, 0, 0.02);
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: var(--bg-app);
            color: var(--text-main);
            min-height: 100vh;
            padding: 0 0 3rem 0;
        }

        /* --- Global Dynamic Navbar --- */
        .global-header {
            background: #ffffff;
            border-bottom: 1px solid var(--border-color);
            position: sticky;
            top: 0;
            z-index: 100;
            box-shadow: var(--shadow-sm);
            margin-bottom: 3rem;
        }

        .header-inner {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 1.5rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            height: 70px;
        }

        .nav-container {
            display: flex;
            gap: 0.25rem;
            height: 100%;
            align-items: center;
        }

        .nav-tab {
            padding: 0.6rem 1.1rem;
            font-weight: 600;
            font-size: 0.9rem;
            color: var(--text-muted);
            text-decoration: none;
            border-radius: var(--radius-md);
            transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
            white-space: nowrap;
        }

        .nav-tab:hover {
            color: var(--primary);
            background: var(--primary-light);
        }

        .nav-tab.active {
            color: #ffffff;
            background: var(--primary-gradient);
            box-shadow: 0 4px 12px rgba(79, 70, 229, 0.25);
        }

        .header-right {
            display: flex;
            align-items: center;
            gap: 1.25rem;
        }

        .user-welcome {
            font-size: 0.9rem;
            color: var(--text-muted);
            background: #f8fafc;
            padding: 0.5rem 1rem;
            border-radius: 30px;
            border: 1px solid var(--border-color);
        }

        .user-welcome strong {
            color: var(--text-main);
            font-weight: 600;
        }

        .btn-logout {
            padding: 0.55rem 1.1rem;
            background: #ffffff;
            color: var(--danger);
            border: 1px solid #fee2e2;
            border-radius: var(--radius-md);
            font-weight: 600;
            cursor: pointer;
            text-decoration: none;
            font-size: 0.85rem;
            transition: all 0.15s ease;
            box-shadow: var(--shadow-sm);
        }

        .btn-logout:hover {
            background: var(--danger);
            color: #ffffff;
            border-color: var(--danger);
            box-shadow: 0 4px 12px rgba(239, 68, 68, 0.2);
        }

        /* --- Container Layout --- */
        .app-container {
            max-width: 1000px;
            margin: 0 auto;
            padding: 0 1rem;
        }

        .card {
            background: var(--surface-card);
            border-radius: var(--radius-lg);
            border: 1px solid var(--border-color);
            box-shadow: var(--shadow-xl);
            padding: 2.5rem;
            margin-bottom: 2rem;
            animation: fadeIn 0.3s ease-out;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        h2 {
            color: var(--text-main);
            font-size: 1.5rem;
            font-weight: 700;
            margin-bottom: 1.75rem;
            letter-spacing: -0.02em;
        }

        /* --- Alerts --- */
        .alert {
            padding: 1rem 1.25rem;
            border-radius: var(--radius-md);
            margin-bottom: 1.5rem;
            font-size: 0.95rem;
            font-weight: 500;
        }

        .alert-success {
            background-color: #ecfdf5;
            border: 1px solid #a7f3d0;
            color: #065f46;
        }

        .alert-error {
            background-color: var(--danger-light);
            border: 1px solid #fca5a5;
            color: #991b1b;
        }

        /* --- Forms & Inputs --- */
        .form-group-row {
            display: flex;
            gap: 0.75rem;
            margin-bottom: 2rem;
        }

        .student-grid-form {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1rem;
            margin-bottom: 1.5rem;
        }

        .input-field {
            flex: 1;
            padding: 0.85rem 1.2rem;
            font-family: inherit;
            font-size: 0.95rem;
            border: 1px solid var(--border-color);
            border-radius: var(--radius-md);
            background-color: #f8fafc;
            color: var(--text-main);
            transition: all 0.2s ease;
        }

        .input-field:focus {
            outline: none;
            border-color: var(--primary);
            background-color: #ffffff;
            box-shadow: 0 0 0 4px rgba(79, 70, 229, 0.12);
        }

        .btn-primary {
            padding: 0.85rem 1.75rem;
            background: var(--primary-gradient);
            color: white;
            border: none;
            border-radius: var(--radius-md);
            font-weight: 600;
            font-size: 0.95rem;
            cursor: pointer;
            transition: all 0.2s ease;
            box-shadow: 0 4px 12px rgba(79, 70, 229, 0.2);
        }

        .btn-primary:hover {
            transform: translateY(-1px);
            box-shadow: 0 6px 16px rgba(79, 70, 229, 0.3);
        }

        /* --- Task System --- */
        .task-list {
            display: flex;
            flex-direction: column;
            gap: 0.75rem;
        }

        .task-item {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 1.25rem;
            background: #ffffff;
            border: 1px solid var(--border-color);
            border-radius: var(--radius-md);
            box-shadow: var(--shadow-sm);
            transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .task-item:hover {
            transform: translateY(-2px);
            border-color: #cbd5e1;
            box-shadow: var(--shadow-md);
        }

        .task-item.completed {
            background: #f8fafc;
            border-color: var(--border-color);
            opacity: 0.75;
        }

        .task-item.completed .task-title {
            text-decoration: line-through;
            color: var(--text-muted);
        }

        .task-left {
            display: flex;
            align-items: center;
            gap: 1rem;
            cursor: pointer;
        }

        .task-checkbox {
            width: 1.25rem;
            height: 1.25rem;
            accent-color: var(--primary);
            cursor: pointer;
            border-radius: 6px;
        }

        .task-title {
            font-weight: 600;
            color: var(--text-main);
            font-size: 1.05rem;
        }

        .package-output-badge {
            display: inline-block;
            margin-top: 0.35rem;
            font-size: 0.75rem;
            color: var(--primary);
            background: var(--primary-light);
            padding: 0.25rem 0.65rem;
            border-radius: 20px;
            font-weight: 600;
            font-family: monospace;
            border: 1px solid rgba(79, 70, 229, 0.1);
        }

        .task-actions {
            display: flex;
            gap: 1rem;
        }

        .action-link {
            text-decoration: none;
            font-size: 0.9rem;
            font-weight: 600;
            background: none;
            border: none;
            cursor: pointer;
            transition: opacity 0.15s ease;
        }

        .action-link:hover {
            opacity: 0.8;
        }

        .action-link.edit {
            color: var(--primary);
        }

        .action-link.delete {
            color: var(--danger);
        }

        /* --- Technical Tables --- */
        .table-responsive {
            overflow-x: auto;
            margin-top: 1.5rem;
            border-radius: var(--radius-md);
            border: 1px solid var(--border-color);
            box-shadow: var(--shadow-sm);
        }

        .modern-table {
            width: 100%;
            border-collapse: collapse;
            text-align: left;
            font-size: 0.95rem;
            background: #ffffff;
        }

        .modern-table th {
            background: #f8fafc;
            padding: 1rem 1.25rem;
            font-weight: 600;
            color: var(--text-muted);
            font-size: 0.8rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            border-bottom: 1px solid var(--border-color);
        }

        .modern-table td {
            padding: 1.1rem 1.25rem;
            border-bottom: 1px solid var(--border-color);
            color: var(--text-main);
        }

        .modern-table tr:last-child td {
            border-bottom: none;
        }

        .modern-table tr:hover td {
            background-color: #faf9ff;
        }

        /* --- Technical Components --- */
        .token-box {
            background: #0f172a;
            color: #38bdf8;
            padding: 1.25rem;
            border-radius: var(--radius-md);
            font-family: monospace;
            font-size: 0.9rem;
            word-break: break-all;
            max-height: 180px;
            overflow-y: auto;
            border: 1px solid #1e293b;
            box-shadow: inset 0 2px 4px rgba(0, 0, 0, 0.2);
        }

        .json-box {
            background: #1e293b;
            color: #f1f5f9;
            padding: 1.5rem;
            border-radius: var(--radius-md);
            font-family: monospace;
            font-size: 0.9rem;
            overflow-x: auto;
            box-shadow: inset 0 2px 4px rgba(0, 0, 0, 0.2);
        }

        .badge {
            display: inline-block;
            padding: 0.35rem 0.65rem;
            font-size: 0.75rem;
            font-weight: 700;
            border-radius: 20px;
            text-transform: uppercase;
            letter-spacing: 0.02em;
        }

        .badge-api {
            background: #ffedd5;
            color: #c2410c;
            border: 1px solid #fed7aa;
        }
    </style>
</head>

<body>

    @php
    $currentPage = request()->query('page', 'tasks');
    @endphp

    <header class="global-header">
        <div class="header-inner">
            <div class="nav-container">
                <a href="?page=tasks" class="nav-tab {{ $currentPage == 'tasks' ? 'active' : '' }}">Workspace Tasks</a>
                <a href="?page=payload" class="nav-tab {{ $currentPage == 'payload' ? 'active' : '' }}">Security Keys</a>
                <a href="?page=contacts" class="nav-tab {{ $currentPage == 'contacts' ? 'active' : '' }}">Contacts Gateway</a>
                <a href="?page=posts" class="nav-tab {{ $currentPage == 'posts' ? 'active' : '' }}">Global Streams</a>
                <a href="?page=students" class="nav-tab {{ $currentPage == 'students' ? 'active' : '' }}">Student Roster</a>
            </div>

            <div class="header-right">
                <div class="user-welcome">Welcome, <strong>{{ Auth::user()->name ?? 'User' }}</strong></div>
                <form method="POST" action="{{ route('logout') }}" onsubmit="return confirm('Are you sure you want to logout?');">
                    @csrf
                    <button type="submit" class="btn-logout">Logout</button>
                </form>
            </div>
        </div>
    </header>

    <div class="app-container">
        @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if(session('api_error'))
        <div class="alert alert-error">{{ session('api_error') }}</div>
        @endif

        @if($currentPage == 'tasks')
        <div class="card">
            <h2>Tasks Management Workspace</h2>

            <form action="{{ route('tasks.store') }}" method="POST">
                @csrf
                <div class="form-group-row">
                    <input type="text" name="title" class="input-field" placeholder="What task are you executing today?" required autocomplete="off">
                    <button type="submit" class="btn-primary">Add Task</button>
                </div>
            </form>

            <div class="task-list">
                @forelse($tasks as $task)
                <div class="task-item {{ $task->is_completed ? 'completed' : '' }}">
                    <form action="{{ route('tasks.toggle', $task->id) }}" method="POST" style="display:inline;" id="toggle-{{ $task->id }}">
                        @csrf
                        @method('PATCH')
                        <div class="task-left" onclick="document.getElementById('toggle-{{ $task->id }}').submit();">
                            <input type="checkbox" class="task-checkbox" {{ $task->is_completed ? 'checked' : '' }}>
                            <div>
                                <div class="task-title">{{ $task->title }}</div>
                                @php
                                $output = $task->title;
                                if (class_exists('\Rupes\JetConverter\JetConverter')) {
                                $reverter = new \Rupes\JetConverter\JetConverter();
                                if (method_exists($reverter, 'reverseString')) $output = $reverter->reverseString($task->title);
                                elseif (method_exists($reverter, 'reversedString')) $output = $reverter->reversedString($task->title);
                                elseif (method_exists($reverter, 'convert')) $output = $reverter->convert($task->title);
                                else $output = strrev($task->title);
                                } else { $output = strrev($task->title); }
                                @endphp
                                <span class="package-output-badge">Package: {{ $output }}</span>
                            </div>
                        </div>
                    </form>

                    <div class="task-actions">
                        <a href="{{ route('tasks.edit', $task->id) }}" class="action-link edit">Edit</a>
                        <form action="{{ route('tasks.destroy', $task->id) }}" method="POST" onsubmit="return confirm('Are you sure?');" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="action-link delete">Delete</button>
                        </form>
                    </div>
                </div>
                @empty
                <div style="text-align:center; color: var(--text-muted); padding:2rem; font-style:italic;">No tasks recorded in this workspace.</div>
                @endforelse
            </div>
        </div>
        @endif

        @if($currentPage == 'payload')
        <div class="card">
            <h2>Secure Synchronization Token</h2>
            <div class="token-box">{{ $secureJwtToken ?? 'No Active Encryption Token' }}</div>
        </div>

        <div class="card">
            <h2>Decoded Claims Array Payload</h2>
            <div class="json-box">
                <pre>{{ json_encode($taskPayload ?? [], JSON_PRETTY_PRINT) }}</pre>
            </div>
        </div>
        @endif

        @if($currentPage == 'contacts')
        <div class="card">
            <h2>Register External Contact Gateway</h2>
            <form action="{{ route('contacts.store') }}" method="POST">
                @csrf
                <div style="display: flex; flex-direction: column; gap: 1rem; margin-bottom: 1.5rem;">
                    <input type="text" name="name" class="input-field" placeholder="Contact Full Name" required>
                    <input type="email" name="email" class="input-field" placeholder="Email Address" required>
                    <input type="text" name="phone" class="input-field" placeholder="Phone Number" required>
                </div>
                <button type="submit" class="btn-primary" style="width: 100%;">Dispatch via cURL POST</button>
            </form>
        </div>

        <div class="card">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem;">
                <h2>Live Microservice Contacts Database</h2>
                <span class="badge badge-api">MockAPI System</span>
            </div>

            @if(!empty($externalContacts) && count($externalContacts) > 0)
            <div class="table-responsive">
                <table class="modern-table">
                    <thead>
                        <tr>
                            <th>Client Identity</th>
                            <th>Email Handle</th>
                            <th>Routing Phone</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($externalContacts as $contact)
                        <tr>
                            <td><strong>{{ $contact['name'] ?? $contact['firstName'] ?? 'Anonymous Profile' }}</strong></td>
                            <td>{{ $contact['Email'] ?? $contact['email'] ?? 'N/A' }}</td>
                            <td>{{ $contact['phone'] ?? $contact['phoneNumber'] ?? 'N/A' }}</td>
                            <td>
                                @if(isset($contact['id']) && $contact['id'] !== '0')
                                <form action="{{ route('wipeContact', $contact['id']) }}" method="POST" onsubmit="return confirm('Wipe database record?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="action-link delete">Wipe</button>
                                </form>
                                @else
                                <span style="color: var(--text-muted);">Locked</span>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @else
            <div style="text-align:center; color: var(--text-muted); padding: 1.5rem;">No active microservice pipeline paths found.</div>
            @endif
        </div>
        @endif

        @if($currentPage == 'posts')
        <div class="card">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem;">
                <h2>Global Public Stream Logs</h2>
                <span class="badge badge-api" style="background:#e2e8f0; color:#334155;">JSONPlaceholder</span>
            </div>

            @if(!empty($publicPosts) && count($publicPosts) > 0)
            <div class="table-responsive">
                <table class="modern-table">
                    <thead>
                        <tr>
                            <th style="width: 80px;">Index</th>
                            <th>Topic Header Title</th>
                            <th>Body Payload Description</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($publicPosts as $posts)
                        <tr>
                            <td><code>#{{ $posts['id'] ?? '?' }}</code></td>
                            <td><strong>{{ $posts['title'] ?? 'Untitled' }}</strong></td>
                            <td style="color: var(--text-muted);">{{ $posts['body'] ?? 'No text provided.' }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @else
            <div style="text-align:center; color: var(--text-muted); padding: 1.5rem;">No internet telemetry packet streams caught.</div>
            @endif
        </div>
        @endif

        @if($currentPage == 'students')
        <div class="card">
            <h2>Add Record into Student Ledger</h2>
            <form action="{{ route('students.store') }}" method="POST">
                @csrf
                <div class="student-grid-form">
                    <input type="text" name="name" class="input-field" placeholder="Student Full Name" required>
                    <input type="text" name="address" class="input-field" placeholder="Physical Address" required>
                    <input type="text" name="college" class="input-field" placeholder="Affiliated College Campus" required>
                    <input type="text" name="level" class="input-field" placeholder="Current Qualification Level" required>
                </div>
                <button type="submit" class="btn-primary" style="width:100%;">Commit to Registry</button>
            </form>
        </div>

        <div class="card">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem;">
                <h2>Verified Student Academic Roster</h2>
                <span class="badge badge-api">MockAPI Core</span>
            </div>

            @if(!empty($externalStudents) && count($externalStudents) > 0)
            <div class="table-responsive">
                <table class="modern-table">
                    <thead>
                        <tr>
                            <th>Student Demographics</th>
                            <th>Assigned Campus</th>
                            <th>Stratum</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($externalStudents as $student)
                        <tr>
                            <td>
                                <div style="font-weight:600; color:var(--text-main);">{{ $student['name'] ?? 'Undefined Profile' }}</div>
                                <div style="font-size:0.8rem; color:var(--text-muted);">Loc: {{ $student['address'] ?? 'Unknown' }}</div>
                            </td>
                            <td>{{ $student['college'] ?? 'N/A' }}</td>
                            <td><span class="package-output-badge" style="color: #047857; background: #ecfdf5;">{{ $student['level'] ?? 'N/A' }}</span></td>
                            <td>
                                @if(isset($student['id']) && $student['id'] !== '0')
                                <form action="{{ route('wipeStudent', $student['id']) }}" method="POST" onsubmit="return confirm('Expel record?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="action-link delete">Remove</button>
                                </form>
                                @else
                                <span style="color:var(--text-muted);">Immutable</span>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @else
            <div style="text-align:center; color: var(--text-muted); padding: 1.5rem;">No active enrollment parameters detected.</div>
            @endif
        </div>
        @endif
    </div>

</body>

</html>