<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>{{ config('app.name', 'Laravel') }}</title>
  <link rel="dns-prefetch" href="//fonts.gstatic.com">
  <link href="{{ asset('css/app.css') }}" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/vuetify@2.x/dist/vuetify.min.css" rel="stylesheet">
  <link href="/img/favicon.png" rel="icon">
  <link href="https://fonts.googleapis.com/css?family=Roboto:400,500,600,700" rel="stylesheet">
  <style>
    .col-md-4 {
      height: 100vh;
      display: flex;
      align-items: center;
    }

    .pre-loader {
      font-size: 14px;
      color: rgba(0, 0, 0, .124);
      font-weight: 600;
      width: 100%;
      text-align: center;
    }
  </style>
</head>

<body>
  <main class="pb-4">
    <div id="app" data-app>
      <v-app>
        <v-main>
          <div class="col-md-4 mx-auto">
            <div class="pre-loader">
              <img src="{{ asset('img/144x144.png') }}">
            </div>
            <v-card outlined style="width: 100%;" class="d-none">
              <v-card-title>
                Acceso root
              </v-card-title>
              <v-divider style="margin-top: 0;"></v-divider>
              <v-card-text>
                <form method="POST" action="{{ route('root.login') }}" id="admin-login-form" ref="LoginForm">
                  @csrf
                  <div class="form-group">
                    <v-text-field outlined dense label="Email" name="email" autofocus required autocomplete="email" />
                    @if( $errors->has('email') )
                    <span class="invalid-feedback" role="alert">
                      <strong>
                        {{ $errors->first('email') }}
                      </strong>
                    </span>
                    @endif
                  </div>

                  <div class="form-group">
                    <v-text-field outlined dense type="password" label="Contraseña" name="password" autofocus required autocomplete="current-password" />
                    @if( $errors->has('password') )
                    <span class="invalid-feedback" role="alert">
                      <strong>
                        {{ $errors->first('password') }}
                      </strong>
                    </span>
                    @endif
                  </div>
                </form>
                <v-card-actions>
                  <v-spacer></v-spacer>
                  <v-btn type="submit" dark @click="$refs.LoginForm.submit()">
                    INGRESAR
                  </v-btn>
                </v-card-actions>
              </v-card-text>
            </v-card>
          </div>
        </v-main>
      </v-app>
    </div>
  </main>
  <script src="https://cdn.jsdelivr.net/npm/vue@2.x/dist/vue.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/vuetify@2.x/dist/vuetify.js"></script>
  <script>
    new Vue({
      el: '#app',
      vuetify: new Vuetify(),
      mounted() {
        document.querySelector('.pre-loader').remove()
        document.querySelector('.d-none').classList.remove('d-none')
      }
    })
  </script>
</body>

</html>