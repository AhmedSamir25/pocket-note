<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>إدارة الملاحظات</title>
    @vite('resources/css/app.css')
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="bg-gray-100 h-screen" x-data="noteStore()">
    
    <div class="navbar bg-base-100 shadow-sm">
        <div class="flex-1">
          <a class="btn btn-ghost text-xl">daisyUI</a>
        </div>
        <div class="flex gap-2">
          <input type="text" placeholder="Search" class="input input-bordered w-24 md:w-auto" />
          <div class="dropdown dropdown-end">
            <div tabindex="0" role="button" class="btn btn-ghost btn-circle avatar">
              <div class="w-10 rounded-full">
                <img
                  alt="Tailwind CSS Navbar component"
                  src="https://img.daisyui.com/images/stock/photo-1534528741775-53994a69daeb.webp" />
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
        </div>
      </div>

    <div class="max-w-2xl mx-auto bg-white p-6 rounded-lg shadow-md">
        <h1 class="text-2xl font-bold text-center mb-4">إدارة الملاحظات</h1>

        <!-- TextField يفتح الـ Dialog -->
        <input type="text" placeholder="اضغط لإضافة ملاحظة" 
               class="w-full p-3 border rounded-lg cursor-pointer"
               @click="openDialog(null, '', '')" readonly>

        <!-- قائمة الملاحظات -->
        <ul class="mt-4">
            @foreach ($notes as $note)
                <li class="p-3 bg-gray-200 rounded-lg my-2 cursor-pointer hover:bg-gray-300"
                    @click="openDialog({{ $note->id }}, '{{ $note->title }}', '{{ $note->content }}')">
                    <strong>{{ $note->title }}</strong>
                    <p class="text-sm text-gray-600">{{ Str::limit($note->content, 50) }}</p>
                </li>
            @endforeach
        </ul>
    </div>

    <!-- Dialog لإضافة أو تعديل الملاحظة -->
    <dialog x-show="showModal" class="modal modal-open">
        <div class="modal-box">
            <h3 class="text-lg font-bold">إضافة / تعديل ملاحظة</h3>
            <input type="text" x-model="title" placeholder="عنوان الملاحظة" class="w-full p-2 border my-2 rounded-lg">
            <textarea x-model="content" placeholder="المحتوى" class="w-full p-2 border my-2 rounded-lg"></textarea>
            
            <div class="modal-action">
                <button @click="saveNote()" class="btn btn-primary">حفظ</button>
                <button @click="showModal = false" class="btn">رجوع</button>
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

                openDialog(id, title, content) {
                    this.showModal = true;
                    this.noteId = id;
                    this.title = title;
                    this.content = content;
                },

                async saveNote() {
                    let url = this.noteId ? `/notes/${this.noteId}` : '/notes';
                    let method = this.noteId ? 'PUT' : 'POST';

                    await fetch(url, {
                        method: method,
                        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                        body: JSON.stringify({ title: this.title, content: this.content })
                    });

                    window.location.reload();
                }
            };
        }
    </script>

</body>
</html>
