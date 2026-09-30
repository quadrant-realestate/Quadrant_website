    @include('admin.include.header')
        <div class="body-wrapper-inner">
            <div class="container-fluid">
            <!--  Row 1 -->
                <div class="row">
                    <div class="col-lg-12 d-flex align-items-strech">              
                        <div class="card card-body py-3">
                            <div class="row align-items-center">
                            <div class="col-12">
                                <div class="d-sm-flex align-items-center justify-space-between">
                                <h4 class="mb-4 mb-sm-0 card-title">Payment</h4>
                                <nav aria-label="breadcrumb" class="ms-auto">
                                    <ol class="breadcrumb">
                                    <li class="breadcrumb-item d-flex align-items-center">
                                        <a class="text-muted text-decoration-none d-flex" >
                                        <iconify-icon icon="solar:home-2-line-duotone" class="fs-6"></iconify-icon>
                                        </a>
                                    </li>
                                    <li class="breadcrumb-item" aria-current="page">
                                        <span class="badge fw-medium fs-2 bg-primary-subtle text-primary">
                                        Payment
                                        </span>
                                    </li>
                                    <li class="breadcrumb-item" aria-current="page">
                                        <span class="badge fw-medium fs-2 bg-primary-subtle text-primary">
                                       Detail
                                        </span>
                                    </li>
                                    </ol>
                                </nav>
                                </div>
                            </div>
                            </div>
                        </div> 
                    </div>           
                </div>  


                <div class="row">
                    <div class="col-12">
                        <!-- ----------------------------------------- -->
                        <!-- 1. Basic Form -->
                        <!-- ----------------------------------------- -->
                        <!-- start Basic Form -->
                        <div class="card">
                        <div class="card-body">
                            <!-- <h4 class="card-title mb-3">Basic Form</h4> -->
                            <form action="" method="POST">
                                @csrf
                                <div class="row">
                                    <div class="col-md-12">
                                    <div class="form-floating mb-3">
                                        <input type="text" class="form-control" name="name">
                                        <label for="tb-fname">Full Name</label>
                                    </div>
                                    </div>
                                    <div class="col-md-12">
                                    <div class="form-floating mb-3">
                                        <input type="text" class="form-control" name="email">
                                        <label >Email</label>
                                    </div>
                                    </div>
                                    <div class="col-md-12">
                                    <div class="form-floating mb-3">
                                        <input type="text" class="form-control" name="amount">
                                        <label >Amount</label>
                                    </div>
                                    </div>
                                    <div class="col-md-12">
                                    <div class="form-floating mb-3">
                                        <input type="text" class="form-control" name="reference">
                                        <label >Reference</label>
                                    </div>
                                    </div>
                                    <div class="col-md-12">
                                    <div class="form-floating mb-3">
                                        <input type="text" class="form-control" name="mode">
                                        <label >Payment Mode</label>
                                    </div>
                                    </div>
                                    <div class="col-md-12">
                                    <div class="form-floating mb-3">
                                        <input type="date" class="form-control" name="data">
                                        <label >DATE</label>
                                    </div>
                                    </div>
                                    {{-- <div class="col-12">
                                    <div class="d-md-flex align-items-center">                                        
                                        <div class="ms-auto mt-3 mt-md-0">
                                        <button type="submit" class="btn btn-primary hstack gap-6">
                                            <i class="ti ti-send fs-4"></i> Submit </button>
                                        </div>
                                    </div> --}}
                                    </div>
                                </div>
                            </form>
                        </div>
                        </div>
                        <!-- end Basic Form -->
                    </div> 
 
            </div>
        </div>
    @include('admin.include.footer')