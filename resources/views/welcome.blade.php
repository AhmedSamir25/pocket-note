<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pocket Note</title>
    @vite('resources/css/app.css')
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="bg-gray-100 h-screen overflow-auto" x-data="{ noteStore: noteStore(), tagStore: tagStore() }">
    
    <div class="navbar shadow-sm bg-white">
        <div class="flex-1 text-black">
          <a class="btn btn-ghost text-xl">Pocket Note</a>
        </div>
        <div class="flex gap-2">
            <label class="input bg-gray-300 rounded-lg">
                <svg class="h-[1em] opacity-50 text-slate-600" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><g stroke-linejoin="round" stroke-linecap="round" stroke-width="2.5" fill="none" stroke="currentColor"><circle cx="11" cy="11" r="8"></circle><path d="m21 21-4.3-4.3"></path></g></svg>
                <input type="search" required placeholder="Search"/>
              </label>
              @auth  
          <div class="dropdown dropdown-end">
            <div tabindex="0" role="button" class="btn btn-ghost btn-circle avatar">
              <div class="w-10 rounded-full">
                <img
                  alt="Tailwind CSS Navbar component"
                  src="https://images.unsplash.com/photo-1741487431784-fd2a1db916bb?q=80&w=1587&auto=format&fit=crop&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D" />
              </div>
            </div>
            <ul
              tabindex="0"
              class="menu menu-sm dropdown-content bg-base-100 rounded-box z-1 mt-3 w-52 p-2 shadow">
              <li>
                <a class="justify-between">
                  Profile
                  <span class="badge">New</span>
                </a>
              </li>
              <li><a>Settings</a></li>
              <li><a>Logout</a></li>
            </ul>
          </div>
          @else
                    <a href="{{ route('login') }}" class="bg-blue-500 text-white p-2 rounded">Login</a>
            @endauth
        </div>
      </div>
      {{-- Drawer --}}
      <div class="drawer bg-white p-2">
        <input id="my-drawer" type="checkbox" class="drawer-toggle" />
        <div class="drawer-content">
          <!-- Page content here -->
          <label for="my-drawer" class="btn btn-primary drawer-button w-15 h-5 bg-black">Open</label>
        </div>
        <div class="drawer-side">
          <label for="my-drawer" aria-label="close sidebar" class="drawer-overlay"></label>
          <ul class="menu bg-base-200 text-base-content min-h-full w-80 p-4">
            <!-- Sidebar content here -->
            <div>
                <div class="flex flex-row justify-between">
                    <li><a>Tags</a></li>
                    <li><a @click="tagStore.openDialog(null, '')">Add Tag</a></li>
                    </div>
                    <ul class="mt-4">
                        @foreach ($tags as $tag)
                            <li class=" bg-gray-400 rounded-lg my-2 cursor-pointer hover:bg-gray-500 h-7"
                                @click="tagStore.openDialog({{ $tag->id }}, '{{ $tag->name }}')">
                                <strong class="text-black">{{ $tag->name }}</strong>
                            </li>
                        @endforeach
                    </ul>
            </div>
          </ul>
        </div>
      </div>
        {{--  --}}
        <div class="h-screen mx-auto bg-white p-6 rounded-lg shadow-md">
            <input type="text" placeholder="Add New Note" 
                   class="w-full p-3 border rounded-lg cursor-pointer bg-gray-300"
                   @click="noteStore.openDialog(null, '', '')" readonly>
        
            <!-- تحويل القائمة إلى Grid -->
            <div class="mt-4 grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                @foreach ($notes as $note)
                    <div class="p-4 bg-gray-200 rounded-lg cursor-pointer hover:bg-gray-300 shadow"
                        @click="noteStore.openDialog(
                            {{ $note->id }}, 
                            '{{ $note->title }}', 
                            `{{ $note->content }}`, 
                            {{ $note->tags->first() ? $note->tags->first()->id : 'null' }}
                        )">
                        <strong class="text-black text-lg">{{ $note->title }}</strong>
                        <p class="text-sm text-gray-600 mt-2">{{ Str::limit($note->content, 50) }}</p>
                        @if ($note->tags->isNotEmpty())
                            <span class="text-sm text-gray-700 block mt-2">Tag: {{ $note->tags->first()->name }}</span>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>
        

        <dialog x-ref="modal" x-show="noteStore.showModal" @click.away="noteStore.showModal = false" class="modal modal-open">
            <div class="modal-box max-w-6xl h-10/12 p-6">
                <h3 class="text-lg font-bold">Add / Edit Note</h3>
                <input type="text" x-model="noteStore.title" placeholder="Title" class="w-full p-2 border my-2 rounded-lg">
                <textarea x-model="noteStore.content" placeholder="Content" class="w-full p-2 border my-2 rounded-lg h-80"></textarea>
        
                <!-- Dropdown for Tags -->
                <div class="relative">
                    <label class="block text-sm font-medium text-gray-700">Tags</label>
                    <div x-data="{ open: false, selectedTag: noteStore.selectedTagName || 'Select tags' }" class="mt-1">
                        <!-- Selected Tag Display -->
                        <div x-show="selectedTag && selectedTag !== 'Select tags'" class="p-2 bg-gray-700 rounded-lg mb-2">
                            <span x-text="selectedTag"></span>
                            <button @click="selectedTag = 'Select tags'; noteStore.selectedTagId = null;" class="ml-2 text-red-500">×</button>
                        </div>
        
                        <!-- Dropdown Button -->
                        <button @click="open = !open" class="w-full p-2 border rounded-lg text-left bg-black">
                            <span x-text="selectedTag"></span>
                        </button>
        
                        <!-- Dropdown Menu -->
                        <div x-show="open" @click.away="open = false" class="absolute z-10 mt-1 w-full bg-black border rounded-lg shadow-lg">
                            <ul class="max-h-48 overflow-y-auto">
                                @foreach ($tags as $tag)
                                    <li @click="selectedTag = '{{ $tag->name }}'; noteStore.selectedTagId = {{ $tag->id }}; open = false;"
                                        class="p-2 hover:bg-gray-100 cursor-pointer">
                                        {{ $tag->name }}
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
        
                <div class="modal-action">
                    <button @click="noteStore.saveNote(); $refs.modal.close();" class="btn btn-primary p-3">Save</button>
                    <button @click="noteStore.showModal = false; $refs.modal.close();" class="btn p-2">Back</button>
                </div>
            </div>
        </dialog>
        
    

    <dialog x-show="tagStore.showModal" class="modal modal-open">
        <div class="modal-box">
            <h3 class="text-lg font-bold">Add / Edit Tag</h3>
            <input type="text" x-model="tagStore.name" placeholder="Tag Name" class="w-full p-2 border my-2 rounded-lg">
            
            <div class="modal-action">
                <button @click="tagStore.saveTag()" class="btn btn-primary p-3">Save</button>
                <button @click="tagStore.showModal = false" class="btn p-2">Back</button>
            </div>
        </div>
    </dialog>

    <script>
        function noteStore() {
    return {
        showModal: false,
        noteId: null,
        title: '',
        content: '',
        selectedTagId: null,

        openDialog(id, title, content, tagId = null) {
            this.showModal = true;
            this.noteId = id;
            this.title = title;
            this.content = content; // النص مع الأسطر الجديدة
            this.selectedTagId = tagId;
        },

        async saveNote() {
            let url = this.noteId ? `/notes/${this.noteId}` : '/notes';
            let method = this.noteId ? 'PUT' : 'POST';

            try {
                const response = await fetch(url, {
                    method: method,
                    headers: { 
                        'Content-Type': 'application/json', 
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ 
                        title: this.title, 
                        content: this.content, // النص مع الأسطر الجديدة
                        tag_id: this.selectedTagId
                    })
                });

                if (response.ok) {
                    window.location.reload();
                } else {
                    const errorData = await response.json();
                    alert(errorData.message || 'Failed to save the note.');
                }
            } catch (error) {
                console.error('Error:', error);
                alert('An error occurred while saving the note.');
            }
        }
    };
}

        function tagStore() {
            return {
                showModal: false,
                tagId: null,
                name: '',

                openDialog(id, name) {
                    this.showModal = true;
                    this.tagId = id;
                    this.name = name;
                },

                async saveTag() {
                    let url = this.tagId ? `/tags/${this.tagId}` : '/tags';
                    let method = this.tagId ? 'PUT' : 'POST';

                    const response = await fetch(url, {
                        method: method,
                        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                        body: JSON.stringify({ name: this.name })
                    });

                    if (response.ok) {
                        window.location.reload();
                    } else {
                        alert('Failed to save the tag.');
                    }
                }
            };
        }
    </script>

</body>
</html>