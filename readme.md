halaman index:
tombol login

halaman login:
berisi input nama dan kata sandi


dashboard inspektorat memiliki 5 halaman:
input temuan -> perangkat daerah -> crud temuan:
	input form temuan:
		tahun
		jenis laporan: lkpd, kinerja, lainnya
		temuan pemeriksaan -> judul, jumlah, nilai
		rekomendasi > nilai total
		lempiran pdf
	tampilan daftar temuan:
		judul
		tahun
		jenis laporan
		aksi -> tombol mengarahkan ke halaman detail
	halaman detail:
	tombol mengedit temuan
	tombol untuk menambah uraian rekomendasi
		input form uraian rekomendasi:
			urain
			sifat -> finansial, non finansial
		tampilan daftar temuan
			temuan pemeriksaan -> judul, jumlah, nilai
			rekomendasi -> daftar uraian, sifat, nilai
			aksi -> tombol download
	
verifikasi tindak -> memilih perangkat daerah -> menampilkan daftar tindak
	tampilan daftar tindak:
		judul
		tahun 
		jenis laporan
		pelaporan -> tombol mengarahkan ke halaman detail pelaporan
		tindak lanjut -> tombol mengarahkan ke halaman detail tindak lanjut
		status -> belum, sudah sesuai, dikembalikan ke dinas
		aksi -> memilih status untuk di update
	halaman detail pelaporan:
	edit temuan
	tambah uraian
		tampilan table temuan:
			temuan pemeriksaan -> judul, jumlah, nilai
			rekomendasi -> daftar uraian, sifat, nilai
			aksi -> download file
	halaman detail tindak lanjut:
		tampilan table daftar tindak lanjut:
			tindak lanjut
			sifat
			periode
			bukti tindak lanjut -> download


verifikasi bpk -> sama seperti verifikasi tindak
pengaduan -> memilih menu: masyarakat, asn -> menampilkan daftar pengaduan
	tampilan daftar pengaduan:
		tanggal
		nama lengkap
		alamat
		nomor handphone
		jenis pengaduan
		lampiran -> download
		isi pengaduan
		aksi -> hapus
daftar opd -> crud akun perangkat daerah
	input form perangkat daerah:
		nama perangkat daerah
		password
	daftar akun perangkat daerah:
		nama perangkat daerah
		aksi -> edit, hapus

dashboard skpd (perangkat daerah)
memiliki 1 halaman:
temuan -> menampilkan daftar temuan:
	table daftar temuan lkpd:
		judul
		tahun
		jenis laporan
		aksi -> tombol bernama detail yang mengarahkan ke halaman detail temuan

	halaman detail temuan:
		menampilkan detail dari temuan:
			temuan pemeriksaan -> judul, jumlah, nilai
			rekomendasi -> uraian, sifat, nilai
			aksi -> download file temuan
			tindak lanjut -> memiliki tombol yang mengarahkan ke halaman detail tindak lanjut dari temuan
			status
		halaman detail tindak lanjut:
			input form tindak lanjut:
				memilih uraian
				uraian
				periode
				lampiran file pdf
			table daftar tindak lanjut:
				tindak lanjut
				sifat -> diambil dari sifat uraian saat menambahkan tindak lanjut
				periode
				aksi -> download lampiran

halaman pengaduan (terbuka untuk semua orang)
input form pengaduan:
	nama lengkap
	alamat
	nomor handphone
	jenis pengaduan -> lkpd, kinerja, lainnya
	isi pengaduan
	lampiran