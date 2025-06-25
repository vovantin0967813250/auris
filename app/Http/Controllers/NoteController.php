<?php

namespace App\Http\Controllers;

use App\Models\Note;
use Illuminate\Http\Request;

class NoteController extends Controller
{
    // Hiển thị form và danh sách ghi chú
    public function index()
    {
        $notes = Note::orderByDesc('note_date')->orderByDesc('created_at')->get();
        return view('notes.index', compact('notes'));
    }

    // Lưu ghi chú mới
    public function store(Request $request)
    {
        $validated = $request->validate([
            'content' => 'required|string',
            'note_date' => 'required|date',
        ]);
        Note::create($validated);
        return redirect()->route('notes.index')->with('success', 'Đã thêm ghi chú!');
    }

    // Hiển thị form sửa ghi chú (dùng chung view index)
    public function edit($id)
    {
        $notes = Note::orderByDesc('note_date')->orderByDesc('created_at')->get();
        $editNote = Note::findOrFail($id);
        return view('notes.index', compact('notes', 'editNote'));
    }

    // Cập nhật ghi chú
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'content' => 'required|string',
            'note_date' => 'required|date',
        ]);
        $note = Note::findOrFail($id);
        $note->update($validated);
        return redirect()->route('notes.index')->with('success', 'Đã cập nhật ghi chú!');
    }
}
