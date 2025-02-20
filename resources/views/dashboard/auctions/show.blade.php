@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="card mb-4">
            <div class="card-header bg-primary text-white">
                <h4 class="mb-0">تفاصيل المزاد</h4>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <h5 class="card-title">{{ $auction->title }}</h5>
                        <p class="card-text">{{ $auction->description }}</p>
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item">
                                <strong>السعر الابتدائي:</strong> {{ $auction->start_price }} 
                            </li>
                            <li class="list-group-item">
                                <strong>الحالة:</strong>
                                <span class="badge {{ $auction->status ? 'bg-success' : 'bg-danger' }}">
                                    {{ $auction->status ? 'نشط' : 'مغلق' }}
                                </span>
                            </li>
                            <li class="list-group-item">
                                <strong>التاريخ:</strong> {{ $auction->created_at->format('Y-m-d') }}
                            </li>
                            <li class="list-group-item">
                                <strong>المستخدم:</strong> {{ $auction->user->name }}
                            </li>
                            <li class="list-group-item">
                                <strong>القسم:</strong> {{ $auction->category->name ?? 'N/A' }}
                            </li>
                        </ul>
                    </div>
                    <div class="col-md-6">
                        @if ($auction->images)
                            <img src="{{ asset('storage/' . $auction->images[0]) }}" class="img-fluid rounded"
                                alt="صورة المزاد">
                        @else
                            <img src="https://via.placeholder.com/400x300" class="img-fluid rounded" alt="صورة افتراضية">
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- عرض العروض (Offers) -->
        <div class="card">
            <div class="card-header bg-info text-white">
                <h4 class="mb-0">عروض المزاد</h4>
            </div>
            <div class="card-body">
                @if ($auction->offers->isEmpty())
                    <div class="alert alert-warning">لا توجد عروض حتى الآن.</div>
                @else
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>المستخدم</th>
                                    <th>السعر</th>
                                    <th>ملاحظة</th>
                                    <th>التاريخ</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($auction->offers as $offer)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $offer->user->name }}</td>
                                        <td>{{ $offer->offer_price }}</td>
                                        <td>{{ $offer->note ?? 'لا توجد ملاحظات' }}</td>
                                        <td>{{ $offer->created_at->format('Y-m-d H:i') }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>

        <!-- زر العودة -->
        <div class="mt-4">
            <a href="{{ route('auctions.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> العودة
            </a>
        </div>
    </div>
@endsection
