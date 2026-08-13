@extends('layouts.app')

@section('title', 'Tambah Pengeluaran - Dashboard Monitoring Produktivitas Karyawan Dan Transaksi Jasa')

@section('content')
     <style>
          .expense-form-page {
               min-height: calc(100vh - 70px);
               padding: 22px 18px 36px;
               background:
                    radial-gradient(circle at 7% 8%, rgba(14, 165, 233, 0.12), transparent 24%),
                    radial-gradient(circle at 93% 10%, rgba(20, 184, 166, 0.1), transparent 30%),
                    linear-gradient(160deg, #f8fcff 0%, #f1f7fb 48%, #eef4fa 100%);
          }

          .expense-shell {
               max-width: 1280px;
               margin: 0 auto;
          }

          .expense-header {
               display: flex;
               justify-content: space-between;
               align-items: center;
               gap: 14px;
               margin-bottom: 16px;
               padding: 22px;
               border-radius: 18px;
               color: #fff;
               background: linear-gradient(124deg, #0f766e 0%, #0369a1 52%, #0ea5e9 100%);
               box-shadow: 0 20px 42px rgba(3, 105, 161, 0.24);
          }

          .expense-header h4 {
               margin: 0;
               font-weight: 800;
               letter-spacing: -0.01em;
          }

          .expense-header p {
               margin: 6px 0 0;
               color: rgba(255, 255, 255, 0.9);
          }

          .expense-header .btn .bi {
               width: 16px;
               height: 16px;
               font-size: 16px;
          }

          .expense-header .btn.icon-only {
               width: 42px;
               min-width: 42px;
               height: 42px;
               padding: 0;
          }
     </style>

     <div class="expense-form-page">
          <div class="expense-shell">
               <div class="expense-header">
                    <div>
                         <h4>Tambah Pengeluaran</h4>
                         <p>Input biaya operasional untuk analitik produktivitas dan transaksi jasa.</p>
                    </div>
                    <a href="{{ route('super-admin.expenses.index') }}"
                         class="btn btn-light border-0 d-inline-flex align-items-center justify-content-center icon-only"
                         aria-label="Kembali" title="Kembali">
                         <i class="bi bi-arrow-left" aria-hidden="true"></i>
                    </a>
               </div>

               @if ($errors->any())
                    <div class="alert alert-danger">
                         <ul class="mb-0">
                              @foreach ($errors->all() as $error)
                                   <li>{{ $error }}</li>
                              @endforeach
                         </ul>
                    </div>
               @endif

               <form action="{{ route('super-admin.expenses.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @include('super-admin.expenses.partials.form', [
                        'expense' => $expense,
                        'orders' => $orders,
                        'selectedOrder' => $selectedOrder,
                    ])
               </form>
          </div>
     </div>
@endsection
