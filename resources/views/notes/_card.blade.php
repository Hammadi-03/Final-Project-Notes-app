@php
$colorMap = [
    'default' => '',
    'blue'    => 'color-blue',
    'green'   => 'color-green',
    'yellow'  => 'color-yellow',
    'red'     => 'color-red',
    'purple'  => 'color-purple',
];
$colorClass = $colorMap[$note->color] ?? '';
@endphp
<div class="note-card {{ $note->is_pinned ? 'pinned' : '' }} {{ $colorClass }}" id="note-card-{{ $note->id }}" onclick="openEditModal({{ $note->id }}, '{{ addslashes($note->title) }}', '{{ addslashes($note->content) }}', '{{ $note->color }}', '{{ $note->image ? asset('storage/' . $note->image) : '' }}', {{ $note->is_pinned ? 'true' : 'false' }})" style="cursor:pointer;">
    <!-- Card Image Header -->
    @if($note->image)
    <div class="note-card-image-wrap" style="margin: -16px -16px 12px; overflow: hidden; border-radius: 8px 8px 0 0; max-height: 240px; border-bottom: 1px solid var(--gray-100);">
        <img src="{{ asset('storage/' . $note->image) }}" style="width: 100%; height: auto; display: block; object-fit: cover;">
    </div>
    @endif

    <!-- Checkmark (top-left, visible on hover) -->
    <div class="note-card-check">
        <i class="fa-solid fa-circle-check"></i>
    </div>

    <!-- Pin button (top-right) -->
    <form method="POST" action="{{ route('notes.pin', $note) }}" style="margin:0;padding:0;">
        @csrf
        <button type="submit" class="note-card-pin" title="{{ $note->is_pinned ? 'Unpin note' : 'Pin note' }}" onclick="event.stopPropagation();">
            <i class="fa-solid fa-thumbtack" style="{{ $note->is_pinned ? 'color:#202124;' : 'color:#5f6368;' }}"></i>
        </button>
    </form>

    <!-- Content -->
    <div style="flex:1; display: flex; flex-direction: column; justify-content: space-between;">
        <div>
            <h3 class="note-title" style="margin-right:24px;">{{ $note->title }}</h3>
            @if($note->content)
                <p class="note-content">{{ $note->content }}</p>
            @else
                <p class="note-content" style="font-style:italic;color:#9ca3af;">No content...</p>
            @endif
        </div>

        <!-- Label Pills -->
        @if($note->labels->count() > 0)
        <div style="display:flex; flex-wrap:wrap; gap:6px; margin-top:8px; margin-bottom:8px;">
            @foreach($note->labels as $label)
            <span class="label-pill">
                <i class="fa-solid fa-tag"></i>
                <span>{{ $label->name }}</span>
            </span>
            @endforeach
        </div>
        @endif
    </div>

    <!-- Bottom Container for Time & Toolbar -->
    <div style="height: 28px; display: flex; align-items: center; margin-top: 12px; position: relative; width: 100%;">
        <!-- Time footer (visible before hover) -->
        <span class="note-card-time" style="position: absolute; left: 0; bottom: 6px;">
            {{ $note->updated_at->diffForHumans() }}
        </span>

        <!-- Hover Toolbar (visible on hover) -->
        <div class="note-card-toolbar" style="position: absolute; left: 0; right: 0; bottom: 0;">
            <button type="button" class="card-toolbar-btn" title="Remind me" onclick="event.stopPropagation();"><i class="fa-regular fa-bell"></i></button>
            <button type="button" class="card-toolbar-btn" title="Collaborator" onclick="event.stopPropagation();"><i class="fa-solid fa-user-plus"></i></button>
            
            <!-- Color Picker Palette inside Card Toolbar -->
            <div style="position: relative; display: inline-block;">
                <button type="button" class="card-toolbar-btn" title="Change color" onclick="event.stopPropagation(); toggleColorMenu(event, {{ $note->id }})">
                    <i class="fa-solid fa-palette"></i>
                </button>
                <div class="color-menu" id="color-menu-{{ $note->id }}" style="display: none; position: absolute; bottom: 32px; left: 50%; transform: translateX(-50%); background: #fff; box-shadow: 0 1px 4px rgba(0,0,0,0.2); border-radius: 8px; padding: 6px; gap: 6px; z-index: 100; border: 1px solid var(--gray-200); flex-wrap: nowrap; align-items: center;">
                    <button type="button" onclick="event.stopPropagation(); changeNoteColor(event, {{ $note->id }}, 'default')" style="width: 24px; height: 24px; border-radius: 50%; border: 1px solid #ccc; background: #fff; cursor: pointer; display: flex; align-items: center; justify-content: center;" title="Default"><i class="fa-solid fa-ban" style="font-size: 10px; color: #5f6368;"></i></button>
                    <button type="button" onclick="event.stopPropagation(); changeNoteColor(event, {{ $note->id }}, 'red')" style="width: 24px; height: 24px; border-radius: 50%; border: 1px solid transparent; background: #f28b82; cursor: pointer;" title="Red"></button>
                    <button type="button" onclick="event.stopPropagation(); changeNoteColor(event, {{ $note->id }}, 'yellow')" style="width: 24px; height: 24px; border-radius: 50%; border: 1px solid transparent; background: #fff475; cursor: pointer;" title="Yellow"></button>
                    <button type="button" onclick="event.stopPropagation(); changeNoteColor(event, {{ $note->id }}, 'green')" style="width: 24px; height: 24px; border-radius: 50%; border: 1px solid transparent; background: #ccff90; cursor: pointer;" title="Green"></button>
                    <button type="button" onclick="event.stopPropagation(); changeNoteColor(event, {{ $note->id }}, 'blue')" style="width: 24px; height: 24px; border-radius: 50%; border: 1px solid transparent; background: #cbf0f8; cursor: pointer;" title="Blue"></button>
                    <button type="button" onclick="event.stopPropagation(); changeNoteColor(event, {{ $note->id }}, 'purple')" style="width: 24px; height: 24px; border-radius: 50%; border: 1px solid transparent; background: #d7aefb; cursor: pointer;" title="Purple"></button>
                </div>
            </div>

            <!-- Image Upload Trigger inside Card Toolbar -->
            <button type="button" class="card-toolbar-btn" title="Add image" onclick="event.stopPropagation(); triggerCardImageUpload({{ $note->id }})">
                <i class="fa-regular fa-image"></i>
            </button>
            <input type="file" id="card-image-input-{{ $note->id }}" style="display: none;" accept="image/*" onchange="uploadCardImage(event, {{ $note->id }})">

            <!-- Quick Archive Button inside Card Toolbar -->
            <form method="POST" action="{{ route('notes.toggleArchive', $note) }}" style="margin:0;padding:0;display:inline-block;">
                @csrf
                <button type="submit" class="card-toolbar-btn" title="{{ $note->is_archived ? 'Unarchive' : 'Archive' }}" onclick="event.stopPropagation();">
                    <i class="fa-solid fa-box-archive"></i>
                </button>
            </form>

            <!-- 3-Dot Options Button -->
            <div style="position: relative; display: inline-block;">
                <button type="button" class="card-toolbar-btn" title="More" onclick="event.stopPropagation(); toggleCardMoreMenu(event, {{ $note->id }})">
                    <i class="fa-solid fa-ellipsis-vertical"></i>
                </button>
                <div class="card-more-menu" id="card-more-menu-{{ $note->id }}" style="display: none; position: absolute; bottom: 32px; left: 0; background: #fff; box-shadow: 0 1px 4px rgba(0,0,0,0.2); border-radius: 4px; padding: 4px 0; z-index: 100; border: 1px solid var(--gray-200); min-width: 140px; text-align: left; flex-direction: column;">
                    <form method="POST" action="{{ route('notes.destroy', $note) }}" style="margin:0;padding:0;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" onclick="event.stopPropagation();" style="width: 100%; border: none; background: none; padding: 6px 12px; font-size: 14px; text-align: left; cursor: pointer; color: var(--danger); display: block; font-family: inherit;">Delete note</button>
                    </form>
                    <form method="POST" action="{{ route('notes.duplicate', $note) }}" style="margin:0;padding:0;">
                        @csrf
                        <button type="submit" onclick="event.stopPropagation();" style="width: 100%; border: none; background: none; padding: 6px 12px; font-size: 14px; text-align: left; cursor: pointer; color: var(--gray-800); display: block; font-family: inherit;">Make a copy</button>
                    </form>
                    @if($globalLabels->count() > 0)
                    <div style="border-top: 1px solid var(--gray-200); padding-top: 4px; margin-top: 4px;">
                        <div style="padding: 4px 12px; font-size: 10px; font-weight: 700; color: var(--gray-400); text-transform: uppercase; letter-spacing: 0.5px;">Change Labels</div>
                        @foreach($globalLabels as $label)
                        <label onclick="event.stopPropagation();" style="display: flex; align-items: center; gap: 8px; padding: 4px 12px; font-size: 13px; cursor: pointer; color: var(--gray-700); font-family: inherit; font-weight: 500;">
                            <input type="checkbox" 
                                   onchange="toggleNoteLabel({{ $note->id }}, {{ $label->id }}, this.checked)" 
                                   {{ $note->labels->contains($label->id) ? 'checked' : '' }} 
                                   style="width: 14px; height: 14px; cursor: pointer;">
                            <span style="text-overflow:ellipsis; overflow:hidden; white-space:nowrap;">{{ $label->name }}</span>
                        </label>
                        @endforeach
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
