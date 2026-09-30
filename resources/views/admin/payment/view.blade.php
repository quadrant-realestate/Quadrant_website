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
                                    {{-- <li class="breadcrumb-item" aria-current="page">
                                        <span class="badge fw-medium fs-2 bg-primary-subtle text-primary">
                                        View
                                        </span>
                                    </li> --}}
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
                            <table id="myTable" class="table text-nowrap mb-0 align-middle">
                                <thead class="text-dark fs-4">
                                    <tr>
                                        <th>
                                            <h6 class="fs-4 fw-semibold mb-0">#</h6>
                                        </th>
                                        <th>
                                            <h6 class="fs-4 fw-semibold mb-0">Full Name</h6>
                                        </th>
                                        <th>
                                            <h6 class="fs-4 fw-semibold mb-0">Email</h6>
                                        </th>
                                        <th>
                                            <h6 class="fs-4 fw-semibold mb-0">Amount</h6>
                                        </th>
                                        <th style="float:right">
                                            <h6 class="fs-4 fw-semibold mb-0">Action</h6>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>
                                            <h6 class="fw-semibold mb-0">INV-3066</h6>
                                        </td>
                                        <td>
                                            Nouman Arif                                        </td>
                                        <td>
                                            nouman.arif@knowzalearning.com
                                        </td>
                                        <td>
                                          30 £
                                        </td>
                                        <td style="float:right">  
                                            <div class="align-items-center gap-3">
                                                <a href="{{ route('detailpayment') }}">
                                                    <span class="badge bg-warning-subtle text-warning d-inline-flex align-items-center gap-1"><i class="fs-4 ti ti-eye"></i>View</span>
                                                </a>
                                                <a href="">
                                                    <span class="badge bg-danger-subtle text-danger d-inline-flex align-items-center gap-1"><i class="fs-4 ti ti-eye"></i>Delete</span>
                                                </a>    
                                            </div>                                                  
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <h6 class="fw-semibold mb-0">INV-3067</h6>
                                        </td>
                                        <td>
                                        Mohsin Ahmad                                        </td>
                                        <td>
                                           mohsin@knowzalearning.com
                                        </td>
                                        <td>
                                       600 £
                                        </td>
                                        <td style="float:right">  
                                            <div class="align-items-center gap-3">
                                                <a href="{{ route('detailpayment') }}">
                                                    <span class="badge bg-warning-subtle text-warning d-inline-flex align-items-center gap-1"><i class="fs-4 ti ti-eye"></i>View</span>
                                                </a>
                                                <a href="">
                                                    <span class="badge bg-danger-subtle text-danger d-inline-flex align-items-center gap-1"><i class="fs-4 ti ti-trash"></i>Delete</span>
                                                </a>    
                                            </div>                                                  
                                        </td>
                                    </tr>
                                   
                                    
                                </tbody>
                            </table>
  
                        </div>
                        </div>
                        <!-- end Basic Form -->
                    </div> 
 
            </div>
        </div>
    @include('admin.include.footer')