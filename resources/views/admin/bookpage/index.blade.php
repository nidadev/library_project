<x-admin.layout pageTitle="Book Page">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Book Page</h3>
                        <div class="card-toolbar">

                            <button class="btn btn-primary text-white" data-bs-toggle="modal"
                                data-bs-target="#addBookPageModal">Add Books</button>
                        </div>
                    </div>
                    <div class="card-body">
                        <table class="table table-bordered" id="bookpage_table">
                            <thead>
                                <tr>
                                    <th>Books Name</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>

                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Add BookPage Modal -->
        <div class="modal fade" id="addBookPageModal" tabindex="-1" aria-labelledby="addBookPageModalLabel"
            aria-hidden="false" data-bs-backdrop="static">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addBookPageModalLabel">Add Book</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form action="#" method="POST" id="addBookPageForm">
                            @csrf
                            <!--begin::Input group-->
                            <div class="d-flex flex-column mb-8 fv-row fv-plugins-icon-container">
                                <!--begin::Label-->
                                <label for="book_name" class="d-flex align-items-center fs-6 fw-semibold mb-2">
                                    <span class="required">Title</span>
                                </label>
                                <!--end::Label-->

                                <input type="text" class="form-control" placeholder="Enter Book Title"
                                    name="book_name" id="book_name">
                                <div
                                    class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback">
                                </div>
                            </div>
                            <!--end::Input group-->

                            <!--begin::Input group-->
                            <div class="row g-9 mb-8">
                                <!--begin::Col-->
                                <div class="col-md-12 fv-row">
                                    <label class="fs-6 fw-semibold mb-2" for="book_description">Description</label>
                                    <!--begin::Input-->
                                    <div class="position-relative d-flex align-items-center">
                                        <textarea class="form-control" name="book_description" id="book_description"></textarea>
                                    </div>
                                    <!--end::Input-->
                                </div>
                                <!--end::Col-->
                            </div>
                            <!--end::Input group-->

                            <!-- Certificate Categories (Create) -->
                            <div class="d-flex flex-column mb-8 fv-row fv-plugins-icon-container">
                                <label class="d-flex align-items-center fs-6 fw-semibold mb-2">
                                    <span class="required">Genres</span>
                                </label>
                                <div class="tag-container form-control" data-target="create">
                                    <input type="text" class="form-control tag-input"
                                        placeholder="Enter Genres Categories (comma-separated)"
                                        id="bookpage_categories_create">
                                </div>
                                <div class="fv-plugins-message-container invalid-feedback"></div>
                            </div>

                            <!--begin::Pdf input-->
                            <div class="fv-row mb-0 fv-plugins-icon-container">
                                <label for="book_pdf" class="form-label fs-6 fw-bold mb-3">Pdf Upload</label>
                                <input type="file" class="form-control form-control-lg" name="book_pdf"
                                    id="book_pdf">
                                <div
                                    class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback">
                                </div>
                            </div>
                            <!--end::Pdf input-->

                            <!--begin::Image input-->
                            <div class="fv-row mb-0 fv-plugins-icon-container">
                                <label for="book_image" class="form-label fs-6 fw-bold mb-3">cover Image</label>
                                <input type="file" class="form-control form-control-lg" name="book_image"
                                    id="book_image">
                                <div
                                    class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback">
                                </div>
                            </div>
                            <!--end::Image input-->

                            <!--begin::Image Preview-->
                            <div class="mt-3 mb-4">
                                <img id="BookPageFilePreview" src="#" alt="Image File"
                                    class="rounded border border-primary shadow-sm"
                                    style="display: none; width: 100px; height: 100px;" />
                            </div>
                            <!--end::Image Preview-->
                            <!--begin::Actions-->
                            <div class="text-center">
                                <button id="createbookPageBtn" class="btn btn-primary text-white">
                                    <span class="indicator-label">
                                        Add Book
                                    </span>
                                    <span class="indicator-progress">
                                        Please wait... <span
                                            class="spinner-border spinner-border-sm align-middle ms-2"></span>
                                    </span>
                                </button>
                            </div>
                            <!--end::Actions-->
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>


    <script>
        var maxSize = 20 * 1024 * 1024; // 20MB in Bytes
        var allowedTypes = ['image/jpeg', 'image/jpg', 'image/webp'];
        $(document).ready(function() {
            $('#bookpage_table').DataTable({
                "order": []
            });

            initializeTagInput(".tag-container");

            // Clear Validation when Create Book Page Modal close
            $('#addBookPageModal').on('hidden.bs.modal', function(event) {
                $('#addBookPageForm').find('.fv-row').find('.fv-plugins-message-container').empty();
                $('#addBookPageForm input').removeClass('is-invalid');
                $('#addBookPageForm textarea').removeClass('is-invalid');
                $('#addBookPageForm')[0].reset();
            });

            // Clear Validation when Edit Book Page Modal closes
            // $(document).on('hidden.bs.modal', '[id^=editBookPageModal_]', function(event) {
            //     var BookPageId = $(this).attr('id').split('_')[1]; // Fix index issue

            //     var form = $('#editBookPageForm_' + BookPageId);

            //     form.find('.fv-plugins-message-container').empty();
            //     form.find('input, textarea').removeClass('is-invalid');
            //     form[0].reset();

            //     // Hide file preview & show file input
            //     $('#editBookPageFilePreview_' + BookPageId).hide();
            //     $('#edit_BookPage_file_' + BookPageId).show(); // Fix incorrect selector
            // });

            // We have to show the .bookpage-file when we open the modal
            // $(document).on('show.bs.modal', '[id^=editBookPageModal_]', function(event) {
            //     var BookPageId = $(this).attr('id').split('_')[1]; // Fix index issue
            //     $('#editBookPageFilePreview_' + BookPageId).hide();
            //     $('.BookPage-file').show();
            // });

            // Create Book Page Modal Form - Submission event listener
            $('#addBookPageForm').submit(function(e) {
                alert('11');
                e.preventDefault();; // Prevent the default form submission behavior

                // Validate the form before proceeding
                if (validateCreateBookPageForm()) {
                    // Show loading indicator on the button while processing
                    $('#createbookPageBtn').attr("data-kt-indicator", "on");

                    // Get the form data
                    var formData = new FormData($('#addBookPageForm')[0]);

                    // add csrf token to the form data
                    formData.append('_token', '{{ csrf_token() }}');

                    // Get all category/genres tags and append them as a single input
                    var categories = $('#bookpage_categories_create').closest('.tag-container')
                        .find('.tag')
                        .map(function() {
                            return $(this).text().replace(' ×', '');
                        })
                        .get()
                        .join(',');

                    formData.append('categories', categories);
                    alert(formData);
                   

                    // Send the form data to the server using AJAX this should include the file
                    $.ajax({
                        type: 'POST',
                        url: "{{ route('admin.bookpage.store') }}",
                        data: formData, // Data being sent
                        dataType: 'json', // Expected data type of the response
                        contentType: false, // Content type of the request
                        processData: false, // Do not process the data
                        success: function(response) {
                            if (response.success) {

                                // Remove loading indicator from the button
                                $('#createbookPageBtn').removeAttr("data-kt-indicator",
                                    "on");

                                // Reset validation messages and styles
                                $('.fv-plugins-message-container').text('').hide();
                                $('#addBookPageForm input').removeClass('is-invalid');
                                $('#addBookPageForm textarea').removeClass('is-invalid');

                                // Redirect the page to response.redirect_url
                                window.location.href = response.redirect_url;

                                // Reset the form and close the modal
                                $('#addBookPageForm')[0].reset(); // Reset form fields 
                                $("#addBookPageModal").modal("hide"); // Hide the modal
                            }
                        },
                        error: function(xhr) {
                            // Remove the loading indicator on error
                            $('#createbookPageBtn').removeAttr("data-kt-indicator", "on");

                            // Reset validation messages and styles
                            $('.fv-plugins-message-container').text('').hide();
                            $('#addBookPageForm input').removeClass('is-invalid');
                            $('#addBookPageForm textarea').removeClass('is-invalid');

                            // Handle validation error (422 status code)
                            if (xhr.status === 422) {
                                var errors = xhr.responseJSON.errors;
                                for (var key in errors) {
                                    // Display the validation error messages next to the corresponding fields
                                    var container = $('#' + key).closest('.fv-row');
                                    container.find('.fv-plugins-message-container').text(errors[
                                        key][0]).show();
                                    $('#' + key).addClass(
                                        'is-invalid'); // Highlight the invalid field 
                                }
                            } else if (xhr.status === 403) {
                                // Handle forbidden error (403 status code)
                                Swal.fire({
                                    title: 'Error',
                                    text: xhr.responseJSON.message,
                                    icon: 'error',
                                    showConfirmButton: true,
                                });
                            } else if (xhr.status === 500) {
                                // Handle internal server error (500 status code)
                                Swal.fire({
                                    title: 'Error',
                                    text: 'Failed to create the BookPage. Please try again later.',
                                    icon: 'error',
                                    showConfirmButton: true,
                                });
                            } else {
                                // Handle other unexpected errors
                                Swal.fire({
                                    title: 'Error',
                                    text: 'An unexpected error occurred. Please try again.',
                                    icon: 'error',
                                    showConfirmButton: true,
                                });
                            }
                        }
                    });
                }
            });


        

            // Update BookPage Modal Form - Submission event listener
            $(document).on('submit', '[id^=editBookPageForm_]', function(e) {
                e.preventDefault(); // Prevent default form submission

                var formId = $(this).attr('id');
                var BookPageId = formId.split('_')[1]; // Extract BookPage ID
                var updateButton = $('#updateBookPageBtn_' + BookPageId);

                // Validate the form before submitting
                if (validateUpdateBookPageForm(BookPageId)) {
                    updateButton.attr("data-kt-indicator", "on"); // Show loading indicator

                    // Get the form data
                    var formData = new FormData($('#' + formId)[0]);

                    // Add CSRF token to the form data
                    formData.append('_token', '{{ csrf_token() }}');

                    // Get all category tags and append them as a single input
                    var categories = $('#edit_BookPage_categories_' + BookPageId).closest(
                            '.tag-container')
                        .find('.tag')
                        .map(function() {
                            return $(this).text().replace(' ×', '');
                        })
                        .get()
                        .join(',');

                    formData.append('categories', categories);

                    // Send the form data using AJAX
                    $.ajax({
                        type: 'POST',
                        url: $(this).attr('action'),
                        data: formData,
                        dataType: 'json',
                        contentType: false,
                        processData: false,
                        success: function(response) {
                            if (response.success) {
                                updateButton.removeAttr("data-kt-indicator", "on");

                                // Reset validation messages and styles
                                $('.fv-plugins-message-container').text('').hide();
                                $('#' + formId + ' input').removeClass('is-invalid');
                                $('#' + formId + ' textarea').removeClass('is-invalid');

                                // Redirect the page or reload it
                                window.location.href = response.redirect_url;

                                // Reset the form and close the modal
                                $('#' + formId)[0].reset();
                                $("#editBookPageModal_" + BookPageId).modal("hide");
                            }
                        },
                        error: function(xhr) {
                            updateButton.removeAttr("data-kt-indicator", "on");

                            $('.fv-plugins-message-container').text('').hide();
                            $('#' + formId + ' input').removeClass('is-invalid');
                            $('#' + formId + ' textarea').removeClass('is-invalid');

                            if (xhr.status === 422) {
                                var errors = xhr.responseJSON.errors;
                                for (var key in errors) {
                                    var fieldId = '#edit_' + key + '_' + BookPageId;
                                    $(fieldId).addClass('is-invalid');
                                    $(fieldId).closest('.fv-row')
                                        .find('.fv-plugins-message-container').text(errors[key][
                                            0
                                        ]).show();
                                }
                            } else if (xhr.status === 403) {
                                Swal.fire({
                                    title: 'Error',
                                    text: xhr.responseJSON.message,
                                    icon: 'error',
                                    showConfirmButton: true,
                                });
                            } else if (xhr.status === 500) {
                                Swal.fire({
                                    title: 'Error',
                                    text: 'Failed to update the BookPage. Please try again later.',
                                    icon: 'error',
                                    showConfirmButton: true,
                                });
                            } else {
                                Swal.fire({
                                    title: 'Error',
                                    text: 'An unexpected error occurred. Please try again.',
                                    icon: 'error',
                                    showConfirmButton: true,
                                });
                            }
                        }
                    });
                }
            });

            // Image Preview Functionality
            $('#book_image').change(function() {
                var input = this;
                if (input.files && input.files[0]) {
                    var reader = new FileReader();

                    reader.onload = function(e) {
                        $('#BookPageFilePreview').attr('src', e.target.result).show();
                    }

                    reader.readAsDataURL(input.files[0]); // convert to base64 string
                } else {
                    $('#BookPageFilePreview').hide();
                }
            });

            // Image Preview Functionality for Edit BookPage Modal
            $(document).on('change', '[id^=edit_BookPage_file_]', function() {
                var input = this;
                var BookPageId = $(this).attr('id').split('_')[3];

                if (input.files && input.files[0]) {
                    var reader = new FileReader();
                    reader.onload = function(e) {
                        $('#editBookPageFilePreview_' + BookPageId).attr('src', e.target.result)
                            .show();

                        // we will hide BookPage-file when we showing the preview
                        $('.BookPage-file').hide();
                    }

                    reader.readAsDataURL(input.files[0]); // convert to base64 string
                } else {
                    $('#editBookPageFilePreview_' + BookPageId).hide();
                    $('.BookPage-file').show();
                }
            });

        });

        // Validate Create BookPage Form
        function validateCreateBookPageForm() {
            var isValid = true;

            // Validate BookPage Name
            var bookpageName = $('#book_name').val().trim();
            if (bookpageName === '') {
                $('#book_name').addClass('is-invalid');
                $('#book_name').closest('.fv-row').find('.fv-plugins-message-container').text(
                    'Book Title is required.').show();
                isValid = false;
            } else {
                $('#book_name').removeClass('is-invalid');
                $('#book_name').closest('.fv-row').find('.fv-plugins-message-container').empty();
            }

            // Validate BookPage Description its not null and max to 500 characters
            var bookpageDescription = $('#book_description').val();
            if (bookpageDescription != '') {
                if (bookpageDescription.trim().length > 500) {
                    $('#book_description').addClass('is-invalid');
                    $('#book_description').closest('.fv-row').find('.fv-plugins-message-container').text(
                        'BookPage Description must not exceed 500 characters.').show();
                    isValid = false;
                } else {
                    $('#book_description').removeClass('is-invalid');
                    $('#book_description').closest('.fv-row').find('.fv-plugins-message-container').empty();
                }
            }

            // Validate BookPage Level
            // var BookPageLevel = $('#BookPage_level').val().trim();
            // if (BookPageLevel === '') {
            //     $('#BookPage_level').addClass('is-invalid');
            //     $('#BookPage_level').closest('.fv-row').find('.fv-plugins-message-container').text(
            //         'BookPage Level is required.').show();
            //     isValid = false;
            // } else {
            //     $('#BookPage_level').removeClass('is-invalid');
            //     $('#BookPage_level').closest('.fv-row').find('.fv-plugins-message-container').empty();
            // }

            // Validate BookPage Categories (Custom Tag Input)
            var $tagContainer = $('#bookpage_categories_create').closest('.tag-container');
            var tagsCount = $tagContainer.find('.tag').length;

            if (tagsCount === 0) {
                $tagContainer.addClass('is-invalid');
                $tagContainer.closest('.fv-row').find('.fv-plugins-message-container')
                    .text('At least one genre is required.').show();
                isValid = false;
            } else {
                $tagContainer.removeClass('is-invalid');
                $tagContainer.closest('.fv-row').find('.fv-plugins-message-container').empty();
            }

            // Validate BookPage Image File 
            var BookPageImageFile = $('#book_image');
            var file = BookPageImageFile[0].files[0];

            if (file) {
                if (file.size > maxSize) {
                    BookPageImageFile.addClass('is-invalid');
                    BookPageImageFile.closest('.fv-row').find('.fv-plugins-message-container').text(
                        'BookPage File should not exceed 20 MB.').show();
                    isValid = false;
                    $('#BookPageFilePreview').hide();
                } else if (allowedTypes.indexOf(file.type) === -1) {
                    BookPageImageFile.addClass('is-invalid');
                    BookPageImageFile.closest('.fv-row').find('.fv-plugins-message-container').text(
                        'Only JPEG, JPG, and Webp file types are allowed.').show();
                    isValid = false;
                    $('#BookPageFilePreview').hide();
                } else {
                    BookPageImageFile.removeClass('is-invalid');
                    BookPageImageFile.closest('.fv-row').find('.fv-plugins-message-container').empty();
                }
            }

            return isValid;
        }

        // Validate Update BookPage Form
        function validateUpdateBookPageForm(BookPageId) {
            var isValid = true;

            // Validate BookPage Name
            var BookPageName = $('#edit_BookPage_name_' + BookPageId).val().trim();
            if (BookPageName === '') {
                $('#edit_BookPage_name_' + BookPageId).addClass('is-invalid');
                $('#edit_BookPage_name_' + BookPageId).closest('.fv-row')
                    .find('.fv-plugins-message-container').text('BookPage Name is required.').show();
                isValid = false;
            } else {
                $('#edit_BookPage_name_' + BookPageId).removeClass('is-invalid');
                $('#edit_BookPage_name_' + BookPageId).closest('.fv-row')
                    .find('.fv-plugins-message-container').empty();
            }

            // Validate BookPage Description (max 500 characters)
            var BookPageDescription = $('#edit_BookPage_description_' + BookPageId).val();
            if (BookPageDescription != '') {
                if (BookPageDescription.trim().length > 500) {
                    $('#edit_BookPage_description_' + BookPageId).addClass('is-invalid');
                    $('#edit_BookPage_description_' + BookPageId).closest('.fv-row')
                        .find('.fv-plugins-message-container').text('Description must not exceed 500 characters.').show();
                    isValid = false;
                } else {
                    $('#edit_BookPage_description_' + BookPageId).removeClass('is-invalid');
                    $('#edit_BookPage_description_' + BookPageId).closest('.fv-row')
                        .find('.fv-plugins-message-container').empty();
                }
            }

            // Validate BookPage Level
            // var BookPageLevel = $('#edit_BookPage_level_' + BookPageId).val().trim();
            // if (BookPageLevel === '') {
            //     $('#edit_BookPage_level_' + BookPageId).addClass('is-invalid');
            //     $('#edit_BookPage_level_' + BookPageId).closest('.fv-row')
            //         .find('.fv-plugins-message-container').text('BookPage Level is required.').show();
            //     isValid = false;
            // } else {
            //     $('#edit_BookPage_level_' + BookPageId).removeClass('is-invalid');
            //     $('#edit_BookPage_level_' + BookPageId).closest('.fv-row')
            //         .find('.fv-plugins-message-container').empty();
            // }

            // Validate BookPage Categories (Custom Tag Input)
            var $tagContainer = $('#edit_BookPage_categories_' + BookPageId).closest('.tag-container');
            var tagsCount = $tagContainer.find('.tag').length;

            if (tagsCount === 0) {
                $tagContainer.addClass('is-invalid');
                $tagContainer.closest('.fv-row').find('.fv-plugins-message-container')
                    .text('At least one category is required.').show();
                isValid = false;
            } else {
                $tagContainer.removeClass('is-invalid');
                $tagContainer.closest('.fv-row').find('.fv-plugins-message-container').empty();
            }

            // Validate BookPage File (20 MB, JPEG, JPG, WebP)
            var BookPageFile = $('#edit_BookPage_file_' + BookPageId);
            var file = BookPageFile[0].files[0];

            if (file) {
                if (file.size > maxSize) {
                    BookPageFile.addClass('is-invalid');
                    BookPageFile.closest('.fv-row').find('.fv-plugins-message-container')
                        .text('BookPage File should not exceed 20 MB.').show();
                    isValid = false;
                    $('#editBookPageFilePreview_' + BookPageId).hide();
                } else if (!allowedTypes.includes(file.type)) {
                    BookPageFile.addClass('is-invalid');
                    BookPageFile.closest('.fv-row').find('.fv-plugins-message-container')
                        .text('Only JPEG, JPG, and WebP file types are allowed.').show();
                    isValid = false;
                    $('#editBookPageFilePreview_' + BookPageId).hide();
                } else {
                    BookPageFile.removeClass('is-invalid');
                    BookPageFile.closest('.fv-row').find('.fv-plugins-message-container').empty();
                }
            }

            return isValid;
        }
    </script>
</x-admin.layout>