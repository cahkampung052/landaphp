# Landa PHP

### Generate gambar menjadi 3 ukuran (700x700, 350x350, 150x150)

`createImg(PATHFILE, NAMAFILE, ID_UNIK, KUALITAS)`

* PATHFILE = Path menuju gambar yang akan digenerate
* NAMAFILE = Nama gambar yang akan digenerate
* ID_UNIK = Isi dengan angka unik atau ID gambar
* KUALITAS = Kualitas gambar yang baru (Min 20, Max 100)

### Menampilkan terbilang

`terbilang(ANGKA)`

Contoh : 

`terbilang(1200)`

return : **seribu dua ratus**

### Menampilkan format rupiah

`rp(ANGKA, PREFIX, DECIMAL)`

* ANGKA = Angka yang akan dirubah ke format rupiah
* PREFIX = Berisi `true/false` untuk menampilkan simbol `Rp.` sebelum angka
* DECIMAL = Desimal yang dipakai, nilai default 0

Contoh : 

`rp(15000, true, 2)`

return : **Rp. 1.500,00**

### Menampilkan nama bulan berdasarkan urutan

`namaBulan(URUTAN)`

* URUTAN = urutan bulan (1-12)

Contoh : 

`namaBulan(2)`

return : **Februari**

### Konvert base64 ke file

`base64ToFile(BASE64, PATH, CUSTOM_NAME)`

* BASE64 = kode BASE64
* PATH = PATH untuk menyimpan
* CUSTOM_NAME = membuat nama file baru, parameter ini bisa tidak dipakai

Format untuk parameter BASE64 : 
``	
	array(
		base64 => '',
		filename => '',
	)
``