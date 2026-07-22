<!DOCTYPE html>
<html lang="id">

<head>
     <meta charset="UTF-8">

     <meta name="viewport" content="width=device-width, initial-scale=1.0">

     <title>Login | Dashboard Eksekutif</title>

     <style>
          body {
               margin: 0;
               min-height: 100vh;
               font-family: Arial, Helvetica, sans-serif;
               background: #f8fafc;
          }

          .container {
               min-height: 100vh;
               display: flex;
               align-items: center;
               justify-content: center;
               padding: 20px;
          }

          .card {
               width: 100%;
               max-width: 420px;
               background: white;
               padding: 35px;
               border-radius: 20px;
               box-shadow: 0 20px 50px rgba(0, 0, 0, .1);
          }

          h1 {
               margin-bottom: 10px;
               color: #0f172a;
          }

          p {
               color: #64748b;
               font-size: 14px;
          }

          label {
               display: block;
               margin-top: 20px;
               margin-bottom: 7px;
               font-weight: bold;
               font-size: 14px;
          }

          input {
               width: 100%;
               padding: 13px;
               border-radius: 10px;
               border: 1px solid #dbe4f0;
               box-sizing: border-box;
          }

          input:focus {
               outline: none;
               border-color: #2563eb;
          }

          button {
               width: 100%;
               margin-top: 25px;
               padding: 14px;
               border: none;
               border-radius: 10px;
               background: #2563eb;
               color: white;
               font-weight: bold;
               cursor: pointer;
          }

          button:hover {
               background: #1d4ed8;
          }

          .alert {
               padding: 12px;
               border-radius: 10px;
               margin-bottom: 15px;
               font-size: 14px;
          }

          .success {
               background: #dcfce7;
               color: #166534;
          }

          .error {
               background: #fee2e2;
               color: #991b1b;
          }

          .remember {
               margin-top: 15px;
               display: flex;
               gap: 8px;
               align-items: center;
          }

          .remember input {
               width: auto;
          }
     </style>

</head>


<body>

     <div class="container">

          <div class="card">

               <h1>
                    Dashboard Eksekutif
               </h1>


               <p>
                    Silakan login untuk masuk ke dashboard.
               </p>


               @if (session('success'))
                    <div class="alert success">
                         {{ session('success') }}
                    </div>
               @endif



               @if ($errors->any())
                    <div class="alert error">

                         {{ $errors->first() }}

                    </div>
               @endif



               <form action="{{ route('login.process') }}" method="POST">

                    @csrf


                    <label for="email">
                         Email
                    </label>


                    <input type="email" id="email" name="email" value="{{ old('email') }}"
                         placeholder="email@example.com" required>



                    <label for="password">
                         Password
                    </label>


                    <input type="password" id="password" name="password" placeholder="Password" required>



                    <div class="remember">

                         <input type="checkbox" id="remember" name="remember" value="1">

                         <label for="remember">
                              Ingat saya
                         </label>

                    </div>



                    <button type="submit">
                         Masuk Dashboard
                    </button>


               </form>



               <p style="text-align:center;margin-top:25px">

                    © {{ date('Y') }}
                    Dashboard Eksekutif

               </p>


          </div>


     </div>


</body>

</html>
