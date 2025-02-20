@extends('layouts.app')

@section('content')
    <div class="container">
        <h1 class="mb-4">إدارة السلايدرات</h1>

        <button class="btn btn-success mb-3" data-toggle="modal" data-target="#addModal">
            <i class="fas fa-plus"></i> إضافة سلايدر جديد
        </button>

        @include('components.alerts')

        <table class="table table-hover">
            <thead>
                <tr>
                    <th>الصورة</th>
                    <th>العنوان</th>
                    <th>الحالة</th>
                    <th>القسم</th>
                    <th>الإجراءات</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($sliders as $slider)
                    <tr>
                        <td>
                            <img src="{{ $slider->image }}" width="100" class="img-thumbnail">
                        </td>
                        <td>{{ $slider->title }}</td>
                        <td>
                            <span class="badge {{ $slider->status ? 'bg-success' : 'bg-danger' }}">
                                {{ $slider->status ? 'نشط' : 'غير نشط' }}
                            </span>
                        </td>
                        <td>{{ $slider->category->name ?? 'عااام' }}</td>
                        <td>
                            <button class="btn btn-sm btn-primary edit-btn" data-id="{{ $slider->id }}"
                                data-title="{{ $slider->title }}" data-status="{{ $slider->status }}"
                                data-category="{{ $slider->category_id }}" data-image="{{ $slider->image }}"
                                data-toggle="modal" data-target="#editModal">
                                <i class="fas fa-edit"></i>
                            </button>
                            <form action="{{ route('sliders.destroy', $slider->id) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger"
                                    onclick="return confirm('هل أنت متأكد؟')">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <!-- مودال الإضافة -->
        <div class="modal fade" id="addModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form action="{{ route('sliders.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="modal-header">
                            <h5 class="modal-title">إضافة سلايدر جديد</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="form-group">
                                <label>العنوان</label>
                                <input type="text" name="title" class="form-control">
                            </div>
                            <div class="form-group">
                                <label>الصورة</label>
                                <input type="file" name="image" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label>الحالة</label>
                                <select name="status" class="form-control" required>
                                    <option value="1">نشط</option>
                                    <option value="0">غير نشط</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>القسم</label>
                                <select name="category_id" class="form-control">
                                    <option value="">اختر القسم</option>
                                    @foreach ($categories as $category)
                                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                                    @endforeach
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

        <!-- مودال التعديل -->
        <div class="modal fade" id="editModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form id="editForm" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="modal-header">
                            <h5 class="modal-title">تعديل السلايدر</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="form-group">
                                <label>العنوان</label>
                                <input type="text" name="title" id="editTitle" class="form-control">
                            </div>
                            <div class="form-group">
                                <label>الصورة</label>
                                <input type="file" name="image" class="form-control">
                                <img id="editImagePreview" src="" width="100" class="mt-2">
                            </div>
                            <div class="form-group">
                                <label>الحالة</label>
                                <select name="status" id="editStatus" class="form-control" required>
                                    <option value="1">نشط</option>
                                    <option value="0">غير نشط</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>القسم</label>
                                <select name="category_id" id="editCategory" class="form-control">
                                    <option value="">اختر القسم</option>
                                    @foreach ($categories as $category)
                                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">إغلاق</button>
                            <button type="submit" class="btn btn-primary">تحديث</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // تعبئة بيانات التعديل
            document.querySelectorAll('.edit-btn').forEach(button => {
                button.addEventListener('click', function() {
                    const id = this.dataset.id;
                    const title = this.dataset.title;
                    const status = this.dataset.status;
                    const category = this.dataset.category;
                    const image = this.dataset.image;

                    document.getElementById('editTitle').value = title;
                    document.getElementById('editStatus').value = status;
                    document.getElementById('editCategory').value = category;
                    document.getElementById('editImagePreview').src = image;

                    document.getElementById('editForm').action = `/dashboard/sliders`;
                });
            });
        });
    </script>
@endsection
