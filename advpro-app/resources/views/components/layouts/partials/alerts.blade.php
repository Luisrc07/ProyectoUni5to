@if(session('alert') || session('swal') || session('success') || session('error') || $errors->any())
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Manejo de session('alert')
        @if(session('alert'))
            const alert = @json(session('alert'));
            Swal.fire({
                icon: alert.type || 'info',
                title: alert.title || 'Mensaje',
                text: alert.message || '',
                confirmButtonText: alert.button || 'Aceptar',
                confirmButtonColor: '#6C2BD9',
                timer: alert.timer || null,
                showConfirmButton: (alert.showConfirmButton !== false)
            });
        @endif

        // Manejo de session('swal')
        @if(session('swal'))
            Swal.fire(@json(session('swal')));
        @endif

        // Manejo de session('success')
        @if(session('success'))
            Swal.fire({
                icon: 'success',
                title: '{{ session('success') }}',
                timer: 3000,
                showConfirmButton: false,
                toast: true,
                position: 'top-end'
            });
        @endif

        // Manejo de session('error')
        @if(session('error'))
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: '{{ session('error') }}',
                confirmButtonText: 'Aceptar',
                confirmButtonColor: '#d33'
            });
        @endif

        // Manejo de errores de validación
        @if($errors->any())
            Swal.fire({
                icon: 'error',
                title: 'Errores de validación',
                html: `{!! implode('<br>', $errors->all()) !!}`,
                confirmButtonText: 'Entendido',
                confirmButtonColor: '#d33'
            });
        @endif
    });
</script>
@endif