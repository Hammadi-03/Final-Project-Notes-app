@extends('layouts.app')

@section('content')
<style>
    :root{--primary:#4F46E5;--primary-hover:#4338CA;--primary-light:#EEF2FF;--secondary:#06B6D4;--success:#10B981;--danger:#EF4444;--danger-hover:#DC2626;--gray-50:#F9FAFB;--gray-100:#F3F4F6;--gray-200:#E5E7EB;--gray-300:#D1D5DB;--gray-400:#9CA3AF;--gray-500:#6B7280;--gray-600:#4B5563;--gray-700:#374151;--gray-800:#1F2937;--gray-900:#111827;--sidebar-w:260px;--topbar-h:64px;--radius:12px;--radius-sm:8px;}
    /* Layout */
    .app-wrapper{display:flex;min-height:100vh;background:var(--gray-50);}
    /* Topbar */
    .topbar{position:fixed;top:0;left:0;right:0;height:var(--topbar-h);background:#fff;border-bottom:1px solid var(--gray-200);display:flex;align-items:center;justify-content:space-between;padding:0 16px;z-index:200;}
    .topbar-left{display:flex;align-items:center;gap:12px;min-width: 244px;}
    .topbar-logo{display:flex;align-items:center;gap:8px;text-decoration:none;}
    .topbar-logo-icon{width:40px;height:40px;background:#F5B041;border-radius:8px;display:flex;align-items:center;justify-content:center;font-size:22px;}
    .topbar-logo span{font-size:22px;font-weight:400;color:#5f6368;font-family: 'Product Sans', 'Inter', sans-serif;}
    .hamburger{display:flex;align-items:center;justify-content:center;background:none;border:none;cursor:pointer;width:48px;height:48px;border-radius:50%;color:#5f6368;font-size:20px;}
    .hamburger:hover{background:rgba(60,64,67,0.08);}
    .search-container{flex:1;display:flex;justify-content:flex-start;max-width:720px;margin-right:20px;}
    .search-bar{display:flex;align-items:center;gap:10px;background:#f1f3f4;border:none;border-radius:8px;padding:11px 16px;width:100%;transition:all .2s;}
    .search-bar:focus-within{background:#fff;box-shadow:0 1px 1px 0 rgba(65,69,73,0.3),0 1px 3px 1px rgba(65,69,73,0.15);}
    .search-bar input{background:none;border:none;outline:none;font-size:16px;color:#3c4043;font-family:inherit;width:100%;}
    .search-bar input::placeholder{color:#5f6368;}
    .topbar-right{display:flex;align-items:center;gap:4px;}
    .topbar-icon-btn{display:flex;align-items:center;justify-content:center;width:48px;height:48px;border-radius:50%;background:none;border:none;color:#5f6368;font-size:20px;cursor:pointer;text-decoration:none;transition:background .2s;}
    .topbar-icon-btn:hover{background:rgba(60,64,67,0.08);}
    .user-menu{display:flex;align-items:center;justify-content:center;width:40px;height:40px;border-radius:50%;cursor:pointer;position:relative;margin-left:8px;}
    .user-menu:hover{background:rgba(60,64,67,0.08);}
    .user-avatar{width:32px;height:32px;background:linear-gradient(135deg,var(--primary),#7C3AED);border-radius:50%;display:flex;align-items:center;justify-content:center;color:#fff;font-size:14px;font-weight:700;}
    .user-name{display:none;}
    .user-dropdown{position:absolute;top:calc(100% + 8px);right:0;background:#fff;border:1px solid var(--gray-200);border-radius:var(--radius);padding:8px;min-width:180px;box-shadow:0 10px 40px rgba(0,0,0,.12);opacity:0;pointer-events:none;transform:translateY(-6px);transition:all .15s ease;}
    .user-menu.open .user-dropdown{opacity:1;pointer-events:all;transform:translateY(0);}
    .dropdown-item{display:flex;align-items:center;gap:10px;padding:9px 12px;border-radius:8px;font-size:14px;color:var(--gray-700);cursor:pointer;transition:background .15s;text-decoration:none;border:none;background:none;width:100%;font-family:inherit;}
    .dropdown-item:hover{background:var(--gray-100);}
    .dropdown-item.danger{color:var(--danger);}
    .dropdown-item.danger:hover{background:#FEF2F2;}
    .dropdown-divider{height:1px;background:var(--gray-200);margin:6px 0;}

    /* Sidebar */
    .sidebar{position:fixed;top:var(--topbar-h);left:0;width:var(--sidebar-w);height:calc(100vh - var(--topbar-h));background:#fff;border-right:none;padding:12px 12px 12px 0;overflow-y:auto;z-index:150;transition:transform .2s cubic-bezier(0.4,0.0,0.2,1),width .2s cubic-bezier(0.4,0.0,0.2,1);}
    .sidebar.closed{transform:translateX(-100%);width:0;}
    .sidebar-section-label{font-size:11px;font-weight:600;color:var(--gray-400);letter-spacing:.06em;text-transform:uppercase;padding:0 12px 0 24px;margin-bottom:8px;margin-top:16px;}
    .sidebar-section-label:first-child{margin-top:0;}
    .nav-item{display:flex;align-items:center;gap:10px;padding:12px 24px 12px 24px;border-radius:0 24px 24px 0;font-size:14px;font-weight:500;color:#3c4043;cursor:pointer;transition:all .15s;text-decoration:none;border:none;background:none;width:100%;font-family:inherit;}
    .nav-item:hover{background:#f1f3f4;}
    .nav-item.active{background:#feefc3;color:#202124;}
    .nav-item.active .nav-icon{color:#202124;}
    .nav-icon{font-size:18px;flex-shrink:0;color:#5f6368;}
    .nav-badge{margin-left:auto;background:var(--primary);color:#fff;font-size:11px;font-weight:600;padding:2px 8px;border-radius:999px;}

    /* Main */
    .main-content{margin-left:var(--sidebar-w);margin-top:var(--topbar-h);flex:1;padding:32px;min-width:0;transition:margin-left .2s cubic-bezier(0.4,0.0,0.2,1);}
    .main-content.expanded{margin-left:0;}

    /* Page header */
    .page-header{display:flex;align-items:center;justify-content:space-between;margin-bottom:28px;flex-wrap:wrap;gap:12px;}
    .page-title-block h1{font-size:26px;font-weight:800;color:var(--gray-900);}
    .page-title-block p{font-size:14px;color:var(--gray-500);margin-top:4px;}
    .btn{display:inline-flex;align-items:center;gap:6px;padding:10px 20px;border-radius:var(--radius-sm);font-size:14px;font-weight:600;border:none;cursor:pointer;transition:all .2s ease;text-decoration:none;font-family:inherit;}
    .btn-primary{background:var(--primary);color:#fff;}
    .btn-primary:hover{background:var(--primary-hover);transform:translateY(-1px);box-shadow:0 4px 12px rgba(79,70,229,.35);}
    .btn-ghost{background:#fff;color:var(--gray-600);border:1.5px solid var(--gray-200);}
    .btn-ghost:hover{background:var(--gray-50);color:var(--gray-800);}
    .btn-sm{padding:7px 14px;font-size:13px;}
    .btn-icon-sm{padding:6px;border-radius:8px;background:none;border:none;cursor:pointer;transition:all .15s;display:inline-flex;align-items:center;font-size:15px;}
    .btn-icon-sm:hover{background:var(--gray-100);}
    .btn-icon-sm.pin-active{color:var(--warning);}

    /* Stats */
    .stats-row{display:flex;gap:16px;margin-bottom:32px;flex-wrap:wrap;}
    .stat-card{background:#fff;border:1px solid var(--gray-200);border-radius:var(--radius);padding:20px 24px;display:flex;align-items:center;gap:16px;flex:1;min-width:160px;}
    .stat-icon{width:44px;height:44px;border-radius:12px;display:flex;align-items:center;justify-content:center;font-size:20px;flex-shrink:0;}
    .stat-icon.blue{background:#EEF2FF;}
    .stat-icon.green{background:#ECFDF5;}
    .stat-icon.amber{background:#FFFBEB;}
    .stat-value{font-size:26px;font-weight:800;color:var(--gray-900);}
    .stat-label{font-size:13px;color:var(--gray-500);margin-top:2px;}

    /* Section headers */
    .section-header{display:flex;align-items:center;gap:10px;margin-bottom:16px;}
    .section-header h2{font-size:15px;font-weight:700;color:var(--gray-700);}
    .section-count{background:var(--gray-100);color:var(--gray-500);font-size:12px;font-weight:600;padding:3px 10px;border-radius:999px;}

    /* Notes Grid */
    .notes-grid{display:grid;grid-template-columns:repeat(auto-fill,minmax(240px,1fr));gap:16px;margin-bottom:40px;}
    .note-card{background:#fff;border:1px solid var(--gray-200);border-radius:8px;padding:16px;transition:box-shadow .15s ease, border-color .15s ease;display:flex;flex-direction:column;cursor:pointer;position:relative;}
    .note-card:hover{box-shadow:0 1px 2px 0 rgba(60,64,67,0.3), 0 2px 6px 2px rgba(60,64,67,0.15); border-color:transparent;}
    .note-card-check{position:absolute;top:-10px;left:-10px;opacity:0;transition:opacity .15s;z-index:10;background:#fff;border-radius:50%;width:22px;height:22px;display:flex;align-items:center;justify-content:center;color:#202124;box-shadow:0 1px 3px rgba(0,0,0,0.3);font-size:14px;}
    .note-card:hover .note-card-check{opacity:1;}
    .note-card-pin{position:absolute;top:12px;right:12px;opacity:0;transition:opacity .15s;background:none;border:none;cursor:pointer;color:#5f6368;font-size:16px;width:32px;height:32px;border-radius:50%;display:flex;align-items:center;justify-content:center;z-index:10;}
    .note-card-pin:hover{background:rgba(95,99,104,0.08);color:#202124;}
    .note-card.pinned .note-card-pin{opacity:1;color:#202124;}
    .note-card:hover .note-card-pin{opacity:1;}
    .note-title{font-size:16px;font-weight:600;color:#202124;margin-bottom:8px;display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden;font-family: 'Google Sans', 'Inter', sans-serif;}
    .note-content{font-size:14px;color:#3c4043;line-height:1.5;flex:1;display:-webkit-box;-webkit-line-clamp:6;-webkit-box-orient:vertical;overflow:hidden;margin-bottom:12px;font-family: inherit;}
    .note-card-time{font-size:11px;color:#5f6368;transition:opacity .15s ease;opacity:1;}
    .note-card:hover .note-card-time{opacity:0;}
    .note-card-toolbar{display:flex;justify-content:space-between;align-items:center;width:100%;opacity:0;pointer-events:none;transition:opacity .15s ease;}
    .note-card:hover .note-card-toolbar{opacity:1;pointer-events:auto;}
    .card-toolbar-btn{background:none;border:none;color:#5f6368;font-size:14px;width:28px;height:28px;border-radius:50%;display:flex;align-items:center;justify-content:center;cursor:pointer;transition:background .15s, color .15s;text-decoration:none;}
    .card-toolbar-btn:hover{background:rgba(95,99,104,0.08);color:#202124;}
    .card-toolbar-btn.danger:hover{background:#FEF2F2;color:var(--danger);}

    /* Color variants */
    .color-blue{background:#cbf0f8 !important; border-color:#cbf0f8 !important;}
    .color-green{background:#ccff90 !important; border-color:#ccff90 !important;}
    .color-yellow{background:#fff475 !important; border-color:#fff475 !important;}
    .color-red{background:#f28b82 !important; border-color:#f28b82 !important;}
    .color-purple{background:#d7aefb !important; border-color:#d7aefb !important;}

    /* Color picker menu */
    .color-menu {
        display: none;
        flex-direction: row;
        animation: fadeIn 0.15s ease-out;
    }
    @keyframes fadeIn {
        from { opacity: 0; transform: translate(-50%, 4px); }
        to { opacity: 1; transform: translate(-50%, 0); }
    }
    .sortable-ghost {
        opacity: 0.4;
        border: 2px dashed var(--primary) !important;
        background: rgba(79,70,229,0.05) !important;
    }

    /* Empty state */
    .empty-state{text-align:center;padding:80px 24px;background:#fff;border:1px solid var(--gray-200);border-radius:var(--radius);}
    .empty-icon{font-size:64px;margin-bottom:20px;line-height:1;}
    .empty-title{font-size:20px;font-weight:700;color:var(--gray-900);margin-bottom:10px;}
    .empty-sub{font-size:15px;color:var(--gray-500);max-width:360px;margin:0 auto 28px;}

    /* Search results bar */
    .search-results-bar{background:var(--primary-light);border:1px solid rgba(79,70,229,.2);border-radius:var(--radius-sm);padding:12px 16px;margin-bottom:24px;display:flex;align-items:center;justify-content:space-between;font-size:14px;color:var(--primary);font-weight:500;}

    /* Quick Note Creator */
    .quick-note-wrapper{max-width:600px;margin:0 auto 40px;position:relative;}
    .quick-note-card{background:#fff;border:1px solid var(--gray-200);border-radius:var(--radius);box-shadow:var(--shadow-sm);transition:all .3s cubic-bezier(.4,0,.2,1);overflow:hidden;}
    .quick-note-card.expanded{box-shadow:0 4px 14px rgba(0,0,0,.15);}
    .qn-title-wrap{display:none;padding:12px 16px 0;}
    .quick-note-card.expanded .qn-title-wrap{display:block;}
    .qn-title{width:100%;border:none;outline:none;font-size:16px;font-weight:600;color:var(--gray-900);font-family:inherit;}
    .qn-content-wrap{padding:12px 16px;display:flex;align-items:center;gap:10px;}
    .qn-content{width:100%;border:none;outline:none;font-size:14px;color:var(--gray-800);font-family:inherit;resize:none;background:transparent;padding:0;}
    .qn-content::placeholder{color:var(--gray-600);font-weight:500;}
    .qn-icons{display:flex;gap:4px;color:var(--gray-500);}
    .quick-note-card.expanded .qn-icons{display:none;}
    .qn-footer{display:none;justify-content:space-between;align-items:center;padding:4px 16px 12px;}
    .quick-note-card.expanded .qn-footer{display:flex;}
    .qn-toolbar{display:flex;gap:2px;}
    .qn-tool-btn{background:none;border:none;color:var(--gray-500);font-size:15px;width:34px;height:34px;display:flex;align-items:center;justify-content:center;border-radius:50%;cursor:pointer;transition:background .2s;}
    .qn-tool-btn:hover{background:var(--gray-100);color:var(--gray-800);}
    .qn-actions{display:flex;gap:8px;align-items:center;}

    @media(max-width:768px){
        .sidebar{position:fixed;z-index:200;}
        .main-content{margin-left:0 !important;}
        .topbar-left{min-width:auto; gap:4px;}
        .topbar-logo span{display:none;}
        .search-container{margin-left:10px;margin-right:10px;}
        .stats-row{gap:12px;}
        .stat-card{padding:16px;}
        .notes-grid{grid-template-columns:1fr;}
        .note-actions{opacity:1;}
        .topbar-icon-btn.hide-mobile{display:none;}
    }
    @media(max-width:480px){
        .main-content{padding:20px 16px;}
        .topbar{padding:0 16px;}
        .page-header{flex-direction:column;align-items:flex-start;}
    }

    /* Modal Backdrop Overlay */
    .modal-backdrop {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(32, 33, 36, 0.6);
        backdrop-filter: blur(4px);
        display: none; /* Flex when active */
        align-items: center;
        justify-content: center;
        z-index: 1000;
        transition: all 0.25s ease;
    }
    .modal-backdrop.active {
        display: flex;
    }
    /* Modal Content Card */
    .edit-modal-card {
        background: #fff;
        border-radius: 8px;
        width: 90%;
        max-width: 600px;
        box-shadow: 0 4px 16px rgba(0,0,0,0.2);
        overflow: hidden;
        border: 1px solid var(--gray-200);
        animation: scaleUp 0.15s cubic-bezier(0, 0, 0.2, 1) forwards;
    }
    @keyframes scaleUp {
        from { transform: scale(0.9); opacity: 0; }
        to { transform: scale(1); opacity: 1; }
    }
    
    /* List view modifier */
    .notes-grid.list-view {
        grid-template-columns: 1fr !important;
        max-width: 600px;
        margin-left: auto;
        margin-right: auto;
    }
</style>

<div class="app-wrapper">

    <!-- Top Bar -->
    <header class="topbar">
        <div class="topbar-left">
            <button type="button" class="hamburger" onclick="toggleSidebar(event)" aria-label="Toggle sidebar">
                <svg focusable="false" viewBox="0 0 24 24" style="width:24px;height:24px;fill:currentColor;"><path d="M3 18h18v-2H3v2zm0-5h18v-2H3v2zm0-7v2h18V6H3z"></path></svg>
            </button>
            <a href="{{ route('notes.index') }}" class="topbar-logo">
                <div class="topbar-logo-icon" style="background:#F5B041;color:#fff;font-size:18px;display:flex;align-items:center;justify-content:center;"><i class="fa-solid fa-lightbulb"></i></div>
                <span>Mynotepad</span>
            </a>
        </div>
        <div class="search-container">
            <form method="GET" action="{{ route('notes.index') }}" style="width:100%;">
                <div class="search-bar">
                    <button type="submit" style="background:none;border:none;cursor:pointer;color:#5f6368;display:flex;">
                        <svg focusable="false" viewBox="0 0 24 24" style="width:20px;height:20px;fill:currentColor;"><path d="M15.5 14h-.79l-.28-.27A6.471 6.471 0 0 0 16 9.5 6.5 6.5 0 1 0 9.5 16c1.61 0 3.09-.59 4.23-1.57l.27.28v.79l5 4.99L20.49 19l-4.99-5zm-6 0C7.01 14 5 11.99 5 9.5S7.01 5 9.5 5 14 7.01 14 9.5 11.99 14 9.5 14z"></path></svg>
                    </button>
                    <input type="text" name="search" placeholder="Search" value="{{ request('search') }}" autocomplete="off">
                </div>
            </form>
        </div>
        <div class="topbar-right">
            <a href="{{ route('notes.index') }}" class="topbar-icon-btn hide-mobile" title="Refresh">
                <svg focusable="false" viewBox="0 0 24 24" style="width:24px;height:24px;fill:currentColor;"><path d="M17.65 6.35C16.2 4.9 14.21 4 12 4c-4.42 0-7.99 3.58-7.99 8s3.57 8 7.99 8c3.73 0 6.84-2.55 7.73-6h-2.08c-.82 2.33-3.04 4-5.65 4-3.31 0-6-2.69-6-6s2.69-6 6-6c1.66 0 3.14.69 4.22 1.78L13 11h7V4l-2.35 2.35z"></path></svg>
            </a>
            <button type="button" class="topbar-icon-btn hide-mobile" id="viewModeToggleBtn" onclick="toggleViewMode()" title="Toggle View Mode">
                <svg focusable="false" viewBox="0 0 24 24" style="width:24px;height:24px;fill:currentColor;"><path d="M3 13h2v-2H3v2zm0 4h2v-2H3v2zm0-8h2V7H3v2zm4 4h14v-2H7v2zm0 4h14v-2H7v2zM7 7v2h14V7H7z"></path></svg>
            </button>
            <div style="position: relative; display: inline-block;">
                <button type="button" class="topbar-icon-btn" onclick="toggleSettingsDropdown(event)" title="Settings">
                    <svg focusable="false" viewBox="0 0 24 24" style="width:24px;height:24px;fill:currentColor;"><path d="M19.43 12.98c.04-.32.07-.64.07-.98 0-.34-.03-.66-.07-.98l2.11-1.65c.19-.15.24-.42.12-.64l-2-3.46c-.12-.22-.39-.3-.61-.22l-2.49 1c-.52-.4-1.08-.73-1.69-.98l-.38-2.65C14.46 2.18 14.25 2 14 2h-4c-.25 0-.46.18-.49.42l-.38 2.65c-.61.25-1.17.59-1.69.98l-2.49-1c-.23-.09-.49 0-.61.22l-2 3.46c-.13.22-.07.49.12.64l2.11 1.65c-.04.32-.07.65-.07.98 0 .33.03.66.07.98l-2.11 1.65c-.19.15-.24.42-.12.64l2 3.46c.12.22.39.3.61.22l2.49-1c.52.4 1.08.73 1.69.98l.38 2.65c.03.24.24.42.49.42h4c.25 0 .46-.18.49-.42l.38-2.65c.61-.25 1.17-.59 1.69-.98l2.49 1c.23.09.49 0 .61-.22l2-3.46c.12-.22.07-.49-.12-.64l-2.11-1.65zM12 15.5c-1.93 0-3.5-1.57-3.5-3.5s1.57-3.5 3.5-3.5 3.5 1.57 3.5 3.5-1.57 3.5-3.5 3.5z"></path></svg>
                </button>
                <div class="user-dropdown" id="settingsDropdown" style="display:none; position:absolute; right:0; top:42px; width:180px; z-index:1000; box-shadow:var(--shadow-md);">
                    <button type="button" class="dropdown-item" onclick="openSettingsModal()"><i class="fa-solid fa-gear" style="width:18px;"></i> Settings</button>
                    <button type="button" class="dropdown-item" onclick="openFeedbackModal()"><i class="fa-regular fa-comment-dots" style="width:18px;"></i> Send feedback</button>
                </div>
            </div>
            <div class="user-menu" id="userMenu" onclick="toggleUserMenu(event)">
                <div class="user-avatar">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</div>
                <div class="user-dropdown">
                    <div style="padding:8px 12px 12px;">
                        <div style="font-size:13px;font-weight:700;color:var(--gray-900);">{{ auth()->user()->name }}</div>
                        <div style="font-size:12px;color:var(--gray-400);">{{ auth()->user()->email }}</div>
                    </div>
                    <div class="dropdown-divider"></div>
                    <a href="{{ route('notes.create') }}" class="dropdown-item"><i class="fa-regular fa-file-lines" style="width:18px;"></i> New Note</a>
                    <div class="dropdown-divider"></div>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="dropdown-item danger"><i class="fa-solid fa-right-from-bracket" style="width:18px;"></i> Sign Out</button>
                    </form>
                </div>
            </div>
        </div>
    </header>

    <!-- Sidebar -->
    <aside class="sidebar" id="sidebar">
        <a href="{{ route('notes.index') }}" class="nav-item {{ (Request::routeIs('notes.index') && !isset($currentLabel) && !isset($isArchivePage)) ? 'active' : '' }}">
            <span class="nav-icon" style="width:24px;text-align:center;"><i class="fa-regular fa-lightbulb"></i></span> Notes
        </a>
        
        <!-- Display dynamic user labels -->
        @foreach($globalLabels as $lbl)
        <a href="{{ route('labels.show', $lbl->id) }}" class="nav-item {{ (isset($currentLabel) && $currentLabel->id === $lbl->id) ? 'active' : '' }}">
            <span class="nav-icon" style="width:24px;text-align:center;"><i class="fa-solid fa-tag"></i></span> {{ $lbl->name }}
        </a>
        @endforeach

        <a href="#" class="nav-item" onclick="openEditLabelsModal(); return false;">
            <span class="nav-icon" style="width:24px;text-align:center;"><i class="fa-solid fa-pencil"></i></span> Edit labels
        </a>
        <a href="{{ route('notes.archive') }}" class="nav-item {{ isset($isArchivePage) ? 'active' : '' }}">
            <span class="nav-icon" style="width:24px;text-align:center;"><i class="fa-solid fa-box-archive"></i></span> Archive
        </a>
        <a href="{{ route('notes.trash') }}" class="nav-item {{ Request::routeIs('notes.trash') ? 'active' : '' }}">
            <span class="nav-icon" style="width:24px;text-align:center;"><i class="fa-regular fa-trash-can"></i></span> Trash
        </a>
    </aside>

    <!-- Main Content -->
    <main class="main-content">

        @if(request('search'))
        <div class="search-results-bar">
            <span>🔍 Results for "{{ request('search') }}" — {{ $notes->count() }} note{{ $notes->count() !== 1 ? 's' : '' }} found</span>
            <a href="{{ route('notes.index') }}" style="color:var(--primary);font-size:13px;text-decoration:none;font-weight:600;">Clear ✕</a>
        </div>
        <div class="page-header">
            <div class="page-title-block">
                <h1>Search Results</h1>
                <p>{{ $totalNotes }} note{{ $totalNotes !== 1 ? 's' : '' }} · Last updated {{ now()->format('M j, Y') }}</p>
            </div>
        </div>
        @elseif(isset($isArchivePage))
        <div class="page-header">
            <div class="page-title-block">
                <h1>Archive</h1>
            </div>
        </div>
        @elseif(isset($currentLabel))
        <div class="page-header">
            <div class="page-title-block">
                <h1>Label: {{ $currentLabel->name }}</h1>
            </div>
        </div>
        @endif

        {{-- Show Quick Note Creator (unless it is Archive page) --}}
        @if(!isset($isArchivePage) && !request('search'))
        <!-- Quick Note Creator -->
        <div class="quick-note-wrapper" id="quickNoteWrapper">
            <form action="{{ route('notes.store') }}" method="POST" id="quickNoteForm" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="color" id="quickNoteColor" value="default">
                <input type="hidden" name="is_archived" id="quickNoteIsArchived" value="0">
                @if(isset($currentLabel))
                    <input type="hidden" name="label_ids[]" value="{{ $currentLabel->id }}">
                @endif
                <input type="file" name="image" id="quickNoteImageInput" style="display:none;" accept="image/*" onchange="previewQuickNoteImage(this)">
                
                <div class="quick-note-card" id="quickNoteCard">
                    <!-- Image Preview Section (At the top, initially hidden) -->
                    <div id="quickNoteImagePreview" style="display:none; position:relative; overflow:hidden; border-radius: 8px 8px 0 0; max-height: 240px; border-bottom: 1px solid var(--gray-100);">
                        <img src="" style="width: 100%; height: auto; display: block; object-fit: cover;">
                        <button type="button" onclick="removeQuickNoteImage()" style="position:absolute; top:8px; right:8px; background:rgba(0,0,0,0.6); color:#fff; border:none; border-radius:50%; width:28px; height:28px; display:flex; align-items:center; justify-content:center; cursor:pointer;" title="Remove image"><i class="fa-solid fa-xmark"></i></button>
                    </div>

                    <!-- Title (Visible when expanded) -->
                    <div class="qn-title-wrap">
                        <input type="text" name="title" id="quickNoteTitle" class="qn-title" placeholder="Title">
                    </div>
                    
                    <!-- Content -->
                    <div class="qn-content-wrap">
                        <textarea name="content" class="qn-content" id="qnContent" rows="1" placeholder="Take a note..." onfocus="expandQuickNote()"></textarea>
                        
                        <!-- Unexpanded right icons -->
                        <div class="qn-icons" id="qnIcons">
                            <button type="button" class="qn-tool-btn" title="New list"><i class="fa-regular fa-square-check"></i></button>
                            <button type="button" class="qn-tool-btn" title="New note with drawing"><i class="fa-solid fa-paint-brush"></i></button>
                            <button type="button" class="qn-tool-btn" title="New note with image" onclick="document.getElementById('quickNoteImageInput').click(); expandQuickNote();"><i class="fa-regular fa-image"></i></button>
                        </div>
                    </div>

                    <!-- Footer Toolbar (Visible when expanded) -->
                    <div class="qn-footer">
                        <div class="qn-toolbar">
                            <button type="button" class="qn-tool-btn" title="Remind me"><i class="fa-regular fa-bell"></i></button>
                            <button type="button" class="qn-tool-btn" title="Collaborator"><i class="fa-solid fa-user-plus"></i></button>
                            
                            <!-- Color Picker Palette inside Quick Note Toolbar -->
                            <div style="position: relative; display: inline-block;">
                                <button type="button" class="qn-tool-btn" title="Change color" onclick="event.stopPropagation(); toggleQuickNoteColorMenu(event)">
                                    <i class="fa-solid fa-palette"></i>
                                </button>
                                <div class="color-menu" id="quickNoteColorMenu" style="display: none; position: absolute; bottom: 38px; left: 50%; transform: translateX(-50%); background: #fff; box-shadow: 0 1px 4px rgba(0,0,0,0.2); border-radius: 8px; padding: 6px; gap: 6px; z-index: 100; border: 1px solid var(--gray-200); flex-wrap: nowrap; align-items: center;">
                                    <button type="button" onclick="event.stopPropagation(); selectQuickNoteColor('default')" style="width: 24px; height: 24px; border-radius: 50%; border: 1px solid #ccc; background: #fff; cursor: pointer; display: flex; align-items: center; justify-content: center;" title="Default"><i class="fa-solid fa-ban" style="font-size: 10px; color: #5f6368;"></i></button>
                                    <button type="button" onclick="event.stopPropagation(); selectQuickNoteColor('red')" style="width: 24px; height: 24px; border-radius: 50%; border: 1px solid transparent; background: #f28b82; cursor: pointer;" title="Red"></button>
                                    <button type="button" onclick="event.stopPropagation(); selectQuickNoteColor('yellow')" style="width: 24px; height: 24px; border-radius: 50%; border: 1px solid transparent; background: #fff475; cursor: pointer;" title="Yellow"></button>
                                    <button type="button" onclick="event.stopPropagation(); selectQuickNoteColor('green')" style="width: 24px; height: 24px; border-radius: 50%; border: 1px solid transparent; background: #ccff90; cursor: pointer;" title="Green"></button>
                                    <button type="button" onclick="event.stopPropagation(); selectQuickNoteColor('blue')" style="width: 24px; height: 24px; border-radius: 50%; border: 1px solid transparent; background: #cbf0f8; cursor: pointer;" title="Blue"></button>
                                    <button type="button" onclick="event.stopPropagation(); selectQuickNoteColor('purple')" style="width: 24px; height: 24px; border-radius: 50%; border: 1px solid transparent; background: #d7aefb; cursor: pointer;" title="Purple"></button>
                                </div>
                            </div>

                            <button type="button" class="qn-tool-btn" title="Add image" onclick="document.getElementById('quickNoteImageInput').click();"><i class="fa-regular fa-image"></i></button>
                            <button type="button" class="qn-tool-btn" onclick="archiveQuickNote()" title="Archive"><i class="fa-solid fa-box-archive"></i></button>
                            <button type="button" class="qn-tool-btn" title="More"><i class="fa-solid fa-ellipsis-vertical"></i></button>
                        </div>
                        <div class="qn-actions">
                            <button type="button" class="btn btn-ghost btn-sm" style="border:none;" onclick="closeQuickNote()">Close</button>
                            <button type="submit" class="btn btn-ghost btn-sm" style="border:none;color:var(--primary);font-weight:700;">Save</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
        @endif

        {{-- Pinned Notes --}}
        @if($pinned->count() > 0)
        <div id="pinned-section">
            <div class="section-header">
                <span style="color:#5f6368;font-size:12px;transform:rotate(45deg);display:inline-block;"><i class="fa-solid fa-thumbtack"></i></span>
                <h2 style="font-size:11px;font-weight:600;color:#5f6368;text-transform:uppercase;letter-spacing:0.8px;">Pinned</h2>
                <span class="section-count">{{ $pinned->count() }}</span>
            </div>
            <div class="notes-grid">
                @foreach($pinned as $note)
                    @include('notes._card', ['note' => $note])
                @endforeach
            </div>
        </div>
        @endif

        {{-- All / Unpinned Notes --}}
        @if($unpinned->count() > 0)
        <div class="section-header">
            <span style="color:#5f6368;font-size:12px;display:inline-block;"><i class="fa-regular fa-note-sticky"></i></span>
            <h2 style="font-size:11px;font-weight:600;color:#5f6368;text-transform:uppercase;letter-spacing:0.8px;">{{ $pinned->count() > 0 ? 'Others' : 'Notes' }}</h2>
            <span class="section-count">{{ $unpinned->count() }}</span>
        </div>
        <div class="notes-grid">
            @foreach($unpinned as $note)
                @include('notes._card', ['note' => $note])
            @endforeach
        </div>
        @endif

        {{-- Empty State --}}
        @if($notes->count() === 0)
        <div class="empty-state" style="border:none;background:none;padding:120px 24px;">
            <div class="empty-icon" style="color:#e0e0e0;font-size:96px;margin-bottom:24px;line-height:1;">
                @if(request('search'))
                    <i class="fa-solid fa-magnifying-glass"></i>
                @else
                    <i class="fa-regular fa-lightbulb"></i>
                @endif
            </div>
            <div class="empty-title" style="color:#80868b;font-weight:500;">{{ request('search') ? 'No notes found' : 'No noteyyyyy' }}</div>
            <div class="empty-sub">
                @if(request('search'))
                    No notes match "{{ request('search') }}". Try a different keyword.
                @else
                @endif
            </div>
        </div>
        @endif

    </main>
</div>

<!-- Edit Note Modal (Keep Style Pop-up) -->
<div id="editNoteModal" class="modal-backdrop" onclick="closeEditModalOutside(event)">
    <div class="edit-modal-card" id="editModalCard">
        <form id="editNoteForm" enctype="multipart/form-data" onsubmit="event.preventDefault(); saveEditModal();">
            <!-- Modal Image Header -->
            <div id="editModalImageWrap" style="display:none; position:relative; overflow:hidden; border-radius: 8px 8px 0 0; max-height: 320px; border-bottom: 1px solid var(--gray-100);">
                <img id="editModalImg" src="" style="width: 100%; height: auto; display: block; object-fit: cover;">
                <button type="button" onclick="removeEditModalImage()" style="position:absolute; top:8px; right:8px; background:rgba(0,0,0,0.6); color:#fff; border:none; border-radius:50%; width:28px; height:28px; display:flex; align-items:center; justify-content:center; cursor:pointer;" title="Remove image"><i class="fa-solid fa-xmark"></i></button>
            </div>

            <!-- Title field -->
            <div style="padding: 16px 20px 0; display: flex; align-items: center; justify-content: space-between;">
                <input type="text" id="editModalTitle" name="title" placeholder="Title" style="width:100%; border:none; outline:none; font-size:18px; font-weight:600; color:var(--gray-900); font-family:inherit; background:transparent;">
                <button type="button" id="editModalPinBtn" class="card-toolbar-btn" style="color: #5f6368;" onclick="toggleEditModalPin()"><i class="fa-solid fa-thumbtack"></i></button>
            </div>

            <!-- Content textarea -->
            <div style="padding: 12px 20px 16px;">
                <textarea id="editModalContent" name="content" rows="4" placeholder="Note" style="width:100%; border:none; outline:none; font-size:15px; color:var(--gray-800); font-family:inherit; resize:none; background:transparent; padding:0; line-height: 1.5;"></textarea>
            </div>

            <!-- Hidden input for note ID, color, is_pinned -->
            <input type="hidden" id="editModalNoteId">
            <input type="hidden" id="editModalColor" name="color">
            <input type="hidden" id="editModalIsPinned" name="is_pinned">
            <input type="file" id="editModalImageInput" name="image" style="display:none;" accept="image/*" onchange="previewEditModalImage(this)">

            <!-- Modal Footer Toolbar -->
            <div style="display:flex; justify-content:space-between; align-items:center; padding:12px 20px; border-radius: 0 0 8px 8px;">
                <div style="display:flex; gap:8px;">
                    <button type="button" class="card-toolbar-btn" title="Remind me"><i class="fa-regular fa-bell"></i></button>
                    <button type="button" class="card-toolbar-btn" title="Collaborator"><i class="fa-solid fa-user-plus"></i></button>
                    
                    <!-- Color Picker inside Edit Modal -->
                    <div style="position: relative; display: inline-block;">
                        <button type="button" class="card-toolbar-btn" title="Change color" onclick="event.stopPropagation(); toggleEditModalColorMenu(event)">
                            <i class="fa-solid fa-palette"></i>
                        </button>
                        <div class="color-menu" id="editModalColorMenu" style="display: none; position: absolute; bottom: 34px; left: 50%; transform: translateX(-50%); background: #fff; box-shadow: 0 1px 4px rgba(0,0,0,0.2); border-radius: 8px; padding: 6px; gap: 6px; z-index: 1100; border: 1px solid var(--gray-200); flex-wrap: nowrap; align-items: center;">
                            <button type="button" onclick="event.stopPropagation(); changeEditModalColor('default')" style="width: 24px; height: 24px; border-radius: 50%; border: 1px solid #ccc; background: #fff; cursor: pointer; display: flex; align-items: center; justify-content: center;" title="Default"><i class="fa-solid fa-ban" style="font-size: 10px; color: #5f6368;"></i></button>
                            <button type="button" onclick="event.stopPropagation(); changeEditModalColor('red')" style="width: 24px; height: 24px; border-radius: 50%; border: 1px solid transparent; background: #f28b82; cursor: pointer;" title="Red"></button>
                            <button type="button" onclick="event.stopPropagation(); changeEditModalColor('yellow')" style="width: 24px; height: 24px; border-radius: 50%; border: 1px solid transparent; background: #fff475; cursor: pointer;" title="Yellow"></button>
                            <button type="button" onclick="event.stopPropagation(); changeEditModalColor('green')" style="width: 24px; height: 24px; border-radius: 50%; border: 1px solid transparent; background: #ccff90; cursor: pointer;" title="Green"></button>
                            <button type="button" onclick="event.stopPropagation(); changeEditModalColor('blue')" style="width: 24px; height: 24px; border-radius: 50%; border: 1px solid transparent; background: #cbf0f8; cursor: pointer;" title="Blue"></button>
                            <button type="button" onclick="event.stopPropagation(); changeEditModalColor('purple')" style="width: 24px; height: 24px; border-radius: 50%; border: 1px solid transparent; background: #d7aefb; cursor: pointer;" title="Purple"></button>
                        </div>
                    </div>

                    <button type="button" class="card-toolbar-btn" title="Add image" onclick="document.getElementById('editModalImageInput').click();"><i class="fa-regular fa-image"></i></button>
                    
                    <!-- 3 Dot Options Button inside Modal -->
                    <div style="position: relative; display: inline-block;">
                        <button type="button" class="card-toolbar-btn" title="More" onclick="event.stopPropagation(); toggleEditModalMoreMenu(event)">
                            <i class="fa-solid fa-ellipsis-vertical"></i>
                        </button>
                        <div id="editModalMoreMenu" style="display: none; position: absolute; bottom: 34px; left: 0; background: #fff; box-shadow: 0 1px 4px rgba(0,0,0,0.2); border-radius: 4px; padding: 4px 0; z-index: 1100; border: 1px solid var(--gray-200); min-width: 140px; text-align: left; flex-direction: column;">
                            <button type="button" onclick="deleteModalNote()" style="width: 100%; border: none; background: none; padding: 6px 12px; font-size: 14px; text-align: left; cursor: pointer; color: var(--danger); display: block; font-family: inherit;">Delete note</button>
                            <button type="button" onclick="duplicateModalNote()" style="width: 100%; border: none; background: none; padding: 6px 12px; font-size: 14px; text-align: left; cursor: pointer; color: var(--gray-800); display: block; font-family: inherit;">Make a copy</button>
                        </div>
                    </div>
                </div>
                <div style="display:flex; gap:8px;">
                    <button type="button" class="btn btn-ghost btn-sm" style="border:none; color:var(--gray-900); font-weight:700; font-family: inherit; font-size: 14px;" onclick="saveEditModal()">Close</button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
function expandQuickNote() {
    const card = document.getElementById('quickNoteCard');
    const content = document.getElementById('qnContent');
    if (!card.classList.contains('expanded')) {
        card.classList.add('expanded');
        content.rows = 3;
    }
}

function closeQuickNote() {
    const card = document.getElementById('quickNoteCard');
    const content = document.getElementById('qnContent');
    if(card) {
        card.classList.remove('expanded');
        content.rows = 1;
        card.classList.remove('color-blue', 'color-green', 'color-yellow', 'color-red', 'color-purple');
        document.getElementById('quickNoteColor').value = 'default';
        removeQuickNoteImage();
        document.getElementById('quickNoteForm').reset();
    }
}

// Click outside Quick Note Form -> collapse/submit
document.addEventListener('click', function(e) {
    const wrapper = document.getElementById('quickNoteWrapper');
    const colorMenu = document.getElementById('quickNoteColorMenu');
    if (wrapper && !wrapper.contains(e.target)) {
        if (colorMenu && colorMenu.contains(e.target)) return;
        
        const title = document.getElementById('quickNoteTitle').value.trim();
        const content = document.getElementById('qnContent').value.trim();
        const image = document.getElementById('quickNoteImageInput').value;
        const color = document.getElementById('quickNoteColor').value;
        
        if (title !== '' || content !== '' || image !== '' || color !== 'default') {
            document.getElementById('quickNoteForm').submit();
        } else {
            closeQuickNote();
        }
    }
});

// Quick Note Color Picker functions
function toggleQuickNoteColorMenu(event) {
    event.stopPropagation();
    const menu = document.getElementById('quickNoteColorMenu');
    menu.style.display = menu.style.display === 'none' ? 'flex' : 'none';
}

function selectQuickNoteColor(color) {
    document.getElementById('quickNoteColor').value = color;
    const card = document.getElementById('quickNoteCard');
    card.classList.remove('color-blue', 'color-green', 'color-yellow', 'color-red', 'color-purple');
    if (color !== 'default') {
        card.classList.add(`color-${color}`);
    }
    document.getElementById('quickNoteColorMenu').style.display = 'none';
}

// Quick Note Image Preview functions
function previewQuickNoteImage(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            const preview = document.getElementById('quickNoteImagePreview');
            preview.querySelector('img').src = e.target.result;
            preview.style.display = 'block';
        }
        reader.readAsDataURL(input.files[0]);
    }
}

function removeQuickNoteImage() {
    document.getElementById('quickNoteImageInput').value = '';
    const preview = document.getElementById('quickNoteImagePreview');
    preview.style.display = 'none';
    preview.querySelector('img').src = '';
}

// Note Card Color menu toggles
function toggleColorMenu(event, noteId) {
    event.stopPropagation();
    const menu = document.getElementById(`color-menu-${noteId}`);
    document.querySelectorAll('.color-menu').forEach(m => {
        if (m.id !== `color-menu-${noteId}`) m.style.display = 'none';
    });
    menu.style.display = menu.style.display === 'none' ? 'flex' : 'none';
}

function toggleCardMoreMenu(event, noteId) {
    event.stopPropagation();
    const menu = document.getElementById(`card-more-menu-${noteId}`);
    document.querySelectorAll('.card-more-menu').forEach(m => {
        if (m.id !== `card-more-menu-${noteId}`) m.style.display = 'none';
    });
    menu.style.display = menu.style.display === 'none' ? 'flex' : 'none';
}

// Close color & dropdown menus when clicking outside
document.addEventListener('click', () => {
    document.querySelectorAll('.color-menu').forEach(m => m.style.display = 'none');
    document.querySelectorAll('.card-more-menu').forEach(m => m.style.display = 'none');
    const qnMenu = document.getElementById('quickNoteColorMenu');
    if (qnMenu) qnMenu.style.display = 'none';
    
    const editColorMenu = document.getElementById('editModalColorMenu');
    if (editColorMenu) editColorMenu.style.display = 'none';
    const editMoreMenu = document.getElementById('editModalMoreMenu');
    if (editMoreMenu) editMoreMenu.style.display = 'none';
});

// AJAX note color update
function changeNoteColor(event, noteId, color) {
    event.stopPropagation();
    
    fetch(`/notes/${noteId}/color`, {
        method: 'PATCH',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({ color: color })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            const card = document.getElementById(`note-card-${noteId}`);
            card.classList.remove('color-blue', 'color-green', 'color-yellow', 'color-red', 'color-purple');
            if (color !== 'default') {
                card.classList.add(`color-${color}`);
            }
            document.getElementById(`color-menu-${noteId}`).style.display = 'none';
        }
    })
    .catch(err => console.error(err));
}

// Card image uploads
function triggerCardImageUpload(noteId) {
    document.getElementById(`card-image-input-${noteId}`).click();
}

function uploadCardImage(event, noteId) {
    const file = event.target.files[0];
    if (!file) return;

    const formData = new FormData();
    formData.append('image', file);

    fetch(`/notes/${noteId}/image`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            const card = document.getElementById(`note-card-${noteId}`);
            let imgWrap = card.querySelector('.note-card-image-wrap');
            if (!imgWrap) {
                imgWrap = document.createElement('div');
                imgWrap.className = 'note-card-image-wrap';
                imgWrap.style.cssText = "margin: -16px -16px 12px; overflow: hidden; border-radius: 8px 8px 0 0; max-height: 240px; border-bottom: 1px solid var(--gray-100);";
                const img = document.createElement('img');
                img.style.cssText = "width: 100%; height: auto; display: block; object-fit: cover;";
                imgWrap.appendChild(img);
                card.insertBefore(imgWrap, card.firstChild);
            }
            imgWrap.querySelector('img').src = data.image_url;
        }
    })
    .catch(err => console.error(err));
}

// Edit Note Modal Javascript logic
let currentModalImageFile = null;

function openEditModal(noteId, title, content, color, imageUrl, isPinned) {
    document.getElementById('editModalNoteId').value = noteId;
    document.getElementById('editModalTitle').value = title;
    document.getElementById('editModalContent').value = content;
    document.getElementById('editModalColor').value = color;
    document.getElementById('editModalIsPinned').value = isPinned ? '1' : '0';
    
    // Update Modal Pin Button Icon color/class
    const pinBtn = document.getElementById('editModalPinBtn');
    if (isPinned) {
        pinBtn.style.color = '#202124';
        pinBtn.querySelector('i').className = 'fa-solid fa-thumbtack';
    } else {
        pinBtn.style.color = '#5f6368';
        pinBtn.querySelector('i').className = 'fa-regular fa-thumbtack';
    }

    // Set color class on Modal Card
    const card = document.getElementById('editModalCard');
    card.className = 'edit-modal-card';
    if (color && color !== 'default') {
        card.classList.add(`color-${color}`);
    }

    // Handle Image wrap
    const imgWrap = document.getElementById('editModalImageWrap');
    const img = document.getElementById('editModalImg');
    if (imageUrl && imageUrl !== '') {
        img.src = imageUrl;
        imgWrap.style.display = 'block';
    } else {
        img.src = '';
        imgWrap.style.display = 'none';
    }

    // Open backdrop
    document.getElementById('editNoteModal').classList.add('active');
    
    // Reset file input
    document.getElementById('editModalImageInput').value = '';
    currentModalImageFile = null;
}

function closeEditModalOutside(event) {
    const card = document.getElementById('editModalCard');
    const colorMenu = document.getElementById('editModalColorMenu');
    const moreMenu = document.getElementById('editModalMoreMenu');
    if (!card.contains(event.target)) {
        if (colorMenu && colorMenu.contains(event.target)) return;
        if (moreMenu && moreMenu.contains(event.target)) return;
        saveEditModal();
    }
}

function saveEditModal() {
    const noteId = document.getElementById('editModalNoteId').value;
    if (!noteId) return;

    const title = document.getElementById('editModalTitle').value.trim();
    const content = document.getElementById('editModalContent').value.trim();
    const color = document.getElementById('editModalColor').value;

    const formData = new FormData();
    formData.append('_method', 'PUT');
    formData.append('title', title);
    formData.append('content', content);
    formData.append('color', color);
    
    if (currentModalImageFile) {
        formData.append('image', currentModalImageFile);
    }

    const imgWrap = document.getElementById('editModalImageWrap');
    const isImageRemoved = (imgWrap.style.display === 'none');
    if (isImageRemoved) {
        formData.append('remove_image', '1');
    }

    fetch(`/notes/${noteId}`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json'
        },
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            window.location.reload();
        }
    })
    .catch(err => {
        console.error(err);
        window.location.reload();
    });

    document.getElementById('editNoteModal').classList.remove('active');
}

function toggleEditModalColorMenu(event) {
    event.stopPropagation();
    const menu = document.getElementById('editModalColorMenu');
    menu.style.display = menu.style.display === 'none' ? 'flex' : 'none';
}

function changeEditModalColor(color) {
    document.getElementById('editModalColor').value = color;
    const card = document.getElementById('editModalCard');
    card.className = 'edit-modal-card';
    if (color !== 'default') {
        card.classList.add(`color-${color}`);
    }
    document.getElementById('editModalColorMenu').style.display = 'none';
}

function previewEditModalImage(input) {
    if (input.files && input.files[0]) {
        currentModalImageFile = input.files[0];
        const reader = new FileReader();
        reader.onload = function(e) {
            const preview = document.getElementById('editModalImageWrap');
            document.getElementById('editModalImg').src = e.target.result;
            preview.style.display = 'block';
        }
        reader.readAsDataURL(input.files[0]);
    }
}

function removeEditModalImage() {
    currentModalImageFile = null;
    document.getElementById('editModalImageInput').value = '';
    const preview = document.getElementById('editModalImageWrap');
    preview.style.display = 'none';
    document.getElementById('editModalImg').src = '';
}

function toggleEditModalMoreMenu(event) {
    event.stopPropagation();
    const menu = document.getElementById('editModalMoreMenu');
    menu.style.display = menu.style.display === 'none' ? 'flex' : 'none';
}

function deleteModalNote() {
    const noteId = document.getElementById('editModalNoteId').value;
    if (!noteId) return;

    if (confirm('Move this note to trash?')) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `/notes/${noteId}`;
        
        const csrfInput = document.createElement('input');
        csrfInput.type = 'hidden';
        csrfInput.name = '_token';
        csrfInput.value = '{{ csrf_token() }}';
        form.appendChild(csrfInput);

        const methodInput = document.createElement('input');
        methodInput.type = 'hidden';
        methodInput.name = '_method';
        methodInput.value = 'DELETE';
        form.appendChild(methodInput);

        document.body.appendChild(form);
        form.submit();
    }
}

function duplicateModalNote() {
    const noteId = document.getElementById('editModalNoteId').value;
    if (!noteId) return;

    const form = document.createElement('form');
    form.method = 'POST';
    form.action = `/notes/${noteId}/copy`;
    
    const csrfInput = document.createElement('input');
    csrfInput.type = 'hidden';
    csrfInput.name = '_token';
    csrfInput.value = '{{ csrf_token() }}';
    form.appendChild(csrfInput);

    document.body.appendChild(form);
    form.submit();
}

function toggleEditModalPin() {
    const pinVal = document.getElementById('editModalIsPinned');
    const isPinned = pinVal.value === '1';
    pinVal.value = isPinned ? '0' : '1';
    
    const pinBtn = document.getElementById('editModalPinBtn');
    if (!isPinned) {
        pinBtn.style.color = '#202124';
        pinBtn.querySelector('i').className = 'fa-solid fa-thumbtack';
    } else {
        pinBtn.style.color = '#5f6368';
        pinBtn.querySelector('i').className = 'fa-regular fa-thumbtack';
    }
    
    const noteId = document.getElementById('editModalNoteId').value;
    if (noteId) {
        fetch(`/notes/${noteId}/pin`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            // Updated pin state successfully
        })
        .catch(err => console.error(err));
    }
}

function archiveQuickNote() {
    document.getElementById('quickNoteIsArchived').value = '1';
    document.getElementById('quickNoteForm').submit();
}

function toggleViewMode() {
    const grids = document.querySelectorAll('.notes-grid');
    let isList = false;
    grids.forEach(grid => {
        isList = grid.classList.toggle('list-view');
    });
    localStorage.setItem('viewMode', isList ? 'list' : 'grid');
    updateViewModeIcon();
}

function updateViewModeIcon() {
    const grid = document.querySelector('.notes-grid');
    const btn = document.getElementById('viewModeToggleBtn');
    if (!grid || !btn) return;
    const isList = grid.classList.contains('list-view');
    btn.innerHTML = isList 
        ? `<svg focusable="false" viewBox="0 0 24 24" style="width:24px;height:24px;fill:currentColor;"><path d="M4 11h5V5H4v6zm0 7h5v-6H4v6zm6 0h5v-6h-5v6zm6 0h5v-6h-5v6zm-6-7h5V5h-5v6zm6-6v6h5V5h-5z"></path></svg>`
        : `<svg focusable="false" viewBox="0 0 24 24" style="width:24px;height:24px;fill:currentColor;"><path d="M3 13h2v-2H3v2zm0 4h2v-2H3v2zm0-8h2V7H3v2zm4 4h14v-2H7v2zm0 4h14v-2H7v2zM7 7v2h14V7H7z"></path></svg>`;
    btn.title = isList ? 'Grid view' : 'List view';
}

function toggleSettingsDropdown(event) {
    if (event) event.stopPropagation();
    const dropdown = document.getElementById('settingsDropdown');
    dropdown.style.display = dropdown.style.display === 'none' ? 'block' : 'none';
}

// Initialize Drag and Drop (SortableJS) and View Mode
document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('.notes-grid').forEach(grid => {
        new Sortable(grid, {
            animation: 150,
            ghostClass: 'sortable-ghost',
            onEnd: function (evt) {
                // Dragging visually shifts items instantly
            }
        });
    });

    // Restore View Mode from localStorage
    const savedViewMode = localStorage.getItem('viewMode');
    if (savedViewMode === 'list') {
        document.querySelectorAll('.notes-grid').forEach(grid => {
            grid.classList.add('list-view');
        });
    }
    updateViewModeIcon();
});

function toggleSidebar(event) {
    if (event) event.stopPropagation();
    const sidebar = document.getElementById('sidebar');
    const mainContent = document.querySelector('.main-content');
    sidebar.classList.toggle('closed');
    mainContent.classList.toggle('expanded');
}

function toggleUserMenu(event) {
    if (event) event.stopPropagation();
    document.getElementById('userMenu').classList.toggle('open');
}

document.addEventListener('click', function(e) {
    const menu = document.getElementById('userMenu');
    if (menu && !menu.contains(e.target)) menu.classList.remove('open');
    
    const dropdown = document.getElementById('settingsDropdown');
    if (dropdown && !dropdown.contains(e.target)) dropdown.style.display = 'none';
});

// Close sidebar on overlay click (mobile)
document.addEventListener('click', function(e) {
    const sidebar = document.getElementById('sidebar');
    const hamburger = document.querySelector('.hamburger');
    if (window.innerWidth <= 768 && sidebar && !sidebar.classList.contains('closed')) {
        if (!sidebar.contains(e.target) && !hamburger.contains(e.target)) {
            sidebar.classList.add('closed');
            document.querySelector('.main-content').classList.add('expanded');
        }
    }
});

// Initialize mobile state
if (window.innerWidth <= 768) {
    document.getElementById('sidebar').classList.add('closed');
    document.querySelector('.main-content').classList.add('expanded');
}
</script>
@endsection
