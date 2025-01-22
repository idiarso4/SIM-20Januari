@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card">
        <div class="card-header">
            <h4>Buat Pengajuan Izin Siswa</h4>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.student-permits.store') }}" method="POST">
                @csrf
                
                @if(session('error'))
                    <div class="alert alert-danger">
                        {{ session('error') }}
                    </div>
                @endif

                <div class="form-group">
                    <label for="student_id">Nama Siswa</label>
                    <select name="student_id" id="student_id" class="form-control select2" required>
                        <option value="">-- Pilih Siswa --</option>
                        @foreach($students as $student)
                            <option value="{{ $student->id }}">
                                {{ $student->name }} - {{ optional($student->class)->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label for="teacher_id">Guru Pengaju</label>
                    <select name="teacher_id" id="teacher_id" class="form-control" required>
                        <option value="">-- Pilih Guru --</option>
                        @foreach($teachers as $teacher)
                            <option value="{{ $teacher->id }}">{{ $teacher->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label for="reason">Alasan Izin</label>
                    <textarea name="reason" id="reason" class="form-control" required rows="3"></textarea>
                </div>

                <div class="form-group">
                    <button type="submit" class="btn btn-primary">Simpan</button>
                    <a href="{{ route('admin.student-permits.index') }}" class="btn btn-secondary">Kembali</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        $('.select2').select2({
            placeholder: "Pilih Siswa",
            allowClear: true
        });
    });
</script>
@endpush 