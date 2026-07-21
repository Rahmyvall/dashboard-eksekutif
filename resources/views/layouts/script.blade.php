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
          const body = document.body;
          const themeToggle = document.getElementById("themeToggle");

          function setTheme(theme) {
               body.classList.remove("white-theme", "black-theme");

               if (theme === "black") {
                    body.classList.add("black-theme");
               } else {
                    body.classList.add("white-theme");
               }

               localStorage.setItem("dashboardTheme", theme);
          }

          const savedTheme = localStorage.getItem("dashboardTheme");
          setTheme(savedTheme === "black" ? "black" : "white");

          if (themeToggle) {
               themeToggle.addEventListener("click", function() {
                    const currentTheme = body.classList.contains("black-theme") ?
                         "black" :
                         "white";

                    setTheme(currentTheme === "black" ? "white" : "black");
               });
          }
     });
</script>
