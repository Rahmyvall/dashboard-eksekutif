@extends('layouts.app')

@section('page-title', 'Edit Pesanan Layanan')

@push('styles')
     <style>
          .so-form-page {
               min-height: calc(100vh - 70px);
               padding: 28px 16px 40px;
               background: radial-gradient(circle at 10% 10%, rgba(245, 158, 11, .14), transparent 22%), #f8fafc;
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
               background: linear-gradient(120deg, #b45309, #ea580c);
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
               color: #9a3412;
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
               background: #b45309;
               color: #fff;
          }
     </style>
@endpush

@section('content')
     <div class="so-form-page">
          <div class="so-form-wrap">
               <div class="so-form-hero">
                    <div>
                         <h4><i class="bi bi-pencil-square me-2"></i>Edit Pesanan Layanan</h4>
                         <p>Perbarui data order dan detail layanan secara terstruktur.</p>
                    </div>
                    <a href="{{ route('super-admin.service-orders.show', $serviceOrder) }}" class="so-btn so-btn-back">
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

                         <form method="POST" action="{{ route('super-admin.service-orders.update', $serviceOrder) }}">
                              @method('PUT')
                              @include('super-admin.service-orders._form')
                              <div class="mt-4 d-flex justify-content-end">
                                   <button type="submit" class="so-btn so-submit">
                                        <i class="bi bi-save2"></i>Update Pesanan
                                   </button>
                              </div>
                         </form>
                    </div>
               </div>
          </div>
     </div>
@endsection
