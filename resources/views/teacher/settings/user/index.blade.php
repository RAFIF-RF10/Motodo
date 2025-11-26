@extends('layout.app')

@section('content')
<section class="p-6 mt-6">

    <!-- Header -->
    <div class="relative mb-6 rounded-3xl overflow-hidden shadow-xl border border-white/20 bg-gradient-to-r from-[#0064E0] to-[#0082FB] text-white p-8">
        <div class="relative z-10">
            <h1 class="text-3xl font-bold mb-2">Daftar Siswa</h1>
            <p class="text-white/80 text-sm">Kelola data siswa dan role masing-masing</p>
        </div>
        <div class="absolute right-0 bottom-0 opacity-20 pointer-events-none select-none">
            {{ svg('hugeicons-book-02', 'w-48 h-48') }}
        </div>
    </div>

    <!-- Search + Tambah -->
    <div class="flex flex-col md:flex-row md:justify-between md:items-end gap-4 mb-4">
        <div class="flex-grow">
            <input id="searchInput" type="text" placeholder="Cari nama atau email..."
                class="w-full px-4 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-400 dark:bg-gray-800 dark:text-gray-200">
        </div>
        <div class="flex gap-2">
            <button id="openAddModal" class="bg-blue-500 cursor-pointer text-white px-4 py-2 rounded-lg hover:bg-blue-600 transition">Tambah User</button>
        </div>
    </div>

    <!-- Table -->
    <div class="overflow-x-auto bg-white dark:bg-gray-800 rounded-xl shadow-md border border-gray-200 dark:border-gray-700 relative">
        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700" id="userTable">
            <thead class="bg-[#0064E0] dark:bg-gray-700">
                <tr>
                    <th class="px-6 py-3 text-left text-sm font-semibold text-white">#</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold text-white">Nama</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold text-white">Email</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold text-white">Role</th>
                    <th class="px-6 py-3 text-center text-sm font-semibold text-white">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                @foreach($users as $index => $user)
                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                    <td class="px-6 py-4 text-sm text-gray-700 dark:text-gray-300">{{ $index + 1 }}</td>
                    <td class="px-6 py-4 font-medium text-gray-900 dark:text-gray-100">{{ $user->name }}</td>
                    <td class="px-6 py-4 text-gray-600 dark:text-gray-400">{{ $user->email }}</td>
                    <td class="px-6 py-4 text-gray-700 dark:text-gray-300">{{ $roles->firstWhere('id', $user->role_id)?->name ?? '-' }}</td>
                    <td class="px-6 py-4 text-center flex justify-center gap-2">
                        <button class="openEditModal cursor-pointer bg-yellow-400 text-white px-3 py-1 rounded hover:bg-yellow-500 transition"
                            data-id="{{ $user->id }}" data-name="{{ $user->name }}" data-email="{{ $user->email }}" data-role="{{ $user->role_id }}">Edit</button>
                        <form action="{{ route('users.destroy', $user->id) }}" method="POST" onsubmit="return confirmDelete(event);">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="bg-red-500 text-white px-3 py-1 rounded hover:bg-red-600 transition">Hapus</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <div id="noResults" class="hidden text-center py-6 text-gray-500 dark:text-gray-400">Tidak ada user ditemukan.</div>
    </div>

</section>
<!-- Modal Tambah -->
<div id="addModal" data-turbo="false" class="hidden  w-full bg-black/50 flex items-center justify-center absolute inset-0 z-50">
    <div class="bg-white rounded-lg w-96 p-6 shadow-lg">
        <h2 class="text-xl font-bold mb-4">Tambah User</h2>
        <form action="{{ route('users.store') }}" method="POST">
            @csrf
            <input type="text" name="name" placeholder="Nama" class="w-full border rounded px-3 py-2 mb-3" required>
            <input type="email" name="email" placeholder="Email" class="w-full border rounded px-3 py-2 mb-3" required>
            <input type="password" name="password" placeholder="Password" class="w-full border rounded px-3 py-2 mb-3" required>
            <input type="password" name="password_confirmation" placeholder="Konfirmasi Password" class="w-full border rounded px-3 py-2 mb-3" required>
            <select name="role_id" class="w-full border rounded px-3 py-2 mb-3">
                @foreach($roles as $role)
                    <option value="{{ $role->id }}">{{ $role->name }}</option>
                @endforeach
            </select>
            <div class="flex justify-end space-x-2">
                <button type="button" id="closeAddModal" class="px-4 py-2 cursor-pointer bg-gray-400 text-white rounded hover:bg-gray-500">Batal</button>
                <button type="submit" class="px-4 py-2 bg-green-500 cursor-pointer text-white rounded hover:bg-green-600">Simpan</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Edit -->
<div id="editModal" class="hidden w-full bg-black/50 flex items-center justify-center absolute inset-0 z-50">
    <div class="bg-white rounded-lg w-96 p-6 shadow-lg">
        <h2 class="text-xl font-bold mb-4">Edit User</h2>
        <form id="editForm" data-turbo="false" method="POST">
            @csrf
            @method('PATCH')
            <input type="text" name="name" id="editName" class="w-full border rounded px-3 py-2 mb-3" required>
            <input type="email" name="email" id="editEmail" class="w-full border rounded px-3 py-2 mb-3" required>
            <input type="password" name="password" placeholder="Password Baru (kosongkan jika tidak ganti)" class="w-full border rounded px-3 py-2 mb-3">
            <input type="password" name="password_confirmation" placeholder="Konfirmasi Password Baru" class="w-full border rounded px-3 py-2 mb-3">
            <select name="role_id" id="editRole" class="w-full border rounded px-3 py-2 mb-3">
                @foreach($roles as $role)
                    <option value="{{ $role->id }}">{{ $role->name }}</option>
                @endforeach
            </select>
            <div class="flex justify-end space-x-2">
                <button type="button" id="closeEditModal" class="px-4 py-2 cursor-pointer bg-gray-400 text-white rounded hover:bg-gray-500">Batal</button>
                <button type="submit" class="px-4 py-2 cursor-pointer bg-yellow-500 text-white rounded hover:bg-yellow-600">Update</button>
            </div>
        </form>
    </div>
</div>
@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    // Delete confirm
    window.confirmDelete = function(e) {
        e.preventDefault();
        const form = e.target.closest('form');
        Swal.fire({
            title: 'Yakin hapus?',
            text: "Data ini tidak bisa dikembalikan.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya, hapus'
        }).then((result) => {
            if(result.isConfirmed && form) form.submit();
        });
    }

    const addModal = document.getElementById('addModal');
    const editModal = document.getElementById('editModal');
    const editForm = document.getElementById('editForm');
    const editName = document.getElementById('editName');
    const editEmail = document.getElementById('editEmail');
    const editRole = document.getElementById('editRole');

    document.getElementById('openAddModal').addEventListener('click', () => addModal.classList.remove('hidden'));
    document.getElementById('closeAddModal').addEventListener('click', () => addModal.classList.add('hidden'));

    document.getElementById('closeEditModal').addEventListener('click', () => editModal.classList.add('hidden'));

    document.body.addEventListener('click', function(e) {
        if(e.target && e.target.matches('.openEditModal')) {
            const button = e.target;
            editForm.action = '/settings/users/' + button.dataset.id;
            editName.value = button.dataset.name;
            editEmail.value = button.dataset.email;

            Array.from(editRole.options).forEach(option => {
                option.selected = option.value == button.dataset.role;
            });

            editModal.classList.remove('hidden');
        }
    });
  const searchInput = document.getElementById('searchInput');
const tableBody = document.querySelector('#userTable tbody');
const noResults = document.getElementById('noResults');

searchInput.addEventListener('input', () => {
    const filter = searchInput.value.toLowerCase();
    let visible = 0;

    tableBody.querySelectorAll('tr').forEach(row => {
        const name = row.querySelector('td:nth-child(2)').textContent.toLowerCase();
        const email = row.querySelector('td:nth-child(3)').textContent.toLowerCase();

        if(name.includes(filter) || email.includes(filter)) {
            row.style.display = '';
            visible++;
        } else {
            row.style.display = 'none';
        }
    });

    // Tampilkan pesan kalau nggak ada hasil
    noResults.classList.toggle('hidden', visible > 0);
});
</script>
@endpush


@endsection
