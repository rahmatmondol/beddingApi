@extends('admin.layouts.master')
@section('title')
    <title> Add Category Page</title>
@endsection

@section('breadcrumb-items')
    <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
    </li>
    <li class="breadcrumb-item active" aria-current="page"> Category Inputs</li>
@endsection
@section('content')
    <div class="row">
        <div class="col-xl-12 mx-auto">
            <div class="card">
                <div class="card-body p-4">
                    <form class="row g-3" id="categoryForm" method="POST" action="{{ route('categories.store') }}" enctype="multipart/form-data">
                        @csrf
                        <div class="col-md-12">
                            <label for="input1" class="form-label">Category Name</label>
                            <input type="text" class="form-control" id="input1" name="name" placeholder="Category Name" required>
                        </div>
                        <div class="col-md-12">
                            <div id="imagePreview" class="mb-3"></div>
                            <label for="input3" class="form-label">Category Image</label>
                            <input type="file" class="form-control" id="input3" name="image" required onchange="previewImage(event)">
                        </div>
                        <div class="col-md-12">
                            <div class="d-md-flex d-grid justify-content-end gap-3">
                                <button type="submit" class="btn btn-primary px-4">Submit</button>
                                <button type="button" class="btn btn-light px-4" onclick="resetForm()">Reset</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        function previewImage(event) {
            const reader = new FileReader();
            reader.onload = function () {
                const imagePreview = document.getElementById('imagePreview');
                imagePreview.innerHTML = ''; // Clear any existing preview
                const imgElement = document.createElement('img');
                imgElement.src = reader.result;
                imgElement.alt = 'Category Image';
                imgElement.className = 'img-thumbnail';
                imgElement.style.width = '200px';
                imgElement.style.height = '200px';
                imagePreview.appendChild(imgElement);
            };
            reader.readAsDataURL(event.target.files[0]);
        }
    </script>
    <script>
        function submitForm() {
            // Add your code here to handle form submission
            // For example, you can use JavaScript or AJAX to submit the form data to the server
        }

        function resetForm() {
            const imagePreview = document.getElementById('imagePreview');
            imagePreview.innerHTML = '';
            document.getElementById("categoryForm").reset();
        }
    </script>
@endsection
