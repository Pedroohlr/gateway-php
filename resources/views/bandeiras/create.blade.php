<!DOCTYPE html>
<html>

<head>
    <title>Criar Bandeira</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-200">
    <a href="{{ url('/') }}">
        <button class="bg-sky-500 my-2 mx-2 text-white text-black px-3 py-2 h-10" type="submit">Home</button>
    </a>

    <a href="{{ url('/bandeiras') }}">
        <button class="bg-sky-500 my-2 mx-2 text-white text-black px-3 py-2 h-10" type="submit">Bandeira</button>
    </a>

    <div
        class="flex justify-center  items-center content-center flex-col align-items-center min-h-screen border-2 border-black">
        <div class="w-1/4">
            <div class="flex justify-center my-2 w-full">
                <h1 class="text-xl font-bold my-2">Criar Bandeira</h1>
            </div>
            <hr>
            <form method="post" action="{{ route('bandeiras.store') }}">
                @csrf
                <div class="flex flex-col gep-y-2">
                    <label for="nome">Nome:</label>
                    <input class="px-2 h-10 border border-black" type="text" name="nome" required><br>
                </div>
                <div class="flex flex-col gep-y-2">
                    <label for="grupo_economico_id">Grupo Economico</label>
                    <input class="px-2 h-10 border border-black" type="text" name="grupo_economico_id" required><br>
                </div>
                <button class="bg-sky-500 text-white text-black px-3 py-2 h-10" type="submit">Criar</button>
            </form>
        </div>
    </div>
</body>

</html>
