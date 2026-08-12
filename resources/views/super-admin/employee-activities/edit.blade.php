@extends('layouts.app')

@section('title', 'Edit Aktivitas Pegawai')

@section('content')
     <style>
          .ea-edit-page {
               min-height: calc(100vh - 70px);
               padding: 28px 18px 42px;
               background: radial-gradient(circle at 8% 10%, rgba(20, 184, 166, .12), transparent 24%), radial-gradient(circle at 94% 7%, rgba(37, 99, 235, .12), transparent 26%), linear-gradient(145deg, #f9fcff 0%, #f8fbff 45%, #f1f8ff 100%);
          }

          .ea-edit-container {
               max-width: 1560px;
               margin: 0 auto;
          }

          .ea-edit-hero {
               display: flex;
               gap: 18px;
               align-items: center;
               justify-content: space-between;
               padding: 28px;
               margin-bottom: 20px;
               color: #fff;
               border-radius: 26px;
               background: linear-gradient(125deg, #0f766e 0%, #0284c7 46%, #2563eb 100%);
               box-shadow: 0 20px 48px rgba(14, 116, 144, .22);
          }

          .ea-edit-icon {
               display: inline-flex;
               width: 62px;
               height: 62px;
               align-items: center;
               justify-content: center;
               color: #0f766e;
               border-radius: 18px;
               background: rgba(255, 255, 255, .95);
          }

          .ea-edit-icon svg {
               width: 28px;
               height: 28px;
          }

          .ea-edit-title {
               display: flex;
               gap: 15px;
               align-items: center;
          }

          .ea-edit-hero h1 {
               margin: 0;
               font-size: clamp(1.6rem, 2.5vw, 2.2rem);
               font-weight: 850;
               letter-spacing: -.03em;
          }

          .ea-edit-hero p {
               margin: 7px 0 0;
               color: rgba(255, 255, 255, .92);
          }

          .ea-edit-back {
               display: inline-flex;
               min-height: 44px;
               padding: 10px 15px;
               align-items: center;
               gap: 8px;
               color: #fff;
               font-weight: 800;
               text-decoration: none;
               border: 1px solid rgba(255, 255, 255, .35);
               border-radius: 13px;
               background: rgba(255, 255, 255, .12);
          }

          .ea-edit-card {
               overflow: hidden;
               border: 1px solid #e5ecf6;
               border-radius: 24px;
               background: rgba(255, 255, 255, .98);
               box-shadow: 0 18px 40px rgba(51, 65, 85, .08);
          }

          .ea-edit-card-head {
               padding: 22px 24px;
               border-bottom: 1px solid #edf2f7;
               background: linear-gradient(90deg, #ffffff 0%, #f6fbff 100%);
          }

          .ea-edit-card-head h2 {
               margin: 0;
               color: #24324a;
               font-size: 1.05rem;
               font-weight: 840;
          }

          .ea-edit-card-head p {
               margin: 5px 0 0;
               color: #6b7a90;
               font-size: .82rem;
          }

          .ea-edit-card-body {
               padding: 24px;
          }

          .ea-edit-actions {
               display: flex;
               gap: 10px;
               justify-content: flex-end;
               margin-top: 20px;
          }

          .ea-edit-btn,
          .ea-edit-btn-secondary {
               display: inline-flex;
               min-height: 46px;
               padding: 10px 16px;
               align-items: center;
               justify-content: center;
               gap: 8px;
               font-weight: 800;
               text-decoration: none;
               border-radius: 13px;
          }

          .ea-edit-btn {
               color: #fff;
               border: 0;
               background: linear-gradient(135deg, #0f766e, #0284c7);
          }

          .ea-edit-btn-secondary {
               color: #475569;
               border: 1px solid #dbe5f1;
               background: #fff;
          }

          @media (max-width: 991px) {
               .ea-edit-hero {
                    flex-direction: column;
                    align-items: flex-start;
               }
          }

          @media (max-width: 575px) {
               .ea-edit-page {
                    padding: 18px 12px 34px;
               }

               .ea-edit-card-body {
                    padding: 18px;
               }

               .ea-edit-actions {
                    flex-direction: column-reverse;
               }

               .ea-edit-btn,
               .ea-edit-btn-secondary,
               .ea-edit-back {
                    width: 100%;
               }
          }
     </style>

     <div class="ea-edit-page">
          <div class="ea-edit-container">
               <section class="ea-edit-hero">
                    <div class="ea-edit-title">
                         <span class="ea-edit-icon"><i data-feather="edit-3"></i></span>
                         <div>
                              <h1>Edit Aktivitas Pegawai</h1>
                              <p>Perbarui detail aktivitas, service order terkait, dan status verifikasi secara terstruktur.
                              </p>
                         </div>
                    </div>
                    <a href="{{ route('super-admin.employee-activities.show', $employeeActivity) }}" class="ea-edit-back"><i
                              data-feather="arrow-left"></i> Kembali ke detail</a>
               </section>

               <section class="ea-edit-card">
                    <div class="ea-edit-card-head">
                         <h2>Form Perubahan Aktivitas</h2>
                         <p>Aktivitas: {{ $employeeActivity->activity_name }} | Pegawai:
                              {{ $employeeActivity->employee?->full_name ?? '-' }}</p>
                    </div>
                    <div class="ea-edit-card-body">
                         <form method="POST"
                              action="{{ route('super-admin.employee-activities.update', $employeeActivity) }}">
                              @csrf
                              @method('PUT')
                              @include('super-admin.employee-activities._form')
                              <div class="ea-edit-actions">
                                   <a href="{{ route('super-admin.employee-activities.show', $employeeActivity) }}"
                                        class="ea-edit-btn-secondary">Batal</a>
                                   <button type="submit" class="ea-edit-btn">Perbarui Aktivitas</button>
                              </div>
                         </form>
                    </div>
               </section>
          </div>
     </div>
@endsection
