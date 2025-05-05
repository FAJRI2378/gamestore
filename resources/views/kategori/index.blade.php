@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="mb-0">📁 Daftar Kategori</h3>
        <div>
            <a href="{{ route('kategori.create') }}" class="btn btn-success">
                <i class="fa fa-plus-circle"></i> Tambah Kategori
            </a>
            <a href="{{ route('home') }}" class="btn btn-secondary">
                <i class="fa fa-arrow-left"></i> Kembali
            </a>
        </div>
    </div>

    <div class="card shadow rounded-4">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle text-center">
                    <thead class="table-dark rounded-top">
                        <tr>
                            <th>Nama</th>
                            <th style="width: 200px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($kategoris as $kategori)
                            <tr>
                                <td>{{ $kategori->nama }}</td>
                                <td>
                                    <a href="{{ route('kategori.edit', $kategori->id) }}" class="btn btn-warning btn-sm me-2">
                                        <i class="fa fa-edit"></i> Edit
                                    </a>
                                    <form action="{{ route('kategori.destroy', $kategori->id) }}" method="POST" class="d-inline delete-form">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" class="btn btn-danger btn-sm btn-delete"
                                            data-nama="{{ $kategori->nama }}">
                                            <i class="fa fa-trash"></i> Hapus
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="2" class="text-muted">Belum ada kategori yang tersedia.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if(method_exists($kategoris, 'links'))
    <div class="mt-3">
        {{ $kategoris->links() }}
    </div>
@endif
         
        </div>
    </div>
</div>

<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const deleteButtons = document.querySelectorAll('.btn-delete');

        deleteButtons.forEach(button => {
            button.addEventListener('click', function () {
                const form = this.closest('.delete-form');
                const kategoriNama = this.dataset.nama;

                Swal.fire({
                    title: `Hapus kategori "${kategoriNama}"?`,
                    text: "Data yang dihapus tidak dapat dikembalikan!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Ya, hapus!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit(); // Only submit the form if confirmed
                    }
                });
            });
        });

        // Show success alert if a success message is set in the session
        @if(session('success'))
        Swal.fire({
            title: 'Berhasil!',
            text: '{{ session("success") }}',
            icon: 'success',
            timer: 2000,
            showConfirmButton: false
        });
        @endif
    });
</script>
@endsection
