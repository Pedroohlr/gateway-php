<x-app-layout :route="'[ADMIN] Ajustes Landing Page'">
    <div class="main-content app-content">
        <div class="container-fluid">

            <div class="row">
                <div class="mb-3 row justify-content-between align-items-center">
                    <div class="mb-5 col-12 col-md-4 d-flex justify-content-start align-items-center">
                        <h1 class="mb-0 display-5">Ajustes da landing page</h1>
                    </div>
                </div>

                <div class="col-xl-12">
                    <form method="POST" action="{{ route('admin.landing.update') }}" enctype="multipart/form-data">
                        @csrf
                        @method('POST')

                        <div class="row">
                            <div class="col-12 col-lg-6">
                            {{-- SECTION 1 --}}
                            <div class="mb-4 card card-raised">
                                <div class="card-body">
                                    <x-section-title title="Seção 1" />
                                    <x-text-field name="section1_title" label="Título" :value="$landing->section1_title" />
                                    <x-text-field name="section1_description" label="Descrição" :value="$landing->section1_description" />
                                    <x-image-upload id="section1_image" name="section1_image" label="Imagem" :value="asset($landing->section1_image)" />
                                </div>
                            </div>

                            {{-- SECTION 2 --}}
                            <div class="mb-4 card card-raised">
                                <div class="card-body">
                                    <x-section-title title="Seção 2" />
                                    <x-text-field name="section2_title" label="Título" :value="$landing->section2_title" />
                                    <x-text-field name="section2_description" label="Descrição" :value="$landing->section2_description" />
                                    <x-image-upload id="section2_image1" name="section2_image1" label="Imagem 1" :value="asset($landing->section2_image1)" />
                                    <x-image-upload id="section2_image2" name="section2_image2" label="Imagem 2" :value="asset($landing->section2_image2)" />
                                    <x-image-upload id="section2_image3" name="section2_image3" label="Imagem 3" :value="asset($landing->section2_image3)" />
                                </div>
                            </div>
                            {{-- SECTION 6 --}}
                            <div class="mb-4 card card-raised">
                                <div class="card-body">
                                    <x-section-title title="Seção 6" />
                                    <x-text-field name="section6_title" label="Título" :value="$landing->section6_title" />
                                    <x-text-field name="section6_description" label="Descrição" :value="$landing->section6_description" />
                                    <x-image-upload id="section6_image" name="section6_image" label="Imagem" :value="asset($landing->section6_image)" />
                                </div>
                            </div>

                            </div>
                            <div class="col-12 col-lg-6">
                        {{-- SECTION 3 --}}
                        <div class="mb-4 card card-raised">
                            <div class="card-body">
                                <x-section-title title="Seção 3" />
                                <x-text-field name="section3_title" label="Título" :value="$landing->section3_title" />

                                <x-subsection-title title="Item 1" />
                                <x-image-upload id="section3_item1_image" name="section3_item1_image" label="Imagem" :value="asset($landing->section3_item1_image)" />
                                <x-text-field name="section3_item1_title" label="Título" :value="$landing->section3_item1_title" />
                                <x-text-field name="section3_item1_description" label="Descrição" :value="$landing->section3_item1_description" />

                                <x-subsection-title title="Item 2" />
                                <x-image-upload id="section3_item2_image" name="section3_item2_image" label="Imagem" :value="asset($landing->section3_item2_image)" />
                                <x-text-field name="section3_item2_title" label="Título" :value="$landing->section3_item2_title" />
                                <x-text-field name="section3_item2_description" label="Descrição" :value="$landing->section3_item2_description" />

                                <x-subsection-title title="Item 3" />
                                <x-image-upload id="section3_item3_image" name="section3_item3_image" label="Imagem" :value="asset($landing->section3_item3_image)" />
                                <x-text-field name="section3_item3_title" label="Título" :value="$landing->section3_item3_title" />
                                <x-text-field name="section3_item3_description" label="Descrição" :value="$landing->section3_item3_description" />
                            </div>
                        </div>

                        {{-- SECTION 4 --}}
                        <div class="mb-4 card card-raised">
                            <div class="card-body">
                                <x-section-title title="Seção 4" />
                                <x-image-upload id="section4_image" name="section4_image" label="Imagem" :value="asset($landing->section4_image)" />
                                <x-text-field name="section4_title" label="Título" :value="$landing->section4_title" />
                                <x-text-field name="section4_description" label="Descrição" :value="$landing->section4_description" />
                                <x-text-field name="section4_link" label="Link" :value="$landing->section4_link" />
                            </div>
                        </div>
                        {{-- SECTION 5 --}}
                        <div class="mb-4 card card-raised">
                            <div class="card-body">
                                <x-section-title title="Seção 5" />
                                <x-text-field name="section5_title" label="Título" :value="$landing->section5_title" />
                                <x-text-field name="section5_description" label="Descrição" :value="$landing->section5_description" />
                                <x-image-upload id="section5_image" name="section5_image" label="Imagem" :value="asset($landing->section5_image)" />
                            </div>
                        </div>


                    </div>
                        {{-- BOTÃO --}}
                        <div class="text-end">
                            <button type="submit" class="btn btn-primary">Salvar Alterações</button>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
