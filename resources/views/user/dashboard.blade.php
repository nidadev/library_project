<style>
    .tag
    {
        width:150px !important;
        background: #fff !important;
    }
    </style>
<x-user.layout pageTitle="Dashboard">
    <!-- Date Range -->
    @if(session()->has('message'))
    {{ session()->get('message') }}
    @endif
    <div class="col-xl-12 mx-auto mb-3">
        <div class="d-flex justify-content-between">
            <div>
                <input class="form-control" placeholder="Pick date range" id="dateRange" />
            </div>
            <div>
                <select class="form-select" id="author" aria-label="Default select example">
                    <option selected>Select</option>
                    @foreach($author as $at)
                    <?php //dd($author) ?>
                    <option value="{{ $at }}">Admin</option>
                    @endforeach
                  </select>
            </div>
            <div>
                <!--input class="form-control" placeholder="Pick Genre" id="genre" /-->
                <select class="form-control tag js-example-basic-multiple" name="states[]" multiple="multiple" id="genre">
                @foreach($genre as $gn)
                    <?php //dd($author) ?>
                    <option value="{{ $gn }}">{{ $gn }}</option>

                    @endforeach

                  </select>
            </div>
            <div>
                <select class="form-select" id="year" aria-label="Default select example">
                    <option selected>Select</option>
                    @foreach($release_year as $release_year)
                    <?php //dd($author) ?>
                    <option value="{{ $release_year }}">{{ $release_year }}</option>

                    @endforeach
                  </select>
            </div>
            <div>
                <form method="POST" action="#" id="titleSearch">
                    @csrf
                <input type="text" name="search" class="form-control" id="searchTitle" />
                <input type="submit" name="submit" class="btn btn-primary form-control">
                </form>
            </div>

        </div>
    </div>

    <!-- Loading Skeleton -->
    <div id="dashboard-skeleton" class="d-none">
        <div class="row gy-5">
            <div class="col-md-3">
                <div class="card text-center">
                    <div class="card-header">
                        <div class="skeleton skeleton-text"></div>
                    </div>
                    <div class="card-body">
                        <div class="skeleton skeleton-text"></div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-center">
                    <div class="card-header">
                        <div class="skeleton skeleton-text"></div>
                    </div>
                    <div class="card-body">
                        <div class="skeleton skeleton-text"></div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-center">
                    <div class="card-header">
                        <div class="skeleton skeleton-text"></div>
                    </div>
                    <div class="card-body">
                        <div class="skeleton skeleton-text"></div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-center">
                    <div class="card-header">
                        <div class="skeleton skeleton-text"></div>
                    </div>
                    <div class="card-body">
                        <div class="skeleton skeleton-text"></div>
                    </div>
                </div>
            </div>
            <div class="col-md-12 mt-3">
                <div class="card text-center">
                    <div class="card-header">
                        <div class="skeleton skeleton-text"></div>
                    </div>
                    <div class="card-body">
                        <div class="skeleton skeleton-text"></div>
                    </div>
                </div>
            </div>
            <div class="col-md-12 mt-3">
                <div class="card text-center">
                    <div class="card-header">
                        <div class="skeleton skeleton-text"></div>
                    </div>
                    <div class="card-body">
                        <div class="skeleton skeleton-text"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Dynamic Data Section -->
    <div id="dashboard-content">
        <?php //dd($bookData); ?>
        @include('partials.user.dashboard', ['books' => $bookData ])
    </div>

    <!-- Job Details Drawer -->
    <div id="jobDetailDrawer" class="bg-white" data-kt-drawer="true" data-kt-drawer-activate="true"
        data-kt-drawer-toggle="#kt_drawer_example_basic_button" data-kt-drawer-close="#kt_drawer_example_basic_close"
        data-kt-drawer-width="600px">
        <!--begin::Card-->
        <div class="card w-100 rounded-0 border-0" id="jobDetailsContent">
            <p class="text-center text-muted">Select a job to view details.</p>
        </div>
        <!--end::Card-->
    </div>

    <!-- Apply Job Modal -->
    <div class="modal fade" id="applyJobModal" tabindex="-1" aria-labelledby="applyJobModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Apply for Job</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form method="POST" action="#" id="applyJobForm" enctype="multipart/form-data">
                    <div class="modal-body">
                        @csrf
                        <div class="mb-3 fv-row">
                            <label for="resumes" class="form-label">Resume</label>
                            <select class="form-select mb-2" name="resume" id="resumes">

                                <option value="new">Upload New</option>
                            </select>
                            <div
                                class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback">
                            </div>
                        </div>
                        <!-- Upload Field -->
                        <div class="mb-3 fv-row" id="upload_file" style="display: none;">
                            <label for="new_resume" class="form-label">Upload Resume</label>
                            <input type="file" class="form-control" name="new_resume" id="new_resume">
                            <div
                                class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback">
                            </div>
                        </div>

                        <!-- Apply Error Messages -->
                        <div id="applyJobError" class="text-danger"></div>

                        <input type="hidden" name="job_post_id" id="jobId">
                    </div>
                    <div class="modal-footer align-items-baseline">
                        <button type="button" class="btn btn-secondary btn-sm"
                            data-bs-dismiss="modal">Cancel</button>

                        <button type="submit" class="btn btn-primary btn-sm text-white">Apply Now</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            $(document).ready(function() {
                //alert('11');
                // Initialize Date Range Picker
                var startOfMonth = moment().startOf('month');
                var endOfMonth = moment().endOf('month');

                // Initialize DataTables
                initializeDataTables();

                $("#dateRange").daterangepicker({
                    startDate: startOfMonth,
                    endDate: endOfMonth,
                    ranges: {
                        "Today": [moment(), moment()],
                        "Last 7 Days": [moment().subtract(6, "days"), moment()],
                        "This Month": [moment().startOf("month"), moment().endOf("month")],
                        "Last Month": [moment().subtract(1, "month").startOf("month"), moment().subtract(1,
                            "month").endOf("month")]
                    }
                }, function(start, end) {
                    loadDashboardData(start.format('YYYY-MM-DD'), end.format('YYYY-MM-DD'));
                });

                // $('#resumes').on('change', function() {
                //     if ($(this).val() == 'new') {
                //         $('#upload_file').show();
                //     } else {
                //         $('#upload_file').hide();
                //     }
                // });

                // Clear validation and reset form when the modal is closed
                $("#applyJobModal").on("hidden.bs.modal", function() {
                    $('#applyJobError').empty();
                });

                $('.js-example-basic-multiple').select2();

                function loadDashboardDataTitle(title) {
                $("#dashboard-content").hide();
                $("#dashboard-skeleton").removeClass('d-none');

                $.ajax({
                    url: "{{ route('user.bookpage.dashboard.data') }}",
                    method: "POST",
                    data: {
                        title: title,
                        _token: "{{ csrf_token() }}"
                    },
                    success: function(response) {
                       // alert(response);
                        $("#dashboard-skeleton").addClass('d-none');
                        $("#dashboard-content").html(response).fadeIn();
                        initializeDataTables();
                    },
                    error: function() {
                        $("#dashboard-skeleton").removeClass('d-none');
                        $("#dashboard-content").html("<p class='text-danger'>Failed to load data.</p>")
                            .fadeIn();
                    }
                });
            }
            $("#titleSearch").submit(function(event) {
                //alert('111');
                    event.preventDefault();
                    var title = $('#searchTitle').val();
                   // alert(title);
                    loadDashboardDataTitle(title);
                });

                $("#applyJobForm").submit(function(event) {
                    event.preventDefault();
                    if (validateApplyJobForm()) {
                        let formData = new FormData(this);
                        let selectedResume = $("#resumes").val();

                        if (selectedResume === "new") {
                            formData.delete("resume");
                        } else {
                            formData.delete("new_resume");
                        }

                        $.ajax({
                            url: "{{ route('admin.bookpage.index') }}",
                            type: "POST",
                            data: formData,
                            processData: false,
                            contentType: false,
                            headers: {
                                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content")
                            },
                            success: function(response) {
                                if (response.success) {
                                    $("#applyJobModal").modal("hide");
                                    location.reload();
                                } else {
                                    let message = response.message || 'Something went wrong.';
                                    $('#applyJobError').empty().append(message);
                                }
                            },
                            error: function(xhr) {
                                let errorMsg = xhr.responseJSON.message ||
                                    "Something went wrong. Please try again.";
                                $('#applyJobError').empty().append(errorMsg);
                            }
                        });
                    }
                });
            });



$("#titleSearch").submit(function(event) {
                    event.preventDefault();
                    var title = $('#searchTitle').val();
                    loadDashboardDataTitle(title);
                });

                function loadDashboardDataTitle(title) {
                $("#dashboard-content").hide();
                $("#dashboard-skeleton").removeClass('d-none');

                $.ajax({
                    url: "{{ route('user.bookpage.dashboard.data') }}",
                    method: "POST",
                    data: {
                        title: title,
                        _token: "{{ csrf_token() }}"
                    },
                    success: function(response) {
                        //alert(response);
                        $("#dashboard-skeleton").addClass('d-none');
                        $("#dashboard-content").html(response).fadeIn();
                        initializeDataTables();
                    },
                    error: function() {
                        $("#dashboard-skeleton").removeClass('d-none');
                        $("#dashboard-content").html("<p class='text-danger'>Failed to load data.</p>")
                            .fadeIn();
                    }
                });
            }
            // Function to initialize DataTables
            function initializeDataTables() {
                $('#appliedJobsTable','#recommendedJobsTable').DataTable({
                    "destroy": true,
                    "order": []
                });
            }
            $('#genre').change(function(){
               // alert('');
                var genre = $('#genre').val();
                //alert(genre);
                gn = '"'+genre+'"';
                gne = gn.split(",");
               //alert(genre.split(","));
               loadDashboardDataByGenre(gne);

            });

            function loadDashboardDataByGenre(genre) {
                //alert(genre);
                
                $("#dashboard-content").hide();
                $("#dashboard-skeleton").removeClass('d-none');

                $.ajax({
                    url: "{{ route('user.bookpage.dashboard.data') }}",
                    method: "POST",
                    data: {
                        genre: genre,
                        _token: "{{ csrf_token() }}"
                    },
                    success: function(response) {
                        //alert(data.author);
                       // alert(response);
                        $("#dashboard-skeleton").addClass('d-none');
                        $("#dashboard-content").html(response).fadeIn();
                        initializeDataTables();
                    },
                    error: function() {
                        $("#dashboard-skeleton").removeClass('d-none');
                        $("#dashboard-content").html("<p class='text-danger'>Failed to load data.</p>")
                            .fadeIn();
                    }
                });
            }
            function loadDashboardData(start, end) {
                $("#dashboard-content").hide();
                $("#dashboard-skeleton").removeClass('d-none');

                $.ajax({
                    url: "{{ route('user.bookpage.dashboard.data') }}",
                    method: "POST",
                    data: {
                        start_date: start,
                        end_date: end,
                        _token: "{{ csrf_token() }}"
                    },
                    success: function(response) {
                        //alert(response);
                        $("#dashboard-skeleton").addClass('d-none');
                        $("#dashboard-content").html(response).fadeIn();
                        initializeDataTables();
                    },
                    error: function() {
                        $("#dashboard-skeleton").removeClass('d-none');
                        $("#dashboard-content").html("<p class='text-danger'>Failed to load data.</p>")
                            .fadeIn();
                    }
                });
            }

            $('#author').change(function(){
               // alert('');
                var author = $('#author').val();
                //alert(author);
                loadDashboardDataByAuthor(author);

            });

            $('#year').change(function(){
                //alert('');
                var year = $('#year').val();
               // alert(year);
                loadDashboardDataByYear(year);

            });



            function loadDashboardDataByAuthor(author) {
                //alert(author);
                $("#dashboard-content").hide();
                $("#dashboard-skeleton").removeClass('d-none');

                $.ajax({
                    url: "{{ route('user.bookpage.dashboard.data') }}",
                    method: "POST",
                    data: {
                        author: author,
                        _token: "{{ csrf_token() }}"
                    },
                    success: function(response) {
                        //alert(data.author);
                       // alert(response);
                        $("#dashboard-skeleton").addClass('d-none');
                        $("#dashboard-content").html(response).fadeIn();
                        initializeDataTables();
                    },
                    error: function() {
                        $("#dashboard-skeleton").removeClass('d-none');
                        $("#dashboard-content").html("<p class='text-danger'>Failed to load data.</p>")
                            .fadeIn();
                    }
                });
            }

            function loadDashboardDataByYear(year) {
              //  alert(year);
                $("#dashboard-content").hide();
                $("#dashboard-skeleton").removeClass('d-none');

                $.ajax({
                    url: "{{ route('user.bookpage.dashboard.data') }}",
                    method: "POST",
                    data: {
                        year: year,
                        _token: "{{ csrf_token() }}"
                    },
                    success: function(response) {
                        //alert(data.author);
                       // alert(response);
                        $("#dashboard-skeleton").addClass('d-none');
                        $("#dashboard-content").html(response).fadeIn();
                        initializeDataTables();
                    },
                    error: function() {
                        $("#dashboard-skeleton").removeClass('d-none');
                        $("#dashboard-content").html("<p class='text-danger'>Failed to load data.</p>")
                            .fadeIn();
                    }
                });
            }

        </script>
    @endpush

    @push('styles')
        <style>
            .skeleton {
                background-color: #e0e0e0;
                border-radius: 4px;
                animation: pulse 1.5s infinite ease-in-out;
            }

            .skeleton-text {
                width: 60%;
                height: 20px;
                margin: 10px auto;
            }

            @keyframes pulse {
                0% {
                    opacity: 1;
                }

                50% {
                    opacity: 0.5;
                }

                100% {
                    opacity: 1;
                }
            }
        </style>
    @endpush
</x-user.layout>
