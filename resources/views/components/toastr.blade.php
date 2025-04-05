<script>
    // Define global showToast function (ALWAYS AVAILABLE)
    window.showToast = function(type, message, title = '') {
        toastr.options = {
            closeButton: true,
            debug: false,
            newestOnTop: true,
            progressBar: true,
            positionClass: "toastr-bottom-right",
            preventDuplicates: false,
            showDuration: "300",
            hideDuration: "1000",
            timeOut: "5000",
            extendedTimeOut: "1000",
            showEasing: "swing",
            hideEasing: "linear",
            showMethod: "fadeIn",
            hideMethod: "fadeOut"
        };

        if (type === "success") {
            toastr.success(message, title);
        } else if (type === "error") {
            toastr.error(message, title);
        } else if (type === "info") {
            toastr.info(message, title);
        } else if (type === "warning") {
            toastr.warning(message, title);
        }
    };

    // Display session messages (ONLY IF THEY EXIST)
    $(document).ready(function() {
        @if (session('success'))
            showToast("success", "{{ session('success') }}", "Success");
        @endif

        @if (session('error'))
            showToast("error", "{{ session('error') }}", "Error");
        @endif

        @if (session('info'))
            showToast("info", "{{ session('info') }}", "Information");
        @endif

        @if (session('warning'))
            showToast("warning", "{{ session('warning') }}", "Warning");
        @endif
    });
</script>
