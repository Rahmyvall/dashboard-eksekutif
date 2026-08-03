{{-- resources/views/super-admin/employees/_form.blade.php --}}

@php
     $isEdit = isset($employee) && $employee->exists;

     $employmentOptions = [
         'permanent' => 'Karyawan Tetap',
         'contract' => 'Karyawan Kontrak',
         'probation' => 'Masa Percobaan',
         'internship' => 'Magang',
         'outsourcing' => 'Outsourcing',
     ];

     $selectedUserId = (string) old('user_id', $isEdit ? $employee->user_id : '');

     $selectedDepartmentId = (string) old('department_id', $isEdit ? $employee->department_id : '');

     $selectedPositionId = (string) old('position_id', $isEdit ? $employee->position_id : '');

     $selectedGender = old('gender', $isEdit ? $employee->gender : '');

     $selectedEmploymentStatus = old('employment_status', $isEdit ? $employee->employment_status : '');

     $selectedStatus = old('status', $isEdit ? $employee->status : 'active');

     $birthDateValue = old(
         'birth_date',
         $isEdit && $employee->birth_date ? $employee->birth_date->format('Y-m-d') : '',
     );

     $hireDateValue = old(
         'hire_date',
         $isEdit && $employee->hire_date ? $employee->hire_date->format('Y-m-d') : now()->format('Y-m-d'),
     );

     $currentPhotoUrl = $isEdit ? $employee->photo_url : null;

     $selectedUserAvailable =
         $selectedUserId === '' || $users->contains(fn($user) => (string) $user->id === $selectedUserId);
@endphp

@once
     <style>
          .employee-form-sections {
               display: grid;
               gap: 22px;
          }

          .employee-form-section {
               padding: 24px;
               border: 1px solid #e0e7ff;
               border-radius: 20px;
               background: linear-gradient(145deg, #ffffff, #fafbff);
          }

          .employee-form-section:nth-child(1) {
               border-top: 4px solid #6366f1;
          }

          .employee-form-section:nth-child(2) {
               border-top: 4px solid #0ea5e9;
          }

          .employee-form-section:nth-child(3) {
               border-top: 4px solid #ec4899;
          }

          .employee-form-section:nth-child(4) {
               border-top: 4px solid #06b6d4;
          }

          .employee-form-section:nth-child(5) {
               border-top: 4px solid #f59e0b;
          }

          .employee-form-section:nth-child(6) {
               border-top: 4px solid #8b5cf6;
          }

          .employee-form-section:nth-child(7) {
               border-top: 4px solid #10b981;
          }

          .employee-section-heading {
               display: flex;
               gap: 12px;
               align-items: center;
               margin-bottom: 20px;
          }

          .employee-section-number {
               display: grid;
               flex: 0 0 38px;
               width: 38px;
               height: 38px;
               place-items: center;
               color: #fff;
               font-size: .8rem;
               font-weight: 850;
               border-radius: 12px;
               background: linear-gradient(135deg, #6366f1, #8b5cf6);
               box-shadow: 0 8px 18px rgba(99, 102, 241, .20);
          }

          .employee-section-heading h5 {
               margin: 0;
               color: #1e293b;
               font-size: .98rem;
               font-weight: 850;
          }

          .employee-section-heading small {
               display: block;
               margin-top: 2px;
               color: #64748b;
               font-size: .78rem;
          }

          .employee-form-field .form-label {
               margin-bottom: 8px;
               color: #1e293b;
               font-size: .87rem;
               font-weight: 800;
          }

          .employee-required {
               color: #ef4444;
          }

          .employee-optional {
               margin-left: 5px;
               color: #94a3b8;
               font-size: .73rem;
               font-weight: 650;
          }

          .employee-field-shell {
               position: relative;
          }

          .employee-field-icon {
               position: absolute;
               top: 50%;
               left: 15px;
               z-index: 3;
               color: #64748b;
               pointer-events: none;
               transform: translateY(-50%);
          }

          .employee-field-shell-textarea .employee-field-icon {
               top: 17px;
               transform: none;
          }

          .employee-form-field .form-control,
          .employee-form-field .form-select {
               min-height: 52px;
               padding: 12px 15px 12px 44px;
               color: #1e293b;
               font-size: .9rem;
               border: 1px solid #cbd5e1;
               border-radius: 15px;
               background-color: #fff;
               box-shadow: 0 2px 5px rgba(15, 23, 42, .03);
          }

          .employee-form-field textarea.form-control {
               min-height: 130px;
               padding-top: 14px;
               resize: vertical;
          }

          .employee-form-field .form-control:focus,
          .employee-form-field .form-select:focus {
               border-color: #818cf8;
               box-shadow: 0 0 0 .24rem rgba(99, 102, 241, .13);
          }

          .employee-form-field .is-invalid {
               border-color: #f87171;
               background-image: none;
          }

          .employee-form-hint {
               display: flex;
               gap: 6px;
               align-items: flex-start;
               margin-top: 8px;
               color: #64748b;
               font-size: .75rem;
               line-height: 1.5;
          }

          .employee-photo-layout {
               display: grid;
               grid-template-columns: 180px 1fr;
               gap: 22px;
               align-items: center;
          }

          .employee-photo-preview {
               display: grid;
               width: 180px;
               height: 180px;
               overflow: hidden;
               place-items: center;
               color: #6366f1;
               font-size: 3.2rem;
               border: 2px dashed #c7d2fe;
               border-radius: 24px;
               background: linear-gradient(135deg, #eef2ff, #ecfeff);
          }

          .employee-photo-preview img {
               width: 100%;
               height: 100%;
               object-fit: cover;
          }

          .employee-photo-placeholder {
               display: grid;
               place-items: center;
          }

          .employee-remove-photo {
               display: flex;
               gap: 9px;
               align-items: center;
               padding: 12px 14px;
               margin-top: 12px;
               color: #9f1239;
               font-size: .8rem;
               font-weight: 700;
               border: 1px solid #fecdd3;
               border-radius: 13px;
               background: #fff1f2;
          }

          .employee-status-options {
               display: grid;
               grid-template-columns: repeat(2, minmax(0, 1fr));
               gap: 14px;
          }

          .employee-status-option {
               position: relative;
          }

          .employee-status-option input {
               position: absolute;
               opacity: 0;
               pointer-events: none;
          }

          .employee-status-label {
               display: flex;
               gap: 13px;
               align-items: center;
               min-height: 88px;
               padding: 16px;
               margin: 0;
               cursor: pointer;
               border: 2px solid transparent;
               border-radius: 18px;
               transition: .2s ease;
          }

          .employee-status-label-active {
               color: #065f46;
               border-color: #a7f3d0;
               background: linear-gradient(135deg, #ecfdf5, #f0fdfa);
          }

          .employee-status-label-inactive {
               color: #9f1239;
               border-color: #fecdd3;
               background: linear-gradient(135deg, #fff1f2, #fdf2f8);
          }

          .employee-status-option input:checked+.employee-status-label-active {
               border-color: #10b981;
               box-shadow: 0 0 0 4px rgba(16, 185, 129, .12);
          }

          .employee-status-option input:checked+.employee-status-label-inactive {
               border-color: #ec4899;
               box-shadow: 0 0 0 4px rgba(236, 72, 153, .12);
          }

          .employee-status-icon {
               display: grid;
               flex: 0 0 44px;
               width: 44px;
               height: 44px;
               place-items: center;
               color: #fff;
               border-radius: 14px;
          }

          .employee-status-label-active .employee-status-icon {
               background: linear-gradient(135deg, #10b981, #06b6d4);
          }

          .employee-status-label-inactive .employee-status-icon {
               background: linear-gradient(135deg, #fb7185, #ec4899);
          }

          .employee-status-label strong {
               display: block;
               margin-bottom: 3px;
               font-size: .88rem;
          }

          .employee-status-label small {
               display: block;
               color: #64748b;
               font-size: .74rem;
               line-height: 1.45;
          }

          @media (max-width: 767.98px) {
               .employee-form-section {
                    padding: 19px;
               }

               .employee-photo-layout {
                    grid-template-columns: 1fr;
               }

               .employee-photo-preview {
                    width: 150px;
                    height: 150px;
                    margin: 0 auto;
               }

               .employee-status-options {
                    grid-template-columns: 1fr;
               }
          }
     </style>
@endonce

<div class="employee-form-sections">
     {{-- 01 IDENTITAS --}}
     <section class="employee-form-section">
          <div class="employee-section-heading">
               <span class="employee-section-number">01</span>

               <div>
                    <h5>Identitas Utama</h5>
                    <small>Nomor employee, nama lengkap, dan akun pengguna.</small>
               </div>
          </div>

          <div class="row g-4">
               <div class="col-lg-4 employee-form-field">
                    <label for="employee_number" class="form-label">
                         Nomor Employee
                         <span class="employee-required">*</span>
                    </label>

                    <div class="employee-field-shell">
                         <i class="bi bi-person-vcard employee-field-icon"></i>

                         <input type="text" id="employee_number" name="employee_number" maxlength="50"
                              class="form-control @error('employee_number') is-invalid @enderror"
                              value="{{ old('employee_number', $isEdit ? $employee->employee_number : '') }}"
                              placeholder="Contoh: EMP-0001" oninput="this.value = this.value.toUpperCase()"
                              autocomplete="off" required>
                    </div>

                    @error('employee_number')
                         <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
               </div>

               <div class="col-lg-4 employee-form-field">
                    <label for="full_name" class="form-label">
                         Nama Lengkap
                         <span class="employee-required">*</span>
                    </label>

                    <div class="employee-field-shell">
                         <i class="bi bi-person-fill employee-field-icon"></i>

                         <input type="text" id="full_name" name="full_name" maxlength="150"
                              class="form-control @error('full_name') is-invalid @enderror"
                              value="{{ old('full_name', $isEdit ? $employee->full_name : '') }}"
                              placeholder="Masukkan nama lengkap" autocomplete="name" required>
                    </div>

                    @error('full_name')
                         <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
               </div>

               <div class="col-lg-4 employee-form-field">
                    <label for="user_id" class="form-label">
                         Akun Pengguna
                         <span class="employee-optional">Opsional</span>
                    </label>

                    <div class="employee-field-shell">
                         <i class="bi bi-person-lock employee-field-icon"></i>

                         <select id="user_id" name="user_id"
                              class="form-select @error('user_id') is-invalid @enderror">
                              <option value="">Tanpa akun pengguna</option>

                              @if (!$selectedUserAvailable)
                                   <option value="{{ $selectedUserId }}" selected>
                                        Akun saat ini — ID {{ $selectedUserId }}
                                   </option>
                              @endif

                              @foreach ($users as $user)
                                   <option value="{{ $user->id }}" @selected($selectedUserId === (string) $user->id)>
                                        {{ $user->name }}
                                        @if ($user->email)
                                             — {{ $user->email }}
                                        @endif
                                   </option>
                              @endforeach
                         </select>
                    </div>

                    @error('user_id')
                         <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror

                    <div class="employee-form-hint">
                         <i class="bi bi-info-circle"></i>
                         <span>Satu akun hanya dapat dipakai satu employee.</span>
                    </div>
               </div>
          </div>
     </section>

     {{-- 02 ORGANISASI --}}
     <section class="employee-form-section">
          <div class="employee-section-heading">
               <span class="employee-section-number">02</span>

               <div>
                    <h5>Penempatan Organisasi</h5>
                    <small>Pilih departemen dan jabatan yang sesuai.</small>
               </div>
          </div>

          <div class="row g-4">
               <div class="col-md-6 employee-form-field">
                    <label for="department_id" class="form-label">
                         Departemen
                         <span class="employee-required">*</span>
                    </label>

                    <div class="employee-field-shell">
                         <i class="bi bi-buildings-fill employee-field-icon"></i>

                         <select id="department_id" name="department_id"
                              class="form-select @error('department_id') is-invalid @enderror" required>
                              <option value="">Pilih departemen</option>

                              @foreach ($departments as $department)
                                   <option value="{{ $department->id }}" @selected($selectedDepartmentId === (string) $department->id)>
                                        {{ $department->code }} — {{ $department->name }}
                                   </option>
                              @endforeach
                         </select>
                    </div>

                    @error('department_id')
                         <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
               </div>

               <div class="col-md-6 employee-form-field">
                    <label for="position_id" class="form-label">
                         Jabatan
                         <span class="employee-required">*</span>
                    </label>

                    <div class="employee-field-shell">
                         <i class="bi bi-briefcase-fill employee-field-icon"></i>

                         <select id="position_id" name="position_id"
                              class="form-select @error('position_id') is-invalid @enderror" required>
                              <option value="">Pilih jabatan</option>

                              @foreach ($positions as $position)
                                   <option value="{{ $position->id }}"
                                        data-department-id="{{ $position->department_id }}"
                                        @selected($selectedPositionId === (string) $position->id)>
                                        {{ $position->name }}
                                   </option>
                              @endforeach
                         </select>
                    </div>

                    @error('position_id')
                         <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror

                    <div class="employee-form-hint">
                         <i class="bi bi-info-circle"></i>
                         <span>Daftar jabatan mengikuti departemen.</span>
                    </div>
               </div>
          </div>
     </section>

     {{-- 03 PERSONAL --}}
     <section class="employee-form-section">
          <div class="employee-section-heading">
               <span class="employee-section-number">03</span>

               <div>
                    <h5>Informasi Pribadi</h5>
                    <small>Jenis kelamin serta data kelahiran employee.</small>
               </div>
          </div>

          <div class="row g-4">
               <div class="col-md-4 employee-form-field">
                    <label for="gender" class="form-label">
                         Jenis Kelamin
                         <span class="employee-required">*</span>
                    </label>

                    <div class="employee-field-shell">
                         <i class="bi bi-gender-ambiguous employee-field-icon"></i>

                         <select id="gender" name="gender" class="form-select @error('gender') is-invalid @enderror"
                              required>
                              <option value="">Pilih jenis kelamin</option>
                              <option value="male" @selected($selectedGender === 'male')>
                                   Laki-laki
                              </option>
                              <option value="female" @selected($selectedGender === 'female')>
                                   Perempuan
                              </option>
                         </select>
                    </div>

                    @error('gender')
                         <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
               </div>

               <div class="col-md-4 employee-form-field">
                    <label for="birth_place" class="form-label">
                         Tempat Lahir
                         <span class="employee-optional">Opsional</span>
                    </label>

                    <div class="employee-field-shell">
                         <i class="bi bi-geo-alt-fill employee-field-icon"></i>

                         <input type="text" id="birth_place" name="birth_place" maxlength="100"
                              class="form-control @error('birth_place') is-invalid @enderror"
                              value="{{ old('birth_place', $isEdit ? $employee->birth_place : '') }}"
                              placeholder="Contoh: Jakarta" autocomplete="off">
                    </div>

                    @error('birth_place')
                         <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
               </div>

               <div class="col-md-4 employee-form-field">
                    <label for="birth_date" class="form-label">
                         Tanggal Lahir
                         <span class="employee-optional">Opsional</span>
                    </label>

                    <div class="employee-field-shell">
                         <i class="bi bi-calendar-event employee-field-icon"></i>

                         <input type="date" id="birth_date" name="birth_date" max="{{ now()->format('Y-m-d') }}"
                              class="form-control @error('birth_date') is-invalid @enderror"
                              value="{{ $birthDateValue }}">
                    </div>

                    @error('birth_date')
                         <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
               </div>
          </div>
     </section>

     {{-- 04 KONTAK --}}
     <section class="employee-form-section">
          <div class="employee-section-heading">
               <span class="employee-section-number">04</span>

               <div>
                    <h5>Kontak dan Alamat</h5>
                    <small>Informasi komunikasi employee.</small>
               </div>
          </div>

          <div class="row g-4">
               <div class="col-md-6 employee-form-field">
                    <label for="phone" class="form-label">
                         Nomor Telepon
                         <span class="employee-optional">Opsional</span>
                    </label>

                    <div class="employee-field-shell">
                         <i class="bi bi-telephone-fill employee-field-icon"></i>

                         <input type="text" id="phone" name="phone" maxlength="30"
                              class="form-control @error('phone') is-invalid @enderror"
                              value="{{ old('phone', $isEdit ? $employee->phone : '') }}"
                              placeholder="Contoh: 081234567890" inputmode="tel" autocomplete="tel">
                    </div>

                    @error('phone')
                         <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
               </div>

               <div class="col-md-6 employee-form-field">
                    <label for="email" class="form-label">
                         Email Employee
                         <span class="employee-optional">Opsional</span>
                    </label>

                    <div class="employee-field-shell">
                         <i class="bi bi-envelope-fill employee-field-icon"></i>

                         <input type="email" id="email" name="email" maxlength="150"
                              class="form-control @error('email') is-invalid @enderror"
                              value="{{ old('email', $isEdit ? $employee->email : '') }}"
                              placeholder="nama@perusahaan.com" autocomplete="email">
                    </div>

                    @error('email')
                         <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
               </div>

               <div class="col-12 employee-form-field">
                    <label for="address" class="form-label">
                         Alamat
                         <span class="employee-optional">Opsional</span>
                    </label>

                    <div class="employee-field-shell employee-field-shell-textarea">
                         <i class="bi bi-house-door-fill employee-field-icon"></i>

                         <textarea id="address" name="address" rows="4" class="form-control @error('address') is-invalid @enderror"
                              placeholder="Masukkan alamat lengkap employee...">{{ old('address', $isEdit ? $employee->address : '') }}</textarea>
                    </div>

                    @error('address')
                         <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
               </div>
          </div>
     </section>

     {{-- 05 KEPEGAWAIAN --}}
     <section class="employee-form-section">
          <div class="employee-section-heading">
               <span class="employee-section-number">05</span>

               <div>
                    <h5>Data Kepegawaian</h5>
                    <small>Tanggal masuk, hubungan kerja, dan gaji pokok.</small>
               </div>
          </div>

          <div class="row g-4">
               <div class="col-md-4 employee-form-field">
                    <label for="hire_date" class="form-label">
                         Tanggal Mulai Bekerja
                         <span class="employee-required">*</span>
                    </label>

                    <div class="employee-field-shell">
                         <i class="bi bi-calendar-check-fill employee-field-icon"></i>

                         <input type="date" id="hire_date" name="hire_date"
                              class="form-control @error('hire_date') is-invalid @enderror"
                              value="{{ $hireDateValue }}" required>
                    </div>

                    @error('hire_date')
                         <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
               </div>

               <div class="col-md-4 employee-form-field">
                    <label for="employment_status" class="form-label">
                         Status Kepegawaian
                         <span class="employee-required">*</span>
                    </label>

                    <div class="employee-field-shell">
                         <i class="bi bi-briefcase-fill employee-field-icon"></i>

                         <select id="employment_status" name="employment_status"
                              class="form-select @error('employment_status') is-invalid @enderror" required>
                              <option value="">Pilih status kepegawaian</option>

                              @foreach ($employmentOptions as $value => $label)
                                   <option value="{{ $value }}" @selected($selectedEmploymentStatus === $value)>
                                        {{ $label }}
                                   </option>
                              @endforeach
                         </select>
                    </div>

                    @error('employment_status')
                         <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
               </div>

               <div class="col-md-4 employee-form-field">
                    <label for="basic_salary" class="form-label">
                         Gaji Pokok
                         <span class="employee-optional">Opsional</span>
                    </label>

                    <div class="employee-field-shell">
                         <i class="bi bi-cash-stack employee-field-icon"></i>

                         <input type="number" id="basic_salary" name="basic_salary" min="0" step="0.01"
                              class="form-control @error('basic_salary') is-invalid @enderror"
                              value="{{ old('basic_salary', $isEdit ? $employee->basic_salary : '') }}"
                              placeholder="Contoh: 5000000" inputmode="decimal">
                    </div>

                    @error('basic_salary')
                         <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror

                    <div class="employee-form-hint">
                         <i class="bi bi-info-circle"></i>
                         <span>Masukkan angka tanpa pemisah ribuan.</span>
                    </div>
               </div>
          </div>
     </section>

     {{-- 06 FOTO --}}
     <section class="employee-form-section">
          <div class="employee-section-heading">
               <span class="employee-section-number">06</span>

               <div>
                    <h5>Foto Employee</h5>
                    <small>JPG, JPEG, PNG, atau WEBP maksimal 2 MB.</small>
               </div>
          </div>

          <div class="employee-photo-layout">
               <div class="employee-photo-preview">
                    <img id="employeePhotoPreviewImage" src="{{ $currentPhotoUrl ?? '' }}"
                         alt="Pratinjau foto employee" @style(['display: none' => !$currentPhotoUrl])>

                    <span id="employeePhotoPlaceholder" class="employee-photo-placeholder" @style(['display: none' => $currentPhotoUrl])>
                         <i class="bi bi-person-bounding-box"></i>
                    </span>
               </div>

               <div class="employee-form-field">
                    <label for="photo" class="form-label">
                         Pilih Foto
                         <span class="employee-optional">Opsional</span>
                    </label>

                    <div class="employee-field-shell">
                         <i class="bi bi-image-fill employee-field-icon"></i>

                         <input type="file" id="photo" name="photo"
                              accept=".jpg,.jpeg,.png,.webp,image/jpeg,image/png,image/webp"
                              class="form-control @error('photo') is-invalid @enderror">
                    </div>

                    @error('photo')
                         <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror

                    @if ($isEdit && $currentPhotoUrl)
                         <label class="employee-remove-photo">
                              <input type="checkbox" id="remove_photo" name="remove_photo" value="1"
                                   @checked(old('remove_photo'))>

                              <span>
                                   Hapus foto lama ketika perubahan disimpan
                              </span>
                         </label>
                    @endif
               </div>
          </div>
     </section>

     {{-- 07 STATUS --}}
     <section class="employee-form-section">
          <div class="employee-section-heading">
               <span class="employee-section-number">07</span>

               <div>
                    <h5>Status Employee</h5>
                    <small>Tentukan apakah employee aktif di sistem.</small>
               </div>
          </div>

          <div class="employee-status-options">
               <div class="employee-status-option">
                    <input type="radio" id="status_active" name="status" value="active"
                         @checked($selectedStatus === 'active')>

                    <label for="status_active" class="employee-status-label employee-status-label-active">
                         <span class="employee-status-icon">
                              <i class="bi bi-check-circle-fill"></i>
                         </span>

                         <span>
                              <strong>Active</strong>
                              <small>
                                   Employee aktif dan dapat digunakan pada modul lain.
                              </small>
                         </span>
                    </label>
               </div>

               <div class="employee-status-option">
                    <input type="radio" id="status_inactive" name="status" value="inactive"
                         @checked($selectedStatus === 'inactive')>

                    <label for="status_inactive" class="employee-status-label employee-status-label-inactive">
                         <span class="employee-status-icon">
                              <i class="bi bi-pause-circle-fill"></i>
                         </span>

                         <span>
                              <strong>Inactive</strong>
                              <small>
                                   Data tersimpan, tetapi employee belum aktif.
                              </small>
                         </span>
                    </label>
               </div>
          </div>

          @error('status')
               <div class="invalid-feedback d-block">{{ $message }}</div>
          @enderror
     </section>
</div>

@once
     @push('scripts')
          <script>
               document.addEventListener('DOMContentLoaded', function() {
                    const departmentSelect =
                         document.getElementById('department_id');

                    const positionSelect =
                         document.getElementById('position_id');

                    const photoInput =
                         document.getElementById('photo');

                    const previewImage =
                         document.getElementById('employeePhotoPreviewImage');

                    const placeholder =
                         document.getElementById('employeePhotoPlaceholder');

                    const removePhoto =
                         document.getElementById('remove_photo');

                    if (departmentSelect && positionSelect) {
                         const originalOptions = Array.from(
                              positionSelect.options
                         ).map(function(option) {
                              return option.cloneNode(true);
                         });

                         const selectedPositionId = @json($selectedPositionId);

                         function refreshPositions() {
                              const departmentId = departmentSelect.value;
                              positionSelect.innerHTML = '';

                              originalOptions.forEach(function(option) {
                                   const optionDepartmentId =
                                        option.dataset.departmentId || '';

                                   if (
                                        option.value === '' ||
                                        departmentId === '' ||
                                        optionDepartmentId === departmentId
                                   ) {
                                        const cloned = option.cloneNode(true);

                                        if (
                                             String(cloned.value) ===
                                             String(selectedPositionId)
                                        ) {
                                             cloned.selected = true;
                                        }

                                        positionSelect.appendChild(cloned);
                                   }
                              });

                              const selectedOption =
                                   positionSelect.options[
                                        positionSelect.selectedIndex
                                   ];

                              if (
                                   selectedOption &&
                                   selectedOption.value !== '' &&
                                   departmentId !== '' &&
                                   selectedOption.dataset.departmentId !==
                                   departmentId
                              ) {
                                   positionSelect.value = '';
                              }
                         }

                         departmentSelect.addEventListener(
                              'change',
                              refreshPositions
                         );

                         refreshPositions();
                    }

                    if (photoInput && previewImage && placeholder) {
                         photoInput.addEventListener(
                              'change',
                              function(event) {
                                   const file = event.target.files[0];

                                   if (!file) {
                                        return;
                                   }

                                   if (!file.type.startsWith('image/')) {
                                        photoInput.value = '';
                                        alert('File harus berupa gambar.');
                                        return;
                                   }

                                   if (file.size > 2 * 1024 * 1024) {
                                        photoInput.value = '';
                                        alert('Ukuran foto maksimal 2 MB.');
                                        return;
                                   }

                                   const reader = new FileReader();

                                   reader.onload = function(loadEvent) {
                                        previewImage.src = loadEvent.target.result;
                                        previewImage.style.display = 'block';
                                        placeholder.style.display = 'none';

                                        if (removePhoto) {
                                             removePhoto.checked = false;
                                        }
                                   };

                                   reader.readAsDataURL(file);
                              }
                         );
                    }

                    if (
                         removePhoto &&
                         previewImage &&
                         placeholder
                    ) {
                         removePhoto.addEventListener(
                              'change',
                              function() {
                                   if (removePhoto.checked) {
                                        previewImage.style.display = 'none';
                                        placeholder.style.display = 'grid';
                                   } else if (previewImage.getAttribute('src')) {
                                        previewImage.style.display = 'block';
                                        placeholder.style.display = 'none';
                                   }
                              }
                         );
                    }
               });
          </script>
     @endpush
@endonce
