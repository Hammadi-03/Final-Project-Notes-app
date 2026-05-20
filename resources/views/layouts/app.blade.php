<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="MyNotepad - Your thoughts, organized beautifully. A modern note-taking app for productivity.">
    <title>MyNotepad - Your thoughts, organized beautifully</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        /* ── Design Tokens ── */
        :root {
            --primary: #4F46E5;
            --primary-hover: #4338CA;
            --primary-light: #EEF2FF;
            --secondary: #06B6D4;
            --success: #10B981;
            --warning: #F59E0B;
            --danger: #EF4444;
            --danger-hover: #DC2626;
            --gray-50: #F9FAFB;
            --gray-100: #F3F4F6;
            --gray-200: #E5E7EB;
            --gray-300: #D1D5DB;
            --gray-400: #9CA3AF;
            --gray-500: #6B7280;
            --gray-600: #4B5563;
            --gray-700: #374151;
            --gray-800: #1F2937;
            --gray-900: #111827;
            --sidebar-w: 260px;
            --topbar-h: 64px;
            --radius: 12px;
            --radius-sm: 8px;
            --shadow-sm: 0 1px 3px rgba(0,0,0,.06), 0 1px 2px rgba(0,0,0,.04);
            --shadow: 0 4px 12px rgba(0,0,0,.08);
            --shadow-lg: 0 10px 40px rgba(0,0,0,.12);
        }

        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        html { scroll-behavior: smooth; }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            color: var(--gray-800);
            background: var(--gray-50);
            line-height: 1.6;
            -webkit-font-smoothing: antialiased;
        }

        /* ── Buttons ── */
        .btn {
            display: inline-flex; align-items: center; gap: 6px;
            padding: 10px 20px; border-radius: var(--radius-sm);
            font-size: 14px; font-weight: 600;
            border: none; cursor: pointer;
            transition: all .2s ease;
            text-decoration: none; line-height: 1;
            white-space: nowrap;
        }
        .btn-primary {
            background: var(--primary); color: #fff;
        }
        .btn-primary:hover {
            background: var(--primary-hover);
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(79,70,229,.35);
        }
        .btn-secondary {
            background: #fff; color: var(--primary);
            border: 1.5px solid var(--primary);
        }
        .btn-secondary:hover {
            background: var(--primary-light);
            transform: translateY(-1px);
        }
        .btn-ghost {
            background: transparent; color: var(--gray-600);
            border: 1.5px solid var(--gray-200);
        }
        .btn-ghost:hover { background: var(--gray-100); color: var(--gray-800); }
        .btn-danger {
            background: var(--danger); color: #fff;
        }
        .btn-danger:hover {
            background: var(--danger-hover);
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(239,68,68,.3);
        }
        .btn-sm { padding: 7px 14px; font-size: 13px; }
        .btn-lg { padding: 14px 28px; font-size: 16px; border-radius: var(--radius); }
        .btn-icon { padding: 8px; border-radius: var(--radius-sm); }

        /* ── Inputs ── */
        .input-field {
            width: 100%; padding: 12px 16px;
            border: 1.5px solid var(--gray-200);
            border-radius: var(--radius-sm);
            font-size: 14px; font-family: inherit; color: var(--gray-800);
            background: #fff; transition: all .2s ease;
            outline: none;
        }
        .input-field:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(79,70,229,.12);
        }
        .input-field::placeholder { color: var(--gray-400); }

        /* ── Cards ── */
        .card {
            background: #fff; border: 1px solid var(--gray-200);
            border-radius: var(--radius); padding: 24px;
            transition: all .2s ease;
        }
        .card:hover {
            box-shadow: var(--shadow);
            transform: translateY(-2px);
        }

        /* ── Toast notifications ── */
        #toast-container {
            position: fixed; top: 24px; left: 50%; transform: translateX(-50%);
            z-index: 9999; display: flex; flex-direction: column; gap: 12px;
            align-items: center; pointer-events: none;
        }
        .toast {
            pointer-events: auto;
            display: flex; align-items: center; gap: 16px;
            padding: 8px 24px 8px 8px; border-radius: 9999px;
            background: #ffffff;
            box-shadow: 0 10px 40px -10px rgba(0,0,0,0.25), 0 1px 3px rgba(0,0,0,0.1);
            animation: slideDown .4s cubic-bezier(0.16, 1, 0.3, 1) forwards;
            min-width: 320px; max-width: 420px;
            border: 1px solid rgba(0,0,0,0.04);
        }
        .toast-icon-wrap {
            width: 44px; height: 44px; border-radius: 14px;
            background: #1c1c1e; color: #ffffff;
            display: flex; align-items: center; justify-content: center;
            flex-shrink: 0; font-size: 20px;
        }
        .toast-content {
            display: flex; flex-direction: column; justify-content: center;
            gap: 2px; padding: 4px 0;
        }
        .toast-title {
            font-weight: 700; font-size: 15px; color: #111827; line-height: 1.2;
        }
        .toast-message {
            font-size: 14px; color: #4b5563; line-height: 1.2;
        }
        .toast-undo {
            color: #4F46E5; font-weight: 600; background: none; border: none; cursor: pointer;
            padding: 8px 16px; font-size: 14px; margin-left: auto; border-radius: 999px;
            transition: background 0.2s;
        }
        .toast-undo:hover { background: rgba(79,70,229,0.1); }
        .toast-close { display: none; }
        @keyframes slideDown {
            from { opacity: 0; transform: translateY(-20px) scale(0.95); }
            to   { opacity: 1; transform: translateY(0) scale(1); }
        }
        @keyframes fadeOutUp {
            from { opacity: 1; transform: translateY(0) scale(1); }
            to   { opacity: 0; transform: translateY(-20px) scale(0.95); }
        }

        /* ── Modal ── */
        .modal-overlay {
            position: fixed; inset: 0; z-index: 1000;
            background: rgba(17,24,39,.5); backdrop-filter: blur(4px);
            display: flex; align-items: center; justify-content: center;
            opacity: 0; pointer-events: none; transition: opacity .2s ease;
        }
        .modal-overlay.active { opacity: 1; pointer-events: all; }
        .modal {
            background: #fff; border-radius: var(--radius);
            padding: 32px; max-width: 420px; width: 90%;
            box-shadow: var(--shadow-lg);
            transform: scale(.95); transition: transform .2s ease;
        }
        .modal-overlay.active .modal { transform: scale(1); }

        /* ── Reusable Keep Modals ── */
        .keep-modal-backdrop {
            position: fixed; inset: 0; background: rgba(32,33,36,0.6); backdrop-filter: blur(4px);
            display: none; align-items: center; justify-content: center; z-index: 1000;
        }
        .keep-modal-backdrop.active { display: flex; }
        .keep-modal-card {
            background: #fff; border: 1px solid var(--gray-200); border-radius: 8px;
            box-shadow: var(--shadow-lg); width: 90%; max-width: 440px; padding: 24px;
            position: relative; animation: scaleUp 0.15s cubic-bezier(0, 0, 0.2, 1) forwards;
        }
        .label-pill {
            font-size: 11px;
            font-weight: 600;
            padding: 2px 8px;
            border-radius: 12px;
            background: var(--gray-100);
            color: var(--gray-700);
            display: inline-flex;
            align-items: center;
            gap: 4px;
            max-width: 100%;
            border: 1px solid var(--gray-200);
        }
        .label-pill i {
            font-size: 9px;
            opacity: 0.7;
        }
        
        /* ── Dark Mode Styles ── */
        body.dark-theme {
            --gray-50: #202124;
            --gray-100: #303134;
            --gray-200: #3c4043;
            --gray-300: #5f6368;
            --gray-400: #80868b;
            --gray-500: #9ca3af;
            --gray-600: #bdc1c6;
            --gray-700: #dadce0;
            --gray-800: #e8eaed;
            --gray-900: #f1f3f4;
            background: var(--gray-50) !important;
            color: var(--gray-800) !important;
        }
        body.dark-theme .topbar,
        body.dark-theme .sidebar,
        body.dark-theme .card,
        body.dark-theme .note-card,
        body.dark-theme .quick-note-card,
        body.dark-theme .edit-modal-card,
        body.dark-theme .modal,
        body.dark-theme .user-dropdown,
        body.dark-theme .color-menu,
        body.dark-theme .card-more-menu,
        body.dark-theme #editModalMoreMenu,
        body.dark-theme .keep-modal-card {
            background-color: #202124 !important;
            border-color: #3c4043 !important;
            color: var(--gray-800) !important;
        }
        body.dark-theme .dropdown-item {
            color: var(--gray-800) !important;
        }
        body.dark-theme .dropdown-item:hover {
            background: var(--gray-100) !important;
        }
        body.dark-theme .nav-item {
            color: var(--gray-800) !important;
        }
        body.dark-theme .nav-item:hover {
            background: #35363a !important;
        }
        body.dark-theme .nav-item.active {
            background: #41331c !important;
            color: #feefc3 !important;
        }
        body.dark-theme .nav-item.active .nav-icon {
            color: #feefc3 !important;
        }
        body.dark-theme input,
        body.dark-theme textarea,
        body.dark-theme select {
            background-color: transparent !important;
            color: var(--gray-800) !important;
            border-color: var(--gray-200);
        }
        body.dark-theme .search-bar {
            background: #303134 !important;
        }
        body.dark-theme .search-bar input {
            color: var(--gray-800) !important;
        }
        body.dark-theme .btn-ghost {
            border-color: #3c4043;
            color: var(--gray-800);
        }
        body.dark-theme .btn-ghost:hover {
            background: #303134;
        }
        body.dark-theme .qn-tool-btn,
        body.dark-theme .card-toolbar-btn {
            color: var(--gray-600) !important;
        }
        body.dark-theme .qn-tool-btn:hover,
        body.dark-theme .card-toolbar-btn:hover {
            background: rgba(241, 243, 244, 0.08) !important;
            color: var(--gray-800) !important;
        }
        body.dark-theme .color-blue { background: #1e3a8a !important; border-color: #1e3a8a !important; }
        body.dark-theme .color-green { background: #064e3b !important; border-color: #064e3b !important; }
        body.dark-theme .color-yellow { background: #78350f !important; border-color: #78350f !important; }
        body.dark-theme .color-red { background: #7f1d1d !important; border-color: #7f1d1d !important; }
        body.dark-theme .color-purple { background: #581c87 !important; border-color: #581c87 !important; }
        
        body.dark-theme .edit-modal-card.color-blue { background: #1e3a8a !important; }
        body.dark-theme .edit-modal-card.color-green { background: #064e3b !important; }
        body.dark-theme .edit-modal-card.color-yellow { background: #78350f !important; }
        body.dark-theme .edit-modal-card.color-red { background: #7f1d1d !important; }
        body.dark-theme .edit-modal-card.color-purple { background: #581c87 !important; }

        /* ── Responsive ── */
        @media (max-width: 768px) {
            .sidebar { transform: translateX(-100%); position: fixed; z-index: 100; height: 100vh; }
            .sidebar.open { transform: translateX(0); }
            .main-content { margin-left: 0 !important; }
            .topbar-logo span { display: none; }
        }
    </style>
</head>
<body class="{{ ($globalSettings['dark_theme'] ?? false) ? 'dark-theme' : '' }}">
    <!-- Toast Container -->
    <div id="toast-container"></div>

    @yield('content')

    <!-- Delete Confirm Modal -->
    <div class="modal-overlay" id="deleteModal">
        <div class="modal">
            <div style="text-align:center; margin-bottom:20px;">
                <div style="width:56px;height:56px;background:#FEF2F2;border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto 16px;font-size:24px;"><i class="fa-regular fa-trash-can" style="color:#EF4444;font-size:24px;"></i></div>
                <h3 style="font-size:18px;font-weight:700;color:var(--gray-900);margin-bottom:8px;">Delete Note</h3>
                <p style="color:var(--gray-500);font-size:14px;">Are you sure you want to delete <strong id="deleteNoteTitle"></strong>? This action cannot be undone.</p>
            </div>
            <div style="display:flex;gap:12px;">
                <button class="btn btn-ghost" style="flex:1;" onclick="closeDeleteModal()">Cancel</button>
                <form id="deleteForm" method="POST" style="flex:1;">
                    @csrf @method('DELETE')
                    <button type="submit" class="btn btn-danger" style="width:100%;">Delete Note</button>
                </form>
            </div>
        </div>
    </div>

    <!-- Settings Modal -->
    <div class="keep-modal-backdrop" id="settingsModal">
        <div class="keep-modal-card">
            <h3 style="font-size:18px;font-weight:700;margin-bottom:16px;">Settings</h3>
            <form id="settingsForm" method="POST" action="{{ route('settings.save') }}">
                @csrf
                <div style="display:flex; flex-direction:column; gap:16px; margin-bottom:24px;">
                    <label style="display:flex; align-items:center; gap:12px; cursor:pointer;">
                        <input type="checkbox" name="add_to_bottom" value="1" {{ ($globalSettings['add_to_bottom'] ?? true) ? 'checked' : '' }} style="width:18px; height:18px;">
                        <span>Add new items to the bottom</span>
                    </label>
                    <label style="display:flex; align-items:center; gap:12px; cursor:pointer;">
                        <input type="checkbox" name="move_checked_to_bottom" value="1" {{ ($globalSettings['move_checked_to_bottom'] ?? true) ? 'checked' : '' }} style="width:18px; height:18px;">
                        <span>Move checked items to bottom</span>
                    </label>
                    <label style="display:flex; align-items:center; gap:12px; cursor:pointer;">
                        <input type="checkbox" name="dark_theme" id="darkThemeCheckbox" value="1" {{ ($globalSettings['dark_theme'] ?? false) ? 'checked' : '' }} style="width:18px; height:18px;">
                        <span>Enable dark theme</span>
                    </label>
                </div>
                <div style="display:flex; justify-content:flex-end; gap:12px;">
                    <button type="button" class="btn btn-ghost" onclick="closeSettingsModal()">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Send Feedback Modal -->
    <div class="keep-modal-backdrop" id="feedbackModal">
        <div class="keep-modal-card">
            <h3 style="font-size:18px;font-weight:700;margin-bottom:16px;">Send feedback to Antigravity Notes</h3>
            <form id="feedbackForm" method="POST" action="{{ route('feedback.submit') }}">
                @csrf
                <textarea name="content" required placeholder="Tell us what you think! Describe your issue or share your ideas..." style="width:100%; height:120px; padding:12px; border:1px solid var(--gray-200); border-radius:6px; font-family:inherit; resize:none; margin-bottom:16px;"></textarea>
                <div style="display:flex; justify-content:flex-end; gap:12px;">
                    <button type="button" class="btn btn-ghost" onclick="closeFeedbackModal()">Cancel</button>
                    <button type="submit" class="btn btn-primary">Submit</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Edit Labels Modal -->
    <div class="keep-modal-backdrop" id="editLabelsModal">
        <div class="keep-modal-card" style="max-width: 360px; padding: 16px;">
            <h3 style="font-size:16px;font-weight:600;margin-bottom:16px;">Edit labels</h3>
            
            <!-- Create new label line -->
            <div style="display:flex; align-items:center; gap:8px; border-bottom:1px solid var(--gray-200); padding-bottom:12px; margin-bottom:16px;">
                <button type="button" class="card-toolbar-btn" style="padding:4px; font-size:14px; opacity:0.6;"><i class="fa-solid fa-plus"></i></button>
                <input type="text" id="newLabelInput" placeholder="Create new label" style="flex:1; border:none; outline:none; font-size:14px; font-weight:500; background:transparent;">
                <button type="button" class="card-toolbar-btn" onclick="submitCreateLabel()" style="padding:4px; font-size:14px; color:var(--primary);"><i class="fa-solid fa-check"></i></button>
            </div>

            <!-- Existing Labels Scroll Container -->
            <div id="labelsListContainer" style="max-height: 240px; overflow-y: auto; display:flex; flex-direction:column; gap:12px; margin-bottom:16px;">
                @foreach($globalLabels as $lbl)
                <div class="label-row" id="label-row-{{ $lbl->id }}" style="display:flex; align-items:center; gap:8px;">
                    <button type="button" class="card-toolbar-btn delete-btn" onclick="submitDeleteLabel({{ $lbl->id }})" style="padding:4px; font-size:14px; opacity:0.6;"><i class="fa-solid fa-trash-can"></i></button>
                    <input type="text" class="label-name-input" id="label-input-{{ $lbl->id }}" value="{{ $lbl->name }}" style="flex:1; border:none; outline:none; font-size:14px; background:transparent;" onfocus="showSaveLabelIcon({{ $lbl->id }})">
                    <button type="button" class="card-toolbar-btn save-btn" id="label-save-{{ $lbl->id }}" onclick="submitUpdateLabel({{ $lbl->id }})" style="padding:4px; font-size:14px; color:var(--primary); display:none;"><i class="fa-solid fa-check"></i></button>
                </div>
                @endforeach
            </div>

            <div style="display:flex; justify-content:flex-end; border-top:1px solid var(--gray-200); padding-top:12px;">
                <button type="button" class="btn btn-ghost" style="padding: 6px 16px; font-size:13px;" onclick="closeEditLabelsModal()">Done</button>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
    <script>
    // ── Toast System ──
    function showToast(message, type = 'success') {
        const icons = { 
            success: '<i class="fa-solid fa-check"></i>', 
            error: '<i class="fa-solid fa-xmark"></i>', 
            info: '<i class="fa-solid fa-info"></i>',
            deleted: '<i class="fa-solid fa-trash"></i>'
        };
        const titles = {
            success: 'Success',
            error: 'Error',
            info: 'Information',
            deleted: 'Note Trashed'
        };
        
        let title = titles[type] || 'Notification';
        let displayMessage = message;
        if (type === 'deleted') {
            displayMessage = 'Note moved to trash.';
        }

        const container = document.getElementById('toast-container');
        const toast = document.createElement('div');
        toast.className = `toast ${type}`;
        
        let undoBtn = type === 'deleted' ? `<button class="toast-undo" onclick="dismissToast(this.closest('.toast'))">Undo</button>` : '';
        
        toast.innerHTML = `
            <div class="toast-icon-wrap">
                ${icons[type] || '<i class="fa-solid fa-bell"></i>'}
            </div>
            <div class="toast-content">
                <div class="toast-title">${title}</div>
                <div class="toast-message">${displayMessage}</div>
            </div>
            ${undoBtn}
        `;
        
        container.appendChild(toast);
        setTimeout(() => dismissToast(toast), 4000);
    }
    function dismissToast(el) {
        if (!el) return;
        el.style.animation = 'fadeOutUp .3s cubic-bezier(0.16, 1, 0.3, 1) forwards';
        setTimeout(() => el.remove(), 300);
    }

    // ── Delete Modal ──
    function confirmDelete(noteId, noteTitle, deleteUrl) {
        document.getElementById('deleteNoteTitle').textContent = '"' + noteTitle + '"';
        document.getElementById('deleteForm').action = deleteUrl;
        document.getElementById('deleteModal').classList.add('active');
    }
    function closeDeleteModal() {
        document.getElementById('deleteModal').classList.remove('active');
    }
    document.getElementById('deleteModal').addEventListener('click', function(e) {
        if (e.target === this) closeDeleteModal();
    });

    // ── Mobile Sidebar ──
    function toggleSidebar() {
        const sidebar = document.querySelector('.sidebar');
        if (sidebar) sidebar.classList.toggle('open');
    }

    // ── Reusable Modal Helpers ──
    function openSettingsModal() {
        document.getElementById('settingsModal').classList.add('active');
    }
    function closeSettingsModal() {
        document.getElementById('settingsModal').classList.remove('active');
    }
    function openFeedbackModal() {
        document.getElementById('feedbackModal').classList.add('active');
    }
    function closeFeedbackModal() {
        document.getElementById('feedbackModal').classList.remove('active');
    }
    function openEditLabelsModal() {
        document.getElementById('editLabelsModal').classList.add('active');
    }
    function closeEditLabelsModal() {
        document.getElementById('editLabelsModal').classList.remove('active');
    }
    
    // Close on clicking backdrop
    document.querySelectorAll('.keep-modal-backdrop').forEach(backdrop => {
        backdrop.addEventListener('click', function(e) {
            if (e.target === this) {
                this.classList.remove('active');
            }
        });
    });

    // ── Label Actions ──
    function submitCreateLabel() {
        const name = document.getElementById('newLabelInput').value.trim();
        if (!name) return;
        fetch("{{ route('labels.store') }}", {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            },
            body: JSON.stringify({ name: name })
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                showToast('Label created', 'success');
                location.reload();
            }
        });
    }

    function showSaveLabelIcon(id) {
        document.getElementById(`label-save-${id}`).style.display = 'inline-block';
    }

    // Live theme switcher checkbox behavior
    const themeCheckbox = document.getElementById('darkThemeCheckbox');
    if (themeCheckbox) {
        themeCheckbox.addEventListener('change', function() {
            if (this.checked) {
                document.body.classList.add('dark-theme');
            } else {
                document.body.classList.remove('dark-theme');
            }
        });
    }

    function submitUpdateLabel(id) {
        const name = document.getElementById(`label-input-${id}`).value.trim();
        if (!name) return;
        fetch(`/labels/${id}`, {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            },
            body: JSON.stringify({ name: name })
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                showToast('Label updated', 'success');
                document.getElementById(`label-save-${id}`).style.display = 'none';
            }
        });
    }

    function toggleNoteLabel(noteId, labelId, isChecked) {
        fetch(`/notes/${noteId}/labels`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            },
            body: JSON.stringify({ label_id: labelId })
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                showToast(data.attached ? 'Label added' : 'Label removed', 'success');
                location.reload();
            }
        });
    }

    function submitDeleteLabel(id) {
        if (!confirm('Are you sure you want to delete this label?')) return;
        fetch(`/labels/${id}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            }
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                showToast('Label deleted', 'success');
                document.getElementById(`label-row-${id}`).remove();
                location.reload();
            }
        });
    }

    // ── Auto-show session flash toasts ──
    document.addEventListener('DOMContentLoaded', function() {
        @if(session('success'))
            showToast(@json(session('success')), 'success');
        @endif
        @if(session('error'))
            showToast(@json(session('error')), 'error');
        @endif
        @if(session('info'))
            showToast(@json(session('info')), 'info');
        @endif
        @if(session('deleted'))
            showToast('Note trashed', 'deleted');
        @endif
    });
    </script>
</body>
</html>
