@extends('layouts.app')

@section('title', 'Tambah Performance Indicator')

@section('content')
     <style>
          :root {
               --indicator-primary: #6366f1;
               --indicator-primary-dark: #4f46e5;
               --indicator-secondary: #06b6d4;
               --indicator-purple: #8b5cf6;
          }

          .indicator-page {
               min-height: calc(100vh - 70px);
               padding: 30px 18px 44px;
               background:
                    radial-gradient(circle at 5% 5%, rgba(129, 140, 248, .20), transparent 24%),
                    radial-gradient(circle at 96% 8%, rgba(34, 211, 238, .20), transparent 25%),
                    linear-gradient(145deg, #fbfdff 0%, #f7f5ff 46%, #f0fbff 100%);
          }

          .indicator-container {
               max-width: 1440px;
               margin: 0 auto;
          }

          .indicator-hero {
               position: relative;
               overflow: hidden;
               padding: 32px 34px;
               margin-bottom: 22px;
               color: #fff;
               border: 1px solid rgba(255, 255, 255, .7);
               border-radius: 28px;
               background:
                    radial-gradient(circle at 88% 16%, rgba(255, 255, 255, .34), transparent 23%),
                    linear-gradient(120deg, #818cf8 0%, #8b5cf6 42%, #22d3ee 100%);
               box-shadow: 0 22px 52px rgba(99, 102, 241, .21);
          }

          .indicator-hero::before {
               position: absolute;
               top: -78px;
               right: 11%;
               width: 215px;
               height: 215px;
               content: '';
               border: 35px solid rgba(255, 255, 255, .12);
               border-radius: 50%;
          }

          .hero-content {
               position: relative;
               z-index: 2;
               display: flex;
               gap: 24px;
               align-items: center;
               justify-content: space-between;
          }

          .hero-title-wrap {
               display: flex;
               gap: 17px;
               align-items: center;
          }

          .hero-icon {
               display: inline-flex;
               flex: 0 0 64px;
               width: 64px;
               height: 64px;
               align-items: center;
               justify-content: center;
               color: #4f46e5;
               font-size: 1.75rem;
               border: 1px solid rgba(255, 255, 255, .8);
               border-radius: 20px;
               background: rgba(255, 255, 255, .94);
               box-shadow: 0 14px 28px rgba(76, 29, 149, .16);
          }

          .indicator-hero h1 {
               margin: 0;
               font-size: clamp(1.65rem, 2.4vw, 2.3rem);
               font-weight: 850;
               letter-spacing: -.035em;
          }

          .indicator-hero p {
               max-width: 760px;
               margin: 8px 0 0;
               color: rgba(255, 255, 255, .94);
               font-size: .95rem;
               line-height: 1.7;
          }

          .btn-hero {
               display: inline-flex;
               min-height: 47px;
               padding: 10px 17px;
               gap: 9px;
               align-items: center;
               justify-content: center;
               color: #4338ca;
               font-size: .86rem;
               font-weight: 800;
               text-decoration: none;
               white-space: nowrap;
               border: 1px solid rgba(255, 255, 255, .8);
               border-radius: 14px;
               background: rgba(255, 255, 255, .95);
               box-shadow: 0 12px 24px rgba(76, 29, 149, .16);
          }

          .btn-hero:hover {
               color: #312e81;
               background: #fff;
          }

          @media (max-width: 767.98px) {
               .indicator-page {
                    padding: 20px 12px 34px;
               }

               .indicator-hero {
                    padding: 23px 20px;
                    border-radius: 22px;
               }

               .hero-content {
                    align-items: flex-start;
                    flex-direction: column;
               }
          }
     </style>

     <div class="indicator-page">
          <div class="indicator-container">
               <section class="indicator-hero">
                    <div class="hero-content">
                         <div class="hero-title-wrap">
                              <span class="hero-icon">
                                   <i class="bi bi-speedometer2"></i>
                              </span>

                              <div>
                                   <h1>Tambah Performance Indicator</h1>
                                   <p>
                                        Buat indikator kinerja baru berdasarkan struktur tabel
                                        <strong>performance_indicators</strong>.
                                   </p>
                              </div>
                         </div>

                         <a href="{{ route('super-admin.performance-indicators.index') }}" class="btn-hero">
                              <i class="bi bi-arrow-left-circle-fill"></i>
                              Kembali ke Daftar
                         </a>
                    </div>
               </section>

               @if (session('error'))
                    <div class="alert alert-danger border-0 rounded-4 shadow-sm mb-3" role="alert">
                         <i class="bi bi-exclamation-triangle-fill me-2"></i>
                         {{ session('error') }}
                    </div>
               @endif

               @include('super-admin.performance-indicators.partials.form')
          </div>
     </div>
@endsection
