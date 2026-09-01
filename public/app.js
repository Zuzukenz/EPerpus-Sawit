const SUPABASE_URL = 'https://bqyrhbezlurbbrswaxiw.supabase.co';
const SUPABASE_ANON_KEY = 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJzdXBhYmFzZSIsInJlZiI6ImJxeXJoYmV6bHVyYmJyc3dheGl3Iiwicm9sZSI6ImFub24iLCJpYXQiOjE3MjQwODI0NTgsImV4cCI6MjAzOTY1ODQ1OH0.SgBvDVT5FrHQ-GtHkUqBG9MJ2ZhOtLw5qJc_qN5T5ww';

const kotakLogin = document.getElementById('kotak-login');
const kotakDashboard = document.getElementById('kotak-dashboard');
const formLogin = document.getElementById('form-login');
const pesanError = document.getElementById('pesan-error');
const btnLogout = document.getElementById('btn-logout');
const formBuku = document.getElementById('form-buku');
const tabelBuku = document.getElementById('tabel-buku');

async function callSupabaseAPI(endpoint, method = 'GET', body = null) {
  const token = localStorage.getItem('eperpus_token');
  const options = {
    method,
    headers: {
      'Content-Type': 'application/json',
      'apikey': SUPABASE_ANON_KEY,
      'Authorization': `Bearer ${token || SUPABASE_ANON_KEY}`
    }
  };
  if (body) options.body = JSON.stringify(body);
  
  return fetch(`${SUPABASE_URL}/rest/v1${endpoint}`, options).then(r => r.json());
}

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
    let respons = await fetch(`${SUPABASE_URL}/auth/v1/token?grant_type=password`, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'apikey': SUPABASE_ANON_KEY
      },
      body: JSON.stringify({
        email: emailInput,
        password: passwordInput
      })
    });

    let hasil = await respons.json();
    if (respons.ok && hasil.access_token) {
      localStorage.setItem('eperpus_token', hasil.access_token);
      bukaDashboard();
      ambilDataBuku();
    } else {
      pesanError.textContent = hasil.error_description || "Login gagal: Email atau Password salah.";
    }
  } catch (error) {
    console.error("Kesalahan Login:", error);
    pesanError.textContent = "Gagal terhubung ke Supabase!";
  }
});

async function ambilDataBuku() {
  try {
    let hasil = await callSupabaseAPI('/books?select=*,category:categories(name)');
    
    if (Array.isArray(hasil)) {
      let barisTabel = '';
      let nomor = 1;
      hasil.forEach(buku => {
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
    } else if (hasil.code === 'PGRST301') {
      aksiLogout();
    } else {
      console.error("Error memuat buku:", hasil);
    }
  } catch (error) {
    console.error("Gagal memuat katalog buku:", error);
  }
}

formBuku.addEventListener('submit', async function(e) {
  e.preventDefault();
  let payloadBuku = {
    category_id: parseInt(document.getElementById('category-id').value),
    title: document.getElementById('title').value,
    author: document.getElementById('author').value,
    publisher: document.getElementById('publisher').value,
    published_year: parseInt(document.getElementById('published-year').value),
    stock: parseInt(document.getElementById('stock').value)
  };

  try {
    let hasil = await callSupabaseAPI('/books', 'POST', payloadBuku);
    
    if (!hasil.code) {
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

  try {
    let hasil = await callSupabaseAPI(`/books?id=eq.${id}`, 'DELETE');
    
    if (!hasil.code || hasil.code === 204) {
      ambilDataBuku();
    } else {
      alert('Gagal menghapus buku dari server.');
    }
  } catch (error) {
    console.error("Kesalahan hapus buku:", error);
  }
}

async function aksiLogout() {
  localStorage.removeItem('eperpus_token');
  kotakLogin.classList.remove('sembunyi');
  kotakDashboard.classList.add('sembunyi');
  pesanError.textContent = '';
}

btnLogout.addEventListener('click', aksiLogout);

function bukaDashboard() {
  kotakLogin.classList.add('sembunyi');
  kotakDashboard.classList.remove('sembunyi');
}
