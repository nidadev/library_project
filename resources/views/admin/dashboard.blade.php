<x-admin.layout pageTitle="Dashboard">
    <!-- Date Range -->
    <div class="col-xl-12 mx-auto mb-3">
        <div class="d-flex justify-content-between">
            <div>
                <input class="form-control" placeholder="Pick date range" id="dateRange" />
            </div>
            <div>
                <!--input class="form-control" placeholder="Pick Author" id="author" /-->
                <select class="form-select" id="author" aria-label="Default select example">
                    <option selected>Select</option>
                    @foreach($author as $at)
                    <?php //dd($author) ?>
                    <option value="{{ $at }}">Admin</option>

                    @endforeach
                  </select>
            </div>
            <div>
                <input class="form-control" placeholder="Pick Genre" id="genre" />
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
                <input type="text" name="search" class="form-control"/>
                <input type="submit" name="submit" class="btn btn-primary form-control">
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
        @include('partials.admin.dashboard', ['users' => $userData, 'bookData' => $bookData ])
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


    @push('scripts')
        <script>
            $(document).ready(function() {
                //alert('')
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
                    //alert(start);
                    loadDashboardData(start.format('YYYY-MM-DD'), end.format('YYYY-MM-DD'));
                });



            });

            // Function to initialize DataTables
            function initializeDataTables() {
                $('#usersTable','#booksTable').DataTable({
                    "destroy": true,
                    "order": []
                });
            }

            function loadDashboardData(start, end) {
            alert(start,end);
                $("#dashboard-content").hide();
                $("#dashboard-skeleton").removeClass('d-none');

                $.ajax({
                    url: "{{ route('admin.bookpage.dashboard.data') }}",
                    method: "POST",
                    data: {
                        start_date: start,
                        end_date: end,
                        _token: "{{ csrf_token() }}"
                    },
                    success: function(response) {
                        alert(response);
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
                alert('');
                var author = $('#author').val();
                alert(author);
                loadDashboardDataByAuthor(author);

            });

            $('#year').change(function(){
                //alert('');
                var year = $('#year').val();
                alert(year);
                loadDashboardDataByYear(year);

            });

            function loadDashboardDataByAuthor(author) {
                //alert(author);
                $("#dashboard-content").hide();
                $("#dashboard-skeleton").removeClass('d-none');

                $.ajax({
                    url: "{{ route('admin.bookpage.dashboard.data') }}",
                    method: "POST",
                    data: {
                        author: author,
                        _token: "{{ csrf_token() }}"
                    },
                    success: function(response) {
                        //alert(data.author);
                        alert(response);
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
                alert(year);
                $("#dashboard-content").hide();
                $("#dashboard-skeleton").removeClass('d-none');

                $.ajax({
                    url: "{{ route('admin.bookpage.dashboard.data') }}",
                    method: "POST",
                    data: {
                        year: year,
                        _token: "{{ csrf_token() }}"
                    },
                    success: function(response) {
                        //alert(data.author);
                        alert(response);
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
</x-admin.layout>
