@extends('layouts.app')

@section('content')
    <div class="container">
        <h1 class="mb-4">إدارة المزادات</h1>

        <!-- زر فتح مودال الإضافة -->
        <button class="btn btn-success mb-3" data-toggle="modal" data-target="#addModal">
            <i class="fas fa-plus"></i> إضافة مزاد جديد
        </button>

        <!-- جدول البيانات -->
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>الصور</th>
                    <th>العنوان</th>
                    <th>المستخدم</th>
                    <th>القسم</th>
                    <th>السعر</th>
                    <th>الحالة</th>
                    <th>الإجراءات</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($auctions as $auction)
                    <tr>
                        <td>
                            @if (count($auction->images) > 0)
                                <div class="d-flex gap-2">
                                    @foreach ($auction->images as $image)
                                        <img src="{{ asset('storage/' . $image) }}" class="img-thumbnail" width="60"
                                            alt="صورة المزاد">
                                    @endforeach
                                </div>
                            @else
                                <span class="text-muted">لا توجد صور</span>
                            @endif
                        </td>
                        <td>{{ $auction->title }}</td>
                        <td>{{ $auction->user->name }}</td>
                        <td>{{ $auction->category->name }}</td>
                        <td>
                            {{ number_format($auction->start_price) }} -
                            {{ $auction->end_price ? number_format($auction->end_price) : 'مفتوح' }}
                        </td>
                        <td>
                            <span class="badge {{ $auction->status ? 'bg-success' : 'bg-danger' }}">
                                {{ $auction->status ? 'نشط' : 'مغلق' }}
                            </span>
                        </td>
                        <td>
                            <button class="btn btn-sm btn-primary edit-btn" data-id="{{ $auction->id }}"
                                data-toggle="modal" data-target="#editModal">
                                <i class="fas fa-edit"></i> تعديل
                            </button>
                            <button class="btn btn-sm btn-danger delete-btn" data-id="{{ $auction->id }}"
                                data-toggle="modal" data-target="#deleteModal">
                                <i class="fas fa-trash"></i> حذف
                            </button>
                            <a href="{{ route('auctions.show', $auction->id) }}" class="btn btn-sm btn-info">
                                <i class="fas fa-eye"></i> عرض
                            </a>
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
                        <h5 class="modal-title" id="addModalLabel">إضافة مزاد جديد</h5>
                        <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form id="addForm" action="{{ route('auctions.store') }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        <div class="modal-body">
                            <div class="mb-3">
                                <label>العنوان</label>
                                <input type="text" name="title" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label>المستخدم</label>
                                <select name="user_id" class="form-control" required>
                                    @foreach (App\Models\User::all() as $user)
                                        <option value="{{ $user->id }}">{{ $user->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-3">
                                <label>القسم</label>
                                <select name="category_id" class="form-control" required>
                                    @foreach (App\Models\Category::all() as $category)
                                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-3">
                                <label>الصور</label>
                                <input type="file" name="images[]" class="form-control" multiple>
                            </div>
                            <div class="mb-3">
                                <label>السعر الابتدائي</label>
                                <input type="number" name="start_price" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label>السعر النهائي</label>
                                <input type="number" name="end_price" class="form-control">
                            </div>
                            <div class="mb-3">
                                <label>الحالة</label>
                                <select name="status" class="form-control" required>
                                    <option value="1">نشط</option>
                                    <option value="0">مغلق</option>
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
        <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editModalLabel">تعديل المزاد</h5>
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
                        <h5 class="modal-title" id="deleteModalLabel">حذف المزاد</h5>
                        <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form id="deleteForm" method="POST">
                        @csrf
                        @method('DELETE')
                        <div class="modal-body">
                            <p>هل أنت متأكد من حذف هذا المزاد؟</p>
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
        // تحميل بيانات المزاد في مودال التعديل
        $('.edit-btn').click(function() {
            var auctionId = $(this).data('id');
            $.get("{{ route('auctions.show', '') }}/" + auctionId, function(data) {
                $('#editForm').attr('action', "{{ route('auctions.update', '') }}/" + auctionId);
                $('#editForm .modal-body').html(`
                <div class="mb-3">
                    <label>العنوان</label>
                    <input type="text" name="title" class="form-control" value="${data.title}" required>
                </div>
                <div class="mb-3">
                    <label>المستخدم</label>
                    <select name="user_id" class="form-control" required>
                        @foreach (App\Models\User::all() as $user)
                        <option value="{{ $user->id }}" ${data.user_id == {{ $user->id }} ? 'selected' : ''}>{{ $user->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-3">
                    <label>القسم</label>
                    <select name="category_id" class="form-control" required>
                        @foreach (App\Models\Category::all() as $category)
                        <option value="{{ $category->id }}" ${data.category_id == {{ $category->id }} ? 'selected' : ''}>{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-3">
                    <label>الصور</label>
                    <input type="file" name="images[]" class="form-control" multiple>
                </div>
                <div class="mb-3">
                    <label>السعر الابتدائي</label>
                    <input type="number" name="start_price" class="form-control" value="${data.start_price}" required>
                </div>
                <div class="mb-3">
                    <label>السعر النهائي</label>
                    <input type="number" name="end_price" class="form-control" value="${data.end_price}">
                </div>
                <div class="mb-3">
                    <label>الحالة</label>
                    <select name="status" class="form-control" required>
                        <option value="1" ${data.status ? 'selected' : ''}>نشط</option>
                        <option value="0" ${!data.status ? 'selected' : ''}>مغلق</option>
                    </select>
                </div>
            `);
            });
        });

        // تعيين رابط الحذف في مودال الحذف
        $('.delete-btn').click(function() {
            var auctionId = $(this).data('id');
            $('#deleteForm').attr('action', "{{ route('auctions.destroy', '') }}/" + auctionId);
        });
    </script>
@endsection
