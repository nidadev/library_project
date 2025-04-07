<style>
    .img_src
    {
        width:20%;
    }
    </style>
<div class="row gy-5">
    <!-- States -->

    <div class="col-md-3">
        <div class="card text-center">
            <div class="card-header align-items-center justify-content-center">
                <h5 class="fs-1 fw-bolder">Total Books </h5>
            </div>
            <div class="card-body">
                <h2 class="display-3">{{ $totalBooks }}</h2>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-center">
            <div class="card-header align-items-center justify-content-center">
                <h5 class="fs-1 fw-bolder">Borrowed Books </h5>
            </div>
            <div class="card-body">
                <h2 class="display-3">{{ $totalBorrow }}</h2>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-center">
            <div class="card-header align-items-center justify-content-center">
                <h5 class="fs-1 fw-bolder">Total Users</h5>
            </div>
            <div class="card-body">
                <h2 class="display-3">{{ $totalUsers }}</h2>
            </div>
        </div>
    </div>
    <!-- Job Applications -->
    <div class="col-md-12 mt-3">
        <div class="card h-100">
            <div class="card-header">
                <h3 class="card-title fs-1">Newly Registered Users</h3>
            </div>
            <div class="px-3 py-1">
                @if ($userData->isEmpty())
                    <x-empty-state title="No Users" message="You have no users yet." />
                @else
                    <table class="table table-striped" id="appliedJobsTable">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Applied Date</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($userData as $user)
                                <tr>
                                    <td>{{$user->name}}</td>
                                    <td>{{$user->email}}</td>
                                    <td>{{$user->created_at->format('d M Y')}}</td>
                                    <td></td>
                                    <td>
                                        <button class="btn btn-primary view-job" data-id="{{ $user->id }}"
                                            data-bs-toggle="modal" data-bs-target="#jobDetailModal">
                                            <i class="fas fa-eye me-1"></i> View
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endif

            </div>
        </div>
    </div>

    <!-- Books  -->
    <div class="col-md-12 mt-3">
        <div class="card h-100">
            <div class="card-header">
                <h3 class="card-title fs-1">Recently added books</h3>
            </div>
            <div class="px-3 py-1">
                @if ($bookData->isEmpty())
                    <x-empty-state title="No Recommended Jobs" message="No recommended jobs available at the moment." />
                @else
                    <table class="table table-striped" id="recommendedJobsTable">
                        <thead>
                            <tr>
                                <th>Book Title</th>
                                <th>Book Cover Image</th>
                                <th>Auther</th>
                                <th>Release Year</th>
                                <th>Description</th>
                                <th>Genres</th>
                                <th>Pdf</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($bookData as $bk)
                                <tr>
                                    <td>{{ $bk->name }}</td>
                                    <td><img class="img_src" src="{{ asset('storage/' . $bk->file_path) }}" alt=""> </td>

                                    <td>{{ $bk->user->name }}</td>

                                    <td>{{ $bk->release_year }}</td>
                                    <td>{{ $bk->description }}</td>
                                    <td>{{ $bk->categories }}</td>

                                    <td>
                                        <a class="btn btn-primary" data-id="{{ $bk->id }}"
                                            data-bs-toggle="modal" data-bs-target="#jobDetailModal">
                                            <i class="bi bi-eye"></i> View
                                        </a>

                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endif
            </div>
        </div>
    </div>


</div>
