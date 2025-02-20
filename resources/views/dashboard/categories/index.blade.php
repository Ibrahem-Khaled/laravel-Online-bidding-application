@extends('layouts.app')

@section('content')
    <div class="container">
        <h1 class="mb-4">إدارة الفئات</h1>

        <!-- زر فتح مودال الإضافة -->
        <button class="btn btn-success mb-3" data-toggle="modal" data-target="#addModal">
            <i class="fas fa-plus"></i> إضافة فئة جديدة
        </button>

        @include('components.alerts')

        <!-- جدول البيانات -->
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>الصورة</th>
                    <th>الاسم</th>
                    <th>الحالة</th>
                    <th>الإجراءات</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($categories as $category)
                    <tr>
                        <td>
                            @if ($category->image)
                                <img src="{{ $category->image }}" width="40" class="rounded-circle">
                            @else
                                <img src="https://via.placeholder.com/40" width="40" class="rounded-circle">
                            @endif
                        </td>
                        <td>{{ $category->name }}</td>
                        <td>
                            <span class="badge {{ $category->status ? 'bg-success' : 'bg-danger' }}">
                                {{ $category->status ? 'نشط' : 'غير نشط' }}
                            </span>
                        </td>
                        <td>
                            <button class="btn btn-sm btn-primary" data-toggle="modal"
                                data-target="#editModal{{ $category->id }}">
                                <i class="fas fa-edit"></i> تعديل
                            </button>
                            <button class="btn btn-sm btn-danger" data-toggle="modal"
                                data-target="#deleteModal{{ $category->id }}">
                                <i class="fas fa-trash"></i> حذف
                            </button>

                            <div class="modal fade" id="editModal{{ $category->id }}" tabindex="-1"
                                aria-labelledby="editModalLabel{{ $category->id }}" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="editModalLabel{{ $category->id }}">تعديل الفئة</h5>
                                            <button type="button" class="btn-close" data-dismiss="modal"
                                                aria-label="Close"></button>
                                        </div>
                                        <form action="{{ route('categories.update', $category->id) }}" method="POST"
                                            enctype="multipart/form-data">
                                            @csrf
                                            @method('PUT')
                                            <div class="modal-body">
                                                <div class="mb-3">
                                                    <label>الاسم</label>
                                                    <input type="text" name="name" class="form-control"
                                                        value="{{ $category->name }}" required>
                                                </div>
                                                <div class="mb-3">
                                                    <label>الصورة</label>
                                                    <input type="file" name="image" class="form-control">
                                                    @if ($category->image)
                                                        <img src="{{ $category->image }}" width="100" class="mt-2">
                                                    @endif
                                                </div>
                                                <div class="mb-3">
                                                    <label>الحالة</label>
                                                    <select name="status" class="form-control" required>
                                                        <option value="1" {{ $category->status ? 'selected' : '' }}>
                                                            نشط</option>
                                                        <option value="0" {{ !$category->status ? 'selected' : '' }}>
                                                            غير نشط</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary"
                                                    data-dismiss="modal">إغلاق</button>
                                                <button type="submit" class="btn btn-primary">تحديث</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>

                            <!-- مودال الحذف لكل فئة -->
                            <div class="modal fade" id="deleteModal{{ $category->id }}" tabindex="-1"
                                aria-labelledby="deleteModalLabel{{ $category->id }}" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="deleteModalLabel{{ $category->id }}">حذف الفئة</h5>
                                            <button type="button" class="btn-close" data-dismiss="modal"
                                                aria-label="Close"></button>
                                        </div>
                                        <form action="{{ route('categories.destroy', $category->id) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <div class="modal-body">
                                                <p>هل أنت متأكد من حذف هذه الفئة؟</p>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary"
                                                    data-dismiss="modal">إغلاق</button>
                                                <button type="submit" class="btn btn-danger">حذف</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <!-- مودال الإضافة -->
        <div class="modal fade" id="addModal" tabindex="-1" aria-labelledby="addModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addModalLabel">إضافة فئة جديدة</h5>
                        <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form id="addForm" action="{{ route('categories.store') }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        <div class="modal-body">
                            <div class="mb-3">
                                <label>الاسم</label>
                                <input type="text" name="name" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label>الصورة</label>
                                <input type="file" name="image" class="form-control">
                            </div>
                            <div class="mb-3">
                                <label>الحالة</label>
                                <select name="status" class="form-control" required>
                                    <option value="1">نشط</option>
                                    <option value="0">غير نشط</option>
                                </select>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">إغلاق</button>
                            <button type="submit" class="btn btn-primary">حفظ</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
