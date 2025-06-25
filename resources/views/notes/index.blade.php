@extends('layouts.app')

@section('title', 'Ghi chú')
@section('page-title', 'Ghi chú')

@section('content')
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">
            <i class="fas fa-sticky-note me-2"></i>{{ isset($editNote) ? 'Chỉnh sửa ghi chú' : 'Thêm ghi chú mới' }}
        </h6>
    </div>
    <div class="card-body">
        <form method="POST" action="{{ isset($editNote) ? route('notes.update', $editNote) : route('notes.store') }}">
            @csrf
            <div class="mb-3">
                <label for="content" class="form-label">Nội dung ghi chú <span class="text-danger">*</span></label>
                <textarea class="form-control" id="content" name="content" rows="2" required>{{ old('content', $editNote->content ?? '') }}</textarea>
            </div>
            <div class="mb-3">
                <label for="note_date" class="form-label">Ngày <span class="text-danger">*</span></label>
                <input type="date" class="form-control" id="note_date" name="note_date" value="{{ old('note_date', isset($editNote) ? (strlen($editNote->note_date) === 10 ? $editNote->note_date : \Illuminate\Support\Carbon::parse($editNote->note_date)->format('Y-m-d')) : date('Y-m-d')) }}" required>
            </div>
            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-{{ isset($editNote) ? 'warning' : 'primary' }}">
                    <i class="fas fa-save me-2"></i>{{ isset($editNote) ? 'Cập nhật' : 'Lưu ghi chú' }}
                </button>
                @if(isset($editNote))
                    <a href="{{ route('notes.index') }}" class="btn btn-secondary">Hủy</a>
                @endif
            </div>
        </form>
    </div>
</div>

<div class="card shadow">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">
            <i class="fas fa-list me-2"></i>Danh sách ghi chú
        </h6>
    </div>
    <div class="card-body p-0 px-2">
        <table class="table table-hover mb-0">
            <thead class="table-light">
                <tr>
                    <th style="width: 120px;">Ngày</th>
                    <th>Nội dung</th>
                    <th style="width: 100px;">Thao tác</th>
                </tr>
            </thead>
            <tbody>
                @forelse($notes as $note)
                    <tr>
                        <td>{{ \Carbon\Carbon::parse($note->note_date)->format('d/m/Y') }}</td>
                        <td>{{ $note->content }}</td>
                        <td>
                            <a href="{{ route('notes.edit', $note) }}" class="btn btn-sm btn-outline-warning" title="Sửa">
                                <i class="fas fa-edit"></i>
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" class="text-center text-muted py-4">Chưa có ghi chú nào.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection 