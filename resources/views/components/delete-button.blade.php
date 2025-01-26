<form id="delete-form-{{ $route }}" action="{{ $route }}" method="POST" class="d-inline" data-toggle="tooltip" data-placement="top" title="{{ $title }}">
    @csrf
    @method('DELETE')
    <a href="javascript:void(0);" onclick="confirmDelete('{{ $route }}', 'delete-form-{{ $route }}')" class="iq-bg-primary">
        <i class="ri-delete-bin-line"></i>
    </a>
</form>

<script>
   function confirmDelete(route, formId) {
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
            // Submit the correct form using its unique ID
            document.getElementById(formId).submit();
        }
    });
}

</script>
