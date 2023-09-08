<!-- Scroll to Top Button-->
<a class="scroll-to-top rounded" href="#page-top">
    <i class="fas fa-angle-up"></i>
</a>

<!-- Logout Modal-->
<div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Ready to Leave?</h5>
                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">Select "Logout" below if you are ready to end your current session.</div>
            <div class="modal-footer">
                <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                <a class="btn btn-primary" href="{{ route('logout') }}">Logout</a>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="displayImage" tabindex="-1" role="dialog" aria-labelledby="displayImage"
    aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title h3 mb-0 text-primary font-weight-bold" id="exampleModalLabel">Image Preview</h1>
                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body attached_credentials" id="image_holder">

            </div>
            <div class="modal-footer"></div>
        </div>
    </div>
</div>

<div id="confirmation"></div>
<div id="ui_content"></div>
<div id="ui_content2"></div>
<div id="ui_content3"></div>
<div id="ui_content4"></div>
<div id="modal_container"></div>
<!-- Bootstrap core JavaScript-->
{{-- <script src="{{ asset('js/jquery-3.5.1.js') }}"></script> --}}
<script src="{{ asset('sbadmin/vendor/jquery/jquery.min.js') }}"></script>
<script src="{{ asset('sbadmin/js/jquery-ui.min.js') }}"></script>
<script src="{{ asset('sbadmin/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>

<!-- Core plugin JavaScript-->
<script src="{{ asset('sbadmin/vendor/jquery-easing/jquery.easing.min.js') }}"></script>

<!-- Custom scripts for all pages-->
{{-- <script src="{{ asset('sbadmin/js/sb-admin-2.min.js') }}"></script> --}}

<!-- Page level plugins -->
{{-- <script src="{{ asset('sbadmin/vendor/datatables/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('sbadmin/vendor/datatables/dataTables.bootstrap4.min.js') }}"></script>

<!-- Page level custom scripts -->
<script src="{{ asset('sbadmin/js/demo/datatables-demo.js') }}"></script> --}}
<script>
    var baseUrl = '{{ asset("") }}';
</script>
<script src="{{ asset('js/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('js/common.js') }}"></script>
<script src="{{ asset('js/moment.js') }}"></script>
@php
    if(Helpers::getLoad()){ 
        if(is_array(Helpers::getLoad())){
            foreach(Helpers::getLoad() as $links){
                @endphp
                    <script src="{{ asset('js/'.$links) }}"></script>
                @php
            }
        }
    }
@endphp