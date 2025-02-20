@extends('layouts.app')

@section('content')
    <div class="container">
        <h1 class="mb-4">إدارة المستخدمين</h1>

        <!-- زر فتح مودال الإضافة -->
        <button class="btn btn-success mb-3" data-toggle="modal" data-target="#addModal">
            <i class="fas fa-plus"></i> إضافة مستخدم جديد
        </button>

        @include('components.alerts')
        <!-- جدول البيانات -->
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>الصورة</th>
                    <th>الاسم</th>
                    <th>البريد</th>
                    <th>رقم الهاتف</th>
                    <th>الدور</th>
                    <th>الحالة</th>
                    <th>الإجراءات</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($users as $user)
                    <tr>
                        <td>
                            @if ($user->avatar)
                                <img src="{{ asset('storage/' . $user->avatar) }}" width="40" class="rounded-circle">
                            @else
                                <img src="https://via.placeholder.com/40" width="40" class="rounded-circle">
                            @endif
                        </td>
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->email ?? 'N/A' }}</td>
                        <td>{{ $user->phone ?? 'N/A' }}</td>
                        <td>{{ $user->role == 'admin' ? 'مدير' : 'مستخدم' }}</td>
                        <td>
                            <span class="badge {{ $user->status ? 'bg-success' : 'bg-danger' }}">
                                {{ $user->status ? 'نشط' : 'غير نشط' }}
                            </span>
                        </td>
                        <td>
                            <button class="btn btn-sm btn-primary edit-btn" data-id="{{ $user->id }}"
                                data-toggle="modal" data-target="#editModal">
                                <i class="fas fa-edit"></i> تعديل
                            </button>
                            <button class="btn btn-sm btn-danger delete-btn" data-id="{{ $user->id }}"
                                data-toggle="modal" data-target="#deleteModal">
                                <i class="fas fa-trash"></i> حذف
                            </button>
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
                        <h5 class="modal-title" id="addModalLabel">إضافة مستخدم جديد</h5>
                        <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form id="addForm" action="{{ route('users.store') }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        <div class="modal-body">
                            <div class="mb-3">
                                <label>الاسم الكامل</label>
                                <input type="text" name="name" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label>البريد الإلكتروني</label>
                                <input type="email" name="email" class="form-control">
                            </div>
                            <div class="mb-3">
                                <label>الهاتف</label>
                                <input type="text" name="phone" class="form-control">
                            </div>
                            <div class="mb-3">
                                <label>العنوان</label>
                                <input type="text" name="address" class="form-control">
                            </div>
                            <div class="mb-3">
                                <label>الصورة</label>
                                <input type="file" name="avatar" class="form-control">
                            </div>
                            <div class="mb-3">
                                <label>الدور</label>
                                <select name="role" class="form-control" required>
                                    <option value="user">مستخدم</option>
                                    <option value="admin">مدير</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label>الحالة</label>
                                <select name="status" class="form-control" required>
                                    <option value="1">نشط</option>
                                    <option value="0">غير نشط</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label>كلمة المرور</label>
                                <input type="password" name="password" class="form-control" required>
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
        <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editModalLabel">تعديل المستخدم</h5>
                        <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form id="editForm" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="modal-body">
                            <!-- سيتم تعبئة الحقول هنا باستخدام JavaScript -->
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">إغلاق</button>
                            <button type="submit" class="btn btn-primary">تحديث</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- مودال الحذف -->
        <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="deleteModalLabel">حذف المستخدم</h5>
                        <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form id="deleteForm" method="POST">
                        @csrf
                        @method('DELETE')
                        <div class="modal-body">
                            <p>هل أنت متأكد من حذف هذا المستخدم؟</p>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">إغلاق</button>
                            <button type="submit" class="btn btn-danger">حذف</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- إضافة jQuery و AJAX -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        // تحميل بيانات المستخدم في مودال التعديل
        $('.edit-btn').click(function() {
            var userId = $(this).data('id');
            $.get("{{ route('users.show', '') }}/" + userId, function(data) {
                $('#editForm').attr('action', "{{ route('users.update', '') }}/" + userId);
                $('#editForm .modal-body').html(`
                <div class="mb-3">
                    <label>الاسم الكامل</label>
                    <input type="text" name="name" class="form-control" value="${data.name}" required>
                </div>
                <!-- باقي الحقول بنفس النمط -->
            `);
            });
        });

        // تعيين رابط الحذف في مودال الحذف
        $('.delete-btn').click(function() {
            var userId = $(this).data('id');
            $('#deleteForm').attr('action', "{{ route('users.destroy', '') }}/" + userId);
        });
    </script>
@endsection
