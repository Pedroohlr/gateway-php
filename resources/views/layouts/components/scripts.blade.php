<!-- SCROLL-TO-TOP -->
<div class="scrollToTop">
    <span class="arrow lh-1"><i class="ti ti-caret-up fs-20"></i></span>
</div>
<div id="responsive-overlay"></div>

<script src="{{ asset('assets/libs/@popperjs/core/umd/popper.min.js') }}"></script>
<script src="{{ asset('assets/libs/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('assets/libs/node-waves/waves.min.js') }}"></script>
<script src="{{ asset('assets/libs/simplebar/simplebar.min.js') }}"></script>
<script src="{{ asset('assets/js/simplebar.js') }}"></script>
<script src="{{ asset('assets/libs/flatpickr/flatpickr.min.js') }}"></script>
<script src="{{ asset('assets/libs/@simonwep/pickr/pickr.es5.min.js') }}"></script>
<script src="{{ asset('assets/libs/@tarekraafat/autocomplete.js/autoComplete.min.js') }}"></script>
<script src="{{ asset("/assets/js/sticky.js") }}"></script>
<script src="{{ asset("/assets/js/defaultmenu.js") }}"></script>
<script src="{{ asset("/assets/js/custom.js") }}"></script>
<script src="{{ asset("/assets/js/custom-switcher.js") }}"></script>
<script src="{{ asset("/assets/libs/apexcharts/apexcharts.min.js") }}"></script>
<script src="{{ asset("/assets/js/apexcharts-area.js") }}"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- DataTables CSS -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.4.1/css/responsive.dataTables.min.css">
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.4.1/js/dataTables.responsive.min.js"></script>
 <!-- jQuery -->

 <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.inputmask/5.0.9/jquery.inputmask.min.js" integrity="sha512-F5Ul1uuyFlGnIT1dk2c4kB4DBdi5wnBJjVhL7gQlGh46Xn0VhvD8kgxLtjdZ5YN83gybk/aASUAlpdoWUjRR3g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
 <!-- DataTables JS -->
 <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg==" crossorigin="anonymous" referrerpolicy="no-referrer" />

 <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>


<script>
    function showToast(type, message) {


        Swal.fire({
            toast: true,
            icon: type,
            //imageUrl: "/assets/images/toast/success.png", // URL da imagem
            //imageWidth: 50, // Ajuste o tamanho
            //imageHeight: 50,
            title: message,
            animation: false,
            position: 'top-end',
            showConfirmButton: false,
            timer: 4000,
            timerProgressBar: true,
            didOpen: (toast) => {
                toast.addEventListener('mouseenter', Swal.stopTimer);
                toast.addEventListener('mouseleave', Swal.resumeTimer);
            }
        });
    }
</script>
