<form 
    action="{{ url('/mahasiswa/materi') }}" 
    method="POST" 
    enctype="multipart/form-data"
>
    @csrf

    <input type="text" name="judul_materi" required>
    <input type="text" name="matkul" required>
    <textarea name="deskripsi" required></textarea>

    <input type="file" name="file_materi" required>

    <button type="submit">Upload</button>
</form>
