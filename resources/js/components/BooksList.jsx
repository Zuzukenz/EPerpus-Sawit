import React, { useState } from 'react';
import { useBooks } from '../hooks/useBooks';
import { bookService } from '../services/bookService';
import './BooksList.css';

function BooksList() {
  const { books, loading, error, refetch } = useBooks();
  const [searchQuery, setSearchQuery] = useState('');
  const [showForm, setShowForm] = useState(false);
  const [formData, setFormData] = useState({
    title: '',
    author: '',
    publisher: '',
    published_year: new Date().getFullYear(),
    quantity: 1,
    category_id: '',
  });

  const handleSearch = async (e) => {
    e.preventDefault();
    if (searchQuery.trim()) {
      const result = await bookService.searchBooks(searchQuery);
      if (result.success) {
        setBooks(result.data);
      }
    } else {
      refetch();
    }
  };

  const handleSubmit = async (e) => {
    e.preventDefault();
    const result = await bookService.createBook(formData);
    if (result.success) {
      setFormData({ title: '', author: '', publisher: '', published_year: new Date().getFullYear(), quantity: 1, category_id: '' });
      setShowForm(false);
      refetch();
    }
  };

  const handleDelete = async (id) => {
    if (confirm('Yakin ingin menghapus buku ini?')) {
      const result = await bookService.deleteBook(id);
      if (result.success) {
        refetch();
      }
    }
  };

  if (loading) return <div className="loading">Loading...</div>;
  if (error) return <div className="error">Error: {error}</div>;

  return (
    <div className="books-list">
      <div className="header">
        <h2>Daftar Buku</h2>
        <button className="btn-primary" onClick={() => setShowForm(!showForm)}>
          {showForm ? '✕ Tutup' : '+ Tambah Buku'}
        </button>
      </div>

      {showForm && (
        <form className="book-form" onSubmit={handleSubmit}>
          <input
            type="text"
            placeholder="Judul"
            required
            value={formData.title}
            onChange={(e) => setFormData({ ...formData, title: e.target.value })}
          />
          <input
            type="text"
            placeholder="Pengarang"
            required
            value={formData.author}
            onChange={(e) => setFormData({ ...formData, author: e.target.value })}
          />
          <input
            type="text"
            placeholder="Penerbit"
            value={formData.publisher}
            onChange={(e) => setFormData({ ...formData, publisher: e.target.value })}
          />
          <input
            type="number"
            placeholder="Tahun Terbit"
            value={formData.published_year}
            onChange={(e) => setFormData({ ...formData, published_year: parseInt(e.target.value) })}
          />
          <input
            type="number"
            placeholder="Stok"
            required
            value={formData.quantity}
            onChange={(e) => setFormData({ ...formData, quantity: parseInt(e.target.value) })}
          />
          <button type="submit" className="btn-primary">Simpan</button>
        </form>
      )}

      <form className="search-form" onSubmit={handleSearch}>
        <input
          type="text"
          placeholder="Cari buku..."
          value={searchQuery}
          onChange={(e) => setSearchQuery(e.target.value)}
        />
        <button type="submit" className="btn-secondary">Cari</button>
      </form>

      <div className="table-container">
        <table>
          <thead>
            <tr>
              <th>ID</th>
              <th>Judul</th>
              <th>Pengarang</th>
              <th>Penerbit</th>
              <th>Tahun</th>
              <th>Stok</th>
              <th>Aksi</th>
            </tr>
          </thead>
          <tbody>
            {books.map((book) => (
              <tr key={book.id}>
                <td>{book.id}</td>
                <td>{book.title}</td>
                <td>{book.author}</td>
                <td>{book.publisher}</td>
                <td>{book.published_year}</td>
                <td>{book.quantity}</td>
                <td>
                  <button className="btn-danger" onClick={() => handleDelete(book.id)}>Hapus</button>
                </td>
              </tr>
            ))}
          </tbody>
        </table>
      </div>
    </div>
  );
}

export default BooksList;
