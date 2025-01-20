<!-- resources/views/components/delete-button.blade.php -->
<form id="delete-form" action="{{ $route }}" method="POST" class="d-inline" data-toggle="tooltip" data-placement="top" title="{{ $title }}">
    @csrf
    @method('DELETE')
    <a href="javascript:void(0);" onclick="confirmDelete('{{ $route }}')" class="iq-bg-primary">
        <i class="ri-delete-bin-line"></i>
    </a>
</form>

<script>
    function confirmDelete(route) {
        Swal.fire({
            title: '{{ ___('Are you sure?') }}',
            text: '{{ ___('You will not be able to recover this data!') }}',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: '{{ ___('Yes delete it!') }}',
            cancelButtonText: '{{ ___('No, cancel!') }}',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                // Submit the form if confirmed
                document.getElementById('delete-form').submit();
            }
        });
    }
</script>
