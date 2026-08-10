{{-- JQuery --}}
<script src="{{ asset('backend/lib/jquery/jquery.min.js') }}"></script>

{{-- Bootstrap --}}
<script src="{{ asset('backend/lib/bootstrap/js/bootstrap.bundle.min.js') }}"></script>

{{-- Feather Icons --}}
<script src="{{ asset('backend/lib/feather-icons/feather.min.js') }}"></script>

{{-- Perfect Scrollbar --}}
<script src="{{ asset('backend/lib/perfect-scrollbar/perfect-scrollbar.min.js') }}"></script>

{{-- Cookie --}}
<script src="{{ asset('backend/lib/js-cookie/js.cookie.js') }}"></script>


{{-- Chart --}}
<script src="{{ asset('backend/lib/chart.js/Chart.bundle.min.js') }}"></script>


{{-- Flot Chart --}}
<script src="{{ asset('backend/lib/jquery.flot/jquery.flot.js') }}"></script>

<script src="{{ asset('backend/lib/jquery.flot/jquery.flot.stack.js') }}"></script>

<script src="{{ asset('backend/lib/jquery.flot/jquery.flot.resize.js') }}"></script>

<script src="{{ asset('backend/lib/jquery.flot/jquery.flot.threshold.js') }}"></script>


{{-- Vector Map --}}
<script src="{{ asset('backend/lib/jqvmap/jquery.vmap.min.js') }}"></script>

<script src="{{ asset('backend/lib/jqvmap/maps/jquery.vmap.world.js') }}"></script>



{{-- Cassie --}}
<script src="{{ asset('backend/assets/js/cassie.js') }}"></script>


{{-- Sample Data --}}
<script src="{{ asset('backend/assets/js/flot.sampledata.js') }}"></script>

<script src="{{ asset('backend/assets/js/vmap.sampledata.js') }}"></script>


{{-- Dashboard Cassie DISABLE --}}
{{-- 
<script src="{{ asset('backend/assets/js/dashboard-one.js') }}"></script>
--}}

{{-- Theme Toggle --}}
<script>
     document.addEventListener("DOMContentLoaded", function() {
          const themeToggle = document.getElementById("themeToggle");
          const themeIcon = document.getElementById("themeIcon");

          if (!themeToggle || !themeIcon) return;

          function applyTheme(theme) {
               const isDark = theme === "dark";
               document.documentElement.setAttribute("data-theme", theme);
               document.body.classList.toggle("black-theme", isDark);
               localStorage.setItem("theme", theme);
               updateThemeButton(isDark);
          }

          function updateThemeButton(isDark) {
               themeToggle.setAttribute("aria-pressed", String(isDark));
               themeToggle.setAttribute("aria-label", isDark ? "Aktifkan tema terang" : "Aktifkan tema gelap");
               themeToggle.setAttribute("title", isDark ? "Aktifkan Tema Terang" : "Aktifkan Tema Gelap");
               themeIcon.setAttribute("data-feather", isDark ? "sun" : "moon");
               if (typeof feather !== "undefined") feather.replace();
          }

          // Terapkan tema tersimpan saat halaman dimuat
          const savedTheme = localStorage.getItem("theme") || "light";
          applyTheme(savedTheme);

          themeToggle.addEventListener("click", function() {
               const currentTheme = document.documentElement.getAttribute("data-theme") || "light";
               applyTheme(currentTheme === "dark" ? "light" : "dark");
          });
     });
</script>
