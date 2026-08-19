import React, { useState } from 'react';
import { Outlet, Link } from 'react-router-dom';
import './Layout.css';

function Layout() {
  const [sidebarOpen, setSidebarOpen] = useState(true);

  return (
    <div className="layout">
      {/* Sidebar */}
      <aside className={`sidebar ${sidebarOpen ? 'open' : 'closed'}`}>
        <div className="sidebar-header">
          <h1>EPerpus</h1>
          <button className="toggle-btn" onClick={() => setSidebarOpen(!sidebarOpen)}>
            ☰
          </button>
        </div>
        <nav className="sidebar-nav">
          <Link to="/" className="nav-link">📊 Dashboard</Link>
          <Link to="/books" className="nav-link">📚 Buku</Link>
          <Link to="/categories" className="nav-link">🏷️ Kategori</Link>
          <Link to="/members" className="nav-link">👥 Member</Link>
          <Link to="/borrowings" className="nav-link">📋 Peminjaman</Link>
        </nav>
      </aside>

      {/* Main Content */}
      <main className="main-content">
        <Outlet />
      </main>
    </div>
  );
}

export default Layout;
