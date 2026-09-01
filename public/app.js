const URL_API = 'http://127.0.0.1:8000/api';

const kotakLogin = document.getElementById('kotak-login');
const kotakDashboard = document.getElementById('kotak-dashboard');
const formLogin = document.getElementById('form-login');
const pesanError = document.getElementById('pesan-error');
const btnLogout = document.getElementById('btn-logout');
const formBuku = document.getElementById('form-buku');
const tabelBuku = document.getElementById('tabel-buku');

document.addEventListener('DOMContentLoaded', () => {
  let token = localStorage.getItem('eperpus_token');
  if (token) {
    bukaDashboard();
    ambilDataBuku();
  }
});

formLogin.addEventListener('submit', async function(e) {
  e.preventDefault();
  let emailInput = document.getElementById('email').value;
  let passwordInput = document.getElementById('password').value;

  try {
    let respons = await fetch(`${URL_API}/login`, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json'
      },
      body: JSON.stringify({
        email: emailInput,
        password: passwordInput
      })
    });

    let hasil = await respons.json();
    if (respons.ok && hasil.status) {
      localStorage.setItem('eperpus_token', hasil.access_token);
      bukaDashboard();
      ambilDataBuku();
    } else {
      pesanError.textContent = hasil.message || "Login gagal: Email atau Password salah.";
    }
  } catch (error) {
    console.error("Kesalahan Login:", error);
    pesanError.textContent = "Gagal terhubung ke server backend E-Perpus!";
  }
});

async function ambilDataBuku() {
  let token = localStorage.getItem('eperpus_token');
  try {
    let respons = await fetch(`${URL_API}/books`, {
      method: 'GET',
      headers: {
        'Authorization': `Bearer ${token}`,
        'Accept': 'application/json'
      }
    });

    let hasil = await respons.json();
    if (respons.ok && hasil.status) {
      let barisTabel = '';
      let nomor = 1;
      hasil.data.forEach(buku => {
        let namaKategori = buku.category ? buku.category.name : '-';
        barisTabel += `
          <tr>
            <td>${nomor++}</td>
            <td><strong>${buku.title}</strong></td>
            <td>${namaKategori}</td>
            <td>${buku.author} / <br><small>${buku.publisher}</small></td>
            <td>${buku.published_year}</td>
            <td>${buku.stock} eks</td>
            <td>
              <button onclick="hapusBuku(${buku.id})" class="btn merah" style="padding: 4px 8px; font-size: 11px;">Hapus</button>
            </td>
          </tr>
        `;
      });
      tabelBuku.innerHTML = barisTabel;
    } else if (respons.status === 401) {
      aksiLogout();
    }
  } catch (error) {
    console.error("Gagal memuat katalog buku:", error);
  }
}

formBuku.addEventListener('submit', async function(e) {
  e.preventDefault();
  let token = localStorage.getItem('eperpus_token');
  let payloadBuku = {
    category_id: document.getElementById('category-id').value,
    title: document.getElementById('title').value,
    author: document.getElementById('author').value,
    publisher: document.getElementById('publisher').value,
    published_year: document.getElementById('published-year').value,
    stock: document.getElementById('stock').value
  };

  try {
    let respons = await fetch(`${URL_API}/books`, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'Authorization': `Bearer ${token}`,
        'Accept': 'application/json'
      },
      body: JSON.stringify(payloadBuku)
    });

    let hasil = await respons.json();
    if (respons.ok && hasil.status) {
      formBuku.reset();
      ambilDataBuku();
      alert('Buku berhasil ditambahkan ke katalog!');
    } else {
      alert('Gagal menambah buku: ' + (hasil.message || 'Periksa kembali inputan.'));
    }
  } catch (error) {
    console.error("Kesalahan simpan buku:", error);
  }
});

async function hapusBuku(id) {
  if (!confirm("Apakah Anda yakin ingin menghapus buku ini dari perpustakaan?")) return;
  let token = localStorage.getItem('eperpus_token');

  try {
    let respons = await fetch(`${URL_API}/books/${id}`, {
      method: 'DELETE',
      headers: {
        'Authorization': `Bearer ${token}`,
        'Accept': 'application/json'
      }
    });

    if (respons.ok) {
      ambilDataBuku();
    } else {
      alert('Gagal menghapus buku dari server.');
    }
  } catch (error) {
    console.error("Kesalahan hapus buku:", error);
  }
}

async function aksiLogout() {
  let token = localStorage.getItem('eperpus_token');
  try {
    await fetch(`${URL_API}/logout`, {
      method: 'POST',
      headers: {
        'Authorization': `Bearer ${token}`,
        'Accept': 'application/json'
      }
    });
  } catch (error) {
    console.error("Logout error:", error);
  }

  localStorage.removeItem('eperpus_token');
  kotakLogin.classList.remove('sembunyi');
  kotakDashboard.classList.add('sembunyi');
}

btnLogout.addEventListener('click', aksiLogout);

function bukaDashboard() {
  kotakLogin.classList.add('sembunyi');
  kotakDashboard.classList.remove('sembunyi');
}