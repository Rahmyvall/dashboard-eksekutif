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
               themeToggle.setAttribute("aria-label", isDark ? "Aktifkan tema terang" :
                    "Aktifkan tema gelap");
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

<script>
     document.addEventListener('DOMContentLoaded', function() {
          const isReadOnlyRole = String(document.body?.dataset?.readOnlyRole || '0') === '1' || window
               .__READ_ONLY_ROLE__ === true;

          if (!isReadOnlyRole) {
               return;
          }

          const textPatterns = [
               /\btambah\b/i,
               /\bcreate\b/i,
               /\badd\b/i,
               /\bedit\b/i,
               /\bubah\b/i,
               /\bupdate\b/i,
               /\bsimpan\b/i,
               /\bdelete\b/i,
               /\bhapus\b/i,
               /\bapprove\b/i,
               /\breject\b/i,
               /\brestore\b/i,
               /\bstatus\b/i,
               /\bforce\s*delete\b/i,
          ];

          const hrefPatterns = [
               '/create',
               '/edit',
               '/destroy',
               '/delete',
               '/force-delete',
               '/restore',
               '/approve',
               '/reject',
               '/status',
               '/toggle-status',
               '/capture-proof',
          ];

          const markAsRestricted = function(element) {
               element.setAttribute('aria-hidden', 'true');
               element.classList.add('d-none');
          };

          const shouldHideByText = function(element) {
               const text = (element.textContent || '').replace(/\s+/g, ' ').trim();

               if (!text) {
                    return false;
               }

               return textPatterns.some(function(pattern) {
                    return pattern.test(text);
               });
          };

          document.querySelectorAll('a[href]').forEach(function(anchor) {
               const href = (anchor.getAttribute('href') || '').toLowerCase();

               if (hrefPatterns.some(function(pattern) {
                         return href.includes(pattern);
                    }) || shouldHideByText(anchor)) {
                    markAsRestricted(anchor);
               }
          });

          document.querySelectorAll('form').forEach(function(form) {
               const method = (form.getAttribute('method') || 'get').toLowerCase();
               const hiddenMethod = (form.querySelector('input[name="_method"]')?.value || '')
                    .toLowerCase();
               const effectiveMethod = hiddenMethod || method;
               const action = (form.getAttribute('action') || '').toLowerCase();
               const isLogoutForm = action.includes('/logout');

               if (effectiveMethod === 'get') {
                    return;
               }

               // Keep logout available for read-only roles.
               if (isLogoutForm) {
                    return;
               }

               form.querySelectorAll('button, input[type="submit"], input[type="button"]').forEach(
                    function(control) {
                         control.disabled = true;
                         markAsRestricted(control);
                    });

               form.addEventListener('submit', function(event) {
                    event.preventDefault();
               });
          });

          document.querySelectorAll('button, [role="button"]').forEach(function(button) {
               if (shouldHideByText(button)) {
                    button.disabled = true;
                    markAsRestricted(button);
               }
          });

          document.addEventListener('click', function(event) {
               const target = event.target instanceof Element ? event.target.closest('a, button') :
                    null;

               if (!target) {
                    return;
               }

               if (target.classList.contains('d-none')) {
                    event.preventDefault();
                    event.stopPropagation();
               }
          }, true);
     });
</script>
