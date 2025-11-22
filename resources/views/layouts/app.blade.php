<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'AppNotiDeudores')</title>

    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- CSS Global -->
    {{-- <link rel="stylesheet" href="{{ asset('css/app.css') }}"> --}}
    <!-- Opción 2: Si no compilaste, usa esto temporalmente -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">

    @yield('styles')
</head>

<body>
    @yield('content')

    <script>
        const API_CONFIG = {
            baseUrl: "{{ env('API_URL') }}",

            getToken() {
                return localStorage.getItem('token');
            },

            getHeaders() {
                return {
                    'Content-Type': 'application/json',
                    ...(this.getToken() && { 'Authorization': 'Bearer ' + this.getToken() })
                };
            },

            async call(endpoint, method = 'GET', data = null) {
                const url = `${this.baseUrl}/${endpoint}`;
                const options = {
                    method: method,
                    headers: this.getHeaders()
                };

                if (data) {
                    options.body = JSON.stringify(data);
                }

                try {
                    const response = await fetch(url, options);
                    const result = await response.json();

                    if (response.status === 401) {
                        localStorage.removeItem('token');
                        localStorage.removeItem('usuario');
                        window.location.href = '/login';
                    }

                    return result;
                } catch (error) {
                    console.error('Error en API:', error);
                    throw error;
                }
            }
        };
    </script>

    @yield('scripts')
</body>

</html>