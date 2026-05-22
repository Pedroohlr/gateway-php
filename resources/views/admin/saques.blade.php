<x-app-layout :route="'[ADMIN] Dashboard'">
    <div class="main-content app-content">
        <div class="container-fluid">

            <div class="d-flex align-items-center justify-content-between my-4 page-header-breadcrumb flex-wrap gap-2">
                <div>
                    <p class="fw-medium fs-20 mb-0">Olá, ADM {{explode(' ',auth()->user()->name)[0]}}</p>
                </div>
            </div>


        </div>
    </div>
</x-app-layout>
