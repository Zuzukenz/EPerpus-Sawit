import React, { useState } from 'react';
import { useBorrowings, useOverdueBorrowings } from '../hooks/useBorrowings';
import { borrowingService } from '../services/borrowingService';
import './BorrowingsList.css';

function BorrowingsList() {
  const { borrowings, loading, error, refetch } = useBorrowings();
  const { overdue } = useOverdueBorrowings();
  const [showForm, setShowForm] = useState(false);
  const [formData, setFormData] = useState({
    member_id: '',
    book_id: '',
    borrow_date: new Date().toISOString().split('T')[0],
    return_date: '',
    status: 'borrowed',
  });

  const handleSubmit = async (e) => {
    e.preventDefault();
    const result = await borrowingService.createBorrowing(formData);
    if (result.success) {
      setFormData({ member_id: '', book_id: '', borrow_date: new Date().toISOString().split('T')[0], return_date: '', status: 'borrowed' });
      setShowForm(false);
      refetch();
    }
  };

  const handleReturn = async (id) => {
    if (confirm('Yakin ingin mengembalikan buku ini?')) {
      const result = await borrowingService.returnBook(id);
      if (result.success) {
        refetch();
      }
    }
  };

  const handleDelete = async (id) => {
    if (confirm('Yakin ingin menghapus data peminjaman ini?')) {
      const result = await borrowingService.deleteBorrowing(id);
      if (result.success) {
        refetch();
      }
    }
  };

  if (loading) return <div className="loading">Loading...</div>;
  if (error) return <div className="error">Error: {error}</div>;

  return (
    <div className="borrowings-list">
      <div className="header">
        <h2>Manajemen Peminjaman</h2>
        <button className="btn-primary" onClick={() => setShowForm(!showForm)}>
          {showForm ? '✕ Tutup' : '+ Tambah Peminjaman'}
        </button>
      </div>

      {overdue.length > 0 && (
        <div className="alert-overdue">
          ⚠️ Ada {overdue.length} peminjaman yang JATUH TEMPO!
        </div>
      )}

      {showForm && (
        <form className="borrowing-form" onSubmit={handleSubmit}>
          <input
            type="number"
            placeholder="ID Member"
            required
            value={formData.member_id}
            onChange={(e) => setFormData({ ...formData, member_id: e.target.value })}
          />
          <input
            type="number"
            placeholder="ID Buku"
            required
            value={formData.book_id}
            onChange={(e) => setFormData({ ...formData, book_id: e.target.value })}
          />
          <input
            type="date"
            value={formData.borrow_date}
            onChange={(e) => setFormData({ ...formData, borrow_date: e.target.value })}
          />
          <input
            type="date"
            placeholder="Tanggal Kembali"
            required
            value={formData.return_date}
            onChange={(e) => setFormData({ ...formData, return_date: e.target.value })}
          />
          <button type="submit" className="btn-primary">Simpan</button>
        </form>
      )}

      <div className="table-container">
        <table>
          <thead>
            <tr>
              <th>ID</th>
              <th>Member</th>
              <th>Buku</th>
              <th>Tanggal Pinjam</th>
              <th>Tanggal Kembali</th>
              <th>Status</th>
              <th>Aksi</th>
            </tr>
          </thead>
          <tbody>
            {borrowings.map((borrowing) => (
              <tr key={borrowing.id} className={borrowing.status === 'returned' ? 'returned' : ''} style={{ background: overdue.find(o => o.id === borrowing.id) ? '#fee2e2' : '' }}>
                <td>{borrowing.id}</td>
                <td>{borrowing.members?.name || 'N/A'}</td>
                <td>{borrowing.books?.title || 'N/A'}</td>
                <td>{new Date(borrowing.borrow_date).toLocaleDateString('id-ID')}</td>
                <td>{new Date(borrowing.return_date).toLocaleDateString('id-ID')}</td>
                <td>
                  <span className={`status-badge ${borrowing.status}`}>
                    {borrowing.status === 'borrowed' ? 'Dipinjam' : 'Dikembalikan'}
                  </span>
                </td>
                <td>
                  {borrowing.status === 'borrowed' && (
                    <button className="btn-success" onClick={() => handleReturn(borrowing.id)}>Kembalikan</button>
                  )}
                  <button className="btn-danger" onClick={() => handleDelete(borrowing.id)}>Hapus</button>
                </td>
              </tr>
            ))}
          </tbody>
        </table>
      </div>
    </div>
  );
}

export default BorrowingsList;
