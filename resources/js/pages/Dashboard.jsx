import React from 'react';
import { useBooks } from '../hooks/useBooks';
import { useMembers } from '../hooks/useMembers';
import { useBorrowings, useOverdueBorrowings } from '../hooks/useBorrowings';
import './Dashboard.css';

function Dashboard() {
  const { books, loading: booksLoading } = useBooks();
  const { members, loading: membersLoading } = useMembers();
  const { borrowings, loading: borrowingsLoading } = useBorrowings();
  const { overdue } = useOverdueBorrowings();

  const totalBooks = books.length;
  const totalMembers = members.length;
  const activeBorrowings = borrowings.filter(b => b.status === 'borrowed').length;
  const totalBorrowings = borrowings.length;

  if (booksLoading || membersLoading || borrowingsLoading) {
    return <div className="loading">Loading...</div>;
  }

  return (
    <div className="dashboard">
      <h1>Dashboard EPerpus Sawit</h1>
      
      <div className="stats-grid">
        <div className="stat-card">
          <div className="stat-header">📚</div>
          <div className="stat-content">
            <h3>Total Buku</h3>
            <p className="stat-number">{totalBooks}</p>
          </div>
        </div>

        <div className="stat-card">
          <div className="stat-header">👥</div>
          <div className="stat-content">
            <h3>Total Member</h3>
            <p className="stat-number">{totalMembers}</p>
          </div>
        </div>

        <div className="stat-card">
          <div className="stat-header">📋</div>
          <div className="stat-content">
            <h3>Peminjaman Aktif</h3>
            <p className="stat-number">{activeBorrowings}</p>
          </div>
        </div>

        <div className="stat-card alert">
          <div className="stat-header">⚠️</div>
          <div className="stat-content">
            <h3>Jatuh Tempo</h3>
            <p className="stat-number" style={{ color: '#dc2626' }}>{overdue.length}</p>
          </div>
        </div>
      </div>

      <div className="dashboard-content">
        <div className="recent-section">
          <h2>Peminjaman Terakhir</h2>
          <div className="recent-list">
            {borrowings.slice(0, 5).map((borrowing) => (
              <div key={borrowing.id} className="recent-item">
                <div className="recent-info">
                  <strong>{borrowing.books?.title || 'N/A'}</strong>
                  <span className="member-name">oleh {borrowing.members?.name || 'N/A'}</span>
                </div>
                <span className={`status-badge ${borrowing.status}`}>
                  {borrowing.status === 'borrowed' ? '🔴 Dipinjam' : '🟢 Dikembalikan'}
                </span>
              </div>
            ))}
          </div>
        </div>

        <div className="stats-section">
          <h2>Statistik</h2>
          <ul>
            <li>Total Peminjaman: <strong>{totalBorrowings}</strong></li>
            <li>Peminjaman Aktif: <strong>{activeBorrowings}</strong></li>
            <li>Dikembalikan: <strong>{totalBorrowings - activeBorrowings}</strong></li>
            <li>Jatuh Tempo: <strong style={{ color: '#dc2626' }}>{overdue.length}</strong></li>
          </ul>
        </div>
      </div>
    </div>
  );
}

export default Dashboard;
