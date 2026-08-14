@extends('layouts.app')

@section('title', 'Tambah Kehadiran')

@section('content')
     <style>
          @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Sora:wght@600;700;800&display=swap');

          .attf-page {
               min-height: calc(100vh - 70px);
               padding: 26px 18px 44px;
               color: #16324b;
               font-family: 'Plus Jakarta Sans', 'Segoe UI', sans-serif;
               background:
                    radial-gradient(circle at 8% 7%, rgba(20, 184, 166, .12), transparent 24%),
                    radial-gradient(circle at 94% 9%, rgba(14, 165, 233, .13), transparent 26%),
                    linear-gradient(155deg, #f8fcff 0%, #eff8ff 50%, #edf9f8 100%);
          }

          .attf-wrap {
               max-width: 1500px;
               margin: 0 auto;
          }

          .attf-hero {
               display: flex;
               align-items: center;
               justify-content: space-between;
               gap: 14px;
               padding: 24px 28px;
               margin-bottom: 16px;
               border-radius: 22px;
               color: #fff;
               background: linear-gradient(124deg, #0f766e 0%, #0369a1 56%, #0ea5e9 100%);
               box-shadow: 0 20px 46px rgba(3, 105, 161, .25);
          }

          .attf-hero h1 {
               margin: 0;
               font-family: 'Sora', 'Plus Jakarta Sans', sans-serif;
               font-size: clamp(1.08rem, 2vw, 1.55rem);
               font-weight: 700;
          }

          .attf-hero p {
               margin: 7px 0 0;
               font-size: .84rem;
               color: rgba(255, 255, 255, .9);
          }

          .attf-icon-btn {
               display: inline-flex;
               width: 42px;
               height: 42px;
               align-items: center;
               justify-content: center;
               border-radius: 12px;
               border: 1px solid rgba(255, 255, 255, .35);
               color: #fff;
               background: rgba(255, 255, 255, .14);
               text-decoration: none;
               transition: .18s ease;
          }

          .attf-icon-btn:hover {
               color: #fff;
               transform: translateY(-2px);
               background: rgba(255, 255, 255, .24);
          }

          .attf-card {
               border: 1px solid #d6e4f0;
               border-radius: 22px;
               box-shadow: 0 16px 36px rgba(15, 23, 42, .08);
               background: #fff;
          }
     </style>

     <div class="attf-page">
          <div class="attf-wrap">
               <section class="attf-hero">
                    <div>
                         <h1>Tambah Data Kehadiran</h1>
                         <p>Dashboard Monitoring Produktivitas Karyawan Dan Transaksi Jasa</p>
                    </div>

                    <a href="{{ route('super-admin.attendances.index') }}" class="attf-icon-btn" aria-label="Kembali"
                         title="Kembali">
                         <i data-feather="arrow-left"></i>
                    </a>
               </section>

               <section class="card attf-card">
                    <div class="card-body p-4">
                         <form action="{{ route('super-admin.attendances.store') }}" method="POST">
                              @csrf
                              @include('super-admin.attendances._form', [
                                  'attendance' => $attendance,
                                  'employees' => $employees,
                                  'statuses' => $statuses,
                              ])

                              <div class="mt-4 d-flex gap-2 justify-content-end">
                                   <a href="{{ route('super-admin.attendances.index') }}" class="btn btn-outline-secondary"
                                        aria-label="Batal" title="Batal">
                                        <i data-feather="x"></i>
                                   </a>

                                   <button type="submit" class="btn btn-primary" aria-label="Simpan" title="Simpan">
                                        <i data-feather="save"></i>
                                   </button>
                              </div>
                         </form>
                    </div>
               </section>
          </div>
     </div>

     @once
          <script>
               document.addEventListener('DOMContentLoaded', function() {
                    if (typeof feather !== 'undefined') {
                         feather.replace();
                    }
               });
          </script>
     @endonce
@endsection
