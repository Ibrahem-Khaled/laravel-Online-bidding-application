@extends('layouts.app')

@section('content')
    <div class="container">
        <h1 class="mb-4">إدارة البثوث المباشرة</h1>

        <!-- زر فتح مودال الإضافة -->
        <button class="btn btn-success mb-3" data-toggle="modal" data-target="#addModal">
            <i class="fas fa-plus"></i> إضافة بث جديد
        </button>

        <!-- نموذج البحث والترتيب -->
        <form action="{{ route('live-streamings.index') }}" method="GET" class="mb-4">
            <div class="row">
                <div class="col-md-4">
                    <input type="text" name="search" class="form-control" placeholder="بحث بالعنوان"
                        value="{{ request('search') }}">
                </div>
                <div class="col-md-3">
                    <select name="status" class="form-control">
                        <option value="1" {{ request('status') == '1' ? 'selected' : '' }}>نشط</option>
                        <option value="">كل الحالات</option>
                        <option value="0" {{ request('status') == '0' ? 'selected' : '' }}>غير نشط</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <select name="sort" class="form-control">
                        <option value="title_asc" {{ request('sort') == 'title_asc' ? 'selected' : '' }}>العنوان من أ إلى ي
                        </option>
                        <option value="title_desc" {{ request('sort') == 'title_desc' ? 'selected' : '' }}>العنوان من ي إلى
                            أ</option>
                        <option value="created_at_asc" {{ request('sort') == 'created_at_asc' ? 'selected' : '' }}>الأقدم
                            أولاً</option>
                        <option value="created_at_desc" {{ request('sort') == 'created_at_desc' ? 'selected' : '' }}>الأحدث
                            أولاً</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary w-100">بحث</button>
                </div>
            </div>
        </form>

        @include('components.alerts')

        <!-- جدول البيانات -->
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>الصورة</th>
                    <th>العنوان</th>
                    <th>المستخدم</th>
                    <th>القسم</th>
                    <th>البث المباشر</th>
                    <th>الحالة</th>
                    <th>الإجراءات</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($streams as $stream)
                    <tr>
                        <td>
                            @if ($stream->image)
                                <img src="{{ asset('storage/' . $stream->image) }}" width="40" class="rounded-circle">
                            @else
                                <img src="https://via.placeholder.com/40" width="40" class="rounded-circle">
                            @endif
                        </td>
                        <td>{{ $stream->title }}</td>
                        <td>{{ $stream->user->name }}</td>
                        <td>{{ $stream->category->name ?? 'N/A' }}</td>
                        <td>{{ $stream->live_stream_id ?? 'N/A' }}</td>
                        <td>
                            <span class="badge {{ $stream->status ? 'bg-success' : 'bg-danger' }}">
                                {{ $stream->status ? 'نشط' : 'غير نشط' }}
                            </span>
                        </td>
                        <td>
                            <button class="btn btn-sm btn-primary" data-toggle="modal"
                                data-target="#editModal{{ $stream->id }}">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button class="btn btn-sm btn-danger" data-toggle="modal"
                                data-target="#deleteModal{{ $stream->id }}">
                                <i class="fas fa-trash"></i>
                            </button>
                        </td>
                    </tr>

                    <!-- مودال التعديل لكل بث -->
                    <div class="modal fade" id="editModal{{ $stream->id }}" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">تعديل البث</h5>
                                    <button type="button" class="btn-close" data-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>
                                <form action="{{ route('live-streamings.update', $stream->id) }}" method="POST"
                                    enctype="multipart/form-data">
                                    @csrf
                                    @method('PUT')
                                    <div class="modal-body">
                                        <div class="mb-3">
                                            <label>العنوان</label>
                                            <input type="text" name="title" class="form-control"
                                                value="{{ $stream->title }}" required>
                                        </div>
                                        <div class="mb-3">
                                            <label>المستخدم</label>
                                            <select name="user_id" class="form-control" required>
                                                @foreach (App\Models\User::all() as $user)
                                                    <option value="{{ $user->id }}"
                                                        {{ $user->id == $stream->user_id ? 'selected' : '' }}>
                                                        {{ $user->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="mb-3">
                                            <label>القسم</label>
                                            <select name="category_id" class="form-control">
                                                <option value="">اختر القسم</option>
                                                @foreach (App\Models\Category::all() as $category)
                                                    <option value="{{ $category->id }}"
                                                        {{ $category->id == $stream->category_id ? 'selected' : '' }}>
                                                        {{ $category->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="mb-3">
                                            <label>الصورة</label>
                                            <input type="file" name="image" class="form-control">
                                            @if ($stream->image)
                                                <img src="{{ asset('storage/' . $stream->image) }}" width="100"
                                                    class="mt-2">
                                            @endif
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

                    <!-- مودال الحذف لكل بث -->
                    <div class="modal fade" id="deleteModal{{ $stream->id }}" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">حذف البث</h5>
                                    <button type="button" class="btn-close" data-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>
                                <form action="{{ route('live-streamings.destroy', $stream->id) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <div class="modal-body">
                                        <p>هل أنت متأكد من حذف هذا البث؟</p>
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
                @endforeach
            </tbody>
        </table>

        <!-- مودال الإضافة -->
        <div class="modal fade" id="addModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">إضافة بث جديد</h5>
                        <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form action="{{ route('live-streamings.store') }}" method="POST" enctype="multipart/form-data">
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
                                <select name="category_id" class="form-control">
                                    <option value="">اختر القسم</option>
                                    @foreach (App\Models\Category::all() as $category)
                                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-3">
                                <label>الصورة</label>
                                <input type="file" name="image" class="form-control">
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary">حفظ</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="d-flex justify-content-center mt-4">
        {{ $streams->links() }}
    </div>
@endsection
