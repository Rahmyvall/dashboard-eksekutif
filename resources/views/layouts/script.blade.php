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


          const themeButton = document.getElementById("themeToggle");
          const themeIcon = document.getElementById("themeIcon");


          if (themeButton && themeIcon) {


               if (localStorage.getItem("theme") === "black") {

                    document.body.classList.add("black-theme");

                    themeIcon.setAttribute(
                         "data-feather",
                         "sun"
                    );

               }


               themeButton.addEventListener("click", function(e) {

                    e.preventDefault();


                    document.body.classList.toggle("black-theme");


                    if (document.body.classList.contains("black-theme")) {


                         localStorage.setItem(
                              "theme",
                              "black"
                         );


                         themeIcon.setAttribute(
                              "data-feather",
                              "sun"
                         );


                    } else {


                         localStorage.setItem(
                              "theme",
                              "white"
                         );


                         themeIcon.setAttribute(
                              "data-feather",
                              "moon"
                         );

                    }


                    feather.replace();


               });


          }


     });
</script>
