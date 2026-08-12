@extends('layouts.app')

@section('page-title', 'Buat Pesanan Layanan')

@push('styles')
     <style>
          .so-form-page {
               min-height: calc(100vh - 70px);
               padding: 28px 16px 40px;
               background: radial-gradient(circle at 10% 10%, rgba(14, 165, 233, .12), transparent 22%), #f8fafc;
          }

          .so-form-wrap {
               max-width: 1200px;
               margin: 0 auto;
          }

          .so-form-hero {
               border-radius: 18px;
               color: #fff;
               padding: 22px;
               margin-bottom: 16px;
               background: linear-gradient(120deg, #0f766e, #0284c7);
               display: flex;
               justify-content: space-between;
               align-items: center;
               gap: 14px;
          }

          .so-form-hero h4 {
               margin: 0;
               font-weight: 800;
          }

          .so-form-hero p {
               margin: 6px 0 0;
               opacity: .9;
          }

          .so-btn {
               display: inline-flex;
               align-items: center;
               gap: 8px;
               border: 0;
               border-radius: 12px;
               padding: 10px 14px;
               font-weight: 700;
               text-decoration: none;
          }

          .so-btn-back {
               color: #0f766e;
               background: #fff;
          }

          .so-panel {
               border: 1px solid #e2e8f0;
               border-radius: 18px;
               background: #fff;
               box-shadow: 0 14px 30px rgba(15, 23, 42, .06);
          }

          .so-panel .card-body {
               padding: 22px;
          }

          .so-submit {
               background: #0f766e;
               color: #fff;
          }
     </style>
@endpush

@section('content')
     <div class="so-form-page">
          <div class="so-form-wrap">
               <div class="so-form-hero">
                    <div>
                         <h4><i class="bi bi-plus-circle me-2"></i>Buat Pesanan Layanan</h4>
                         <p>Isi data order dan item layanan dengan struktur yang jelas.</p>
                    </div>
                    <a href="{{ route('super-admin.service-orders.index') }}" class="so-btn so-btn-back">
                         <i class="bi bi-arrow-left"></i>Kembali
                    </a>
               </div>

               <div class="card so-panel">
                    <div class="card-body">
                         @if ($errors->any())
                              <div class="alert alert-danger">
                                   <ul class="mb-0">
                                        @foreach ($errors->all() as $error)
                                             <li>{{ $error }}</li>
                                        @endforeach
                                   </ul>
                              </div>
                         @endif

                         <form method="POST" action="{{ route('super-admin.service-orders.store') }}">
                              @include('super-admin.service-orders._form')
                              <div class="mt-4 d-flex justify-content-end">
                                   <button type="submit" class="so-btn so-submit">
                                        <i class="bi bi-check2-circle"></i>Simpan Pesanan
                                   </button>
                              </div>
                         </form>
                    </div>
               </div>
          </div>
     </div>
@endsection
